@extends('layout.master')
@section('title', 'Progress Plan')
@section('parentPageTitle', '')



<style>
/* Assessment Container Styles */
.assessment-container {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.assessment-container:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
}

/* Triangle Indicator Styles */
.triangle-indicator {
    position: relative;
    width: 60px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.triangle-wrapper {
    position: relative;
    width: 50px;
    height: 45px;
}

.triangle-side {
    position: absolute;
    background: #e9ecef;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

/* Triangle Side 1 - Bottom */
.triangle-side.side-1 {
    bottom: 0;
    left: 0;
    width: 50px;
    height: 4px;
    border-radius: 2px;
}

/* Triangle Side 2 - Left */
.triangle-side.side-2 {
    bottom: 0;
    left: 0;
    width: 4px;
    height: 45px;
    border-radius: 2px;
    transform-origin: bottom center;
    transform: rotate(30deg);
}

/* Triangle Side 3 - Right */
.triangle-side.side-3 {
    bottom: 0;
    right: 0;
    width: 4px;
    height: 45px;
    border-radius: 2px;
    transform-origin: bottom center;
    transform: rotate(-30deg);
}

/* Active Triangle States */
.triangle-indicator.level-1 .side-1 {
    background: linear-gradient(45deg, #28a745, #20c997);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    transform: scaleY(1.1);
}

.triangle-indicator.level-2 .side-1,
.triangle-indicator.level-2 .side-2 {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    transform: scaleY(1.1);
}

.triangle-indicator.level-2 .side-2 {
    transform: rotate(30deg) scaleY(1.1);
}

.triangle-indicator.level-3 .side-1,
.triangle-indicator.level-3 .side-2,
.triangle-indicator.level-3 .side-3 {
    background: linear-gradient(45deg, #dc3545, #e83e8c);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    transform: scaleY(1.1);
}

.triangle-indicator.level-3 .side-2 {
    transform: rotate(30deg) scaleY(1.1);
}

.triangle-indicator.level-3 .side-3 {
    transform: rotate(-30deg) scaleY(1.1);
}

/* Custom Radio Button Styles */
.custom-control-input:checked ~ .assessment-label {
    color: #007bff;
    font-weight: 600;
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.assessment-label {
    font-size: 14px;
    font-weight: 500;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 8px 12px;
    border-radius: 6px;
    margin-left: 5px;
}

.assessment-label:hover {
    background-color: #f8f9fa;
    color: #007bff;
}

.custom-control-input:checked ~ .assessment-label {
    background-color: #e3f2fd;
    border: 1px solid #007bff;
}

/* Clear Button Styles */
.clear-btn {
    border-radius: 20px;
    font-size: 12px;
    padding: 6px 12px;
    transition: all 0.3s ease;
    border: 1px solid #dc3545;
}

.clear-btn:hover {
    background-color: #dc3545;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

/* Options Container */
.options-container {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .triangle-indicator {
        width: 45px;
        height: 40px;
    }
    
    .triangle-wrapper {
        width: 35px;
        height: 32px;
    }
    
    .triangle-side.side-1 {
        width: 35px;
    }
    
    .triangle-side.side-2,
    .triangle-side.side-3 {
        height: 32px;
    }
    
    .options-container {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* Animation for state changes */
@keyframes trianglePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.triangle-indicator.animate {
    animation: trianglePulse 0.6s ease-in-out;
}
</style>





@section('content')

<div class="assessment-details">
    @php
        $groupedBySubject = $progessPlanData->groupBy(function($item) {
            return $item->subActivity->activity->subject->name ?? 'Unknown';
        });
    @endphp
    
    @foreach($groupedBySubject as $subjectName => $assessments)
        <div class="subject-group mb-4">
            <h6 class="subject-title">
                <i class="fas fa-book mr-2"></i>{{ $subjectName }}
                <span class="badge badge-success ml-2">{{ $assessments->count() }} items</span>
            </h6>
            <div class="assessment-items">
               <div class="row">
                @foreach($assessments as $assessment)
                <div class="col-md-6">
                    <div class="assessment-item-wrapper mb-3">
                        <div class="assessment-container-clickable d-flex align-items-center">
                            <!-- Clickable Triangle Visual Indicator -->
                            <div class="triangle-indicator-clickable  {{ auth()->user()->userType === 'Parent' ? 'not-clickable' : '' }}" 
                                 id="triangle-{{ $assessment->id }}"
                                 data-assessment-id="{{ $assessment->id }}"
                                 data-current-status="{{ $assessment->status }}"
                                 title="Click to change status">
                                <div class="triangle-wrapper">
                                    <div class="triangle-side side-1" data-level="1"></div>
                                    <div class="triangle-side side-2" data-level="2"></div>
                                    <div class="triangle-side side-3" data-level="3"></div>
                                </div>
                            </div>

                            <!-- Assessment Content -->
                            <div class="item-content-new ml-3 flex-grow-1">
                                <div class="activity-title">
                                    <strong>{{ $assessment->subActivity->activity->title ?? 'N/A' }}</strong>
                                </div>
                                <div class="sub-activity-title">
                                    {{ $assessment->subActivity->title ?? 'N/A' }}
                                </div>
                            </div>

                            <!-- Status Display -->
                            <div class="item-status-new">
                                @php
                                    $statusClass = [
                                        'Introduced' => 'badge-info',
                                        'Practicing' => 'badge-warning',
                                        'Completed' => 'badge-success'
                                    ];
                                    $displayStatus = $assessment->status === 'Working' ? 'Practicing' : $assessment->status;
                                @endphp
                                <span class="badge status-badge {{ $statusClass[$displayStatus] ?? 'badge-secondary' }}" 
                                      id="status-{{ $assessment->id }}">
                                    {{ $displayStatus }}
                                </span>
                            </div>
                        </div>
                 </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<style>
/* Additional CSS for clickable triangles */
.assessment-container-clickable {
    background: #ffffff;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.assessment-container-clickable:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
    transform: translateY(-2px);
}

.triangle-indicator-clickable {
    position: relative;
    width: 60px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.triangle-indicator-clickable:hover {
    background-color: #f8f9fa;
    transform: scale(1.1);
}

.triangle-indicator-clickable:active {
    transform: scale(0.95);
}

.item-content-new {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.activity-title {
    font-size: 16px;
    color: #2c3e50;
    font-weight: 600;
}

.sub-activity-title {
    font-size: 14px;
    color: #6c757d;
    font-weight: 400;
}

.item-status-new {
    display: flex;
    align-items: center;
}

.status-badge {
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.subject-group {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
    border: 1px solid #e9ecef;
}

.subject-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
}

.assessment-item-wrapper {
    transition: all 0.3s ease;
}

.assessment-item-wrapper:hover {
    transform: translateX(5px);
}

/* Loading Overlay */
#loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Triangle status states - same as before but with clickable prefix */
.triangle-indicator-clickable.status-introduced .side-1 {
 
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    transform: scaleY(1.1);
}

.triangle-indicator-clickable.status-practicing .side-1,
.triangle-indicator-clickable.status-practicing .side-2 {
    
    /* background: linear-gradient(45deg, #dc3545, #e83e8c); */
    background: linear-gradient(45deg, #176ba6, #00a8ff);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    transform: scaleY(1.1);
}

.triangle-indicator-clickable.status-practicing .side-2 {
    transform: rotate(30deg) scaleY(1.1);
}

.triangle-indicator-clickable.status-completed .side-1,
.triangle-indicator-clickable.status-completed .side-2,
.triangle-indicator-clickable.status-completed .side-3 {
    background: linear-gradient(45deg, #28a745, #20c997);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    transform: scaleY(1.1);
}

.triangle-indicator-clickable.status-completed .side-2 {
    transform: rotate(30deg) scaleY(1.1);
}

.triangle-indicator-clickable.status-completed .side-3 {
    transform: rotate(-30deg) scaleY(1.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .assessment-container-clickable {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .triangle-indicator-clickable {
        width: 45px;
        height: 40px;
    }
    
    .item-content-new {
        margin-left: 0 !important;
    }
}
</style>

<script>
// Status cycle order
const statusCycle = ['Introduced', 'Practicing', 'Completed'];

// Initialize triangles on page load
document.addEventListener('DOMContentLoaded', function() {
    const triangles = document.querySelectorAll('.triangle-indicator-clickable');
    
    triangles.forEach(triangle => {
        const currentStatus = triangle.dataset.currentStatus;
        updateTriangleDisplay(triangle, currentStatus);
        
        // Add click event listener
        triangle.addEventListener('click', function() {
            if (this.classList.contains('not-clickable')) {
        return;
    }
            handleTriangleClick(this);
        });
    });
});

function updateTriangleDisplay(triangle, status) {
    // Remove all status classes
    triangle.classList.remove('status-introduced', 'status-practicing', 'status-completed');
    
    // Add appropriate status class
    switch(status) {
        case 'Introduced':
            triangle.classList.add('status-introduced');
            break;
        case 'Practicing':
            triangle.classList.add('status-practicing');
            break;
        case 'Working':
            triangle.classList.add('status-practicing');
            break;
        case 'Completed':
            triangle.classList.add('status-completed');
            break;
    }
}

function getNextStatus(currentStatus) {
    const currentIndex = statusCycle.indexOf(currentStatus);
    const nextIndex = (currentIndex + 1) % statusCycle.length;
    return statusCycle[nextIndex];
}

function handleTriangleClick(triangle) {
    const assessmentId = triangle.dataset.assessmentId;
   
    let currentStatus = triangle.dataset.currentStatus;
    if (currentStatus === "Working") {
        currentStatus = "Practicing";
    }
    console.log("currentStatus",currentStatus);
    const nextStatus = getNextStatus(currentStatus);
    
    // Show loading
    showLoading();
    
    // Make AJAX request
    updateAssessmentStatus(assessmentId, nextStatus)
        .then(response => {
            if (response.success) {
                // Update triangle display
                updateTriangleDisplay(triangle, nextStatus);
                
                // Update dataset
                triangle.dataset.currentStatus = nextStatus;
                
                // Update status badge
                updateStatusBadge(assessmentId, nextStatus);
                
                // Add success animation
                triangle.classList.add('animate');
                setTimeout(() => {
                    triangle.classList.remove('animate');
                }, 600);
                
            } else {
                throw new Error(response.message || 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error updating status:', error);
            alert('Failed to update assessment status. Please try again.');
        })
        .finally(() => {
            hideLoading();
        });
}

function updateAssessmentStatus(assessmentId, newStatus) {
    return fetch('/learningandprogress/update-assessment-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            assessment_id: assessmentId,
            status: newStatus
        })
    })
    .then(response => response.json());
}

function updateStatusBadge(assessmentId, status) {
    const statusBadge = document.getElementById(`status-${assessmentId}`);
    if (statusBadge) {
        // Update badge text
        const displayStatus = status === 'Working' ? 'Practicing' : status;
        statusBadge.textContent = displayStatus;
        
        // Update badge class
        const statusClasses = {
           
            'Introduced': 'badge-info',
            'Practicing': 'badge-warning',
            'Completed': 'badge-success'
        };
        
        // Remove all badge classes
        statusBadge.classList.remove( 'badge-info', 'badge-warning', 'badge-success');
        
        // Add new badge class
        statusBadge.classList.add(statusClasses[status] || 'badge-secondary');
    }
}

function showLoading() {
    document.getElementById('loading-overlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loading-overlay').style.display = 'none';
}

// Add pulse animation keyframes
const style = document.createElement('style');
style.textContent = `
    @keyframes trianglePulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .triangle-indicator-clickable.animate {
        animation: trianglePulse 0.6s ease-in-out;
    }
`;
document.head.appendChild(style);
</script>

<!-- Add this to your blade template head section -->
<meta name="csrf-token" content="{{ csrf_token() }}">


@include('layout.footer')
@stop