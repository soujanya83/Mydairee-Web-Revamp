@extends('layout.master')
@section('title', 'Staff Details')
@section('parentPageTitle', 'Settings')

@section('content')
<style>
    /* Thumbnail hover (match Announcement view) */
    .thumbnail-hover {
        width: 55px;
        height: 55px;
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .thumbnail-hover:hover {
        transform: scale(5);
        z-index: 2200;
        position: relative;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
        background-color: #fff; /* prevent other previews showing through rounded corners */
        border-radius: 8px;
    }
</style>    
<style>
    .iconcard {
        border-radius: 14px;
        border: 1px solid #e6e6e6;
        transition: all 0.25s ease-in-out;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.20);
    }

    .iconcard:hover {
        transform: translateY(-6px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .iconcard .fw-bold {
        font-size: 1rem;
        color: #333;
        line-height: 1.3;
    }

    .iconcard .text-muted {
        color: #6c757d !important;
    }

    .iconcard .badge {
        border-radius: 6px;
        padding: 4px 8px !important;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .no-data {
        text-align: center;
        padding: 30px;
        color: #999;
        font-style: italic;
        font-size: 1.1rem;
    }


    .permission-card {
        border-radius: 12px;
        transition: all .25s ease, box-shadow 160ms ease, transform 160ms ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
    }

    .permission-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 22px rgba(0, 0, 0, 0.25) !important;
    }

    .permission-card.open {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08) !important;
    }

    .permission-card .badge {
        padding: 6px 10px;
        border-radius: 6px;
    }
    .staff-details-card {
        max-width: 800px;
        margin: 18px auto;
        border-radius: 12px;
        overflow: visible;
        border: 1px solid #e8ecf1;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .card {
        margin-bottom: 0px;
    }

    .staff-details-card .card-body {
        padding: 0;
        background: #fff;
    }

    .staff-details-header {
        display: flex;
        gap: 20px;
        align-items: center;
        padding: 18px 22px;
        background: linear-gradient(90deg, #4a6cf7, #7699f8);
        color: #fff;
    }

    .staff-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.25);
    }

    .staff-meta h2 {
        margin: 0 0 6px 0;
        color: #fff;
    }

    .staff-meta .muted {
        color: rgba(255, 255, 255, 0.85);
    }

    .staff-details-card .card-section {
        background: transparent;
        box-shadow: none;
        min-height: 240px;
        padding: 16px 22px;
        border-radius: 0;
    }

    .details-grid {
        grid-template-columns: 1fr 340px;
        gap: 20px;
        padding: 12px 22px 28px 22px;
    }

    .section-icons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        padding-bottom: 6px;
    }

    .section-icon {
        border: 0;
        background: #fff;
        padding: 12px 14px;
        border-radius: 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    }

    .section-icon.active {
        background: #17a2b8;
        color: #fff;
    }

    .section-icon .count {
        font-size: 1.05rem;
        font-weight: 700;
    }

    .compact-table th,
    .compact-table td {
        padding: 8px 10px;
        font-size: 0.95rem;
    }

    .staff-details-card .badge {
        display: inline-flex;
        align-items: center;
        font-weight: 300;
        border-radius: 999px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        white-space: normal;
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-word;
    }

    .permissions-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .permissions-badges .badge {
        display: inline-flex;
        white-space: normal;
        max-width: 220px;
    }

    @media (max-width: 900px) {
        .details-grid {
            grid-template-columns: 1fr;
        }

        .staff-details-header {
            flex-wrap: wrap;
            gap: 12px;
        }
    }

    .iconcard .meta {
        min-height: 56px;
    }

    /* card-link */
    .card-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }
</style>

<div class="staff-details-wrapper">
    <div class="staff-details-card">
        <div class="card">
            <div class="staff-details-wrapper">
                <div class="staff-details-card">
                    <div class="card-body">

                        @php
                        $staffKeyDisplay = $staff->id ?? '';
                        $permissionCount = 0;
                        $displayPerms = [];
                        if (isset($permissions) && is_object($permissions)) {
                        $permsArr = (array) $permissions->toArray();
                        $ignoreKeys = ['id', 'userid', 'centerid', 'created_at', 'updated_at'];
                        foreach ($permsArr as $k => $v) {
                        if (in_array($k, $ignoreKeys)) {
                        continue;
                        }
                        if ($v) {
                        $displayPerms[] = $k;
                        }
                        }
                        $permissionCount = count($displayPerms);
                        }
                        @endphp

                        <div class="staff-details-header">
                            <img src="{{ $staff->imageUrl ?? asset('assets/img/xs/avatar1.jpg') }}" alt="avatar"
                                class="staff-avatar">
                            <div class="staff-meta">
                                <h2>{{ $staff->name ?? 'Staff Name' }} <small class="muted">({{ $staffKeyDisplay
                                        }})</small></h2>
                                <div class="muted"><strong>Email:</strong> {{ $staff->email ?? '-' }}</div>
                                <div class="muted"><strong>Contact:</strong> {{ $staff->contactNo ?? '-' }}</div>
                                <div class="muted" style="margin-top:6px;"><strong>Role:</strong>
                                    {{ $staff->userType ?? 'Staff' }}</div>
                            </div>
                            <div style="margin-left:auto; text-align:right">
                                @if (isset($staff->status))
                                <div class="muted"><strong>Status:</strong> {{ $staff->status }}</div>
                                @endif
                                @if (isset($staff->wifi_access_until) && $staff->wifi_access_until)
                                <div class="muted"><small>WiFi access until:
                                        {{ \Carbon\Carbon::parse($staff->wifi_access_until)->format('d M Y, h:i A')
                                        }}</small>
                                </div>
                                @endif


                                @if (isset($staff->created_at) && $staff->created_at)
                                <div class="muted"><small>Created:
                                        {{ \Carbon\Carbon::parse($staff->created_at)->format('d M Y, h:i A') }}</small>
                                </div>
                                @else
                                <div class="muted"><small>Created: -</small></div>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="card-section">
                        <div class="section-icons">

                            <button type="button" class="section-icon" data-target="programplans-section"
                                title="Program Plans">
                                <i class="far fa-clipboard  fa-2x "></i>
                                <div>
                                    <div class="small muted">Program Plans</div>
                                    <div class="count">{{ isset($programPlans) ? (is_countable($programPlans)?
                                        $programPlans->count() : '—') : '—' }}</div>
                                </div>
                            </button>

                            <button type="button" class="section-icon" data-target="reflections-section"
                                title="Reflections">
                                <i class="fa fa-window-restore fa-2x "></i>
                                <div>
                                    <div class="small muted">Daily Reflections</div>
                                    <div class="count">{{ isset($reflections) ? (is_countable($reflections)?
                                        $reflections->count() : '—') : '—' }}</div>
                                </div>
                            </button>

                            <button type="button" class="section-icon" data-target="observations-section"
                                title="Observations">
                                <i class="icon-equalizer fa-2x "></i>
                                <div>
                                    <div class="small muted">Observations</div>
                                    <div class="count">{{ isset($observations) ? $observations->count() : '—' }}
                                    </div>
                                </div>
                            </button>

                            <button type="button" class="section-icon" data-target="ptms-section" title="PTMs">
                                <i class="icon-users fa-2x "></i>
                                <div>
                                    <div class="small muted">PTMs</div>
                                    <div class="count">{{ isset($ptms) ? $ptms->count() : '—' }}</div>
                                </div>
                            </button>                            

                            <button type="button" class="section-icon" data-target="snapshots-section"
                                title="Snapshots">
                                <i class="icon-camera  fa-2x "></i>
                                <div>
                                    <div class="small muted">Snapshots</div>
                                    <div class="count">{{ isset($snapshots) ? $snapshots->count() : '—' }}</div>
                                </div>
                            </button>

                            <button type="button" class="section-icon" data-target="announcements-section"
                                title="Announcements">
                                <i class="fa fa-bullhorn fa-2x "></i>
                                <div>
                                    <div class="small muted">Announcements</div>
                                    <div class="count">{{ isset($announcements) ? (is_countable($announcements) ?
                                        $announcements->count() : '—') : '—' }}</div>
                                </div>
                            </button>

                            <button type="button" class="section-icon" data-target="qip-section" title="QIP">
                                <i class="fa fa-book fa-2x "></i>
                                <div>
                                    <div class="small muted">QIP</div>
                                    <div class="count">{{ isset($qips) ? (is_countable($qips)? $qips->count() : '—') :
                                        '—' }}</div>
                                </div>
                            </button>

                            <button type="button" class="section-icon" data-target="permissions-section"
                                title="Permissions">
                                <i class="fa fa-lock fa-2x "></i>
                                <div>
                                    <div class="small muted">Permissions</div>
                                    <div class="count">{{ $permissionCount }}</div>
                                </div>
                            </button>
                        </div>

                        <div class="details-grid">
                            <div>
                                <div id="ptms-section" style="display:none">
                                    @if (isset($ptms) && $ptms->count())
                                    <div class="row">
                                        @foreach ($ptms as $p)
                                        @php $staffKey = $staff->userid ?? $staff->id ?? null; @endphp
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ isset($p->id) ? route('ptm.viewptm', $p->id) : '#' }}"
                                                class="card-link">
                                                <div class="iconcard p-3 position-relative h-100">

                                                    <!-- Top Right : Created / Assigned Badge -->
                                                    <span class="badge position-absolute small text-white"
                                                        style="top:8px; right:8px; font-size:.65rem; padding:3px 6px; background-color: {{ (isset($p->userId) && $p->userId == $staffKey) ? '#0dcaf0' : '#6c757d' }};">
                                                        @if (isset($p->userId) && $p->userId == $staffKey)
                                                        Created
                                                        @else
                                                        Tagged
                                                        @endif
                                                    </span>

                                                    <!-- Card Body -->
                                                    <div class="mt-4">
                                                        <div class="fw-bold">{{ $p->title ?? 'Untitled' }}</div>
                                                        <div class="text-muted small">{{ isset($p->created_at) ?
                                                            \Carbon\Carbon::parse($p->created_at)->format('d M Y') : '-'
                                                            }}</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        @endforeach
                                    </div>
                                    @else
                                    <div class="no-data">No PTMs found for this staff.</div>
                                    @endif
                                </div>

                                <div id="observations-section" style="display:none">
                                    
                                    @if (isset($observations) && $observations->count())
                                    <div class="row">
                                        @foreach ($observations as $o)
                                        @php
                                        $staffKey = $staff->userid ?? ($staff->id ?? null);
                                        $obsType = (isset($o->userId) && $o->userId == $staffKey) ? 'Authored' :
                                        'Tagged';
                                        
                                        @endphp
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ isset($o->id) ? route('observation.view', $o->id) : '#' }}"
                                                class="card-link">
                                                <div class="iconcard p-3 position-relative h-100">
                                                    <span class="badge position-absolute small text-white"
                                                        style="top:8px; right:8px; font-size:.65rem; padding:3px 6px; background-color: {{ $obsType == 'Authored' ? '#0dcaf0' : '#6c757d' }};">{{
                                                        $obsType }}</span>
                                                    <div class="meta mt-3">
                                                        @if(!empty($o->previewImage))
                                                            <div>
                                                                <img src="{{ asset($o->previewImage) }}" alt="Observation Image" class="thumbnail-hover" style="border-radius:8px;" />
                                                            </div>
                                                        @endif
                                                    </div>
                                                            <div class="fw-bold">{{ $o->title ?? \Str::limit($o->summary ?? ($o->text ?? ''), 60) }}</div>
                                                            
                                                    <div class="text-muted small mt-2">Created:{{ isset($o->created_at) ?
                                                        \Carbon\Carbon::parse($o->created_at)->format('d M Y') : '-' }}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="no-data">No observations found.</div>
                                    @endif
                                </div>

                                <div id="snapshots-section" style="display:none">
                                    
                                    @if (isset($snapshots) && $snapshots->count())
                                    <div class="row">
                                        @foreach ($snapshots as $s)
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ isset($s->id) ? route('snapshot.view', $s->id) : '#' }}"
                                                class="card-link">
                                                <div class="iconcard p-3 position-relative h-100">
                                                    @if(!empty($s->previewImage))
                                                        <div>
                                                            <img src="{{ asset($s->previewImage) }}" alt="Snapshot Image" class="thumbnail-hover" style="border-radius:8px;" />
                                                        </div>
                                                        <div class="fw-bold mt-2">{{ $s->title ?? 'Snapshot' }}</div>
                                                    @else
                                                        <div class="fw-bold">{{ $s->title ?? 'Snapshot' }}</div>
                                                    @endif

                                                    <div class="text-muted small mt-2">{{ isset($s->created_at) ? \Carbon\Carbon::parse($s->created_at)->format('d M Y') : '-' }}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="no-data">No snapshots found.</div>
                                    @endif
                                </div>

                                <div id="announcements-section" style="display:none">
                                    
                                    @if (isset($announcements) && (is_countable($announcements) ?
                                    $announcements->count() : 0))
                                    <div class="row">
                                        @foreach ($announcements as $a)
                                        @php $staffKey = $staff->userid ?? ($staff->id ?? null); @endphp
                                        @php $annType = (isset($a->createdBy) && $a->createdBy == $staffKey) ? 'Created'
                                        : 'Tagged'; @endphp
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ isset($a->id) ? route('announcements.view', ['annid' => $a->id]) : route('announcements.events') }}" class="card-link">
                                                <div class="iconcard p-3 position-relative h-100">
                                                    <span class="badge position-absolute small text-white"
                                                        style="top:8px; right:8px; font-size:.65rem; padding:3px 6px; background-color: {{ $annType == 'Created' ? '#0dcaf0' : '#6c757d' }};">{{
                                                        $annType }}</span>
                                                    <div class="meta mt-3">
                                                        @if(!empty($a->previewImage))
                                                            <div>
                                                                <img src="{{ asset($a->previewImage) }}" alt="Announcement Image" class="thumbnail-hover" style="border-radius:8px;" />
                                                            </div>
                                                            <div class="fw-bold">{{ $a->title ?? ($a->caption ?? '-') }}</div>
                                                        @else
                                                            <div class="fw-bold">{{ $a->title ?? ($a->caption ?? '-') }}</div>
                                                        @endif

                                                        <div class="text-muted small mt-1">{{ isset($a->createdAt) ? \Carbon\Carbon::parse($a->createdAt)->format('d M Y') : (isset($a->created_at) ? \Carbon\Carbon::parse($a->created_at)->format('d M Y') : '-') }}</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="no-data">No announcements found.</div>
                                    @endif
                                </div>


                                <div id="qip-section" style="display:none">
                                    
                                    @if (isset($qips) && (is_countable($qips) ? $qips->count() : 0))
                                    <div class="row">
                                        @foreach ($qips as $q)
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ isset($q->id) ? route('qip.index') : '#' }}" class="card-link">
                                                <div class="iconcard p-3 position-relative h-100">
                                                    <div class="fw-bold">{{ $q->name ?? ($q->title ?? 'Untitled') }}
                                                    </div>
                                                    <div class="text-muted small mt-2">{{ isset($q->created_at) ?
                                                        \Carbon\Carbon::parse($q->created_at)->format('d M Y') : '-' }}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="no-data">No QIP items found.</div>
                                    @endif
                                </div>

                                <div id="reflections-section" style="display:none">
                                    
                                    @if (isset($reflections) && (is_countable($reflections) ? $reflections->count() :
                                    0))
                                    <div class="row">
                                        @foreach ($reflections as $r)
                                        @php
                                        $staffKey = $staff->userid ?? ($staff->id ?? null);
                                        $refType = (isset($r->createdBy) && $r->createdBy == $staffKey) ? 'Created' :
                                        'Tagged';
                                        @endphp
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ isset($r->id) ? route('reflection.print', $r->id) : '#' }}"
                                                class="card-link">
                                                <div class="iconcard p-3 position-relative h-100">
                                                    <span class="badge position-absolute small text-white"
                                                        style="top:8px; right:8px; font-size:.65rem; padding:3px 6px; background-color: {{ $refType == 'Created' ? '#0dcaf0' : '#6c757d' }};">{{
                                                        $refType }}</span>
                                                    <div class="meta mt-3">
                                                        @if(!empty($r->previewImage))
                                                            <div>
                                                                <img src="{{ asset($r->previewImage) }}" alt="Reflection Image" class="thumbnail-hover" style="border-radius:8px;" />
                                                            </div>
                                                            <div class="fw-bold">{{ $r->title ?? \Str::limit($r->summary ?? ($r->text ?? ''), 60) }}</div>
                                                        @else
                                                            <div class="fw-bold">{{ $r->title ?? \Str::limit($r->summary ?? ($r->text ?? ''), 60) }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="text-muted small mt-2">{{ isset($r->created_at) ? \Carbon\Carbon::parse($r->created_at)->format('d M Y') : '-' }}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="no-data">No reflections found.</div>
                                    @endif
                                </div>

                                <div id="programplans-section" style="display:none">
                                    
                                    @if (isset($programPlans) && (is_countable($programPlans) ? $programPlans->count() :
                                    0))
                                    <div class="row">
                                        @foreach ($programPlans as $pp)
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ isset($pp->id) ? route('print.programplan', $pp->id) : '#' }}"
                                                class="card-link">
                                                <div class="iconcard p-3 position-relative h-100">
                                                    @php
                                                        $ppRoomNames = null;
                                                        if (isset($pp->room_id) && $pp->room_id) {
                                                            $roomIds = explode(',', $pp->room_id);
                                                            try {
                                                                $roomNames = \App\Models\Room::whereIn('id', $roomIds)->pluck('name')->toArray();
                                                                if (is_array($roomNames) && count($roomNames)) {
                                                                    $ppRoomNames = implode(', ', $roomNames);
                                                                }
                                                            } catch (\Throwable $e) {
                                                                $ppRoomNames = null;
                                                            }
                                                        }
                                                    @endphp

                                                    <div class="fw-bold">Rooms : 
    {{ !empty($ppRoomNames) ? $ppRoomNames : 'No rooms available' }}
</div>

                                                    <div class="text-muted small mt-2">{{ isset($pp->created_at) ? \Carbon\Carbon::parse($pp->created_at)->format('d M Y') : '-' }}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="no-data">No program plans found.</div>
                                    @endif
                                </div>

                                <div id="permissions-section" style="display:none">
                                    

                                    @php
                                    if ($permissionCount === 0) {
                                    echo '<div class="no-data">No permissions found.</div>';
                                    } else {
                                    $groups = [];
                                    foreach ($displayPerms as $perm) {
                                    $feature = null;
                                    if (preg_match('/ptm/i', $perm)) {
                                    $feature = 'ptm';
                                    } else {
                                    if (strpos($perm, '_') !== false || strpos($perm, '-') !== false) {
                                    $parts = preg_split('/[_-]+/', $perm);
                                    $feature = end($parts);
                                    } else {
                                    $parts = preg_split('/(?=[A-Z])/', $perm);
                                    if (is_array($parts) && count($parts) > 1) {
                                    $feature = end($parts);
                                    } else {
                                    $feature = $perm;
                                    }
                                    }
                                    }
                                    $featureKey = strtolower($feature);
                                    // sanitize feature key for use in IDs
                                    $featureKeySafe = preg_replace('/[^a-z0-9]+/i', '-', $featureKey);
                                    $groups[$featureKeySafe][] = $perm;
                                    }
                                    }
                                    @endphp

                                    @if (!empty($groups))
                                        <div class="row mt-3">
                                            @foreach ($groups as $group => $items)
                                                <div class="col-12 col-sm-6 col-md-4 mb-3">
                                                    <div class="permission-card" data-group="{{ $group }}" style="
                                                            border:1px solid #e8ecf1; 
                                                            border-radius:10px; 
                                                            padding:15px; 
                                                            cursor:pointer; 
                                                            box-shadow:0 3px 8px rgba(0,0,0,0.05);
                                                        " onclick="togglePermissionGroup('{{ $group }}')">

                                                            <div style="display:flex; align-items:center; justify-content:space-between; font-weight:700; font-size:1rem;">
                                                                <div>{{ strtoupper($group) }}</div>
                                                                <span class="badge bg-primary text-white" style="font-size:.85rem;">{{ count($items) }}</span>
                                                            </div>

                                                        <div id="group-{{ $group }}" style="display:none; margin-top:10px;">
                                                            @foreach ($items as $i)
                                                                <span class="badge bg-info text-white"
                                                                    style="margin:4px; display:inline-block;">
                                                                    {{ ucwords(str_replace(['_', '-'], ' ', preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $i))) }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var buttons = Array.from(document.querySelectorAll('.section-icon'));
        if (!buttons.length) {
            console.warn('staffdetails: no .section-icon elements found');
            return;
        }

        var sections = ['ptms-section', 'observations-section', 'snapshots-section', 'announcements-section', 'qip-section', 'reflections-section', 'programplans-section', 'permissions-section'];

        function hideAll() {
            sections.forEach(function(s) {
                var el = document.getElementById(s);
                if (!el) return;
                el.style.display = 'none';
            });
            buttons.forEach(function(b) {
                b.classList.remove('active');
                b.setAttribute('aria-pressed', 'false');
            });
        }

        function showSection(target, triggerBtn) {
            hideAll();
            var el = document.getElementById(target);
            if (!el) return;
            el.style.display = '';
            if (triggerBtn) {
                triggerBtn.classList.add('active');
                triggerBtn.setAttribute('aria-pressed', 'true');
            }
            setTimeout(function() {
                el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 80);
        }

        // attach handlers (click toggles same section open/closed)
        buttons.forEach(function(btn) {
            btn.setAttribute('tabindex', '0');
            btn.setAttribute('role', 'button');
            btn.setAttribute('aria-pressed', 'false');
            btn.addEventListener('click', function(e) {
                var t = btn.getAttribute('data-target');
                if (!t) return;
                var el = document.getElementById(t);
                if (!el) return;
                var visible = window.getComputedStyle(el).display !== 'none';
                if (visible) {
                    // already visible -> hide all (toggle off)
                    hideAll();
                } else {
                    showSection(t, btn);
                }
            });
            btn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    var t = btn.getAttribute('data-target');
                    if (!t) return;
                    var el = document.getElementById(t);
                    if (!el) return;
                    var visible = window.getComputedStyle(el).display !== 'none';
                    if (visible) {
                        hideAll();
                    } else {
                        showSection(t, btn);
                    }
                }
            });
        });

        
    });
</script>
<script>
    function togglePermissionGroup(groupName) {
    // close all other groups, toggle the requested one
    const all = Array.from(document.querySelectorAll('[id^="group-"]'));
    const target = document.getElementById("group-" + groupName);
    if (!target) return;

    // determine current visibility
    const isVisible = window.getComputedStyle(target).display !== 'none';

    // hide all groups
    all.forEach(function(el) { el.style.display = 'none'; });

    // remove 'open' class from all cards
    document.querySelectorAll('.permission-card').forEach(function(c){ c.classList.remove('open'); });

    // if target wasn't visible, show it and mark its card open
    if (!isVisible) {
        target.style.display = 'block';
        const card = document.querySelector('.permission-card[data-group="' + groupName + '"]');
        if (card) card.classList.add('open');
    }
}
</script>

@stop