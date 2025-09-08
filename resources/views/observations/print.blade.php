<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Child's Reflection Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"> 

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


        <link rel="icon" type="image/png" sizes="36x36" href="{{ asset('assets/img/Mydiaree d.png') }}">

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
        top: 20%;
        left: 20%;
        transform: translate(-5%, -5%);
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
        margin: 7px;
        width: 91%;
        height: 145px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #bbb;
    }

    @media print {
        .print-button {
            display: none;
        }
        .language-wrapper{
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
            page-break-inside: avoid;
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

        gap: 15px;
        /* Reduce space between images */

    }
    </style>

    <style>
    /* Gallery grid */
    /* .photo-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 12px;
        margin-top: 10px;
    } */

    .photo-thumb {
        display: block;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .photo-thumb:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    /* .child-image {
        width: 100%;
        height: 110px;
        object-fit: cover;
        display: block;
    } */

    /* Lightbox overlay */
    .lightbox-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        display: none;
        /* hidden by default */
        align-items: center;
        justify-content: center;
        z-index: 1055;
        /* above Bootstrap modals (1050) if needed */
        padding: 20px;
    }

    .lightbox-overlay.show {
        display: flex;
    }

    /* Full image */
    #lightboxImage {
        max-width: 90vw;
        max-height: 85vh;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        transition: transform .2s ease;
    }

    /* Close button */
    .lightbox-close {
        position: absolute;
        top: 16px;
        right: 18px;
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        border: none;
        font-size: 32px;
        width: 44px;
        height: 44px;
        line-height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: background .15s ease;
    }

    .lightbox-close:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    /* Optional: click outside to close cursor */

    .lightbox-overlay {
        cursor: zoom-out;
    }

    #lightboxImage {
        cursor: auto;
    }

    .lightbox-prev,
.lightbox-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    border: none;
    font-size: 36px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    cursor: pointer;
    display: grid;
    place-items: center;
    transition: background 0.15s ease;
    z-index: 1056;
}

.lightbox-prev:hover,
.lightbox-next:hover {
    background: rgba(255, 255, 255, 0.25);
}

.lightbox-prev {
    left: 20px;
}

.lightbox-next {
    right: 20px;
}

    </style>

    <style>
.translate-button {
  background-color: #17a2b8;   /* btn-info style */
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 6px 0 0 6px;
  cursor: pointer;
  font-size: 14px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.translate-button i {
  margin-left: 6px;
}

.language-select {
  background-color: #17a2b8;
  color: #fff;
  border: none;
  padding: 8px 8px;
  border-radius: 0 6px 6px 0;
  cursor: pointer;
  font-size: 14px;
  appearance: none;  /* remove native arrow */
  outline: none;
}

.language-wrapper {
    position: fixed;
        top: 20px;
        left: 20px;
        background-color: #17a2b8;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        z-index: 9999;

}

.language-wrapper::after {
  content: "▼";
  font-size: 10px;
  color: #fff;
  position: absolute;
  right: 2px;
  pointer-events: none;
}
</style>
<style>
/* Spinner animation */
.spinner {
    width: 60px;
    height: 60px;
    border: 6px solid rgba(255,255,255,0.3);
    border-top: 6px solid #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    0%   { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

</head>

<body>


<div class="language-wrapper" style="">
  <!-- Main Translate Button -->
  <button onclick="translateText()" class="translate-button" style="">
    Translate <i class="fa-solid fa-language fa-beat-fade"></i>
  </button>

  <!-- Dropdown for languages -->
  <select class="language-select" id="select-language" style="">
  </select>
</div>


    <button onclick="window.print()" class="print-button">Print Page&nbsp;<i
            class="fa-solid fa-print fa-beat-fade"></i></button>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/profile_1739442700.jpeg') }}" alt="NEXTGEN Montessori" class="logo">
            <div class="title">Child's Observation</div>
            <hr>
        </div>

        <div class="info-block">
            <strong>Child's Name</strong>
            <span class="info-text">
                @if($observation->child && $observation->child->isNotEmpty())
                {{ $observation->child->map(fn($c) => $c->child->name . ' ' . $c->child->lastname)->implode(', ') }}
                @endif
            </span>
        </div>

        <div class="info-block">
            <strong>Title:</strong>
            <span class="info-text">
            {!! $observation->obestitle ?? '' !!}
            </span>
        </div>

        <div class="info-block">
            <strong>Date:</strong>
            <span class="info-text">
                {{ \Carbon\Carbon::parse($observation->date_added)->format('d/m/Y') }}
            </span>
        </div>

        <div class="info-block">
            <strong>Educator's Name:</strong>
            <span class="info-text">
                {{ $observation->user->name ?? $observation->name ?? '' }}
            </span>
        </div>

        <div class="info-block">
            <strong>Classroom:</strong>
            <span class="info-text">
                {{ $roomNames ?? $roomNames ?? '' }}
            </span>
        </div>

        <div class="info-block">
            <strong>Child's Photos:</strong>
            <div class="photo-gallery"> @if($observation->media && $observation->media->isNotEmpty())
                @foreach($observation->media as $mediaItem) @if(Str::startsWith($mediaItem->mediaType, ['image',
                'Image'])) <a href="javascript:void(0);" class="photo-thumb"
                    data-full="{{ asset($mediaItem->mediaUrl) }}"> <img src="{{ asset($mediaItem->mediaUrl) }}"
                        class="child-image" alt="Observation Media"> </a> @endif @endforeach @endif </div>
        </div>
        <!-- Lightbox Modal -->
        <div id="imageLightbox" class="lightbox-overlay" aria-hidden="true">
            <button type="button" class="lightbox-close" aria-label="Close">&times;</button>
            
            <!-- Prev button -->
            <button type="button" class="lightbox-prev" aria-label="Previous">&#10094;</button>
            
            <img id="lightboxImage" src="" alt="Full size image">
            
            <!-- Next button -->
            <button type="button" class="lightbox-next" aria-label="Next">&#10095;</button>
        </div>



        <div class="info-block">
            <strong>Observation:</strong>
            <span class="info-text" id="observation">
                {!! html_entity_decode($observation->title ?? '') !!}
            </span>
        </div>




        <div class="info-block">
            <strong>EYLF Outcomes:</strong>
            <span class="info-text" id="eylf">
                @if($observation->eylfLinks && $observation->eylfLinks->isNotEmpty())
                @php
                $groupedByOutcome = $observation->eylfLinks->groupBy(function($item) {
                return $item->subActivity->activity->outcome->title ?? 'Unknown Outcome';
                });
                @endphp

                @foreach($groupedByOutcome as $outcomeTitle => $links)
                <strong>{{ $outcomeTitle }}</strong><br>
                @foreach($links as $link)
                - {{ $link->subActivity->activity->title ?? 'N/A' }}<br>
                &nbsp;&nbsp;&nbsp;• {{ $link->subActivity->title ?? 'N/A' }}<br>
                @endforeach
                <br>
                @endforeach
                @endif
            </span>
        </div>

        <div class="info-block">
            <strong>Analysis/Evaluation:</strong>
            <span class="info-text" id="analysis">
                {!! html_entity_decode($observation->notes ?? '') !!}
            </span>
        </div>


        <div class="info-block">
            <strong>Reflection:</strong>
            <span class="info-text" id="reflection">
                {!! html_entity_decode($observation->reflection ?? '') !!}
            </span>
        </div>


        <div class="info-block">
            <strong>Child's Voice:</strong>
            <span class="info-text" id="childvoice">
                {!! html_entity_decode($observation->child_voice ?? 'Not recorded') !!}
            </span>
        </div>

        <div class="info-block">
            <strong>Montessori Assessment:</strong>
            <span class="info-text" id="montessori_assesment">
                @if($observation->montessoriLinks && $observation->montessoriLinks->isNotEmpty())
                @php
                $groupedBySubject = $observation->montessoriLinks->groupBy(function($item) {
                return $item->subActivity->activity->subject->name ?? 'Unknown';
                });
                @endphp

                @foreach($groupedBySubject as $subjectName => $assessments)
                <strong>{{ $subjectName }}</strong><br>
                @foreach($assessments as $assessment)
                - {{ $assessment->subActivity->activity->title ?? 'N/A' }}<br>
                &nbsp;&nbsp;&nbsp;• {{ $assessment->subActivity->title ?? 'N/A' }}
                @php
                $statusClass = [
                'Not Assessed' => 'badge-danger',
                'Introduced' => 'badge-info',
                'Working' => 'badge-warning',
                'Completed' => 'badge-success'
                ];
                @endphp
                ({{ $assessment->assesment }})<br>
                @endforeach
                <br>
                @endforeach
                @endif
            </span>
        </div>


        <div class="info-block">
            <strong>Development Milestones:</strong>
            <span class="info-text" id="development_milestone">
                @if($observation->devMilestoneSubs && $observation->devMilestoneSubs->isNotEmpty())
                @php
                $groupedByAgeGroup = $observation->devMilestoneSubs->groupBy(function($item) {
                return $item->devMilestone->milestone->ageGroup ?? 'Unknown Age Group';
                });
                @endphp

                @foreach($groupedByAgeGroup as $ageGroup => $milestones)
                <strong>{{ $ageGroup }}</strong><br>
                @php
                $groupedByMain = $milestones->groupBy(function($item) {
                return $item->devMilestone->main->name ?? 'Unknown Category';
                });
                @endphp

                @foreach($groupedByMain as $mainCategory => $categoryMilestones)
                - {{ $mainCategory }}<br>
                @foreach($categoryMilestones as $milestone)
                &nbsp;&nbsp;&nbsp;• {{ $milestone->devMilestone->name ?? 'N/A' }}
                @php
                $statusClass = [
                'Introduced' => 'badge-info',
                'Working towards' => 'badge-warning',
                'Achieved' => 'badge-success'
                ];
                @endphp
                ({{ $milestone->assessment }})<br>
                @endforeach
                @endforeach
                <br>
                @endforeach
                @endif
            </span>
        </div>



        <div class="info-block">
            <strong>Future Plan/Extension:</strong>
            <span class="info-text" id="futureplan">
                {!! html_entity_decode($observation->future_plan ?? '') !!}
            </span>
        </div>



    </div>

    <!-- Overlay -->
<div id="overlay" style="
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,0.6);
    z-index:9998;
    pointer-events:auto;
">
    <!-- Loader -->
    <div style="
        position:absolute;
        top:50%;left:50%;
        transform:translate(-50%,-50%);
        text-align:center;
        color:white;
        font-family:Arial, sans-serif;
    ">
        <!-- Spinner -->
        <div class="spinner"></div>
        <div style="margin-top:15px; font-size:18px; font-weight:500;">
            Translating...
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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



    // "Assamese", "Bengali", "Bodo", "Dogri", "Gujarati", "Hindi",
    // "Kannada", "Kashmiri", "Konkani", "Maithili", "Malayalam",
    // "Manipuri", "Marathi", "Nepali", "Odia", "Punjabi",
    // "Sanskrit", "Santali", "Sindhi", "Tamil", "Telugu", "Urdu",
    // "Bhojpuri", "Magahi", "Awadhi", "Chhattisgarhi",
    // "Rajasthani", "Garhwali", "Kumaoni", "Tulu", "Mizo", "Khasi",
    // "Garo", "Lepcha", "Limbu", "Sherpa", "Bhutia", "Nagamese","English"
 

    const indianLanguages = ["Bengali","Gujarati", "Hindi","Kannada","Malayalam","Marathi", "Odia", "Punjabi/Gurmukhi ",
    "Sindhi", "Tamil", "Telugu", "Urdu", "Tulu", "English","Vietnamese","Mandarin"];

// Total count
console.log(`Total languages in array: ${indianLanguages.length}`);

  // Populate dropdown
indianLanguages.sort((a, b) => a.localeCompare(b));

// Reset dropdown first
// $('#select-language').html('<option value="">Select Language</option>');

// Populate dropdown
indianLanguages.forEach(lang => {
  if (lang === "English") {
    $('#select-language').append(`<option value="${lang}" selected>${lang}</option>`);
  } else {
    $('#select-language').append(`<option value="${lang}">${lang}</option>`);
  }
});

  // Activate Select2 search feature
  $(document).ready(function () {
    $('#select-language').select2({
      placeholder: "Select Language",
      allowClear: true
    });
  });

// GET request
// document.addEventListener("DOMContentLoaded", function () {
//     // Use the correct Metadapi endpoint:
//     fetch("https://global.metadapi.com/lang/v1/languages", {
//         method: "GET"
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error(`Network response was not ok (status: ${response.status})`);
//         }
//         return response.json();
//     })
//     .then(json => {
//         console.log("API Response:", json);

//         const select = document.getElementById("select-language");
//         select.innerHTML = '<option value="">Select Language</option>';

//         if (json.data && Array.isArray(json.data)) {
//             json.data.forEach(lang => {
//                 const option = document.createElement("option");
//                 option.value = lang.langCode; // like "fr"
//                 // Show English + native names
//                 option.textContent = `${lang.langEnglishName} (${lang.langNativeName})`;
//                 select.appendChild(option);
//             });
//         } else {
//             console.error("No language data found", json);
//         }
//     })
//     .catch(error => console.error("Error fetching language list:", error));
// });


function translateText() {
    let data = {
        reflection:  $('#reflection').html(),
        childvoice:  $('#childvoice').html(),
        observation: $('#observation').html(),
        analysis:    $('#analysis').html(),
        futureplan:  $('#futureplan').html(),
        language:    $('#select-language').val(),
        eylf: $('#eylf').html(),
        montessori_assesment : $('#montessori_assesment').html(),
        development_milestone : $('#development_milestone').html()
    };

    // console.log("Sending data:", data);
     $('#overlay').show();

    $.ajax({
        url: '{{ route("observation.translate-observation") }}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: data,
    success: function(response) {
    console.log('Translation Success:', response);

    if (response.status) {
        // Step 1: Clear all fields
        $('#reflection, #childvoice, #observation, #analysis, #futureplan','#development_milestone','#montessori_assesment','#eylf').html('');

        // Step 2: Fill with translated values
        $('#reflection').html(response.data.reflection ?? '');
        $('#childvoice').html(response.data.childvoice ?? '');
        $('#observation').html(response.data.observation ?? '');
        $('#analysis').html(response.data.analysis ?? '');
        $('#futureplan').html(response.data.futureplan ?? '');
         $('#development_milestone').html(response.data.development_milestone);
         $('#eylf').html(response.data.eylf);
          $('#montessori_assesment').html(response.data.montessori_assesment);
    }

}
,
        error: function(xhr) {
            console.error('Translation Failed:', xhr.responseText);
            alert('Something went wrong while translating.');
        },
           complete: function() {
            // Hide loader & allow clicks again
            $('#overlay').hide();
        }
    });
}



    </script>
</body>

</html>