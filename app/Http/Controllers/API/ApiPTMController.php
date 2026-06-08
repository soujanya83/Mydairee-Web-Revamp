<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Center;
use App\Models\Child;
use App\Models\Childparent;
use App\Models\Usercenter;
use App\Models\PTM;
use App\Models\PTMDate;
use App\Models\PTMSlot;
use App\Models\PTMReschedule;
use App\Models\Room;
use App\Models\RoomStaff;

class ApiPTMController extends Controller
{
    public function index(Request $request)
    {
        [$centerId, $currentCenter, $authError] = $this->resolveAuthorizedCenter($request);
        if ($authError) {
            return $authError;
        }

        $user = Auth::user();

        $ptms = collect();

        if ($user->userType === 'Superadmin' || $user->userType === 'Centeradmin' || $user->userType === 'Staff') {
            $ptms = PTM::with(['staff', 'center', 'children', 'ptmDates','ptmSlots',
                'reschedules.rescheduledate','reschedules.rescheduleslot'])
                ->withMin('ptmDates', 'date')
                ->where('centerid', $centerId)
                ->latest()
                ->get();
        } elseif ($user->userType === 'Parent') {
            $ptms = PTM::with(['staff', 'center', 'children', 'ptmDates','ptmSlots',
                'reschedules.rescheduledate','reschedules.rescheduleslot'])
                ->withMin('ptmDates', 'date')
                ->where('centerid', $centerId)
                ->where('status', 'Published')
                ->latest()
                ->get();

            $parentChildIds = $user->children->pluck('id')->toArray();
            $ptms = $ptms->filter(function ($ptm) use ($parentChildIds) {
                $ptmChildIds = $ptm->children->pluck('id')->toArray();
                return count(array_intersect($parentChildIds, $ptmChildIds)) > 0;
            });
        }

        $ptms->transform(function ($ptm) {
            $latestReschedule = $ptm->reschedules->sortByDesc('created_at')->first();
            $ptm->originalDate = $ptm->ptmDates->min('date') ?? null;
            $ptm->originalSlot = $ptm->ptmSlots->first()->slot ?? null;
            $ptm->finalDate = $latestReschedule ? optional($latestReschedule->rescheduledate)->date : $ptm->originalDate;
            $ptm->finalSlot = $latestReschedule ? optional($latestReschedule->rescheduleslot)->slot : $ptm->originalSlot;
            return $ptm;
        });

        $upcomingptms = $ptms->filter(function ($p) {
            if (!$p->ptm_dates_min_date) return false;
            $earliest = Carbon::parse($p->ptm_dates_min_date);
            return $earliest->isToday() || $earliest->isFuture();
        })->values();

        $attendedptms = $ptms->filter(function ($p) {
            if (!$p->ptm_dates_min_date) return false;
            $earliest = Carbon::parse($p->ptm_dates_min_date);
            return $earliest->lt(Carbon::today());
        })->values();

        return response()->json([
            'status' => true,
            'current_center' => $currentCenter,
            'upcoming_ptm' => $upcomingptms,
            'attended_ptm' => $attendedptms,
            'all' => $ptms->values(),
        ]);
    }

    public function store(Request $request)
    {
        [$centerId, $currentCenter, $authError] = $this->resolveAuthorizedCenter($request);
        if ($authError) {
            return $authError;
        }

        $isEdit = $request->filled('id');

        $rules = [
            'selected_rooms'    => 'required',
            'selected_dates'    => 'required',
            'selected_slot'     => 'nullable',
            'title'             => 'required|string',
            'objective'         => 'nullable|string',
            'selected_children' => 'required|string',
            'selected_staff'    => 'nullable|string',
            'action'            => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $authId = Auth::user()->id;
            $action = $request->input('action');

            $ptm = $isEdit ? PTM::findOrFail($request->id) : new PTM();
            if ($isEdit && (int) $ptm->centerid !== (int) $centerId) {
                return response()->json(['success' => false, 'message' => 'PTM not found for the provided center.'], 404);
            }
            $ptm->title     = $request->input('title');
            $ptm->objective = $request->input('objective');
            $ptm->slot      = $request->input('selected_slot');
            $ptm->centerid  = $centerId;
            $ptm->status    = $action;
            if (!$isEdit) $ptm->userId = $authId;
            $ptm->save();
            $ptmId = $ptm->id;

            if ($isEdit) {
                $ptm->ptmDates()->delete();
                $ptm->ptmSlots()->delete();
            }

            $selectedDate = array_filter(explode(',', $request->input('selected_dates')));
            asort($selectedDate);
            $dateIdMap = [];
            foreach ($selectedDate as $date) {
                $dateYMD = Carbon::createFromFormat('d-m-Y', trim($date))->format('Y-m-d');
                $ptmDate = PTMDate::create(['ptm_id' => $ptmId, 'date' => $dateYMD]);
                $dateIdMap[$dateYMD] = $ptmDate->id;
            }

            $dateSlotMapRaw = $request->input('date_slot_map');
            $dateSlotMap = [];
            if ($dateSlotMapRaw) {
                $decoded = json_decode($dateSlotMapRaw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $dateSlotMap = $decoded;
                }
            }

            if (!empty($dateSlotMap)) {
                $nonEmptyDates = array_filter(array_keys($dateSlotMap), function ($d) use ($dateSlotMap) {
                    return !empty($dateSlotMap[$d]) && is_array($dateSlotMap[$d]);
                });
                sort($nonEmptyDates);
                foreach ($dateSlotMap as $dateYMD => $slots) {
                    if (empty($slots) || !is_array($slots)) continue;
                    if (!isset($dateIdMap[$dateYMD])) {
                        $ptmDate = PTMDate::create(['ptm_id' => $ptmId, 'date' => $dateYMD]);
                        $dateIdMap[$dateYMD] = $ptmDate->id;
                    }
                    foreach ($slots as $slot) {
                        PTMSlot::create(['ptm_id' => $ptmId, 'ptmdate_id' => $dateIdMap[$dateYMD], 'slot' => $slot]);
                    }
                }
                if (!empty($nonEmptyDates)) {
                    $earliestDate = $nonEmptyDates[0];
                    $slotsForEarliestDate = $dateSlotMap[$earliestDate] ?? [];
                    if (!empty($slotsForEarliestDate)) {
                        usort($slotsForEarliestDate, function ($a, $b) {
                            $timeA = strtotime(explode(' - ', $a)[0]);
                            $timeB = strtotime(explode(' - ', $b)[0]);
                            return $timeA <=> $timeB;
                        });
                        $earliestSlot = $slotsForEarliestDate[0];
                        $ptm->slot = $earliestSlot;
                        $ptm->save();
                    }
                }
            }

            $selectedChildren = array_filter(explode(',', $request->input('selected_children')));
            sort($selectedChildren);
            $selectedStaff = array_filter(explode(',', $request->input('selected_staff')));
            sort($selectedStaff);
            $selectedRooms = array_filter(explode(',', $request->input('selected_rooms')));
            sort($selectedRooms);

            $ptm->children()->sync($selectedChildren);
            $ptm->staff()->sync($selectedStaff);
            $ptm->room()->sync($selectedRooms);

            DB::commit();

            if (strtolower($action) == 'published') {
                foreach ($selectedChildren as $childId) {
                    $childId = trim($childId);
                    if ($childId === '') continue;
                    $parentRelations = Childparent::where('childid', $childId)->get();
                    foreach ($parentRelations as $relation) {
                        $parentUser = User::find($relation->parentid);
                        if ($parentUser) $parentUser->notify(new \App\Notifications\PTMAdded($ptm));
                    }
                }
                if (!empty($selectedStaff)) {
                    foreach ($selectedStaff as $staffId) {
                        $staffUser = User::find($staffId);
                        if ($staffUser) $staffUser->notify(new \App\Notifications\PTMAdded($ptm));
                    }
                }
            }

            return response()->json(['success' => true, 'ptm_id' => $ptm->id, 'message' => 'PTM '. ucfirst($action) .' successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API PTM store error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getrooms()
    {
        $request = request();
        [$centerId, $currentCenter, $authError] = $this->resolveAuthorizedCenter($request);
        if ($authError) {
            return $authError;
        }

        $user = Auth::user();
        // if ($user->userType === 'Superadmin') {
            $rooms = Room::where('centerid', $centerId)->get();
        // } else {
        //     $rooms = Auth::user()->rooms()->where('centerid', $centerId)->get();
        // }
        return response()->json(['status' => 'success', 'rooms' => $rooms]);
    }

    public function getChildren(Request $request)
    {
        [$centerId, $currentCenter, $authError] = $this->resolveAuthorizedCenter($request);
        if ($authError) {
            return $authError;
        }

        $user = Auth::user();
        $rooms = $request->rooms;
        $roomIds = !empty($rooms) ? explode(',', $rooms) : [];
        if ($user->userType === 'Superadmin' || $user->userType === 'Centeradmin') {
            $children = Child::whereIn('room', $roomIds)->where('status', 'Active')->orderBy('name','asc')->get();
        } elseif ($user->userType === 'Staff') {
            $children = Child::whereIn('room', $roomIds)->where('status', 'Active')->orderBy('name','asc')->get();
        } else {
            $children = Child::whereIn('id', $roomIds)->where('status', 'Active')->orderBy('name','asc')->get();
        }
        return response()->json(['children' => $children, 'status' => 'success']);
    }

    public function getStaff(Request $request)
    {
        [$centerId, $currentCenter, $authError] = $this->resolveAuthorizedCenter($request);
        if ($authError) {
            return $authError;
        }

        $roomIds = explode(',', $request->rooms);
        $staff = collect();
        if (!empty($roomIds)) {
            $staff = RoomStaff::with('staff:id,name')
                ->whereIn('roomid', $roomIds)
                ->get()
                ->pluck('staff')
                ->filter()
                ->unique('id')
                ->values();
        }
        return response()->json(['success' => true, 'staff' => $staff]);
    }

    public function edit(Request $request, PTM $ptm)
    {

        $ptm->load(['room' => fn($q) => $q->orderBy('name'), 'children' => fn($q) => $q->orderBy('name'), 'staff' => fn($q) => $q->orderBy('name'), 'ptmDates','ptmSlots.ptmDate']);

        $dateSlotMap = [];
        $dateIdToDateMap = [];
        foreach ($ptm->ptmDates as $ptmDate) $dateIdToDateMap[$ptmDate->id] = $ptmDate->date;
        foreach ($ptm->ptmSlots as $slotRecord) {
            $date = $slotRecord->ptmDate ? $slotRecord->ptmDate->date : ($dateIdToDateMap[$slotRecord->ptmdate_id] ?? null);
            if ($date) {
                if (!isset($dateSlotMap[$date])) $dateSlotMap[$date] = [];
                $dateSlotMap[$date][] = $slotRecord->slot;
            }
        }

        return response()->json([
            'ptm' => $ptm,
            'selectedRooms' => $ptm->room->pluck('id'),
            'selectedChildren' => $ptm->children->pluck('id'),
            'selectedStaff' => $ptm->staff->pluck('id'),
            'selectedDates' => $ptm->ptmDates->pluck('date'),
            'selectedSlots' => $ptm->ptmSlots->pluck('slot'),
            'dateSlotMap' => $dateSlotMap,
        ]);
    }

    public function delete(Request $request, PTM $ptm)
    {

        DB::beginTransaction();
        try {
            $ptm->children()->detach();
            $ptm->staff()->detach();
            $ptm->room()->detach();
            $ptm->ptmDates()->delete();
            $ptm->ptmSlots()->delete();
            $ptm->reschedules()->delete();
            $ptm->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'PTM deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PTM delete failed: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function view(Request $request, PTM $ptm)
    {
        
        $ptm->load(['ptmDates','ptmSlots','room','children','staff','reschedules.rescheduledate','reschedules.rescheduleslot','reschedules.user']);

        $latestReschedule = $ptm->reschedules->sortByDesc('created_at')->first();
        $finalDate = $latestReschedule && $latestReschedule->rescheduledate ? $latestReschedule->rescheduledate->date : ($ptm->ptmDates->min('date') ?? null);
        $finalSlot = $latestReschedule && $latestReschedule->rescheduleslot ? $latestReschedule->rescheduleslot->slot : ($ptm->slot ?? $ptm->ptmSlots->first()->slot ?? null);

        return response()->json(['ptm' => $ptm, 'finalDate' => $finalDate, 'finalSlot' => $finalSlot]);
    }

    public function getPtmEvents(Request $request)
    {
        [$centerId, $currentCenter, $authError] = $this->resolveAuthorizedCenter($request);
        if ($authError) {
            return $authError;
        }

        $ptms = PTM::with('ptmDates')
            ->where('centerid', $centerId)
            ->where('status', 'Published')
            ->get();
        $events = $ptms->map(function ($ptm) {
            $firstDate = $ptm->ptmDates->min('date');
            $date = $firstDate ? Carbon::parse($firstDate)->format('Y-m-d') : null;
            return ['id' => $ptm->id, 'title' => $ptm->title ?? 'PTM', 'date' => $date, 'type' => 'ptm'];
        })->filter();
        return response()->json(['status' => true, 'events' => $events]);
    }

    public function directPublish(Request $request, PTM $ptm)
    {
        

        try {
            $ptm->status = 'Published';
            $ptm->save();
            $selectedChildren = $ptm->children->pluck('id')->toArray();
            foreach ($selectedChildren as $childId) {
                $parentRelations = Childparent::where('childid', $childId)->get();
                foreach ($parentRelations as $relation) {
                    $parentUser = User::find($relation->parentid);
                    if ($parentUser) $parentUser->notify(new \App\Notifications\PTMAdded($ptm));
                }
            }
            return response()->json(['success' => true, 'message' => 'PTM Published successfully.']);
        } catch (\Exception $e) {
            Log::error('Direct publish failed: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getSlots()
    {
        $staticSlots = [[ 'id'=>1,'time'=>'09:00 AM - 10:00 AM'],[ 'id'=>2,'time'=>'10:00 AM - 11:00 AM'],[ 'id'=>3,'time'=>'11:00 AM - 12:00 PM'],[ 'id'=>4,'time'=>'12:00 PM - 01:00 PM'],[ 'id'=>5,'time'=>'02:00 PM - 03:00 PM'],[ 'id'=>6,'time'=>'03:00 PM - 04:00 PM'],[ 'id'=>7,'time'=>'04:00 PM - 05:00 PM']];
        return response()->json(['success' => true, 'slot' => $staticSlots]);
    }

    public function getPtmDateSlots($ptmid)
    {
        $ptm = PTM::with([
            'ptmDates',
            'ptmSlots'
        ])->find($ptmid);

        if (!$ptm) {
            return response()->json([
                'success' => false,
                'message' => 'PTM not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ptm' => [
                'id' => $ptm->id,
                'title' => $ptm->title,
                'status' => $ptm->status,

                'default_date' => optional(
                    $ptm->ptmDates->sortBy('date')->first()
                )->date,

                'default_slot' => optional(
                    $ptm->ptmSlots->first()
                )->slot,

                'dates' => $ptm->ptmDates->map(function ($date) use ($ptm) {

                    $slots = $ptm->ptmSlots
                        ->where('ptmdate_id', $date->id)
                        ->values()
                        ->map(function ($slot) {
                            return [
                                'id' => $slot->id,
                                'slot' => $slot->slot
                            ];
                        });

                    return [
                        'id' => $date->id,
                        'date' => $date->date,
                        'slots' => $slots
                    ];
                }),
            ]
        ]);
    }

    public function reschedulePtm(Request $request)
    {
        [$centerId, $currentCenter, $authError] = $this->resolveAuthorizedCenter($request);
        if ($authError) {
            return $authError;
        }

        $validated = $request->validate([
            'ptmid' => 'required|integer|exists:ptm,id',
            'ptmdateid' => 'required|integer|exists:ptmdate,id',
            'ptmslotid' => 'required|integer|exists:ptmslot,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $ptm = PTM::with('children')->findOrFail($validated['ptmid']);
        if ((int) $ptm->centerid !== (int) $centerId) {
            return response()->json(['success' => false, 'message' => 'PTM not found for the provided center.'], 404);
        }

        $child = null;
        if ($user->userType === 'Parent') {
            $child = $ptm->children->filter(fn($c) => $c->parents && $c->parents->contains('id', $user->id))->first() ?? $ptm->children->first();
        } else {
            if ($request->filled('childid')) $child = Child::find($request->childid);
        }
        if (!$child) return response()->json(['success'=>false,'message'=>'No child record found for this user/PTM.'],422);

        $reschedule = PTMReschedule::create(['ptmid'=>$ptm->id,'ptmdateid'=>$validated['ptmdateid'],'ptmslotid'=>$validated['ptmslotid'],'userid'=>$user->id,'reason'=>$validated['reason'] ?? null,'childid'=>$child->id]);

        if ($user->userType === 'Parent') {
            try { $user->notify(new \App\Notifications\PTMRescheduled($ptm, $reschedule, $user->userType)); } catch (\Throwable $e) { Log::error($e->getMessage()); }
        } else {
            if ($child->parents) foreach ($child->parents as $parent) { try { $parent->notify(new \App\Notifications\PTMRescheduled($ptm, $reschedule, $user->userType)); } catch (\Throwable $e) { Log::error($e->getMessage()); } }
        }
        foreach ($ptm->staff as $staffUser) { try { $staffUser->notify(new \App\Notifications\PTMRescheduled($ptm, $reschedule, $user->userType)); } catch (\Throwable $e) { Log::error($e->getMessage()); } }

        return response()->json(['success' => true, 'message' => 'PTM successfully rescheduled.']);
    }

    public function ptmDetails(Request $request, $id)
    {
        $ptm = PTM::with([
            'children',
            'ptmDates',
            'ptmSlots',
            'reschedules.user',
            'reschedules.rescheduledate',
            'reschedules.rescheduleslot',
            'reschedules.child'
        ])->findOrFail($id);

        $defaultDate = optional(
            $ptm->ptmDates->sortBy('date')->first()
        )->date;

        $defaultSlot = optional(
            $ptm->ptmSlots->first()
        )->slot ?? 'N/A';

        $childrenData = $ptm->children->map(function ($child) use ($ptm, $defaultDate, $defaultSlot) {

            $childReschedules = $ptm->reschedules
                ->where('childid', $child->id)
                ->sortByDesc('created_at');

            $latestReschedule = $childReschedules->first();

            $rescheduledBy = $latestReschedule && $latestReschedule->user
                ? [
                    'id' => $latestReschedule->user->id,
                    'name' => $latestReschedule->user->name,
                    'userType' => ucfirst($latestReschedule->user->userType ?? 'User')
                ]
                : null;

            $childDate = $latestReschedule && $latestReschedule->rescheduledate
                ? $latestReschedule->rescheduledate->date
                : $defaultDate;

            $childSlot = $latestReschedule && $latestReschedule->rescheduleslot
                ? $latestReschedule->rescheduleslot->slot
                : $defaultSlot;

            $history = $childReschedules->values()->map(function ($res, $index) use ($childReschedules, $defaultDate, $defaultSlot) {

                $rescheduleArray = $childReschedules->values()->all();

                $totalCount = count($rescheduleArray);

                if ($index === $totalCount - 1) {

                    $prevDate = $defaultDate
                        ? Carbon::parse($defaultDate)->format('d-m-Y')
                        : 'N/A';

                    $prevSlot = $defaultSlot ?? 'N/A';

                } else {

                    $nextReschedule = $rescheduleArray[$index + 1];

                    $prevDate = $nextReschedule->rescheduledate
                        ? Carbon::parse($nextReschedule->rescheduledate->date)->format('d-m-Y')
                        : 'N/A';

                    $prevSlot = $nextReschedule->rescheduleslot->slot ?? 'N/A';
                }

                return [
                    'changed_at' => $res->created_at->format('d-m-Y H:i'),

                    'changed_by' => [
                        'name' => $res->user->name ?? 'Unknown',
                        'userType' => ucfirst($res->user->userType ?? 'User')
                    ],

                    'previous_date' => $prevDate,
                    'previous_slot' => $prevSlot
                ];

            })->values();

            return [
                'id' => $child->id,
                'name' => $child->name,
                'date' => $childDate,
                'slot' => $childSlot,
                'isRescheduled' => (bool) $latestReschedule,
                'rescheduledBy' => $rescheduledBy,
                'history' => $history
            ];
        });

        $dates = $ptm->ptmDates->map(function ($date) use ($ptm) {

            $slots = $ptm->ptmSlots
                ->where('ptmdate_id', $date->id)
                ->values()
                ->map(function ($slot) {

                    return [
                        'id' => $slot->id,
                        'slot' => $slot->slot
                    ];
                });

            return [
                'id' => $date->id,
                'date' => $date->date,
                'slots' => $slots
            ];
        });

        return response()->json([
            'success' => true,

            'ptm' => [
                'id' => $ptm->id,
                'title' => $ptm->title,
                'objective' => $ptm->objective,
                'status' => $ptm->status,
                'default_date' => $defaultDate,
                'default_slot' => $defaultSlot,
                'dates' => $dates
            ],

            'children' => $childrenData
        ]);
    }

     public function resupdateFromStaff(Request $request, $ptmId, $childid)
    {
     

        $validated = $request->validate(['ptmdateid'=>'required|integer|exists:ptmdate,id','ptmslotid'=>'required|integer|exists:ptmslot,id','reason'=>'nullable|string|max:500']);
        $ptm = PTM::findOrFail($ptmId);
        

        $child = Child::findOrFail($childid);
        $staff = auth()->user();
        $reschedule = PTMReschedule::create(['ptmid'=>$ptm->id,'ptmdateid'=>$validated['ptmdateid'],'ptmslotid'=>$validated['ptmslotid'],'userid'=>$staff->id,'reason'=>$validated['reason'] ?? null,'childid'=>$request->childid]);
        if ($child->parents) foreach ($child->parents as $parent) { $parent->notify(new \App\Notifications\PTMRescheduled($ptm, $reschedule)); }
        foreach ($ptm->staff as $staffMember) { $staffMember->notify(new \App\Notifications\PTMRescheduled($ptm, $reschedule)); }
        return response()->json(['success'=>true,'message'=>'PTM successfully rescheduled for '.$child->name.' by '.$staff->name]);
    }

    public function bulkResupdate(Request $request, $ptmId)
    {
        [$centerId, $currentCenter, $authError] = $this->resolveAuthorizedCenter($request);
        if ($authError) {
            return $authError;
        }

        $validated = $request->validate(['ptmdateid'=>'required|integer|exists:ptmdate,id','ptmslotid'=>'required|integer|exists:ptmslot,id','reason'=>'nullable|string|max:500','child_ids'=>'required|array','child_ids.*'=>'integer|exists:child,id']);
        $ptm = PTM::findOrFail($ptmId);
        if ((int) $ptm->centerid !== (int) $centerId) {
            return response()->json(['success' => false, 'message' => 'PTM not found for the provided center.'], 404);
        }

        $staff = auth()->user();
        $rescheduledChildren = [];
        foreach ($validated['child_ids'] as $childId) {
            $child = Child::find($childId); if (!$child) continue;
            $reschedule = PTMReschedule::create(['ptmid'=>$ptm->id,'ptmdateid'=>$validated['ptmdateid'],'ptmslotid'=>$validated['ptmslotid'],'userid'=>$staff->id,'reason'=>$validated['reason'] ?? null,'childid'=>$child->id]);
            if ($child->parents) foreach ($child->parents as $parent) $parent->notify(new \App\Notifications\PTMRescheduled($ptm, $reschedule));
            $rescheduledChildren[] = $child->name;
        }
        return response()->json(['success'=>true,'message'=>'PTM bulk rescheduled for: '.(count($rescheduledChildren)?implode(', ',$rescheduledChildren):'no children')]);
    }

    private function resolveAuthorizedCenter(Request $request)
    {
        $centerId = $request->input('center_id', $request->input('user_center_id', session('user_center_id')));
        if (!$centerId) {
            return [null, null, response()->json([
                'status' => false,
                'message' => 'center_id is required.',
            ], 422)];
        }

        $center = Center::find($centerId);
        if (!$center) {
            return [null, null, response()->json([
                'status' => false,
                'message' => 'Center not found.',
            ], 404)];
        }

        $user = Auth::user();
        if (in_array($user->userType, ['Superadmin', 'Centeradmin', 'Staff'], true)) {
            $isAllowed = Usercenter::where('userid', $user->id)->where('centerid', $centerId)->exists();
            if (!$isAllowed) {
                return [null, null, response()->json([
                    'status' => false,
                    'message' => 'You are not allowed to access this center.',
                ], 403)];
            }
        }

        return [(int) $centerId, $center, null];
    }
}