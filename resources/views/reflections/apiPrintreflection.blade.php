<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Child's Reflection Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(to bottom, #f4f6f7, #d9e3fd);
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 9999;
        }

        .container {
            position: relative;
            max-width: 800px;
            margin: auto;
            background-color: #e1eeff;
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 60px 80px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            z-index: 1;
            overflow: hidden;
        }

        .container::before {
            content: "";
            position: absolute;
            top: 33%;
            left: 33%;
            transform: translate(-10%, -10%);
            background-image: url('{{ asset("assets/profile_1739442700.jpeg") }}');
            background-repeat: no-repeat;
            background-size: 500px;
            opacity: 0.06;
            z-index: 0;
            width: 600px;
            height: 600px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .logo {
            max-width: 200px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #8e24aa;
            margin-top: 10px;
        }

        .info-block {
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .info-label {
            font-weight: bold;
            font-size: 15px;
            color: #222;
            margin-bottom: 5px;
        }

        .info-text {
            font-size: 14px;
            color: #000;
        }

        .photo-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }

        .child-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #bbb;
        }

        @media print {
            .print-button {
                display: none;
            }

            body {
                background: none;
                margin: 0;
                padding: 0;
            }

            .container {
                border: 1px solid #000;
                 box-shadow: none;
    page-break-inside: avoid; /* ✅ Prevent element from breaking across pages */
    page-break-before: auto;
    page-break-after: auto;
            }

            .child-image {
                height: 120px;
            }

            @page {
                size: A4;
                margin: 20mm;
            }
        }

        .circular-image {
            width: 110px;
            height: 120px;
            border-radius: 9px;
            object-fit: cover;
            border: 2px solid #aaa;
            margin-bottom: 8px;
        }

        .photo-gallery {

            gap: 2px;
            /* Reduce space between images */

        }
    </style>
    
    <!-- Theme-scoped overrides: apply only when a theme is active -->
    <style>
        /* Keep the defaults; only highlight with theme when active */
        body[class*="theme-"] .title {
            color: var(--sd-accent);
        }

        body[class*="theme-"] .container {
            border-color: var(--sd-accent);
        }

        body[class*="theme-"] .info-block > strong {
            color: var(--sd-accent);
        }
    </style>
</head>

<body>

    <!-- <button onclick="window.print()" class="print-button">
        <i class="fa-solid fa-print"></i> Print
    </button> -->

    <div class="container">
        <div class="header">
            <img src="{{ public_path('assets/profile_1739442700.jpeg') }}" alt="NEXTGEN Montessori" class="logo">
            <div class="title">Daily Reflection </div>
        </div>

        <div class="info-block">
            <strong>Children Name:</strong>
            <span class="info-text">
                {{ $reflection->children?->pluck('child.name')->implode(', ') ?? '-' }}
            </span>
        </div>

        <div class="info-block">
            <strong>Date:</strong>
            <span class="info-text">
                {{ \Carbon\Carbon::parse($reflection->created_at)->format('d/m/Y') }}
            </span>
        </div>

        <div class="info-block">
            <strong>Educator's Name:</strong>
            <span class="info-text">
                {{ $reflection->staff?->pluck('staff.name')->implode(', ') ?? '-' }}
            </span>
        </div>

        <div class="info-block">
            <strong>Classroom:</strong>
            <span class="info-text">
                {{ $roomNames ?? '-' }}
            </span>
        </div>

        <div class="info-block">
            <strong>Daily Reflection:</strong>
            <span class="info-text">
                {!! $reflection->about ?? 'Not updated' !!}
            </span>
        </div>

        <div class="info-block">
            <strong>Child's Photos:</strong>
            <div class="photo-gallery">
                <!-- <img src="{{ public_path('assets/profile_1739442700.jpeg') }}" alt="NEXTGEN Montessori"
                    class="circular-image"> -->

                @foreach($reflection->media ?? [] as $mediaItem)
                @if(Str::startsWith($mediaItem->mediaType, 'image'))
                <img src="{{ public_path($mediaItem->mediaUrl) }}" class="child-image" alt="Photo">
                @endif
                @endforeach
            </div>
        </div>


        <div class="info-block">
            <strong>EYLF Outcomes:</strong>
            <span class="info-text">
                {!! nl2br(e($reflection->eylf ?? '')) !!}
            </span>
        </div>
    </div>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var overlay = document.getElementById('imageLightbox');
    var img = document.getElementById('lightboxImage');
    var closeBtn = overlay.querySelector('.lightbox-close');
    var prevBtn = overlay.querySelector('.lightbox-prev');
    var nextBtn = overlay.querySelector('.lightbox-next');

    var thumbs = document.querySelectorAll('.photo-thumb');
    var currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        img.src = thumbs[currentIndex].getAttribute('data-full');
        overlay.classList.add('show');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        overlay.classList.remove('show');
        overlay.setAttribute('aria-hidden', 'true');
        img.src = '';
        document.body.style.overflow = '';
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % thumbs.length;
        img.src = thumbs[currentIndex].getAttribute('data-full');
    }

    function showPrev() {
        currentIndex = (currentIndex - 1 + thumbs.length) % thumbs.length;
        img.src = thumbs[currentIndex].getAttribute('data-full');
    }

    // Open on thumbnail click
    thumbs.forEach(function(thumb, index) {
        thumb.addEventListener('click', function() {
            openLightbox(index);
        });
    });

    // Button events
    closeBtn.addEventListener('click', closeLightbox);
    nextBtn.addEventListener('click', showNext);
    prevBtn.addEventListener('click', showPrev);

    // Click outside image closes
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay || e.target === closeBtn) {
            closeLightbox();
        }
    });

    // ESC and arrow keys
    document.addEventListener('keydown', function(e) {
        if (!overlay.classList.contains('show')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') showNext();
        if (e.key === 'ArrowLeft') showPrev();
    });
});

    </script>



</body>

</html>