<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Center;
use App\Models\Child;
use App\Models\Childparent;
use App\Models\Usercenter;
use App\Models\PTM;
use App\Models\PTMChild;
use App\Models\PTMStaff;
use App\Models\PTMDate;
use App\Models\PTMSlot;
use App\Models\PTMRoom;
use App\Models\PTMReschedule;
use App\Models\Permission;
use App\Models\Room;
use App\Models\RoomStaff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Notifications\PTMAdded;
use App\Notifications\PTMRescheduled;
use Illuminate\Support\Facades\Mail;

class PTMController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $authId = $user->id;
        $centerId = session('user_center_id');

        // Initialize
        $centers = collect();
        $ptms = collect();

        // 🔹 Superadmin view
        if ($user->userType === 'Superadmin') {
            $centerIds = Usercenter::where('userid', $authId)->pluck('centerid');
            $centers = Center::whereIn('id', $centerIds)->get();

            $ptms = PTM::with(['staff', 'center', 'children', 'ptmDates','ptmSlots',
                'reschedules.rescheduledate',
                'reschedules.rescheduleslot'])
                ->withMin('ptmDates', 'date') 
                ->where('centerid', $centerId)
                ->latest()
                ->get();
        }

        // 🔹 Staff view
        elseif ($user->userType === 'Staff') {
            $centers = Center::where('id', $centerId)->get();

            $ptms = PTM::with(['staff', 'center', 'children', 'ptmDates','ptmSlots',
                'reschedules.rescheduledate',
                'reschedules.rescheduleslot'])
                ->withMin('ptmDates', 'date')
                ->where('centerid', $centerId)
                ->latest()
                ->get();
        }

        // 🔹 Parent view
        elseif ($user->userType === 'Parent') {
            $centers = Center::where('id', $centerId)->get();

            $ptms = PTM::with(['staff', 'center', 'children', 'ptmDates','ptmSlots',
                'reschedules.rescheduledate',
                'reschedules.rescheduleslot'])
                ->withMin('ptmDates', 'date')
                ->where('status', 'Published')
                ->latest()
                ->get();

            // Filter PTMs by their linked children
            $parentChildIds = $user->children->pluck('id')->toArray();

            $ptms = $ptms->filter(function ($ptm) use ($parentChildIds) {
                $ptmChildIds = $ptm->children->pluck('id')->toArray();
                return count(array_intersect($parentChildIds, $ptmChildIds)) > 0;
            });
        }

        $ptms->transform(function ($ptm) 
        {
            $latestReschedule = $ptm->reschedules->sortByDesc('created_at')->first();

            // ✅ Original values
            $ptm->originalDate = $ptm->ptmDates->min('date') ?? null;
            $ptm->originalSlot = $ptm->ptmSlots->first()->slot ?? null;

            // ✅ Final (rescheduled) values — if reschedule exists
            $ptm->finalDate = null;
            $ptm->finalSlot = null;

            if ($latestReschedule) {
                $ptm->finalDate = optional($latestReschedule->rescheduledate)->date;
                $ptm->finalSlot = optional($latestReschedule->rescheduleslot)->slot;
            }

            // ✅ Fallback to original if no reschedule exists
            if (!$ptm->finalDate) {
                $ptm->finalDate = $ptm->originalDate;
            }

            if (!$ptm->finalSlot) {
                $ptm->finalSlot = $ptm->originalSlot;
            }

            return $ptm;
        });

        // dd($ptms);

        // ✅ Separate PTMs by earliest date
        $upcomingptms = $ptms->filter(function ($p) {
            if (!$p->ptm_dates_min_date) return false;

            $earliest = \Carbon\Carbon::parse($p->ptm_dates_min_date);
            return $earliest->isToday() || $earliest->isFuture();
        });

        $attendedptms = $ptms->filter(function ($p) {
            if (!$p->ptm_dates_min_date) return false;

            $earliest = \Carbon\Carbon::parse($p->ptm_dates_min_date);
            return $earliest->lt(\Carbon\Carbon::today());
        });

        // ✅ Pass to view
        return view('ptm.index', compact(
            'centers',
            'centerId',
            'user',
            'upcomingptms',
            'attendedptms',
            'ptms'
        ));
    }

    public function storepage()
    {
        
        return view('ptm.storePtm');
    }

    public function store(Request $request)
    {
    
        $isEdit = $request->filled('id');

        $rules = [
            'selected_rooms'    => 'required',
            'selected_dates'    => 'required',
            'selected_slot'     => 'nullable',
            'title'             => 'required|string',
            'objective'         => 'nullable|string',
            'selected_children' => 'required|string',
            'selected_staff'    => 'nullable|string',
        ];

        $messages = [
            'selected_rooms.required'    => 'Please select at least one room.',
            'selected_dates.required'    => 'Please select at least one date.',
            'selected_slot.required'     => 'Please select at least one slot.',
            'selected_children.required' => 'Please select at least one child.',
            'ptmdate.required'           => 'Please select a PTM date.',
            'title.required'             => 'Please enter a title for the PTM.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
         return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $authId = Auth::user()->id;
            $action = $request->input('action');

            // Create or update PTM
            $ptm = $isEdit ? PTM::findOrFail($request->id) : new PTM();
            $ptm->title     = $request->input('title');
            $ptm->objective = $request->input('objective');
            $ptm->slot   = $request->input('selected_slot'); 
            $ptm->centerid = Session('user_center_id');
            $ptm->status    = $action;

            if (!$isEdit) {
                $ptm->userId = $authId;
            }
     
            $ptm->save(); // Save first to get ID
           
            $ptmId = $ptm->id;

            if ($isEdit) {
                $ptm->ptmDates()->delete();
                $ptm->ptmSlots()->delete();
            }

            // ✅ Save dates in ascending order and build a map of date => ptmdate_id
            $selectedDate = array_filter(explode(',', $request->input('selected_dates')));
            asort($selectedDate);

            $dateIdMap = [];
            foreach ($selectedDate as $date) {
                // because SQL expects Y-m-d, convert from d-m-Y
                $dateYMD = \Carbon\Carbon::createFromFormat('d-m-Y', trim($date))->format('Y-m-d');

                $ptmDate = PTMDate::create([
                    'ptm_id' => $ptmId,
                    'date'   => $dateYMD,
                ]);
                $dateIdMap[$dateYMD] = $ptmDate->id;
            }

            // ✅ Process date_slot_map and save slots with ptmdate_id linkage
            $dateSlotMapRaw = $request->input('date_slot_map');
            $dateSlotMap = [];
            if ($dateSlotMapRaw) {
                $decoded = json_decode($dateSlotMapRaw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $dateSlotMap = $decoded;
                } else {
                    Log::warning('PTM store: date_slot_map JSON decode failed', [
                        'raw' => $dateSlotMapRaw,
                        'json_error' => json_last_error_msg(),
                    ]);
                }
            } else {
                Log::info('PTM store: date_slot_map empty / not provided');
            }
            Log::debug('PTM store: received date_slot_map', [
                'raw' => $dateSlotMapRaw,
                'parsed_keys' => array_keys($dateSlotMap),
            ]);
            $earliestDate = null;
            $earliestSlot = null;

            if (!empty($dateSlotMap)) {
                // Filter to only dates that actually have at least one slot
                $nonEmptyDates = array_filter(array_keys($dateSlotMap), function ($d) use ($dateSlotMap) {
                    return !empty($dateSlotMap[$d]) && is_array($dateSlotMap[$d]);
                });

                // Sort dates in ascending order (Y-m-d format)
                sort($nonEmptyDates);

                // Save all slots per date (ensuring a PTMDate exists and mapping to ptmdate_id)
                foreach ($dateSlotMap as $dateYMD => $slots) {
                    if (empty($slots) || !is_array($slots)) {
                        continue;
                    }

                    // Ensure we have a PTMDate id for this date
                    if (!isset($dateIdMap[$dateYMD])) {
                        $ptmDate = PTMDate::create([
                            'ptm_id' => $ptmId,
                            'date'   => $dateYMD,
                        ]);
                        $dateIdMap[$dateYMD] = $ptmDate->id;
                    }

                    $createdForThisDate = 0;
                    foreach ($slots as $slot) {
                        PTMSlot::create([
                            'ptm_id'     => $ptmId,
                            'ptmdate_id' => $dateIdMap[$dateYMD],
                            'slot'       => $slot,
                        ]);
                        $createdForThisDate++;
                    }
                    Log::debug('PTM store: slots saved for date', [
                        'date' => $dateYMD,
                        'ptmdate_id' => $dateIdMap[$dateYMD],
                        'count' => $createdForThisDate,
                    ]);
                }

                // Compute earliest date and its earliest slot among non-empty dates only
                if (!empty($nonEmptyDates)) {
                    $earliestDate = $nonEmptyDates[0];
                    $slotsForEarliestDate = $dateSlotMap[$earliestDate] ?? [];

                    if (!empty($slotsForEarliestDate)) {
                        // Sort slots by start time (e.g., "09:00 AM - 10:00 AM")
                        usort($slotsForEarliestDate, function ($a, $b) {
                            $timeA = strtotime(explode(' - ', $a)[0]);
                            $timeB = strtotime(explode(' - ', $b)[0]);
                            return $timeA <=> $timeB;
                        });
                        $earliestSlot = $slotsForEarliestDate[0];
                    }
                }

                // Save earliest slot to PTM for quick access (date is already retrievable via ptmDates min)
                if ($earliestDate && $earliestSlot) {
                    $ptm->slot = $earliestSlot;
                    $ptm->save();
                }
            }

            // Process many-to-many relationships
            $selectedChildren = array_filter(explode(',', $request->input('selected_children')));
            sort($selectedChildren);
            $selectedStaff    = array_filter(explode(',', $request->input('selected_staff')));
            sort($selectedStaff);
            $selectedRooms    = array_filter(explode(',', $request->input('selected_rooms')));
            sort($selectedRooms);



            // Sync pivot tables
            $ptm->children()->sync($selectedChildren);
            $ptm->staff()->sync($selectedStaff);
            $ptm->room()->sync($selectedRooms);

            DB::commit();
              
            if(strtolower($action) == 'published'){
                $ptm = PTM::withMin('ptmDates', 'date')->find($ptm->id);
                foreach ($selectedChildren as $childId) {
                    $childId = trim($childId);
                    // dd($childId);
                    if ($childId !== '') {
                        // Get all related parent entries for this child
                        $parentRelations = Childparent::where('childid', $childId)->get();
                        foreach ($parentRelations as $relation) {
                            $parentUser = User::find($relation->parentid); // assuming users table stores parent records
                            // if ($parentUser) {
                            //     $parentUser->notify(new PTMAdded($ptm));
                            // }
                        }
                    }
                }

                if(!empty($selectedStaff) && count($selectedStaff)){
                    foreach ($selectedStaff as $key => $staffId) {
                        $staffUser = User::find($staffId);
                        if ($staffUser) {
                            $staffUser->notify(new PTMAdded($ptm));
                        }
                    }
                }



            }
            
          return redirect()
            ->route('ptm.index')
            ->with('success', 'PTM ' . ucfirst($action) . ' successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PTM Store/Update Failed: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->back()
                            ->with('error', 'An error occurred: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function getrooms()
    {
        try {
            $user = Auth::user();
            $rooms = collect();

            if ($user->userType === 'Superadmin') {
                $rooms = $this->getroomsforSuperadmin();
            } else {
                $rooms = $this->getroomsforStaff();
            }

            return response()->json([
                'rooms' => $rooms,
                'status' => 'success',
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getroomsforSuperadmin()
    {
        $authId = Auth::user()->id;
        $centerid = session('user_center_id');

        $rooms = Room::where('centerid', $centerid)->get();
        return $rooms;
    }

    private function getroomsforStaff()
    {
        $centerId = session('user_center_id');
        return Auth::user()
            ->rooms()
            ->where('centerid', $centerId)
            ->get();
    }

    public function getChildren(Request $request)
    {
        try {
            $user = Auth::user();
            $children = collect();
            $rooms = $request->rooms;

            $roomIds = !empty($rooms) ? explode(',', $rooms) : [];

            // dd($rooms);

            if ($user->userType === 'Superadmin') {
                $children = $this->getChildrenForSuperadmin($roomIds);
            } elseif ($user->userType === 'Staff') {
                $children = $this->getChildrenForStaff($roomIds);
            } else {
                $children = $this->getChildrenForParent($roomIds);
            }

            return response()->json([
                'children' => $children,
                'status' => 'success',
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getChildrenForSuperadmin($roomIds)
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $children = Child::whereIn('room', $roomIds)->where('status', 'Active')->orderBy('name', 'asc')->get();
         if (empty($roomIds)) {
        return collect(); // prevent empty whereIn() returning nothing
        }

        return $children;
    }

    private function getChildrenForStaff($allRoomIds)
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');
        $children = Child::whereIn('room', $allRoomIds)->where('status', 'Active')->orderBy('name', 'asc')->get();

        return $children;
    }

    private function getChildrenForParent($childids)
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // $childids = Childparent::where('parentid', $authId)->pluck('childid');

        $children = Child::whereIn('id', $childids)->where('status', 'Active')->orderBy('name', 'asc')->get();

        return $children;
    }

    public function getStaff(Request $request)
    {
        try {
            $roomIds = explode(',', $request->rooms); // selected room IDs
            $staff = collect();

            if (!empty($roomIds)) {
                $staff = RoomStaff::with('staff:id,name') // use your relationship name 'staff'
                    ->whereIn('roomid', $roomIds)         // correct column name from your model
                    ->get()
                    ->pluck('staff')                      // get only the staff data
                    ->filter()                            // remove nulls
                    ->unique('id')                        // remove duplicates
                    ->values();                           // reset array keys
            }

            return response()->json([
                'success' => true,
                'staff' => $staff,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching staff: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch staff',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function edit(PTM $ptm)
    {
        $ptm->load([
        'room' => fn($q) => $q->orderBy('name'),
        'children' => fn($q) => $q->orderBy('name'),
        'staff' => fn($q) => $q->orderBy('name'),
        'ptmDates', 
        'ptmSlots.ptmDate'
      ]);

        $rooms      = $ptm->room;
        $childrens  = $ptm->children;
        $educators  = $ptm->staff;
        $selectedRooms = $rooms->pluck('id')->toArray();
        $selectedChildren = $childrens->pluck('id')->toArray();
        $selectedStaff = $educators->pluck('id')->toArray();
        $selectedDates = $ptm->ptmDates->pluck('date')->toArray();
        $selectedSlots = $ptm->ptmSlots->pluck('slot')->toArray();
        $selectedSlotId = $ptm->ptmSlots->pluck('id')->toArray();
        
        // ✅ Group slots by date for edit mode
        $dateSlotMap = [];
        
        // Build a map of ptmdate_id => date string
        $dateIdToDateMap = [];
        foreach ($ptm->ptmDates as $ptmDate) {
            $dateIdToDateMap[$ptmDate->id] = $ptmDate->date;
        }
        
        foreach ($ptm->ptmSlots as $slotRecord) {
            $date = null;
            
            // Try to get date from relationship first
            if ($slotRecord->ptmDate) {
                $date = $slotRecord->ptmDate->date;
            } 
            // Fallback: use ptmdate_id to lookup
            elseif (isset($dateIdToDateMap[$slotRecord->ptmdate_id])) {
                $date = $dateIdToDateMap[$slotRecord->ptmdate_id];
            }
            
            if ($date) {
                if (!isset($dateSlotMap[$date])) {
                    $dateSlotMap[$date] = [];
                }
                $dateSlotMap[$date][] = $slotRecord->slot;
            }
        }
        
        Log::debug('PTM edit: dateSlotMap built', [
            'ptm_id' => $ptm->id,
            'slots_count' => $ptm->ptmSlots->count(),
            'dates_count' => $ptm->ptmDates->count(),
            'dateSlotMap' => $dateSlotMap,
        ]);
        
        $currentSlot = $ptm->slot;

        return view('ptm.storePtm', compact(
            'ptm', 'rooms', 'childrens', 'educators',
            'selectedRooms', 'selectedChildren', 'selectedStaff',
            'selectedDates', 'selectedSlots', 'currentSlot','selectedSlotId',
            'dateSlotMap'
        ));
    }

   
    public function delete(PTM $ptm)
    {
        DB::beginTransaction();

        try {

            $ptm->children()->detach();
            $ptm->staff()->detach();
            $ptm->room()->detach();
            $ptm->ptmDates()->delete();
            $ptm->ptmSlots()->delete();
            $ptm->reschedules->delete();
            

            $ptm->delete();

            DB::commit();

            return redirect()->route('ptm.index')->with('success', 'PTM deleted successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('PTM Deletion Failed: ' . $e->getMessage());

            return redirect()->back()
                            ->with('error', 'An error occurred while deleting the PTM: ' . $e->getMessage());
        }
    }

    public function view(PTM $ptm)
    {
        $ptm->load([
            'ptmDates',
            'ptmSlots',
            'room',
            'children',
            'staff',
            'ptmDates',
            'ptmSlots',
            'reschedules.rescheduledate',
            'reschedules.rescheduleslot',
            'reschedules.user'
        ]);

        $rooms     = $ptm->room;
        $childrens = $ptm->children;
        $educators = $ptm->staff;

        $latestReschedule = $ptm->reschedules->sortByDesc('created_at')->first();
        $rescheduledBy = null;
        if ($latestReschedule && $latestReschedule->user) {
            // If the reschedule was done by the current logged-in user, show "You"
            $rescheduledBy = $latestReschedule->user->id === auth()->id() 
                ? 'You' 
                : $latestReschedule->user->name;
        }

        // Final (current) values
        $finalDate = $latestReschedule && $latestReschedule->rescheduledate
            ? $latestReschedule->rescheduledate->date
            : ($ptm->ptmDates->min('date') ?? null);

        $finalSlot = $latestReschedule && $latestReschedule->rescheduleslot
            ? $latestReschedule->rescheduleslot->slot
            : ($ptm->slot ?? $ptm->ptmSlots->first()->slot ?? null);

        // Original (before reschedule) - for staff/admin showing default PTM schedule
        $originalDate = $ptm->ptmDates->min('date') ?? null;
        $originalSlot = $ptm->slot ?? null; // Use PTM table's slot column as default

        // Flag
        $isRescheduled = !is_null($latestReschedule);

         // Send all affiliated dates & slots to view
        $ptmDates = $ptm->ptmDates;
        $ptmSlots = $ptm->ptmSlots;

        $child = null;
        if (auth()->user()->userType === 'Parent') {
            $child = $ptm->children->first(); // or filter by logged-in parent
        }

        return view('ptm.viewptm', compact(
            'ptm',
            'rooms',
            'childrens',
            'educators',
            'finalDate',
            'finalSlot',
            'originalDate',
            'originalSlot',
            'isRescheduled',
            'rescheduledBy',
            'ptmDates',
            'ptmSlots',
            'child'
        ));
    }


    public function getPtmEvents()
    {
        // Build events from related ptmDates to avoid querying a non-existent
        // `ptmdate` column on the `ptm` table. Use the earliest associated date.
        $centerId = session('user_center_id');

        $ptms = PTM::with('ptmDates')
            ->when($centerId, fn($q) => $q->where('centerid', $centerId))
            ->where('status', 'Published')
            ->get();

        $events = $ptms->map(function ($ptm) {
            $firstDate = $ptm->ptmDates->min('date');
            $date = $firstDate ? Carbon::parse($firstDate)->format('Y-m-d') : null;

            return [
                'id'    => $ptm->id,
                'title' => $ptm->title ?? 'PTM',
                'date'  => $date,
                'type'  => 'ptm',
            ];
        })->filter();

        return response()->json([
            'status' => true,
            'events' => $events,
        ]);
    }

    public function directPublish(PTM $ptm)
    {
        try {
            $ptm->status = 'Published';
            $ptm->save();

            // Notify parents
            $selectedChildren = $ptm->children->pluck('id')->toArray();

            foreach ($selectedChildren as $childId) {
                $childId = trim($childId);
                if ($childId !== '') {
                    $parentRelations = Childparent::where('childid', $childId)->get();
                    foreach ($parentRelations as $relation) {
                        $parentUser = User::find($relation->parentid);
                        if ($parentUser) {
                            $parentUser->notify(new PTMAdded($ptm));
                        }
                    }
                }
            }

            return redirect()
                ->route('ptm.index')
                ->with('success', 'PTM Published successfully.');
        } catch (\Exception $e) {
            Log::error('Direct Publish Failed: ' . $e->getMessage());

            return redirect()->back()
                             ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function events()
    {
        $centerId = session('user_center_id');
        $user = auth()->user();

        // Load ptms with related dates, reschedules and children so we can decide per-child reschedules
        $ptms = PTM::with(['ptmDates', 'reschedules.rescheduledate', 'children'])
            ->when($centerId, fn($q) => $q->where('centerid', $centerId))
            ->where('status', 'Published')
            ->get();

        $events = collect();

        foreach ($ptms as $ptm) {
            $firstDate = $ptm->ptmDates->min('date');
            $originalDate = $firstDate ? \Carbon\Carbon::parse($firstDate)->format('Y-m-d') : null;

            // If logged in user is a Parent, return one event per child of that parent (respecting per-child reschedule)
            if ($user && $user->userType === 'Parent') {
                $parentChildIds = $user->children->pluck('id')->toArray();
                $ptmChildIds = $ptm->children->pluck('id')->toArray();

                $intersect = array_intersect($parentChildIds, $ptmChildIds);

                foreach ($intersect as $childId) {
                    // look for latest reschedule for this ptm + child
                    $reschedule = $ptm->reschedules->where('childid', $childId)->sortByDesc('created_at')->first();

                    $dateForChild = $originalDate;
                    if ($reschedule && optional($reschedule->rescheduledate)->date) {
                        $dateForChild = \Carbon\Carbon::parse($reschedule->rescheduledate->date)->format('Y-m-d');
                    }

                    $child = $ptm->children->where('id', $childId)->first();

                    $events->push([
                        'id' => $ptm->id,
                        'title' => $ptm->title,
                        'ptmdate' => $originalDate,
                        'date' => $dateForChild,
                        'objective' => $ptm->objective ?? null,
                        'childid' => $childId,
                        'childname' => $child ? ($child->name ?? null) : null,
                    ]);
                }
            } else {
                // Non-parent users: keep single event per PTM (uses earliest date)
                $events->push([
                    'id' => $ptm->id,
                    'title' => $ptm->title,
                    'ptmdate' => $originalDate,
                    'date' => $originalDate,
                    'objective' => $ptm->objective ?? null,
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'events' => $events->values()->all()
        ]);
    }

    public function getSlots(Request $request)
    {
        // Static slots for testing - will be replaced with DB logic later
        $staticSlots = [
            ['id' => 1, 'time' => '09:00 AM - 10:00 AM'],
            ['id' => 2, 'time' => '10:00 AM - 11:00 AM'],
            ['id' => 3, 'time' => '11:00 AM - 12:00 PM'],
            ['id' => 4, 'time' => '12:00 PM - 01:00 PM'],
            ['id' => 5, 'time' => '02:00 PM - 03:00 PM'],
            ['id' => 6, 'time' => '03:00 PM - 04:00 PM'],
            ['id' => 7, 'time' => '04:00 PM - 05:00 PM'],
        ];

        return response()->json([
            'success' => true,
            'slot' => $staticSlots
        ]);

        // TODO: Future DB implementation
        // $date = $request->date;
        // $roomIds = explode(',', $request->rooms);
        // $slots = Slot::whereIn('room_id', $roomIds)
        //              ->whereDate('date', $date)
        //              ->get(['id', 'time']);
        // return response()->json(['success' => true, 'slot' => $slots]);
    }

    // public function getSlots(Request $request)
    // {
    //     //  $roomIds = explode(',', $request->rooms);
    //      $selectedslot = $request->selectedslot;
    //     // print_r($selectedslot); die();
    //     // Example static slot data — you’ll replace with actual DB logic
    //     //S$slots = 
    //     $selectedslotid = $request->selectedslotid;
    //     // print_r($selectedslotid); die();
    //     $slot = explode(',', $request->selectedslot);
    //     $slotid = explode(',', $request->selectedslotid);
    //     $result = [];

    //     for ($i = 0; $i < count($slotid); $i++) {
    //         $result[] = [
    //             'id' => $slotid[$i],
    //             'time' => $slot[$i]
    //         ];
    //     }
    //     // print_r($result);die();
    //     // Example: if you store slots per room in DB
    //     // $slots = Slot::whereIn('room_id', $roomIds)->whereDate('date', $date)->get(['id', 'time']);

    //     return response()->json([
    //         'success' => true,
    //         'slot' => $result
    //     ]);
    // }

    public function getPtmDateSlots(Request $request)
    {
        $ptms = PTM::with(['staff', 'center', 'children', 'ptmDates','ptmSlots'])->find($request->ptmid);
         return response()->json([
            'success' => true,
            'ptm' => $ptms,
        ]);
         return response()->json($ptms);
    }
    
    public function reschedulePtm(Request $request)
    {
        // ✅ Step 1: Validate the main fields (no need for childid from form)
        $validated = $request->validate([
            'ptmid'      => 'required|integer|exists:ptm,id',
            'ptmdateid'  => 'required|integer|exists:ptmdate,id',
            'ptmslotid'  => 'required|integer|exists:ptmslot,id',
            'userid'     => 'required|integer|exists:users,id',
            'reason'     => 'nullable|string|max:500',
        ]);

        // ✅ Step 2: Get logged-in user and main PTM
        $user = auth()->user();
        $usertype = auth()->user()->userType;
     
        $ptm  = PTM::with('children')->findOrFail($validated['ptmid']);

        // ✅ Step 3: Determine the correct child automatically
        $child = null;

        if ($user->userType === 'Parent') {
            // Find the child linked to this parent for the current PTM
            $child = $ptm->children
                ->filter(fn($c) => $c->parents && $c->parents->contains('id', $user->id))
                ->first();

            // Fallback if no direct relation found
            if (!$child) {
                $child = $ptm->children->first();
            }
        } else {
            // For staff/admin users, accept childid from request (if any)
            if ($request->filled('childid')) {
                $child = \App\Models\Child::find($request->childid);
            }
        }

        // ✅ Step 4: Safety check — ensure we have a child
        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'No child record found for this user/PTM.',
            ], 422);
        }

        // ✅ Step 5: Create reschedule record
        $reschedule = PTMReschedule::create([
            'ptmid'     => $ptm->id,
            'ptmdateid' => $validated['ptmdateid'],
            'ptmslotid' => $validated['ptmslotid'],
            'userid'    => $user->id,
            'reason'    => $validated['reason'] ?? null,
            'childid'   => $child->id,  // ✅ Auto filled dynamically
        ]);

        // ✅ Step 6: Notify related users
        // Notify the parent (if reschedule done by staff)
        if ($user->userType !== 'Parent' && $child->parents) {
            foreach ($child->parents as $parent) {
                $parent->notify(new PTMRescheduled($ptm, $reschedule,$usertype));
            }
        }

        // Notify all staff
        foreach ($ptm->staff as $staffUser) {
            if ($staffUser instanceof \App\Models\User) {
                $staffUser->notify(new PTMRescheduled($ptm, $reschedule,$usertype));
            }
        }

        // ✅ Step 7: Return response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'PTM successfully rescheduled.',
                'redirect' => route('ptm.index'),
            ]);
        }

        return redirect()->back()->with('success', 'PTM successfully rescheduled.');
    }

    //  public function showresheduled($id)
    // {
    //     $ptm = PTM::with(['ptmDates', 'ptmSlots', 'rescheduleptm'])
    //         ->findOrFail($id);

    //     // Get latest reschedule date if available
    //     $latestReschedule = $ptm->reschedules->sortByDesc('created_at')->first();

    //     // If rescheduled, show that date, otherwise show earliest PTM date
    //     $finalDate = $latestReschedule
    //         ? $latestReschedule->ptmdate
    //         : ($ptm->ptmDates->min('date') ?? null);

    //     return view('ptm.viewptm', compact('ptm', 'finalDate'));
    // }

    public function show($id)
    {
        $ptm = PTM::with(['ptmDates', 'ptmSlots', 'reschedulePtm', 'user'])->findOrFail($id);

        return view('ptm.view', compact('ptm'));
    }

    public function ptmDetails($id)
    {
        $ptm = PTM::with([
            'children.parents',
            'ptmDates',
            'ptmSlots',
            'reschedules.user',
            'reschedules.rescheduledate',
            'reschedules.rescheduleslot',
            'reschedules.child', // ✅ ensure we know which child belongs to this reschedule
        ])->findOrFail($id);

        $defaultDate = $ptm->ptmDates->sortBy('date')->first()->date ?? null;
        $defaultSlot = $ptm->ptmSlots->first()->slot ?? 'N/A';

        // ✅ Map each child with their latest reschedule (by any user)
        $childrenData = $ptm->children->map(function ($child) use ($ptm, $defaultDate, $defaultSlot) {

            // ✅ get all reschedules for this child, no matter who did them
            $childReschedules = $ptm->reschedules
                ->where('childid', $child->id)
                ->sortByDesc('created_at');

            $latestReschedule = $childReschedules->first();

            $rescheduledBy = null;
            if ($latestReschedule && $latestReschedule->user) {
                $rescheduledBy = [
                    'id' => $latestReschedule->user->id,
                    'name' => $latestReschedule->user->name,
                    'userType' => ucfirst($latestReschedule->user->userType ?? 'User'),
                ];
            }

            $childDate = $latestReschedule && $latestReschedule->rescheduledate
                ? $latestReschedule->rescheduledate->date
                : $defaultDate;

            $childSlot = $latestReschedule && $latestReschedule->rescheduleslot
                ? $latestReschedule->rescheduleslot->slot
                : $defaultSlot;

            // 🔹 Prepare full reschedule history for this child
                $history = $childReschedules->values()->map(function ($res, $index) use ($childReschedules, $defaultDate, $defaultSlot) {
                    // Convert to indexed array for proper access
                    $rescheduleArray = $childReschedules->values()->all();
                    $totalCount = count($rescheduleArray);
                    
                    // Get previous values: if first reschedule, use default PTM date/slot; otherwise use previous reschedule
                    if ($index === $totalCount - 1) {
                        // First reschedule (oldest) - use default/original PTM date and slot
                        $prevDate = $defaultDate ? \Carbon\Carbon::parse($defaultDate)->format('d-m-Y') : 'N/A';
                        $prevSlot = $defaultSlot ?? 'N/A';
                    } else {
                        // Subsequent reschedules - use the next reschedule's values (which were the previous values)
                        $nextReschedule = $rescheduleArray[$index + 1];
                        $prevDate = $nextReschedule->rescheduledate 
                            ? \Carbon\Carbon::parse($nextReschedule->rescheduledate->date)->format('d-m-Y')
                            : 'N/A';
                        $prevSlot = $nextReschedule->rescheduleslot->slot ?? 'N/A';
                    }
                    
                    return [
                        'changed_at' => $res->created_at->format('d-m-Y H:i'),
                        'changed_by' => [
                            'name' => $res->user->name ?? 'Unknown',
                            'userType' => ucfirst($res->user->userType ?? 'User'),
                        ],
                        'previous_date' => $prevDate,
                        'previous_slot' => $prevSlot,
                    ];
                })->values();
              
            return [
                'id' => $child->id,
                'name' => $child->name,
                'date' => $childDate,
                'slot' => $childSlot,
                'isRescheduled' => (bool) $latestReschedule,
                'rescheduledBy' => $rescheduledBy,
                'history' => $history,
            ];
        });

        return view('ptm.ptmdetails', compact('ptm', 'childrenData'));
    }
   
    public function rescheduleFromStaff($ptmId, $childId)
    {
        $ptm = PTM::with(['ptmDates', 'ptmSlots', 'reschedules.rescheduledate', 'reschedules.rescheduleslot'])->findOrFail($ptmId);
        $child = Child::findOrFail($childId);

        // Get available dates and slots
        $availableDates = $ptm->ptmDates;
        $availableSlots = $ptm->ptmSlots;
        
        // Get current date and slot for this specific child
        $childReschedules = $ptm->reschedules
            ->where('childid', $childId)
            ->sortByDesc('created_at');
        
        $latestReschedule = $childReschedules->first();
        
        // Default date and slot (earliest/first)
        $defaultDate = $ptm->ptmDates->min('date');
        $defaultSlotText = $ptm->slot; // From PTM table slot column
        
        // Current date and slot for this child
        $currentDate = $latestReschedule && $latestReschedule->rescheduledate
            ? $latestReschedule->rescheduledate->date
            : $defaultDate;
        
        $currentSlotText = $latestReschedule && $latestReschedule->rescheduleslot
            ? $latestReschedule->rescheduleslot->slot
            : $defaultSlotText;
        
        // Find the IDs for preselection
        $currentDateId = $latestReschedule && $latestReschedule->rescheduledate
            ? $latestReschedule->rescheduledate->id
            : optional($ptm->ptmDates->firstWhere('date', $defaultDate))->id;
        
        // Find slot ID by matching the slot text
        $currentSlotId = $latestReschedule && $latestReschedule->rescheduleslot
            ? $latestReschedule->rescheduleslot->id
            : optional($ptm->ptmSlots->firstWhere('slot', $defaultSlotText))->id;

        return view('ptm.staffres', compact('ptm', 'child', 'availableDates', 'availableSlots', 'currentDateId', 'currentSlotId', 'currentDate', 'currentSlotText'));
    }

    public function resupdateFromStaff(Request $request, $ptmId, $childid)
    {
        // ✅ Validation (matches your DB columns)
        $validated = $request->validate([
            'ptmdateid'  => 'required|integer|exists:ptmdate,id',
            'ptmslotid'  => 'required|integer|exists:ptmslot,id',
            'reason'     => 'nullable|string|max:500',
        ]);

        // ✅ Get the PTM, Child, and current logged-in staff (teacher)
        $ptm   = \App\Models\PTM::findOrFail($ptmId);
        $child = \App\Models\Child::findOrFail($childid);
        $staff = auth()->user(); // currently logged-in teacher

        // ✅ Create new reschedule record
        $reschedule = \App\Models\PTMReschedule::create([
            'ptmid'     => $ptm->id,
            'ptmdateid' => $validated['ptmdateid'],
            'ptmslotid' => $validated['ptmslotid'],
            'userid'    => $staff->id,  // who did the reschedule
            'reason'    => $validated['reason'] ?? null,
            'childid'   => $request->childid,  // optional if your table has it
        ]);

        // ✅ Notify the parent (if any linked to this child)
        if ($child->parents && $child->parents->count() > 0) {
            foreach ($child->parents as $parent) {
                $parent->notify(new \App\Notifications\PTMRescheduled($ptm, $reschedule));
            }
        }

        // ✅ Notify the staff involved (optional)
        foreach ($ptm->staff as $staffMember) {
            $staffMember->notify(new \App\Notifications\PTMRescheduled($ptm, $reschedule));
        }

        // ✅ Redirect back with success message
        return redirect()
            ->route('ptm.details', $ptm->id)
            ->with('success', 'PTM successfully rescheduled for ' . $child->name . ' by ' . $staff->name);
    }

    public function bulkReschedulePage(Request $request, $ptmId)
    {
        $ptm = \App\Models\PTM::findOrFail($ptmId);
        $childIds = $request->query('child_ids', []);

        // dd($childIds);
        if (empty($childIds)) {
            return redirect()->route('ptm.details', $ptmId)
                ->with('error', 'No children selected for reschedule.');
        }
        // dd($ptm->ptmSlots);
        $children = \App\Models\Child::whereIn('id', $childIds)->get();

        return view('ptm.bulk-reschedule-staff', compact('ptm', 'children'));
    }

    public function bulkResupdate(Request $request, $ptmId)
    {
        // ✅ Step 1: Validate all required fields
        $validated = $request->validate([
            'ptmdateid'   => 'required|integer|exists:ptmdate,id',
            'ptmslotid'   => 'required|integer|exists:ptmslot,id',
            'reason'      => 'nullable|string|max:500',
            'child_ids'   => 'required|array',
            'child_ids.*' => 'integer|exists:child,id',
        ]);
        //  dd($request->child_ids);die();

        // ✅ Step 2: Get PTM and logged-in staff (teacher)
        $ptm   = \App\Models\PTM::findOrFail($ptmId);
        $staff = auth()->user();

        // ✅ Step 3: Keep track of all rescheduled children
        $rescheduledChildren = [];

        // ✅ Step 4: Loop through selected child IDs
        foreach ($validated['child_ids'] as $childId) {
            $child = \App\Models\Child::find($childId);
            if (!$child) continue;

            // ✅ Step 5: Create a new reschedule record
            $reschedule = \App\Models\PTMReschedule::create([
                'ptmid'     => $ptm->id,
                'ptmdateid' => $validated['ptmdateid'],
                'ptmslotid' => $validated['ptmslotid'],
                'userid'    => $staff->id,
                'reason'    => $validated['reason'] ?? null,
                'childid'   => $child->id,
            ]);

            // ✅ Step 6: Notify parents (if any)
            if ($child->parents && $child->parents->count() > 0) {
                foreach ($child->parents as $parent) {
                    $parent->notify(new \App\Notifications\PTMRescheduled($ptm, $reschedule));
                }
            }

            // ✅ Step 7: Notify other staff members (optional)
            if ($ptm->staff && $ptm->staff->count() > 0) {
                foreach ($ptm->staff as $staffMember) {
                    $staffMember->notify(new \App\Notifications\PTMRescheduled($ptm, $reschedule));
                }
            }

            // ✅ Step 8: Store child name for success message
            $rescheduledChildren[] = $child->name;
        }

        // ✅ Step 9: Prepare user feedback
        $childNames = count($rescheduledChildren) > 0
            ? implode(', ', $rescheduledChildren)
            : 'no children';

        // ✅ Step 10: Redirect back with a success message
        return redirect()
            ->route('ptm.details', $ptm->id)
            ->with('success', "PTM successfully rescheduled for: {$childNames} by {$staff->name}");
    }


    




}