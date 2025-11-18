@extends('layout.master')
@section('title', 'L & P')
@section('parentPageTitle', '')

<style>
    /* Enhanced Modern UI Styles */
    .children-container {
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        min-height: 100vh;
        padding: 40px 0;
        margin-top:20px;
    }
    
    .search-container {
        position: relative;
        max-width: 600px;
        margin: 0 auto 50px;
    }
    
    #search {
        border: none;
        border-radius: 50px;
        padding: 18px 60px 18px 25px;
        font-size: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    
    #search:focus {
        outline: none;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 1);
    }
    
    .search-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        font-size: 20px;
        pointer-events: none;
    }
    
    .page-title {
        text-align: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .page-subtitle {
        text-align: center;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin-bottom: 40px;
        font-weight: 300;
    }
    
    .child-card {
        margin-bottom: 30px;
        opacity: 0;
        animation: fadeInUp 0.8s ease forwards;
    }
    
    .child-card:nth-child(1) { animation-delay: 0.1s; }
    .child-card:nth-child(2) { animation-delay: 0.2s; }
    .child-card:nth-child(3) { animation-delay: 0.3s; }
    .child-card:nth-child(4) { animation-delay: 0.4s; }
    .child-card:nth-child(5) { animation-delay: 0.5s; }
    .child-card:nth-child(6) { animation-delay: 0.6s; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .child-card .card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    
    .child-card .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .child-card .card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        background: rgba(255, 255, 255, 1);
    }
    
    .child-card .card:hover::before {
        opacity: 1;
    }
    
    .child-card img {
        height: 220px;
        object-fit: cover;
        transition: transform 0.4s ease;
        filter: brightness(0.9);
    }
    
    .child-card .card:hover img {
        transform: scale(1.05);
        filter: brightness(1);
    }
    
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .child-card .card:hover .image-overlay {
        opacity: 1;
    }
    
    .card-img-container {
        position: relative;
        overflow: hidden;
    }
    
    .card-body {
        padding: 25px;
        position: relative;
    }
    
    .card-title {
        font-weight: 700;
        font-size: 1.4rem;
        color: #2c3e50;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .card-title::before {
        content: '👶';
        margin-right: 10px;
        font-size: 1.2rem;
    }
    
    .card-detail {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 0.95rem;
        color: #555;
        transition: color 0.3s ease;
    }
    
    .card-detail:hover {
        color: #667eea;
    }
    
    .card-detail i {
        width: 20px;
        margin-right: 12px;
        color: #667eea;
        font-size: 16px;
    }
    
    .age-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-left: 10px;
    }
    
    .gender-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .gender-badge.male {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .gender-badge.female {
        background: #fce4ec;
        color: #c2185b;
    }
    
    .btn-lnp {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 25px;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 0.95rem;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .btn-lnp::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-lnp:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-lnp:hover::before {
        left: 100%;
    }
    
    .no-results {
        text-align: center;
        color: white;
        font-size: 1.2rem;
        margin-top: 50px;
        opacity: 0;
        animation: fadeIn 0.5s ease forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .loading-spinner {
        display: none;
        position: absolute;
        right: 25px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }
        
        .child-card .card {
            margin-bottom: 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        #search {
            padding: 15px 50px 15px 20px;
        }
    }
    
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
    }
</style>

@section('content')
<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -60px;">





    <div class="dropdown">
        <button class="btn btn-outline-primary btn-lg dropdown-toggle" type="button" id="centerDropdown"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown"
            style="top:3% !important;left:13px !important;">
            @foreach($centers as $center)
            <a href="javascript:void(0);"
                class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-primary' : '' }}"
                style="background-color:white;" data-id="{{ $center->id }}">
                {{ $center->centerName }}
            </a>
            @endforeach
        </div>
    </div>


    &nbsp;&nbsp;&nbsp;&nbsp;

    <div class="dropdown">
        <button class="btn btn-outline-primary btn-lg dropdown-toggle" type="button" id="centerDropdown"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ $selectedroom->name ?? 'Select Room' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown"
            style="top:3% !important;left:13px !important;">
            @foreach($room as $rooms)
            <a href="javascript:void(0);"
                onclick="window.location.href='{{ route('learningandprogress.index', ['room_id' => $rooms->id, 'center_id' => session('user_center_id')]) }}'"
                class="dropdown-item center-option {{ $selectedroom->id == $rooms->id ? 'active font-weight-bold text-primary' : '' }}"
                style="background-color:white;">
                {{ $rooms->name }}
            </a>
            @endforeach
        </div>
    </div>



</div>




</div>



<div class="children-container card">
    <div class="container">
        <h1 class="page-title">Children Directory</h1>
        <!-- <p class="page-subtitle">Discover and explore children's learning progress</p> -->
        
        <div class="search-container">
            <input type="text" id="search" class="form-control" placeholder="Search children by name...">
            <i class="fas fa-search search-icon"></i>
            <div class="loading-spinner" id="loadingSpinner"></div>
        </div>
        
        <div class="row" id="childrenContainer">
            @foreach($children as $c)
            @php
                $image = asset($c->imageUrl) ?? 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=300&h=300&fit=crop&crop=face';
                $dob = \Carbon\Carbon::parse($c->dob);
                $age = $dob->age;
                $genderClass = strtolower($c->gender);
            @endphp
            <div class="col-lg-4 col-md-6 mb-4 child-card" data-name="{{ strtolower($c->name . ' ' . $c->lastname) }}">
                <div class="card h-100">
                    <div class="card-img-container">
                        <img src="{{ $image }}" 
                             onerror="this.src='https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=300&h=300&fit=crop&crop=face'" 
                             class="card-img-top" 
                             alt="Child Image"
                             loading="lazy">
                        <div class="image-overlay"></div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $c->name }} {{ $c->lastname }}</h5>
                        
                        <div class="card-detail">
                            <i class="fas fa-birthday-cake"></i>
                            <span><strong>DOB:</strong> {{ $dob->format('d M Y') }}</span>
                        </div>
                        
                        <div class="card-detail">
                            <i class="fas fa-child"></i>
                            <span><strong>Age:</strong> {{ $age }} years</span>
                            <span class="age-badge">{{ $age }}y</span>
                        </div>
                        
                        <div class="card-detail">
                            <i class="fas fa-venus-mars"></i>
                            <span><strong>Gender:</strong></span>
                            <span class="gender-badge {{ $genderClass }}">{{ ucfirst($c->gender) }}</span>
                        </div>
                        
                        <a href="{{ url('learningandprogress/lnpdata/' . $c->id) }}" class="mt-auto btn btn-lnp btn-block">
                            <i class="fas fa-chart-line"></i> View Progress
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="no-results" id="noResults" style="display: none;">
            <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.5;"></i>
            <h3>No children found</h3>
            <p>Try adjusting your search terms</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const childrenContainer = document.getElementById('childrenContainer');
    const noResults = document.getElementById('noResults');
    const loadingSpinner = document.getElementById('loadingSpinner');
    let searchTimeout;
    
    // Smooth search with debouncing
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Show loading spinner
        loadingSpinner.style.display = 'block';
        
        // Debounce search
        searchTimeout = setTimeout(() => {
            performSearch(query);
            loadingSpinner.style.display = 'none';
        }, 300);
    });
    
    function performSearch(query) {
        const cards = document.querySelectorAll('.child-card');
        let visibleCount = 0;
        
        cards.forEach((card, index) => {
            const name = card.getAttribute('data-name');
            const isVisible = name.includes(query);
            
            if (isVisible) {
                visibleCount++;
                card.style.display = 'block';
                // Stagger animation for visible cards
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0 && query !== '') {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    }
    
    // Add smooth hover effects
    const cards = document.querySelectorAll('.child-card .card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-15px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add click animation to buttons
    const buttons = document.querySelectorAll('.btn-lnp');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.pointerEvents = 'none';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Add CSS for ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
        .btn-lnp {
            position: relative;
            overflow: hidden;
        }
    `;
    document.head.appendChild(style);
    
    // Add parallax effect to background
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.children-container');
        const speed = scrolled * 0.5;
        parallax.style.backgroundPosition = `center ${speed}px`;
    });
    
    // Add entrance animation for search bar
    setTimeout(() => {
        searchInput.style.transform = 'translateY(0)';
        searchInput.style.opacity = '1';
    }, 500);
    
    // Initialize search bar animation
    searchInput.style.transform = 'translateY(-20px)';
    searchInput.style.opacity = '0';
    searchInput.style.transition = 'all 0.6s ease';
});
</script>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


@include('layout.footer')
@stop