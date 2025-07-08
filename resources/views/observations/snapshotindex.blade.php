@extends('layout.master')
@section('title', 'Snapshots')
@section('parentPageTitle', '')



<style>
        body {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            background-image: url('{{ asset('assets/img/doodle1.png') }}');
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-bottom: 80px;
        }

        .snapshot-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.4s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
            position: relative;
        }

        .snapshot-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 20px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .snapshot-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 20px;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-published {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .status-draft {
            background: rgba(255, 193, 7, 0.9);
            color: #856404;
        }

        .image-gallery {
            position: relative;
            height: 250px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .main-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .image-gallery:hover .main-image {
            transform: scale(1.05);
        }

        .image-count {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .image-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .image-gallery:hover .image-nav {
            opacity: 1;
        }

        .image-nav:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: translateY(-50%) scale(1.1);
        }

        .image-nav.prev {
            left: 10px;
        }

        .image-nav.next {
            right: 10px;
        }

        .card-body {
            padding: 25px;
        }

        .snapshot-details {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .children-section, .rooms-section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .children-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .child-item {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: #333;
            padding: 8px 12px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(255, 154, 158, 0.3);
        }

        .child-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 154, 158, 0.4);
        }

        .child-avatar {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .rooms-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .room-item {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(168, 237, 234, 0.3);
        }

        .room-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(168, 237, 234, 0.4);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-action {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-edit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
        }

        .btn-action::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease;
        }

        .btn-action:hover::before {
            width: 300px;
            height: 300px;
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .child-item {
                font-size: 0.8rem;
                padding: 6px 10px;
            }
            
            .room-item {
                font-size: 0.75rem;
                padding: 5px 10px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>

<style>
    .main-image {
        transition: opacity 0.5s ease-in-out;
    }
</style>

@section('content')

<div class="text-zero top-right-button-container d-flex justify-content-end" style="margin-right: 20px;margin-top: -60px;">


@if(Auth::user()->userType != 'Parent')
                      <!-- Filter Button -->
<!-- <button class="btn btn-outline-primary btn-lg mr-1 filterbutton" data-toggle="modal"
        data-backdrop="static" data-target="#filtersModal">
    FILTERS
</button>
&nbsp;&nbsp;&nbsp; -->
<button type="button" class="btn btn-outline-info" onclick="window.location.href='{{ route('snapshot.addnew') }}'"> <i class="icon-plus" style="margin-right: 5px;"></i>Add New</button>
@endif &nbsp;&nbsp;&nbsp;


    <div class="dropdown">
        <button class="btn btn-outline-primary btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa-brands fa-centercode" style="margin-right: 5px;"></i> {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown" style="top:3% !important;left:13px !important;">
            @foreach($centers as $center)
                <a href="javascript:void(0);"
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-primary' : '' }}"
                 style="background-color:white;"  data-id="{{ $center->id }}">
                    {{ $center->centerName }}
                </a>
            @endforeach
        </div>
    </div>
</div>



<div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="text-center text-white mb-5 font-weight-bold" style="backdrop-filter:blur(10px);">
                    <i class="fas fa-camera-retro mr-3"></i>Snapshot Gallery
                </h1>
            </div>
        </div>
        
        <div class="row" id="snapshotContainer" style="margin-bottom:80px;">
            <!-- Sample Card 1 -->
            @foreach($snapshots as $snapshot)
    @php
    $images = $snapshot->media->map(function($media) {
        return $media->mediaUrl ? asset($media->mediaUrl) : asset('default/default-image.jpg');
    })->toArray();
    $mainImage = $images[0] ?? asset('default/default-image.jpg');
        $children = $snapshot->children->pluck('child')->filter();
        $rooms = $snapshot->rooms->pluck('name')->implode(', ');
        $statusClass = strtolower($snapshot->status) === 'published' ? 'status-published' : 'status-draft';
    @endphp


    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="snapshot-card fade-in" data-images='@json($images)' data-id="{{ $snapshot->id }}">
            <div class="card-header">
                <h3 class="snapshot-title">{!! $snapshot->title !!}</h3>
                <span class="status-badge {{ $statusClass }}">{{ $snapshot->status }}</span>
            </div>

            <div class="image-gallery">
                <img src="{{ $mainImage }}" alt="Snapshot Image" class="main-image">
                <button class="image-nav prev" onclick="previousImage(this)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="image-nav next" onclick="nextImage(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="image-count">
                    <i class="fas fa-images mr-1"></i>1 / {{ count($images) }}
                </div>
            </div>

            <div class="card-body">
                <div class="snapshot-details">
                    {!! $snapshot->about !!}
                </div>

                <div class="children-section">
                    <div class="section-title">
                        <i class="fas fa-child"></i> Children
                    </div>
                    <div class="children-list">
                        @foreach($children as $child)
                            @php
                                $childImage = $child->imageUrl ? asset('public/' . $child->imageUrl) : asset('default/child-avatar.png');
                                $childName = trim("{$child->name} {$child->lastname}");
                            @endphp
                            <div class="child-item">
                                <img src="{{ $childImage }}" alt="Child" class="child-avatar">
                                {{ $childName }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rooms-section">
                    <div class="section-title">
                        <i class="fas fa-door-open"></i> Rooms
                    </div>
                    <div class="rooms-list">
                        @foreach($snapshot->rooms as $room)
                            <span class="room-item">{{ $room->name }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn-action btn-edit" onclick="editSnapshot({{ $snapshot->id }})">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteSnapshot({{ $snapshot->id }})">
                        <i class="fas fa-trash-alt mr-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

            
            <!-- Sample Card 2 -->
            <!-- <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="snapshot-card fade-in">
                    <div class="card-header">
                        <h3 class="snapshot-title">Birthday Party Memories</h3>
                        <span class="status-badge status-draft">Draft</span>
                    </div>
                    
                    <div class="image-gallery">
                        <img src="https://picsum.photos/400/250?random=2" alt="Snapshot Image" class="main-image">
                        <button class="image-nav prev" onclick="previousImage(this)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="image-nav next" onclick="nextImage(this)">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <div class="image-count">
                            <i class="fas fa-images mr-1"></i>1 / 8
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="snapshot-details">
                            A magical birthday celebration with friends and family. The party was filled with laughter, games, and delicious cake that everyone enjoyed.
                        </div>
                        
                        <div class="children-section">
                            <div class="section-title">
                                <i class="fas fa-child"></i>
                                Children
                            </div>
                            <div class="children-list">
                                <div class="child-item">
                                    <img src="https://picsum.photos/50/50?random=13" alt="Child" class="child-avatar">
                                    Oliver
                                </div>
                                <div class="child-item">
                                    <img src="https://picsum.photos/50/50?random=14" alt="Child" class="child-avatar">
                                    Ava
                                </div>
                            </div>
                        </div>
                        
                        <div class="rooms-section">
                            <div class="section-title">
                                <i class="fas fa-door-open"></i>
                                Rooms
                            </div>
                            <div class="rooms-list">
                                <span class="room-item">Party Hall</span>
                                <span class="room-item">Dining Room</span>
                                <span class="room-item">Backyard</span>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <button class="btn-action btn-edit" onclick="editSnapshot(2)">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </button>
                            <button class="btn-action btn-delete" onclick="deleteSnapshot(2)">
                                <i class="fas fa-trash-alt mr-2"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->
            
            <!-- Sample Card 3 -->
            <!-- <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="snapshot-card fade-in">
                    <div class="card-header">
                        <h3 class="snapshot-title">School Event 2024</h3>
                        <span class="status-badge status-published">Published</span>
                    </div>
                    
                    <div class="image-gallery">
                        <img src="https://picsum.photos/400/250?random=3" alt="Snapshot Image" class="main-image">
                        <button class="image-nav prev" onclick="previousImage(this)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="image-nav next" onclick="nextImage(this)">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <div class="image-count">
                            <i class="fas fa-images mr-1"></i>1 / 3
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="snapshot-details">
                            Annual school event showcasing student achievements and talents. A proud moment for parents and teachers alike.
                        </div>
                        
                        <div class="children-section">
                            <div class="section-title">
                                <i class="fas fa-child"></i>
                                Children
                            </div>
                            <div class="children-list">
                                <div class="child-item">
                                    <img src="https://picsum.photos/50/50?random=15" alt="Child" class="child-avatar">
                                    Noah
                                </div>
                                <div class="child-item">
                                    <img src="https://picsum.photos/50/50?random=16" alt="Child" class="child-avatar">
                                    Isabella
                                </div>
                                <div class="child-item">
                                    <img src="https://picsum.photos/50/50?random=17" alt="Child" class="child-avatar">
                                    Mason
                                </div>
                                <div class="child-item">
                                    <img src="https://picsum.photos/50/50?random=18" alt="Child" class="child-avatar">
                                    Mia
                                </div>
                            </div>
                        </div>
                        
                        <div class="rooms-section">
                            <div class="section-title">
                                <i class="fas fa-door-open"></i>
                                Rooms
                            </div>
                            <div class="rooms-list">
                                <span class="room-item">Auditorium</span>
                                <span class="room-item">Classroom A</span>
                                <span class="room-item">Gymnasium</span>
                                <span class="room-item">Library</span>
                                <span class="room-item">Art Room</span>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <button class="btn-action btn-edit" onclick="editSnapshot(3)">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </button>
                            <button class="btn-action btn-delete" onclick="deleteSnapshot(3)">
                                <i class="fas fa-trash-alt mr-2"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->




        </div>
    </div>



    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.snapshot-card').forEach(card => {
            const images = JSON.parse(card.getAttribute('data-images'));
            const img = card.querySelector('.main-image');
            const counter = card.querySelector('.image-count');

            // Initialize index and auto-rotate
            card.currentImageIndex = 0;

            function updateImage(index) {
                if (!images.length) return;

                img.style.opacity = '0';

                setTimeout(() => {
                    img.src = images[index];
                    img.onload = () => img.style.opacity = '1';
                    counter.innerHTML = `<i class="fas fa-images mr-1"></i>${index + 1} / ${images.length}`;
                }, 300);
            }

            function showNext() {
                card.currentImageIndex = (card.currentImageIndex + 1) % images.length;
                updateImage(card.currentImageIndex);
            }

            function showPrevious() {
                card.currentImageIndex = (card.currentImageIndex - 1 + images.length) % images.length;
                updateImage(card.currentImageIndex);
            }

            // Attach functions to global scope for buttons
            card.querySelector('.image-nav.next').onclick = () => showNext();
            card.querySelector('.image-nav.prev').onclick = () => showPrevious();

            // Auto-slide every 5 seconds
            setInterval(() => {
                showNext();
            }, 5000);
        });
    });
</script>



    <script>
    // function nextImage(button) {
    //     const gallery = button.closest('.image-gallery');
    //     const card = button.closest('.snapshot-card');
    //     const images = JSON.parse(card.getAttribute('data-images'));
    //     const img = gallery.querySelector('.main-image');
    //     const counter = gallery.querySelector('.image-count');

    //     card.currentImageIndex = (card.currentImageIndex ?? 0) + 1;
    //     if (card.currentImageIndex >= images.length) card.currentImageIndex = 0;

    //     img.style.opacity = '0';
    //     setTimeout(() => {
    //         img.src = images[card.currentImageIndex];
    //         img.style.opacity = '1';
    //         counter.innerHTML = `<i class="fas fa-images mr-1"></i>${card.currentImageIndex + 1} / ${images.length}`;
    //     }, 150);
    // }

    // function previousImage(button) {
    //     const gallery = button.closest('.image-gallery');
    //     const card = button.closest('.snapshot-card');
    //     const images = JSON.parse(card.getAttribute('data-images'));
    //     const img = gallery.querySelector('.main-image');
    //     const counter = gallery.querySelector('.image-count');

    //     card.currentImageIndex = (card.currentImageIndex ?? 0) - 1;
    //     if (card.currentImageIndex < 0) card.currentImageIndex = images.length - 1;

    //     img.style.opacity = '0';
    //     setTimeout(() => {
    //         img.src = images[card.currentImageIndex];
    //         img.style.opacity = '1';
    //         counter.innerHTML = `<i class="fas fa-images mr-1"></i>${card.currentImageIndex + 1} / ${images.length}`;
    //     }, 150);
    // }

    function editSnapshot(id) {
        window.location.href = '{{ route("snapshot.addnew.optional", "") }}/' + id;
    }

    function deleteSnapshot(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the snapshot.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/snapshot/snapshotsdelete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        // document.querySelector(`[data-id='${id}']`).closest('.col-lg-4').remove();
                        Swal.fire('Deleted!', 'Snapshot has been deleted.', 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error!', 'Failed to delete snapshot.', 'error');
                    }
                });
            }
        });
    }
</script>



@include('layout.footer')
@stop