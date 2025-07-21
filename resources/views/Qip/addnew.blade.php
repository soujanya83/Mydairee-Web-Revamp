@extends('layout.master')
@section('title', 'Qip / Add or Edit')
@section('parentPageTitle', '')

<style>
    .qip-title-card {
        background: linear-gradient(135deg, #f0f9ff, #d9f0ff);
        border-left: 5px solid #007bff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .qip-title-card h4 {
        font-weight: bold;
        color: #333;
    }

    .quality-card {
        background-size: cover;
        max-height: 140px;
        background-position: center;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        color: #333;
        position: relative;
    }

    .quality-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .quality-card .card-body {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(5px);
        padding: 20px;
        height: 100%;
    }

    .quality-card h5 {
        font-weight: 600;
        margin-bottom: 10px;
    }

    .quality-card small {
        color: #666;
    }

    .quality-card .progress {
        height: 6px;
        border-radius: 5px;
        background: #e0e0e0;
    }

    .card-link {
        text-decoration: none !important;
        display: block;
        height: 100%;
    }

    /* Optional: Subtle background image for cards */
    .quality-card::before {
        content: '';
        /* background-image: url('/images/quality-bg.png'); */
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.1;
        z-index: 0;
        background-size: cover;
    }

    .quality-card .card-body {
        position: relative;
        z-index: 1;
    }
</style>

@section('content')



<div class="container mt-4" style="margin-bottom:88px;">

    {{-- QIP Name Section --}}
    <div class="card qip-title-card mb-4 p-4">
        <h4>
            <span id="qipNameDisplay">{{ $qip->name }}</span>
            <input type="text" id="qipNameEdit" value="{{ $qip->name }}" class="form-control d-none" style="display: inline-block; width: auto;" />
            <a href="#" id="editQipName" class="ml-2"><i class="fa fa-pencil"></i></a>
        </h4>
        <small class="text-muted">Progress</small>
        <div class="progress">
            <div class="progress-bar bg-info" role="progressbar" style="width: 0%"></div>
        </div>
    </div>

    {{-- Quality Area Cards --}}
    <div class="row">
        @foreach ($Qip_area as $index => $area)
            <div class="col-md-4 mb-4">
            <a href="{{ route('qip.area.view', ['id' => $qip->id, 'area' => $area->id]) }}" class="card-link">
                    <div class="card quality-card" style="border: 2px solid {{ $area->color }}">
                        <div class="card-body">
                            <h5>Quality Area {{ $index + 1 }}</h5>
                            <p class="mb-2">{{ $area->title }}</p>
                            <div class="progress mb-1">
                                <div class="progress-bar" style="width: 0%; background-color: {{ $area->color }}"></div>
                            </div>
                            <small>0 / 100</small>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

</div>



<script>
    $('#editQipName').on('click', function (e) {
    e.preventDefault();
    $('#qipNameDisplay').toggleClass('d-none');
    $('#qipNameEdit').toggleClass('d-none').focus();
});

$('#qipNameEdit').on('blur', function () {
    let newName = $(this).val();
    let qipId = {{ $qip->id }};

    $.ajax({
        url: "{{ route('qip.update.name') }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id: qipId,
            name: newName
        },
        success: function (res) {
            $('#qipNameDisplay').text(newName).removeClass('d-none');
            $('#qipNameEdit').addClass('d-none');
        },
        error: function () {
            alert('Error updating QIP name.');
        }
    });
});

    </script>








@include('layout.footer')
@stop