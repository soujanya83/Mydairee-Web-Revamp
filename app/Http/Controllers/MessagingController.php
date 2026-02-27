<?php
namespace App\Http\Controllers;
use App\Models\Message;
use App\Models\User;
use App\Models\Usercenter;
use App\Models\GroupMessage;
use App\Models\Child;
use App\Models\Childparent;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MessagingController extends Controller
{
    
    public function index()
    {
        $me = Auth::user();
        $permissions = app('userPermissions');
        $isParent = strtolower($me->userType ?? '') === 'parent';
        if (!$isParent && (empty($permissions['viewMessages']) || !$permissions['viewMessages'])) {
            abort(403, 'You do not have permission to view messages');
        }

        $centerIds = Usercenter::where('userid', $me->id)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
        return view('messaging.index', compact('centers'));
    }

    
    public function contacts(Request $request)
    {
        $me = Auth::user();
        $centerid = session('user_center_id');

        if ((int)$request->input('receiver_id') === (int)$me->id) {
            return response()->json(['success' => false, 'message' => 'You cannot message yourself'], 400);
        }

        if (empty($centerid)) {
            return response()->json(['success' => false, 'message' => 'Center not selected'], 400);
        }

        $userIds = Usercenter::where('centerid', $centerid)->pluck('userid')->toArray();

        if ($me->userType === 'Parent') {

            $childIds = Childparent::where('parentid', $me->id)->pluck('childid')->toArray();
            
            if (empty($childIds)) {
                $contacts = collect([]);
            } else {

                $childRoomIds = Child::whereIn('id', $childIds)
                    ->whereIn('centerid', [$centerid])
                    ->pluck('room')->toArray();
                
                if (empty($childRoomIds)) {
                    $contacts = collect([]);
                } else {
                    $staffIdsFromRooms = DB::table('room_staff')
                        ->whereIn('roomid', $childRoomIds)
                        ->pluck('staffid')
                        ->map(function($id) { return (int) $id; })
                        ->toArray();
                    
                    $contacts = User::where('userType', 'Staff')
                        ->whereIn('id', $staffIdsFromRooms)
                        ->whereIn('id', $userIds)
                        ->select('id', 'name', 'imageUrl', 'userType')
                        ->get();
                }
            }
        } elseif (in_array(strtolower($me->userType), ['admin', 'superadmin', 'manager'])) {
            $contacts = User::whereIn('id', $userIds)
                ->whereIn('userType', ['Parent', 'Staff', 'Admin', 'Superadmin', 'Manager'])
                ->select('id', 'name', 'imageUrl', 'userType')
                ->get();
        } else {
            $staffRoomIds = DB::table('room_staff')
                ->where('room_staff.staffid', $me->id)
                ->join('room', 'room_staff.roomid', '=', 'room.id')
                ->where('room.centerid', $centerid)
                ->pluck('room.id')
                ->toArray();
            
            if (empty($staffRoomIds)) {
                $adminContacts = User::whereIn('id', $userIds)
                    ->whereIn('userType', ['Admin', 'Superadmin', 'Manager'])
                    ->select('id', 'name', 'imageUrl', 'userType')
                    ->get();
                $contacts = $adminContacts;
            } else {
                // Get children in staff's rooms, then their parents
                $parentIds = Child::whereIn('room', $staffRoomIds)
                    ->join('childparent', 'child.id', '=', 'childparent.childid')
                    ->pluck('childparent.parentid')
                    ->unique()
                    ->toArray();

                $parentContacts = User::whereIn('id', $parentIds)
                    ->where('userType', 'Parent')
                    ->select('id', 'name', 'imageUrl', 'userType')
                    ->get();

                $adminContacts = User::whereIn('id', $userIds)
                    ->whereIn('userType', ['Admin', 'Superadmin', 'Manager'])
                    ->select('id', 'name', 'imageUrl', 'userType')
                    ->get();

                $contacts = $parentContacts->merge($adminContacts)->unique('id')->values();
            }
        }

        $sentToQ = Message::where('sender_id', $me->id);
        $receivedFromQ = Message::where('receiver_id', $me->id);
        if (!empty($centerid)) {
            $sentToQ->where('centerid', $centerid);
            $receivedFromQ->where('centerid', $centerid);
        }
        $sentTo = $sentToQ->pluck('receiver_id');
        $receivedFrom = $receivedFromQ->pluck('sender_id');
        $partnerIds = $sentTo->merge($receivedFrom)->unique()->filter(function ($id) use ($me) {
            return $id && $id != $me->id;
        })->values()->toArray();

        if (!empty($partnerIds)) {
            if ($me->userType === 'Parent') {
                $childIds = Childparent::where('parentid', $me->id)->pluck('childid')->toArray();
                
                if (!empty($childIds)) {
                    $childRoomIds = Child::whereIn('id', $childIds)
                        ->whereIn('centerid', [$centerid])
                        ->pluck('room')->toArray();
                    
                    if (!empty($childRoomIds)) {
                        $staffIdsFromRooms = DB::table('room_staff')
                            ->whereIn('roomid', $childRoomIds)
                            ->pluck('staffid')
                            ->map(function($id) { return (int) $id; })
                            ->toArray();
                        
                            $partners = User::whereIn('id', $partnerIds)
                                ->whereIn('id', $userIds)
                                ->where('userType', '!=', 'Parent')
                                ->select('id', 'name', 'imageUrl', 'userType')
                                ->get();
                            $contacts = $contacts->merge($partners)->unique('id')->values();
                    }
                }
            } elseif (in_array(strtolower($me->userType), ['admin', 'superadmin', 'manager'])) {
                // Admin/Superadmin/Manager: include all parents and staff
                $partners = User::whereIn('id', $partnerIds)
                    ->whereIn('id', $userIds)
                    ->whereIn('userType', ['Parent', 'Staff', 'Admin', 'Superadmin', 'Manager'])
                    ->select('id', 'name', 'imageUrl', 'userType')
                    ->get();
                $contacts = $contacts->merge($partners)->unique('id')->values();
            } else {
                // Staff: include parents from their rooms
                $partners = User::whereIn('id', $partnerIds)
                    ->whereIn('id', $userIds)
                    ->where('userType', 'Parent')
                    ->select('id', 'name', 'imageUrl', 'userType')
                    ->get();
                $contacts = $contacts->merge($partners)->unique('id')->values();
            }
        }

        
        // remove self from contacts list
        $contacts = $contacts->reject(function ($c) use ($me) {
            return (int)$c->id === (int)$me->id;
        })->values();

        $contacts = $contacts->map(function ($c) use ($me, $centerid) {
            $last = Message::where(function ($q) use ($me, $c) {
                $q->where('sender_id', $me->id)->where('receiver_id', $c->id);
            })->orWhere(function ($q) use ($me, $c) {
                $q->where('sender_id', $c->id)->where('receiver_id', $me->id);
            })->orderBy('created_at', 'desc')->first();

            $last_message = $last ? $last->body : null;
            $last_at = $last ? $last->created_at : null;
            $last_at_ts = $last_at ? strtotime($last_at) : null;

            
            $unreadQ = Message::where('sender_id', $c->id)
                ->where('receiver_id', $me->id)
                ->whereNull('read_at');
            if (!empty($centerid)) $unreadQ->where('centerid', $centerid);
            $unread_count = $unreadQ->count();

            
            $children = [];
            try {
                if (isset($c->userType) && strtolower($c->userType) === 'parent') {
                    $children = Child::whereHas('parents', function ($q) use ($c) {
                        $q->where('parentid', $c->id)->orWhere('parentid', $c->id);
                    })->select('name','lastname')->get()->map(function ($ch) {
                        $full = trim(($ch->name ?? '') . ' ' . ($ch->lastname ?? ''));
                        return $full ?: trim($ch->name ?? '');
                    })->values()->toArray();
                }
            } catch (\Throwable $e) {
                $children = [];
            }

            
            $rooms = [];
            try {
                if (isset($me->userType) && strtolower($me->userType) === 'superadmin' && isset($c->userType) && strtolower($c->userType) === 'staff') {
                    if (method_exists($c, 'rooms')) {
                        try {
                            $rooms = $c->rooms()->pluck('name')->toArray();
                        } catch (\Throwable $inner) {
                            $rooms = [];
                        }
                    }
                }
            } catch (\Throwable $e) {
                $rooms = [];
            }

            return [
                'id' => $c->id,
                'name' => $c->name,
                'imageUrl' => $c->imageUrl ?? null,
                'userType' => $c->userType ?? null,
                'last_message' => $last_message,
                'last_at' => $last_at ? (string)$last_at : null,
                'last_at_ts' => $last_at_ts,
                'unread_count' => $unread_count,
                'children' => $children,
                'rooms' => $rooms,
            ];
        });

        
        $contacts = $contacts->sort(function ($a, $b) {
            $a_ts = $a['last_at_ts'] ?? null;
            $b_ts = $b['last_at_ts'] ?? null;
            if ($a_ts && $b_ts) {
                if ($a_ts == $b_ts) return strcasecmp($a['name'] ?? '', $b['name'] ?? '');
                return $a_ts < $b_ts ? 1 : -1; // desc
            }
            if ($a_ts && !$b_ts) return -1;
            if (!$a_ts && $b_ts) return 1;
            return strcasecmp($a['name'] ?? '', $b['name'] ?? '');
        })->values();

        return response()->json(['success' => true, 'contacts' => $contacts]);
    }


    public function thread($id)
    {
        $me = Auth::user();
        $centerid = session('user_center_id');
        $other = User::find($id);
        if (!$other) return response()->json(['success' => false, 'message' => 'User not found'], 404);

        // mark messages sent to me by the other user as read (exclude group messages)
        try {
            $updateQ = Message::where('sender_id', $other->id)
                ->where('receiver_id', $me->id)
                ->whereNull('read_at');
            if (!empty($centerid)) {
                $updateQ->where('centerid', $centerid);
            }
            if (Schema::hasColumn('messages', 'is_group')) {
                $updateQ->where('is_group', 0);
            }
            $updateQ->update(['read_at' => now()]);
        } catch (\Throwable $e) {
            
        }


        $q = Message::where(function ($q) use ($me, $other) {
            $q->where('sender_id', $me->id)->where('receiver_id', $other->id);
        })->orWhere(function ($q) use ($me, $other) {
            $q->where('sender_id', $other->id)->where('receiver_id', $me->id);
        });
        if (!empty($centerid)) {
            $q->where('centerid', $centerid);
        }
        if (Schema::hasColumn('messages', 'is_group')) {
            $q->where('is_group', 0);
        }
        $messages = $q->orderBy('created_at', 'asc')->limit(500)->get();

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function groupThread(Request $request)
    {
        $me = Auth::user();
        $centerid = session('user_center_id');

        if (empty($centerid)) {
            return response()->json(['success' => false, 'message' => 'Center not selected'], 400);
        }

        // mark group messages addressed to me as read and fetch messages
        try {
            $userIdsInCenter = Usercenter::where('centerid', $centerid)->pluck('userid')->toArray();

            
            if (Schema::hasTable('group_messages')) {
                
                $gm = GroupMessage::where('centerid', $centerid)->orderBy('created_at', 'asc')->get();
                
                $userCache = [];
                
                $messages = $gm->map(function ($g) use ($me, &$userCache) {
                    $sender = null;
                    if (!empty($g->sender_id)) {
                        if (isset($userCache[$g->sender_id])) {
                            $sender = $userCache[$g->sender_id];
                        } else {
                            $s = User::find($g->sender_id);
                            $sender = $s ? ['name' => $s->name, 'userType' => $s->userType ?? null, 'imageUrl' => $s->imageUrl ?? null] : null;
                            $userCache[$g->sender_id] = $sender;
                        }
                    }
                    return [
                        'id' => $g->id,
                        'sender_id' => $g->sender_id,
                        'sender_name' => $sender['name'] ?? null,
                        'sender_userType' => $sender['userType'] ?? null,
                        'sender_imageUrl' => $sender['imageUrl'] ?? null,
                        'body' => $g->body,
                        'created_at' => $g->created_at ? (string)$g->created_at : null,
                        'read_at' => null,
                    ];
                })->toArray();
            } elseif (Schema::hasColumn('messages', 'is_group')) {
                $rows = Message::where('centerid', $centerid)
                    ->where('is_group', 1)
                    ->where(function ($q) use ($me, $userIdsInCenter) {
                        $q->whereIn('sender_id', $userIdsInCenter)->orWhereIn('receiver_id', $userIdsInCenter);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
                $grouped = [];
                $userCache = [];
                foreach ($rows as $r) {
                    $sig = $r->sender_id . '|' . md5($r->body) . '|' . ($r->created_at ? $r->created_at->format('Y-m-d H:i:s') : '');
                    if (!isset($grouped[$sig])) {
                        
                        $sender = null;
                        if (!empty($r->sender_id)) {
                            if (isset($userCache[$r->sender_id])) {
                                $sender = $userCache[$r->sender_id];
                            } else {
                                $s = User::find($r->sender_id);
                                $sender = $s ? ['name' => $s->name, 'userType' => $s->userType ?? null, 'imageUrl' => $s->imageUrl ?? null] : null;
                                $userCache[$r->sender_id] = $sender;
                            }
                        }

                        $grouped[$sig] = [
                            'id' => $r->id,
                            'sender_id' => $r->sender_id,
                            'sender_name' => $sender['name'] ?? null,
                            'sender_userType' => $sender['userType'] ?? null,
                            'sender_imageUrl' => $sender['imageUrl'] ?? null,
                            'receiver_id' => null,
                            'body' => $r->body,
                            'created_at' => $r->created_at ? (string)$r->created_at : null,
                            'read_at' => null,
                        ];
                    }


                    if ($r->receiver_id == $me->id) {
                        $grouped[$sig]['read_at'] = $r->read_at ? (string)$r->read_at : null;
                    }
                }

          
                $messages = array_values($grouped);
            } else {
              
                $messages = Message::where('centerid', $centerid)
                    ->where(function ($q) use ($userIdsInCenter, $me) {
                        $q->whereIn('sender_id', $userIdsInCenter)->orWhereIn('receiver_id', $userIdsInCenter);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
                
                Message::where('centerid', $centerid)
                    ->whereIn('sender_id', $userIdsInCenter)
                    ->where('receiver_id', $me->id)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            }
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Unable to load group thread', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    
    public function unreadCount()
    {
        $me = Auth::user();
        $centerid = session('user_center_id');

        $q = Message::where('receiver_id', $me->id)->whereNull('read_at');
        if (!empty($centerid)) $q->where('centerid', $centerid);
        $count = $q->count();

        return response()->json(['success' => true, 'unread' => $count]);
    }

    public function send(Request $request)
    {
        $me = Auth::user();

        $permissions = app('userPermissions');
        $isParent = strtolower($me->userType ?? '') === 'parent';
        if (!$isParent && (empty($permissions['sendMessage']) || !$permissions['sendMessage'])) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to send messages'], 403);
        }
      
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|integer|exists:users,id',
            'body' => 'required|string|max:2000'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $centerid = session('user_center_id');

        // Prevent same-role direct messaging (staff->staff, parent->parent)
        try {
            $receiver = User::find($request->input('receiver_id'));
            if ($receiver && isset($me->userType) && isset($receiver->userType)) {
                $meType = strtolower($me->userType);
                $recvType = strtolower($receiver->userType);
                if (($meType === 'staff' && $recvType === 'staff') || ($meType === 'parent' && $recvType === 'parent')) {
                    return response()->json(['success' => false, 'message' => 'Direct messaging between same role is disabled. Use the center group.'], 403);
                }
            }
        } catch (\Throwable $e) {
            // ignore and continue
        }

        try {
            if (Schema::hasColumn('messages', 'is_group')) {
                $msg = Message::create([
                    'centerid' => $centerid,
                    'sender_id' => $me->id,
                    'receiver_id' => $request->input('receiver_id'),
                    'body' => $request->input('body'),
                    'is_group' => 0
                ]);
            } else {
                $msg = Message::create([
                    'centerid' => $centerid,
                    'sender_id' => $me->id,
                    'receiver_id' => $request->input('receiver_id'),
                    'body' => $request->input('body')
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('MessagingController@send error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Failed to send message', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'message' => $msg]);
    }

  
    public function broadcastCenter(Request $request)
    {
        $me = Auth::user();

        $permissions = app('userPermissions');
        $isParent = strtolower($me->userType ?? '') === 'parent';
        if (!$isParent && (empty($permissions['sendGroupMessage']) || !$permissions['sendGroupMessage'])) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to send group messages'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'body' => 'required|string|max:2000'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $centerid = session('user_center_id');

        if (empty($centerid)) return response()->json(['success' => false, 'message' => 'Center not selected'], 400);

        try {
            if (Schema::hasTable('group_messages')) {
                $gm = GroupMessage::create([
                    'centerid' => $centerid,
                    'sender_id' => $me->id,
                    'body' => $request->input('body')
                ]);

                Log::info('broadcastCenter created canonical group_message', ['group_message_id' => $gm->id, 'centerid' => $centerid]);
                return response()->json(['success' => true, 'group_message_id' => $gm->id, 'count' => 1]);
            }
        } catch (\Throwable $e) {
          
        }

        $userIds = Usercenter::where('centerid', $centerid)->pluck('userid')->unique()->values()->toArray();


        $messages = [];
        foreach ($userIds as $uid) {
            try {
                if (Schema::hasColumn('messages', 'is_group')) {
                    $m = Message::create([
                        'centerid' => $centerid,
                        'sender_id' => $me->id,
                        'receiver_id' => $uid,
                        'body' => $request->input('body'),
                        'is_group' => 1
                    ]);
                } else {
                    $m = Message::create([
                        'centerid' => $centerid,
                        'sender_id' => $me->id,
                        'receiver_id' => $uid,
                        'body' => $request->input('body')
                    ]);
                }
                $messages[] = $m;
            } catch (\Throwable $e) {
              
            }
        }

      
        return response()->json(['success' => true, 'count' => count($messages)]);
    }
}
