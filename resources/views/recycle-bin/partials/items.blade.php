<div class="container-fluid py-3">
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-0">{{ ucfirst(str_replace('-', ' ', $module)) }}</h3>
                <p class="mb-0 text-muted">Deleted items from the last 7 days. Restore or permanently delete.</p>
            </div>
            @if(!request()->boolean('embed'))
                <a href="{{ route('recycle-bin.index') }}" class="btn btn-outline-secondary">Back</a>
            @endif
        </div>
    </div>

    <style>
        .recycle-small-card { transition: transform .2s ease, box-shadow .2s ease; }
        .recycle-small-card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(15,23,42,.08); }
        .recycle-status { position: absolute; top: 12px; right: 14px; z-index: 2; }
        .recycle-thumb { width: 100%; height: 120px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem; }
        .recycle-mini-list li { margin-bottom: .4rem; }
        .recycle-action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
        .recycle-modal-shell {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-left: 0;
            margin-right: 0;
        }
        .recycle-embed-shell .recycle-modal-card { height: auto !important; min-height: 0 !important; }
        .recycle-embed-shell .recycle-embed-col {
            flex: 0 0 28%;
            max-width: 28%;
            padding-left: 8px;
            padding-right: 8px;
        }
        .recycle-embed-shell .card-body {
            padding: 14px 16px !important;
        }
        .recycle-embed-shell .reflection-card .card-body,
        .recycle-embed-shell .snapshot-card .card-body {
            padding-top: 10px !important;
        }
        .recycle-embed-shell .image-carousel {
            height: 72px !important;
        }
        .recycle-embed-shell .image-gallery {
            height: 82px !important;
        }
        .recycle-embed-shell .snapshot-details {
            max-height: 36px !important;
            font-size: 0.78rem !important;
        }
        .recycle-embed-shell .card-title,
        .recycle-embed-shell .snapshot-title {
            font-size: 0.95rem !important;
            margin-bottom: 0.5rem !important;
        }
        .recycle-embed-shell .section-title {
            font-size: 0.9rem !important;
            margin-bottom: 8px !important;
        }
        .recycle-embed-shell .child-item,
        .recycle-embed-shell .educator-item,
        .recycle-embed-shell .room-item {
            transform: scale(0.92);
            transform-origin: top left;
        }
        @media (max-width: 991px) {
            .recycle-embed-shell .recycle-embed-col { flex: 0 0 100%; max-width: 100%; }
        }
    </style>

    <div class="row recycle-modal-shell recycle-embed-shell">
        @forelse($items as $item)
            @php
                $status = $item->status ?? 'Draft';
                $statusGradient = strtolower($status) === 'draft'
                    ? 'linear-gradient(135deg, var(--primary-color, #667eea), var(--secondary-color, #764ba2))'
                    : 'linear-gradient(135deg, var(--danger-color, #ef4444), var(--secondary-color, #764ba2))';
            @endphp

            <div class="recycle-embed-col mb-4" data-recycle-item-card>
                @if($module === 'program-plan')
                    <div class="card shadow-sm rounded-3 recycle-small-card recycle-modal-card position-relative">
                        <div class="recycle-status">
                            <span class="badge text-light rounded-pill px-3 py-2 shadow-sm cursor-auto" style="transition: 0.2s; background: {{ $statusGradient }};">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3">{{ $item->months ? $item->months . ' ' . $item->years : ($item->years ?? 'Program Plan') }}</h5>
                            <ul class="list-unstyled mb-3 recycle-mini-list">
                                @php
                                    $roomIds = explode(',', $item->room_id ?? '');
                                    $rooms = \App\Models\Room::whereIn('id', $roomIds)->pluck('name')->toArray();
                                @endphp
                                <li><strong>Room(s):</strong> {{ implode(', ', $rooms) }}</li>
                                <li><strong>Created By:</strong> {{ $item->creator->name ?? '' }}</li>
                                <li><strong>Deleted on:</strong> {{ optional($item->deleted_at)->format('d M Y H:i') }}</li>
                                <li><strong>Deleted by:</strong> {{ $item->deletedByUser->name ?? 'Unknown' }}</li>
                            </ul>

                            <div class="mt-auto d-flex justify-content-start gap-2 flex-wrap">
                                <form class="recycle-restore-form d-inline" action="{{ route('recycle-bin.program-plan.restore', $item->id) }}" method="POST" data-id="{{ $item->id }}">
                                    @csrf
                                    <button class="btn theme-outline-btn btn-sm recycle-action-btn" type="submit" title="Restore" style="background: var(--sd-bg, #fff); color: var(--sd-accent, #007bff); border: 2px solid var(--sd-accent, #007bff);"><i class="fas fa-undo"></i></button>
                                </form>
                                <form class="recycle-delete-form d-inline" action="{{ route('recycle-bin.program-plan.force-delete', $item->id) }}" method="POST" data-id="{{ $item->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm recycle-action-btn" type="submit" title="Delete Forever"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif($module === 'observation')
                    <div class="card shadow-sm rounded-3 recycle-small-card recycle-modal-card position-relative">
                        <div class="recycle-status">
                            <span class="badge text-light rounded-pill px-3 py-2 shadow-sm cursor-auto" style="transition: 0.2s; background: {{ $statusGradient }};">{{ ucfirst($status) }}</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3">{{ $item->title ?? $item->obestitle ?? 'Observation' }}</h5>
                            <ul class="list-unstyled mb-3 recycle-mini-list">
                                <li><strong>Created By:</strong> {{ $item->user->name ?? 'Unknown' }}</li>
                                @php
                                    $childName = 'N/A';
                                    if ($item->child && $item->child->count() > 0 && $item->child->first()->child) {
                                        $firstChild = $item->child->first()->child;
                                        $childName = trim(($firstChild->name ?? '') . ' ' . ($firstChild->lastname ?? ''));
                                    }
                                @endphp
                                <li><strong>Child:</strong> {{ $childName }}</li>
                                <li><strong>Deleted on:</strong> {{ optional($item->deleted_at)->format('d M Y H:i') }}</li>
                                <li><strong>Deleted by:</strong> {{ $item->deletedByUser->name ?? 'Unknown' }}</li>
                            </ul>
                            <div class="mt-auto d-flex justify-content-start gap-2 flex-wrap">
                            <form class="recycle-restore-form d-inline" action="{{ route('recycle-bin.observation.restore', $item->id) }}" method="POST" data-id="{{ $item->id }}">
                                @csrf
                                <button class="btn theme-outline-btn btn-sm recycle-action-btn" type="submit" title="Restore" style="background: var(--sd-bg, #fff); color: var(--sd-accent, #007bff); border: 2px solid var(--sd-accent, #007bff);"><i class="fas fa-undo"></i></button>
                            </form>
                            <form class="recycle-delete-form d-inline" action="{{ route('recycle-bin.observation.force-delete', $item->id) }}" method="POST" data-id="{{ $item->id }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm recycle-action-btn" type="submit" title="Delete Forever"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                        </div>
                    </div>
                @elseif($module === 'reflection')
                    @php
                        $reflectionImages = $item->media->map(fn($media) => $media->mediaUrl ? asset($media->mediaUrl) : asset('default/default-image.jpg'))->toArray();
                        $mainImage = $reflectionImages[0] ?? asset('default/default-image.jpg');
                        $statusClass = strtolower($item->status) === 'published' ? 'status-published' : 'status-draft';
                    @endphp
                    <div class="card reflection-card recycle-small-card recycle-modal-card position-relative">
                        <div class="recycle-status">
                            <span class="badge text-light rounded-pill px-3 py-2 shadow-sm cursor-auto" style="transition: 0.2s; background: {{ strtolower($item->status) === 'published' ? 'linear-gradient(135deg, var(--danger-color, #ef4444), var(--secondary-color, #764ba2))' : 'linear-gradient(135deg, var(--primary-color, #667eea), var(--secondary-color, #764ba2))' }};">
                                {{ $item->status }}
                            </span>
                        </div>
                        <div class="image-carousel" style="height: 100px;">
                            <img src="{{ $mainImage }}" alt="Reflection Image" class="carousel-image active" style="opacity:1;object-fit:cover;">
                        </div>
                        <div class="card-header" style="padding: 12px 16px;">
                            <h5 class="card-title" style="font-size: 1rem;">{{ $item->title }}</h5>
                            <div class="card-date" style="top: 10px; right: 12px;"><i class="fas fa-calendar-alt"></i> {{ optional($item->created_at)->format('M d, Y') }}</div>
                        </div>
                        <div class="card-body" style="padding-top: 12px;">
                            <div class="mb-2 small text-muted">
                                Deleted on {{ optional($item->deleted_at)->format('d M Y H:i') }} by {{ $item->deletedByUser->name ?? 'Unknown' }}
                            </div>
                            <div class="section-title"><i class="fas fa-child"></i> Children</div>
                            <div class="children-grid" style="display:flex;gap:10px;overflow-x:auto;flex-wrap:nowrap;margin-bottom:12px;">
                                @foreach($item->children as $childRelation)
                                    @if($childRelation->child)
                                        <div class="child-item" style="min-width:90px;flex:0 0 auto;text-align:center;background:#fff;border-radius:12px;box-shadow:0px 2px 6px rgba(0,0,0,0.07);padding:10px 0;">
                                            <img src="{{ $childRelation->child->imageUrl ? asset($childRelation->child->imageUrl) : asset('assets/img/xs/avatar1.jpg') }}" alt="{{ $childRelation->child->name }}" class="child-avatar">
                                            <div class="child-name">{{ $childRelation->child->name }}</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="section-title"><i class="fas fa-chalkboard-teacher"></i> Educators</div>
                            <div class="educators-list" style="display:flex;gap:10px;overflow-x:auto;flex-wrap:nowrap;">
                                @foreach($item->staff as $staffRelation)
                                    @if($staffRelation->staff)
                                        <div class="educator-item" style="min-width:90px;flex:0 0 auto;text-align:center;box-shadow:0px 2px 6px rgba(0,0,0,0.07);padding:10px 6px;">
                                            <img src="{{ $staffRelation->staff->imageUrl ? asset($staffRelation->staff->imageUrl) : asset('assets/img/xs/avatar1.jpg') }}" alt="{{ $staffRelation->staff->name }}" class="educator-avatar">
                                            <div class="educator-name">{{ $staffRelation->staff->name }}</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="card-actions" style="justify-content:flex-start;">
                                <form class="recycle-restore-form" action="{{ route('recycle-bin.reflection.restore', $item->id) }}" method="POST" data-id="{{ $item->id }}">
                                    @csrf
                                    <button class="btn btn-edit btn-action recycle-action-btn" type="submit"><i class="fas fa-undo"></i></button>
                                </form>
                                <form class="recycle-delete-form" action="{{ route('recycle-bin.reflection.force-delete', $item->id) }}" method="POST" data-id="{{ $item->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-delete btn-action recycle-action-btn" type="submit"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif($module === 'snapshot')
                    @php
                        $images = $item->media->map(fn($media) => $media->mediaUrl ? asset($media->mediaUrl) : asset('default/default-image.jpg'))->toArray();
                        $mainImage = $images[0] ?? asset('default/default-image.jpg');
                        $children = $item->children->pluck('child')->filter();
                        $rooms = collect();
                        if (!empty($item->roomids)) {
                            $roomIds = explode(',', $item->roomids);
                            $rooms = \App\Models\Room::whereIn('id', $roomIds)->get();
                        }
                        $statusClass = strtolower($item->status) === 'published' ? 'status-published' : 'status-draft';
                    @endphp
                    <div class="snapshot-card fade-in recycle-small-card recycle-modal-card position-relative" data-images='@json($images)' data-id="{{ $item->id }}">
                        <div class="recycle-status">
                            <span class="badge text-light rounded-pill px-3 py-2 shadow-sm cursor-auto" style="transition: 0.2s; background: {{ strtolower($item->status) === 'published' ? 'linear-gradient(135deg, var(--danger-color, #ef4444), var(--secondary-color, #764ba2))' : 'linear-gradient(135deg, var(--primary-color, #667eea), var(--secondary-color, #764ba2))' }};">
                                {{ $item->status }}
                            </span>
                        </div>
                        <div class="card-header" style="padding: 12px 16px; min-height: auto;">
                            <h3 class="snapshot-title" style="font-size: 1rem;">{!! $item->title !!}</h3>
                        </div>
                        <div class="image-gallery" style="height: 120px;">
                            <img src="{{ $mainImage }}" alt="Snapshot Image" class="main-image">
                        </div>
                        <div class="card-body" style="padding-top: 12px;">
                            <div class="mb-2 small text-muted">
                                Deleted on {{ optional($item->deleted_at)->format('d M Y H:i') }} by {{ $item->deletedByUser->name ?? 'Unknown' }}
                            </div>
                            <div class="card-body-scroll">
                                <div class="snapshot-details">{!! $item->about !!}</div>
                                <div class="children-section">
                                    <div class="section-title"><i class="fas fa-child"></i> Children</div>
                                    <div class="children-list">
                                        @foreach($children as $child)
                                            <div class="child-item">
                                                <img src="{{ $child->imageUrl ? asset('public/' . $child->imageUrl) : asset('default/child-avatar.png') }}" alt="Child" class="child-avatar">
                                                {{ trim(($child->name ?? '') . ' ' . ($child->lastname ?? '')) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="rooms-section">
                                    <div class="section-title"><i class="fas fa-door-open"></i> Rooms</div>
                                    <div class="rooms-list">
                                        @foreach($rooms as $room)
                                            <span class="room-item">{{ $room->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="action-buttons d-flex justify-content-center gap-2 mt-3">
                                <form class="recycle-restore-form" action="{{ route('recycle-bin.snapshot.restore', $item->id) }}" method="POST" data-id="{{ $item->id }}">
                                    @csrf
                                    <button type="submit" class="btn-action btn-view recycle-action-btn" title="Restore"><i class="fas fa-undo"></i></button>
                                </form>
                                <form class="recycle-delete-form" action="{{ route('recycle-bin.snapshot.force-delete', $item->id) }}" method="POST" data-id="{{ $item->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete recycle-action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted">No deleted items found for this module in the last 7 days.</div>
            </div>
        @endforelse
    </div>
</div>