@extends('layout.master')
@section('title', 'Create Program Plan')
@section('parentPageTitle', 'Dashboard')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>



<style>
    #eylfModal .modal-body {
    max-height: none !important;
    overflow-y: auto;
}
    </style>
@section('content')

    <main data-centerid="<?= isset($centerId)?$centerId:null; ?>" style="padding-block:5em;padding-inline:2em;">
  

     <!-- <div class="col-12 service-details-header">
    <div class="d-flex justify-content-between align-items-end flex-wrap">
 <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end gap-4"> 
 <h2 class="mb-0">Create Program Plan</h2> 
 <p class="mb-0 text-muted mx-md-4">
    Dashboard <span class="mx-2">|</span> <span>Create Program Plan</span>
  </p> 
</div>



     </div>
    <hr class="mt-3">
   </div>     -->

 

<!-- Form container -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Create Program Plan</h6> 
            <form id="programPlanForm" method="post">

            <?php if(isset($plan_data) && $plan_data): ?>
        <input type="hidden" name="plan_id" id="plan_id" value="<?= $plan_data->id ?>">
    <?php endif; ?>
         
            <input type="hidden" name="centerid" id="centerid" value="<?= isset($centerId)?$centerId:null; ?>";>
<input type="hidden" name="user_id" id="user_id" value="<?= isset($userId)?$userId:null; ?>";>

          
<div class="form-group mb-4">
        <label for="months">Select Month</label>
        <select class="form-control" id="months" name="months" required>
            <option value="">Select Month</option>
            <?php
            $months = [
                '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
            ];
            foreach ($months as $key => $month): ?>
                <option value="<?= $key ?>" <?= (isset($plan_data) && $plan_data->months == $key) ? 'selected' : '' ?>><?= $month ?></option>
            <?php endforeach; ?>
        </select>
    
    </div>

    <div class="form-group mb-4">
    <label for="years">Select Year</label>
    <select class="form-control" id="years" name="years" required>
        <option value="">Select Year</option>
        <?php
        $currentYear = date('Y');
        $startYear = $currentYear - 10; // Adjust as needed, e.g., show last 10 years
        $endYear = $currentYear + 10;   //adjust as needed to show future years.

        for ($year = $startYear; $year <= $endYear; $year++) {
            ?>
            <option value="<?= $year ?>" <?= (isset($plan_data) && $plan_data->years == $year) ? 'selected' : '' ?>><?= $year ?></option>
            <?php
        }
        ?>
    </select>
</div>


                <!-- Room Selection -->
               <!-- Room Selection -->
               <?php
$selectedRooms = isset($plan_data) ? explode(',', $plan_data->room_id) : [];
?> 
    <div class="form-group mb-4">
        <label for="room">Select Room</label>
        <select class="form-control select2-multiple" id="room" name="room[]" multiple="multiple" required>
            <option value="">Select Room</option>
             @foreach($rooms as $room)
               <option value="<?= $room->id ?>" <?= in_array($room->id, $selectedRooms) ? 'selected' : '' ?>>
    <?= $room->name ?>
</option>
             @endforeach
        </select>
    </div>

                <!-- Users Multiple Selection -->
                <div class="form-group mb-4">
                    <label for="users">Select Educators</label>
                    <select class="form-control select2-multiple" id="users" name="users[]" multiple="multiple" required>
                        <!-- Options will be populated via AJAX -->
                    </select>
                </div>

                <!-- Children Multiple Selection -->
                <div class="form-group mb-4">
                    <label for="children">Select Children</label>
                    <select class="form-control select2-multiple" id="children" name="children[]" multiple="multiple" required>
                        <!-- Options will be populated via AJAX -->
                    </select>
                </div>

                <!-- Focus Areas Section -->
                <div class="card mb-4">
                  
                    <div class="card-body">
<div class="form-group mb-3">
    <label>Focus Areas</label>
    <textarea class="form-control ckeditor" id="focus_area" name="focus_area" rows="3" placeholder="Focus Area"><?= isset($plan_data) ? $plan_data->focus_area : '' ?></textarea>
</div>

                        <!-- Practical Life -->
                        <div class="form-group mb-3">
                      
                        <label for="practical_life">Practical Life</label>
          <div class="input-group">
       <textarea class="form-control" id="practical_life" name="practical_life" rows="3" readonly><?= isset($plan_data) ? $plan_data->practical_life : '' ?></textarea>
         <div class="input-group-append">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#practicalLifeModal">
            <i class="fa fa-search"></i> Select Activities
        </button>
             </div>
        </div>

                            <input type="hidden" class="form-control mt-2" name="practical_life_experiences" value="<?= isset($plan_data) ? $plan_data->practical_life_experiences : '' ?>" placeholder="Planned experiences">
                        </div>

                        <!-- Sensorial -->
                        <div class="form-group mb-3">


                        <label for="sensorial">Sensorial</label>
<div class="input-group">
    <textarea class="form-control" id="sensorial" name="sensorial" rows="3" readonly><?= isset($plan_data) ? $plan_data->sensorial : '' ?></textarea>
    <div class="input-group-append">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#sensorialModal">
            <i class="fa fa-search"></i> Select Activities
        </button>
    </div>
</div>
                           
                           
                            <input type="hidden" class="form-control mt-2" name="sensorial_experiences" value="<?= isset($plan_data) ? $plan_data->sensorial_experiences : '' ?>" placeholder="Planned experiences">
                        </div>

                        <!-- Math -->
                        <div class="form-group mb-3"> 

                        <label for="math">Math</label>
<div class="input-group">
    <textarea class="form-control" id="math" name="math" rows="3" readonly><?= isset($plan_data) ? $plan_data->math : '' ?></textarea>
    <div class="input-group-append">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#mathModal">
            <i class="fa fa-search"></i> Select Activities
        </button>
    </div>
</div>
                          
                            <input type="hidden" class="form-control mt-2" name="math_experiences" value="<?= isset($plan_data) ? $plan_data->math_experiences : '' ?>" placeholder="Planned experiences">
                        </div>

                        <!-- Language -->
                        <div class="form-group mb-3">

                        <label for="language">Language</label>
<div class="input-group">
    <textarea class="form-control" id="language" name="language" rows="3" readonly><?= isset($plan_data) ? $plan_data->language : '' ?></textarea>
    <div class="input-group-append">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#languageModal">
            <i class="fa fa-search"></i> Select Activities
        </button>
    </div>
</div>
                           
                            <input type="hidden" class="form-control mt-2" name="language_experiences" value="<?= isset($plan_data) ? $plan_data->language_experiences : '' ?>" placeholder="Planned experiences">
                        </div>

                        <!-- Culture -->
                        <div class="form-group mb-3">
                          
                        <label for="culture">Culture</label>
<div class="input-group">
    <textarea class="form-control" id="culture" name="culture" rows="3" readonly><?= isset($plan_data) ? $plan_data->culture : '' ?></textarea>
    <div class="input-group-append">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#cultureModal">
            <i class="fa fa-search"></i> Select Activities
        </button>
    </div>
</div>
                          
                            <input type="hidden" class="form-control mt-2" name="culture_experiences" value="<?= isset($plan_data) ? $plan_data->culture_experiences : '' ?>" placeholder="Planned experiences">
                        </div>

                        <!-- Art & Craft -->
      <div class="form-group mb-3">
    <label>Art & Craft</label>
    <textarea class="form-control ckeditor" id="art_craft" name="art_craft" rows="3" placeholder="Art & Craft"><?= isset($plan_data) ? $plan_data->art_craft : '' ?></textarea>

    <!-- CKEditor textarea for experiences (commented out for now) -->
    <!--
    <label class="mt-2">Planned Experiences</label>
    <textarea class="form-control ckeditor" name="art_craft_experiences" rows="3" placeholder="Planned experiences"><?= isset($plan_data) ? $plan_data->art_craft_experiences : '' ?></textarea>
    -->
</div>


                    </div>
                </div>

                <!-- Additional Experiences Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Additional Experiences</h5>
                    </div>
                    <div class="card-body">
                        
 <div class="form-group mb-3">
    <label for="eylf">EYLF</label>
    <div class="input-group">
    <textarea class="form-control" id="eylf" name="eylf" rows="3" ><?= isset($plan_data) ? $plan_data->eylf : '' ?></textarea>
        <div class="input-group-append">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#eylfModal">
                <i class="fa fa-search"></i> Select EYLF
            </button>
        </div>
    </div>
</div>


                     <div class="form-group mb-3">
    <label for="outdoor_experiences">Outdoor Experiences <span style="color:blueviolet;font-weight:bold;"> (Add Experiences separated by Comma ",") </span></label>
    <textarea class="form-control ckeditor" id="outdoor_experiences" name="outdoor_experiences" rows="3" placeholder="1st Experiences, 2nd Experiences, 3rd Experiences etc..."><?= isset($plan_data) ? $plan_data->outdoor_experiences : '' ?></textarea>
</div>

<div class="form-group mb-3">
    <label for="inquiry_topic">Inquiry Topic</label>
    <textarea class="form-control ckeditor" id="inquiry_topic" name="inquiry_topic"  rows="3"><?= isset($plan_data) ? $plan_data->inquiry_topic : '' ?></textarea>
</div>

<div class="form-group mb-3">
    <label for="sustainability_topic">Sustainability Topic</label>
    <textarea class="form-control ckeditor" id="sustainability_topic" name="sustainability_topic" rows="3"><?= isset($plan_data) ? $plan_data->sustainability_topic : '' ?></textarea>
</div>

<div class="form-group mb-3">
    <label for="special_events">Special Events <span style="color:blueviolet;font-weight:bold;"> (Add multiple events separated by Comma ",") </span> </label>
    <textarea class="form-control ckeditor" id="special_events" name="special_events" rows="3" placeholder="14th March- Holi, 18th March- Global Recycling Day, 21st March- Harmony Day etc..."><?= isset($plan_data) ? $plan_data->special_events : '' ?></textarea>
</div>

<div class="form-group mb-3">
    <label for="children_voices">Children's Voices</label>
    <textarea class="form-control ckeditor" id="children_voices" name="children_voices"  rows="3"><?= isset($plan_data) ? $plan_data->children_voices : '' ?></textarea>
</div>

<div class="form-group mb-3">
    <label for="families_input">Families Input</label>
    <textarea class="form-control ckeditor" id="families_input" name="families_input"  rows="3"><?= isset($plan_data) ? $plan_data->families_input : '' ?></textarea>
</div>

<div class="form-group mb-3">
    <label for="group_experience">Group Experience</label>
    <textarea class="form-control ckeditor" id="group_experience" name="group_experience" rows="3"><?= isset($plan_data) ? $plan_data->group_experience : '' ?></textarea>
</div>

<div class="form-group mb-3">
    <label for="spontaneous_experience">Spontaneous Experience</label>
    <textarea class="form-control ckeditor" id="spontaneous_experience" name="spontaneous_experience" rows="3"><?= isset($plan_data) ? $plan_data->spontaneous_experience : '' ?></textarea>
</div>

<div class="form-group mb-3">
    <label for="mindfulness_experiences">Mindfulness Experiences</label>
    <textarea class="form-control ckeditor" id="mindfulness_experiences" name="mindfulness_experiences"  rows="3"><?= isset($plan_data) ? $plan_data->mindfulness_experiences : '' ?></textarea>
</div>
                    </div>
                </div>

                <?php if(isset($plan_data) && $plan_data): ?>
                   
                    <div class="form-group">
        <button type="submit" class="btn btn-info" id="updateBtn">Update</button>
       &nbsp;&nbsp;&nbsp; <button type="button" class="btn btn-info" style="background-color:#2eefb7;border-color:#2eefb7;color:black;" id="saveAsNewBtn">Save as New Data</button>
         <button type="button" class="btn btn-default btn-danger" id="cancel-btn">Cancel</button>
    </div>

                <?php else: ?>
                    <div class="form-group">
                    <button type="submit" class="btn btn-info">Submit</button>
                      <button type="button" class="btn btn-default btn-danger" id="cancel-btn">Cancel</button>
                </div>
                <?php endif; ?>
                
            </form>
        </div>
    </div>
</div>


</main>




<!-- EYLF Modal -->
<!-- EYLF Modal -->
<div class="modal" id="eylfModal" tabindex="-1" role="dialog" aria-labelledby="eylfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Select EYLF</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" >
                <div class="eylf-tree">
                    <ul class="list-group">
                        <!-- Main EYLF Framework -->
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#eylfFramework">
                                    <i class="fa fa-chevron-right"></i>
                                </span>
                                <span>Early Years Learning Framework (EYLF) - Australia (V2.0 2022)</span>
                            </div>

                            <!-- Framework Collapse -->
                            <div id="eylfFramework" class="collapse mt-2">
                                <ul class="list-group">
                                    <!-- Learning Outcomes -->
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#eylfOutcomes">
                                                <i class="fa fa-chevron-right"></i>
                                            </span>
                                            <span>EYLF Learning Outcomes</span>
                                        </div>

                                        <!-- Outcomes List -->
                                        <div id="eylfOutcomes" class="collapse mt-2">
                                            <ul class="list-group">
                                                @foreach($eylf_outcomes as $outcome)
                                                    <li class="list-group-item">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#outcome{{ $outcome->id }}">
                                                                <i class="fa fa-chevron-right"></i>
                                                            </span>
                                                            <span>{{ $outcome->title }} - {{ $outcome->name }}</span>
                                                        </div>

                                                        <!-- Activities under each outcome -->
                                                        <div id="outcome{{ $outcome->id }}" class="collapse mt-2">
                                                            <ul class="list-group">
                                                                @foreach($outcome->activities as $activity)
                                                                    @php
                                                                        $lineText = "{$outcome->title} - {$outcome->name}: {$activity->title}";
                                                                        $isChecked = in_array($lineText, $selectedLines ?? []);
                                                                    @endphp
                                                                    <li class="list-group-item">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input eylf-activity-checkbox"
                                                                                   type="checkbox"
                                                                                   value="{{ $activity->id }}"
                                                                                   id="activity{{ $activity->id }}"
                                                                                   data-outcome-id="{{ $outcome->id }}"
                                                                                   data-outcome-title="{{ $outcome->title }}"
                                                                                   data-outcome-name="{{ $outcome->name }}"
                                                                                   data-activity-title="{{ $activity->title }}"
                                                                                   {{ $isChecked ? 'checked' : '' }}>
                                                                            <label class="form-check-label" for="activity{{ $activity->id }}">
                                                                                {{ $activity->title }}
                                                                            </label>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEylfSelections" data-dismiss="modal">Save selections</button>
            </div>

        </div>
    </div>
</div>








<!-- Practical Life Modal -->
<div class="modal" id="practicalLifeModal" tabindex="-1" role="dialog" aria-labelledby="practicalLifeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="practicalLifeModalLabel">Select Practical Life Activities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="max-height:500px; overflow-y:auto;">
                <div class="practical-life-tree">
                    <ul class="list-group">
                        <li class="list-group-item practical-life-framework">
                            <div class="d-flex align-items-center">
                                <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#practicalLifeFramework">
                                    <i class="fa fa-chevron-right"></i>
                                </span>
                                <span>Practical Life (Montessori)</span>
                            </div>

                            <div id="practicalLifeFramework" class="collapse mt-2">
                                <ul class="list-group">
                                    @foreach ($montessori_subjects as $subject)
                                        @if ($subject->name === 'Practical Life')
                                            @foreach ($subject->activities as $activity)
                                                <li class="list-group-item practical-life-activity">
                                                    <div class="d-flex align-items-center">
                                                        <span class="mr-2 toggle-icon"
                                                              data-toggle="collapse"
                                                              data-target="#pl_activity{{ $activity->idActivity }}">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                        <span>{{ $activity->title }}</span>
                                                    </div>

                                                    <div id="pl_activity{{ $activity->idActivity }}" class="collapse mt-2">
                                                        <ul class="list-group">
                                                            @foreach ($activity->subActivities as $index => $subActivity)
                                                                <li class="list-group-item practical-life-sub-activity">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input practical-life-checkbox"
                                                                               type="checkbox"
                                                                               value="{{ $subActivity->title }}"
                                                                               id="pl_subActivity{{ $activity->idActivity }}_{{ $index }}"
                                                                               data-activity-id="{{ $activity->idActivity }}"
                                                                               data-activity-title="{{ $activity->title }}"
                                                                               data-sub-activity-title="{{ $subActivity->title }}">
                                                                        <label class="form-check-label"
                                                                               for="pl_subActivity{{ $activity->idActivity }}_{{ $index }}">
                                                                            {{ $subActivity->title }}
                                                                        </label>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePracticalLifeSelections">Save selections</button>
            </div>
        </div>
    </div>
</div>



<!-- Sensorial Modal -->
<div class="modal" id="sensorialModal" tabindex="-1" role="dialog" aria-labelledby="sensorialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sensorialModalLabel">Select Sensorial Activities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height:500px; overflow-y:auto;">
                <div class="sensorial-tree">
                    <ul class="list-group">
                        <!-- Main Sensorial Framework -->
                        <li class="list-group-item sensorial-framework">
                            <div class="d-flex align-items-center">
                                <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#sensorialFramework">
                                    <i class="fa fa-chevron-right"></i>
                                </span>
                                <span>Sensorial</span>
                            </div>

                            <!-- Sensorial Framework Content -->
                            <div id="sensorialFramework" class="collapse mt-2">
                                <ul class="list-group">
                                    @foreach ($montessori_subjects as $subject)
                                        @if ($subject->name === 'Sensorial')
                                   
                                            @foreach ($subject->activities as $activity)
                                                <li class="list-group-item sensorial-activity">
                                                    <div class="d-flex align-items-center">
                                                        <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#sensorialActivity{{ $activity->idActivity }}">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                        <span>{{ $activity->title }}</span>
                                                    </div>

                                                    <!-- Sub-Activities -->
                                                    <div id="sensorialActivity{{ $activity->idActivity }}" class="collapse mt-2">
                                                        <ul class="list-group">
                                                       
                                                            @foreach ($activity->subActivities as $index => $subActivity)
                                                                <li class="list-group-item sensorial-sub-activity">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input sensorial-checkbox"
                                                                               type="checkbox"
                                                                               value="{{ $subActivity->title }}"
                                                                               id="sensorialSubActivity{{ $activity->idActivity }}_{{ $index }}"
                                                                               data-activity-id="{{ $activity->idActivity }}"
                                                                               data-activity-title="{{ $activity->title }}"
                                                                               data-sub-activity-title="{{ $subActivity->title }}">
                                                                        <label class="form-check-label" for="sensorialSubActivity{{ $activity->idActivity }}_{{ $index }}">
                                                                            {{ $subActivity->title }}
                                                                        </label>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSensorialSelections">Save selections</button>
            </div>
        </div>
    </div>
</div>



<!-- Math Modal -->
<div class="modal" id="mathModal" tabindex="-1" role="dialog" aria-labelledby="mathModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mathModalLabel">Select Math Activities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height:500px;overflow-y:auto;">
                <div class="math-tree">
                    <ul class="list-group">
                        <!-- Main Math Framework -->
                        <li class="list-group-item math-framework">
                            <div class="d-flex align-items-center">
                                <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#mathFramework">
                                    <i class="fa fa-chevron-right"></i>
                                </span>
                                <span>Maths</span>
                            </div>
                            
                            <!-- Math Framework content -->
                            <div id="mathFramework" class="collapse mt-2">
                                <ul class="list-group">
                                    <?php foreach ($montessori_subjects as $subject) : ?>
                                        <?php if ($subject->name === 'Maths') : ?>
                                            <?php foreach ($subject->activities as $activity) : ?>
                                            <li class="list-group-item math-activity">
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#mathActivity<?= $activity->idActivity ?>">
                                                        <i class="fa fa-chevron-right"></i>
                                                    </span>
                                                    <span><?= $activity->title ?></span>
                                                </div>
                                                
                                                <!-- Sub-activities for this activity -->
                                                <div id="mathActivity<?= $activity->idActivity ?>" class="collapse mt-2">
                                                    <ul class="list-group">
                                                        <?php 
                                                         $subActivityCounter = 0;
                                                        foreach ($activity->subActivities

 as $sub_activity) : 
                                                        ?>
                                                           
                                                        <li class="list-group-item math-sub-activity">
                                                            <div class="form-check">
                                                                <input class="form-check-input math-checkbox"
                                                                       type="checkbox"
                                                                       value="<?= $sub_activity->title ?>"
                                                                       id="mathSubActivity<?= $activity->idActivity ?>_<?= $subActivityCounter ?>"
                                                                       data-activity-id="<?= $activity->idActivity ?>"
                                                                       data-activity-title="<?= $activity->title ?>"
                                                                       data-sub-activity-title="<?= $sub_activity->title ?>">
                                                                <label class="form-check-label" for="mathSubActivity<?= $activity->idActivity ?>_<?= $subActivityCounter ?>">
                                                                    <?= $sub_activity->title ?>
                                                                </label>
                                                            </div>
                                                        </li>
                                                    <?php
                                                    $subActivityCounter++; 
                                                    endforeach; 
                                                    ?>
                                                    </ul>
                                                </div>
                                            </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveMathSelections">Save selections</button>
            </div>
        </div>
    </div>
</div>


<!-- Language Modal -->
<div class="modal" id="languageModal" tabindex="-1" role="dialog" aria-labelledby="languageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="languageModalLabel">Select Language Activities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height:500px;overflow-y:auto;">
                <div class="language-tree">
                    <ul class="list-group">
                        <!-- Main Language Framework -->
                        <li class="list-group-item language-framework">
                            <div class="d-flex align-items-center">
                                <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#languageFramework">
                                    <i class="fa fa-chevron-right"></i>
                                </span>
                                <span>Language</span>
                            </div>
                            
                            <!-- Language Framework content -->
                            <div id="languageFramework" class="collapse mt-2">
                                <ul class="list-group">
                                    <?php foreach ($montessori_subjects as $subject) : ?>
                                        <?php if ($subject->name === 'Language') : ?>
                                            <?php foreach ($subject->activities as $activity) : ?>
                                            <li class="list-group-item language-activity">
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#languageActivity<?= $activity->idActivity ?>">
                                                        <i class="fa fa-chevron-right"></i>
                                                    </span>
                                                    <span><?= $activity->title ?></span>
                                                </div>
                                                
                                                <!-- Sub-activities for this activity -->
                                                <div id="languageActivity<?= $activity->idActivity ?>" class="collapse mt-2">
                                                    <ul class="list-group">
                                                        <?php 
                                                         $subActivityCounter = 0;
                                                        foreach ($activity->subActivities
 as $sub_activity) : 
                                                        ?>
                                                           
                                                        <li class="list-group-item language-sub-activity">
                                                            <div class="form-check">
                                                                <input class="form-check-input language-checkbox"
                                                                       type="checkbox"
                                                                       value="<?= $sub_activity->title ?>"
                                                                       id="languageSubActivity<?= $activity->idActivity ?>_<?= $subActivityCounter ?>"
                                                                       data-activity-id="<?= $activity->idActivity ?>"
                                                                       data-activity-title="<?= $activity->title ?>"
                                                                       data-sub-activity-title="<?= $sub_activity->title ?>">
                                                                <label class="form-check-label" for="languageSubActivity<?= $activity->idActivity ?>_<?= $subActivityCounter ?>">
                                                                    <?= $sub_activity->title ?>
                                                                </label>
                                                            </div>
                                                        </li>
                                                    <?php
                                                    $subActivityCounter++; 
                                                    endforeach; 
                                                    ?>
                                                    </ul>
                                                </div>
                                            </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveLanguageSelections">Save selections</button>
            </div>
        </div>
    </div>
</div>


<!-- Culture Modal -->
<div class="modal" id="cultureModal" tabindex="-1" role="dialog" aria-labelledby="cultureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cultureModalLabel">Select Culture Activities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height:500px;overflow-y:auto;">
                <div class="culture-tree">
                    <ul class="list-group">
                        <!-- Main Culture Framework -->
                        <li class="list-group-item culture-framework">
                            <div class="d-flex align-items-center">
                                <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#cultureFramework">
                                    <i class="fa fa-chevron-right"></i>
                                </span>
                                <span>Cultural</span>
                            </div>
                            
                            <!-- Culture Framework content -->
                            <div id="cultureFramework" class="collapse mt-2">
                                <ul class="list-group">
                                    <?php foreach ($montessori_subjects as $subject) : ?>
                                        <?php if ($subject->name === 'Cultural') : ?>
                                            <?php foreach ($subject->activities as $activity) : ?>
                                            <li class="list-group-item culture-activity">
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#cultureActivity<?= $activity->idActivity ?>">
                                                        <i class="fa fa-chevron-right"></i>
                                                    </span>
                                                    <span><?= $activity->title ?></span>
                                                </div>
                                                
                                                <!-- Sub-activities for this activity -->
                                                <div id="cultureActivity<?= $activity->idActivity ?>" class="collapse mt-2">
                                                    <ul class="list-group">
                                                        <?php 
                                                         $subActivityCounter = 0;
                                                        foreach ($activity->subActivities
 as $sub_activity) : 
                                                        ?>
                                                           
                                                        <li class="list-group-item culture-sub-activity">
                                                            <div class="form-check">
                                                                <input class="form-check-input culture-checkbox"
                                                                       type="checkbox"
                                                                       value="<?= $sub_activity->title ?>"
                                                                       id="cultureSubActivity<?= $activity->idActivity ?>_<?= $subActivityCounter ?>"
                                                                       data-activity-id="<?= $activity->idActivity ?>"
                                                                       data-activity-title="<?= $activity->title ?>"
                                                                       data-sub-activity-title="<?= $sub_activity->title ?>">
                                                                <label class="form-check-label" for="cultureSubActivity<?= $activity->idActivity ?>_<?= $subActivityCounter ?>">
                                                                    <?= $sub_activity->title ?>
                                                                </label>
                                                            </div>
                                                        </li>
                                                    <?php
                                                    $subActivityCounter++; 
                                                    endforeach; 
                                                    ?>
                                                    </ul>
                                                </div>
                                            </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCultureSelections">Save selections</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="dateModal" tabindex="-1" role="dialog" aria-labelledby="staffModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center justify-content-between">
        <h5 class="modal-title">Program Plan</h5>
      </div>
      <form action="{{ route('programplan.MonthYear') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="form-group mb-4">
            <label for="months">Select Month</label>
       <select class="form-control" id="months" name="months" required>
    <option value="">Select Month</option>
    <?php
    $months = [
        '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
        '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
        '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
    ];

    $currentMonth = date('m'); // current month in 2-digit format

    foreach ($months as $key => $month):
    ?>
        <option value="<?= $key ?>"
            <?= (isset($plan_data) && $plan_data->months == $key) 
                ? 'selected' 
                : ((!isset($plan_data) && $currentMonth == $key) ? 'selected' : '') ?>>
            <?= $month ?>
        </option>
    <?php endforeach; ?>
</select>

          </div>

          <div class="form-group mb-4">
            <label for="years">Select Year</label>
           <select class="form-control" id="years" name="years" required>
    <option value="">Select Year</option>
    <?php
    $currentYear = date('Y');
    $startYear = $currentYear - 10;
    $endYear   = $currentYear + 10;

    for ($year = $startYear; $year <= $endYear; $year++):
    ?>
        <option value="<?= $year ?>"
            <?= (isset($plan_data) && $plan_data->years == $year) 
                ? 'selected' 
                : ((!isset($plan_data) && $currentYear == $year) ? 'selected' : '') ?>>
            <?= $year ?>
        </option>
    <?php endfor; ?>
</select>

          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-secondary" onclick="window.history.back()">
            <i class="fas fa-times mr-1"></i> Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>












<script>


let editors = {}; // Store all CKEditor instances

document.addEventListener("DOMContentLoaded", function () {
    // Initialize all CKEditor instances
    document.querySelectorAll(".ckeditor").forEach((textarea) => {
        let id = textarea.getAttribute("id");

        ClassicEditor.create(textarea)
            .then(editor => {
                editors[id] = editor;
                console.log(id + " ready ✅");

                // Attach change listener for autosave
                editor.model.document.on("change:data", () => {
                    AutoSave();
                });
            })
            .catch(error => console.error(id + " error ❌", error));
    });
});

// AutoSave function
function AutoSave() {
    // Collect data from all CKEditor instances dynamically
    let dataToSave = {};
    for (let id in editors) {
        dataToSave[id] = editors[id].getData();
    }

    // Include observation_id if exists
    dataToSave.plan_id = document.querySelector('#plan_id') ? document.querySelector('#plan_id').value : null;

    console.log("AutoSaving...", dataToSave);

    fetch("{{ route('programplan.autosave') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify(dataToSave)
    })
    .then(response => response.json())
    .then(data => {
        console.log("AutoSave response ✅", data);

        if (data.status === 'success') {
            // Update hidden observation_id if returned
            if (data.observation_id) {
                let hiddenIdField = document.querySelector('#observation_id');
                if (hiddenIdField) hiddenIdField.value = data.observation_id;
            }
        } 
        else if (data.status === 'error') {
            if (data.errors) {
                console.log("Validation errors:", data.errors);
                Object.keys(data.errors).forEach(key => {
                    showToast('toast-error', `${key.replace('_', ' ')} is required`);
                });
            }
        } 
        else {
            Swal.fire({
                icon: 'warning',
                title: 'Unexpected response',
                text: 'Autosave returned an unknown status.',
            });
        }
    })
    .catch(error => console.error("AutoSave failed ❌", error));
}
</script>

<script>
    

        $(document).ready(function () {
        let reflection = @json($plan_data);

        if (!reflection) {
            $('#dateModal').modal('show');
        }
    }); 
document.addEventListener("DOMContentLoaded", function() {
    const yearSelect = document.getElementById("years");
    const monthSelect = document.getElementById("months");
    const dateDataInput = document.getElementById("dateData");
    const modal = document.getElementById("dateModal");

    function updateDateData() {
        const year = yearSelect && yearSelect.value ? yearSelect.value : "";
        const monthText = monthSelect && monthSelect.selectedIndex > 0 ? monthSelect.options[monthSelect.selectedIndex].text : "";
        dateDataInput.value = (monthText && year) ? monthText + " - " + year : "";
    }

    // Update whenever user changes month or year
    if (yearSelect) yearSelect.addEventListener("change", updateDateData);
    if (monthSelect) monthSelect.addEventListener("change", updateDateData);

    // Update when modal opens
    if (modal) {
        modal.addEventListener('show.bs.modal', updateDateData);
    }

    // Initial update in case plan_data exists
    updateDateData();
}); 
</script>
    <!-- all the script here of this page only -->
<script>
       document.getElementById('cancel-btn').addEventListener('click', function () {
        history.back(); // ✅ Go to the last visited page
    });
$(document).ready(function () {
    // alert('loaded');
    console.log(jQuery.fn.jquery); // Check jQuery version
console.log(jQuery.fn.select2); // Check if Select2 is available
    // Initialize Select2
    $('.select2').select2();
 $('.select2-multiple').select2();

    // Store selected educators and children for edit mode
    var selectedEducators = <?= json_encode($selected_educators) ?>;
    var selectedChildren = <?= json_encode($selected_children) ?>;
    
    // Function to load educators with pre-selection
    // function loadEducators(roomId, centerId) {
    //     // alert();
    //       $('#users').select2({
    //         placeholder: "Select educators"
    //     });
    //     const csrfToken = $('meta[name="csrf-token"]').attr('content');
    //     $.ajax({
    //         url: '{{route ("LessonPlanList.get_room_users") }}',
    //         method: 'POST',
    //          headers: {
    //         'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
    //     },
    //         data: { room_id: roomId, center_id: centerId },
    //         success: function (response) {
    //             let users = JSON.parse(response);
    //             $('#users').empty();
                
    //             users.forEach(user => {
    //                 let option = new Option(user.name, user.id, true, true);
    //                 $('#users').append(option);
    //             });
                
    //             // For edit mode: pre-select educators
    //             if (selectedEducators.length > 0) {
    //                 $('#users').val(selectedEducators).trigger('change');
    //             }
    //         }
    //     });
    // }
   

    function loadEducators(roomId, centerId) {
        //  alert(roomId+ ' ' +centerId);
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '{{ route("LessonPlanList.get_room_users") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            room_id:[roomId],
            center_id: centerId
        },
        success: function (response) {
          
            let users = response;
            $('#users').empty();

           users.forEach(user => {
    console.log(user);  // make sure user.name and user.id are not undefined
    let option = new Option(user.name, user.id, false, false);
    $('#users').append(option);
});

            // Select the educators in edit mode
           if (selectedEducators.length > 0) {
    $('#users').val(selectedEducators.map(String)).trigger('change');
}
        }
    });
}
    
    // Function to load children with pre-selection
    function loadChildren(roomId, centerId) {
        // alert();
        // console.log(roomId);
          const csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{route ("LessonPlanList.get_room_children") }}',
            method: 'POST',
                headers: {
            'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
            data: { room_id: [roomId], center_id: centerId },
            success: function (response) {
                console.log(typeof response); // "string" or "object"

                let children = response;
                $('#children').empty();
                
                // Add "Select All" option as the first option
                $('#children').append(new Option('Select All', 'all', false, false));
                
                children.forEach(child => {
                    let option = new Option(child.name, child.id, false, false);
                    $('#children').append(option);
                });
                
                // For edit mode: pre-select children
                if (selectedChildren.length > 0) {
                    $('#children').val(selectedChildren).trigger('change');
                }

                // Add event handler for "Select All" option
                $('#children').on('change', function(e) {
                    let values = $(this).val();
                    
                    // Check if "all" is selected
                    if (values && values.includes('all')) {
                        // If "all" was just selected, select all options except "all" itself
                        let allOptionValues = [];
                        $('#children option').each(function() {
                            if ($(this).val() !== 'all') {
                                allOptionValues.push($(this).val());
                            }
                        });
                        $(this).val(allOptionValues).trigger('change');
                    }
                });
            }
        });
    }

    // Fetch users and children based on room selection
    $('#room').change(function () {
        // alert();
        let roomId = $(this).val();
        let centerId = $('#centerid').val();
   console.log('Room ID:', roomId);
        console.log('Center ID:', centerId);
        if (roomId) {
            loadEducators(roomId, centerId);
            loadChildren(roomId, centerId);
        }
    });
    
    // If in edit mode, trigger room change to load educators and children
    <?php if(isset($plan_data) && $plan_data): ?>
        // Trigger room change event to load educators and children
        let roomId = '<?= $plan_data->room_id ?>';
        let centerId = '<?= $centerId ?>';
        
        if (roomId) {
            loadEducators(roomId, centerId);
            loadChildren(roomId, centerId);
        }
    <?php endif; ?>

    // Form submission handler
    $('#programPlanForm').on('submit', function(e) {
        e.preventDefault();
        
        // If month field is disabled for edit mode, ensure the value is included
        if ($('#months').prop('disabled')) {
            // The hidden input field already handles this
        }
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{ route ("LessonPlanList.save_program_planinDB") }}',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
            'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
         success: function(response) {
    if (response.success) {
        // console.log("Redirecting to:", response.redirect_url); // ✅ this works
        window.location.href = response.redirect_url; // ✅ actual redirection
    } else {
        alert('Error saving program plan. Please try again.');
    }
},
            error: function() {
                alert('An error occurred while processing your request.');
            }
        });
    });


    $('#saveAsNewBtn').on('click', function() {
    // Remove the plan_id from the form before submission
    $('#programPlanForm input[name="plan_id"]').remove();
    
    // Submit the form
    $('#programPlanForm').submit();
               });


});
</script>


<script>
$(document).ready(function() {
    $(document).on('click', '.toggle-icon', function(e) {
        // Prevent the event from bubbling up
        e.stopPropagation();
        
        // Toggle only the clicked icon's expanded class
        $(this).toggleClass('expanded');
        
        // Change only this icon
        if ($(this).hasClass('expanded')) {
            $(this).find('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        } else {
            $(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-right');
        }
        
        // Toggle the collapse that this icon controls
        const targetId = $(this).data('target');
        $(targetId).collapse('toggle');
    });
    
    // Handle collapse events
    $(document).on('show.bs.collapse', '.collapse', function(e) {
        // Stop event propagation to avoid triggering parent collapses
        e.stopPropagation();
        
        // Only find the toggle icon that directly controls this collapse
        const toggleIcon = $('[data-target="#' + $(this).attr('id') + '"]');
        toggleIcon.addClass('expanded').find('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
    });
    
    $(document).on('hide.bs.collapse', '.collapse', function(e) {
        // Stop event propagation
        e.stopPropagation();
        
        // Only find the toggle icon that directly controls this collapse
        const toggleIcon = $('[data-target="#' + $(this).attr('id') + '"]');
        toggleIcon.removeClass('expanded').find('i').removeClass('fa-chevron-down').addClass('fa-chevron-right');
    });
    
    // Prevent collapse events from triggering multiple collapses
    $(document).on('show.bs.collapse hide.bs.collapse', '.collapse', function(e) {
        // Only trigger for the element that received the click
        if (e.target !== this) {
            e.stopPropagation();
        }
    });
    
    
    // Save EYLF selections - rest of the code remains the same
    $('#saveEylfSelections').on('click', function() {
        var selectedActivities = [];
        
        $('.eylf-activity-checkbox:checked').each(function() {
            var activityId = $(this).val();
            var outcomeId = $(this).data('outcome-id');
            var outcomeTitle = $(this).data('outcome-title');
            var outcomeName = $(this).data('outcome-name');
            var activityTitle = $(this).data('activity-title');
            
            selectedActivities.push({
                activityId: activityId,
                outcomeId: outcomeId,
                outcomeTitle: outcomeTitle,
                outcomeName: outcomeName,
                activityTitle: activityTitle
            });
        });
        
        // Format the selected activities for display in the textarea
        var formattedText = '';
        if (selectedActivities.length > 0) {
            selectedActivities.forEach(function(item, index) {
                formattedText += item.outcomeTitle + ' - ' + item.outcomeName + ': ' + item.activityTitle;
                if (index < selectedActivities.length - 1) {
                    formattedText += '\n';
                }
            });
        }
        
        // Set the formatted text in the textarea
        $('#eylf').val(formattedText);
        
        // Store the raw data in a hidden input for form submission
        if (!$('#eylfData').length) {
            $('<input>').attr({
                type: 'hidden',
                id: 'eylfData',
                name: 'eylfData'
            }).appendTo('form');
        }
        $('#eylfData').val(JSON.stringify(selectedActivities));
        
        // Close the modal
        $('#eylfModal').modal('hide');
    });




    $('#savePracticalLifeSelections').on('click', function() {
        var selectedSubActivities = [];
        
        $('.practical-life-checkbox:checked').each(function() {
            var subActivityTitle = $(this).val();
            var activityId = $(this).data('activity-id');
            var activityTitle = $(this).data('activity-title');
            var subActivityTitle = $(this).data('sub-activity-title');
            
            selectedSubActivities.push({
                subActivityTitle: subActivityTitle,
                activityId: activityId,
                activityTitle: activityTitle
            });
        });
        
       // Format the selected activities for display in the input field
var formattedText = '';
if (selectedSubActivities.length > 0) {
    // Group by activity
    var groupedActivities = {};
    
    selectedSubActivities.forEach(function(item) {
        if (!groupedActivities[item.activityTitle]) {
            groupedActivities[item.activityTitle] = [];
        }
        groupedActivities[item.activityTitle].push(item.subActivityTitle);
    });
    
    // Create the formatted string with each activity on a new line
    var activityStrings = [];
    for (var activity in groupedActivities) {
        // Start with bold activity title
        var activityString = "**" + activity + "** - \n";
        
        // Add sub-activities as bulleted list
        groupedActivities[activity].forEach(function(subActivity) {
            activityString += "**• **" + subActivity + ".\n";
        });
        
        activityStrings.push(activityString.trim());
    }
    
    formattedText = activityStrings.join('\n');
}
        
        // Set the formatted text in the input field
        $('#practical_life').val(formattedText);
        
        // Store the raw data in a hidden input for form submission
        if (!$('#practicalLifeData').length) {
            $('<input>').attr({
                type: 'hidden',
                id: 'practicalLifeData',
                name: 'practicalLifeData'
            }).appendTo('form');
        }
        $('#practicalLifeData').val(JSON.stringify(selectedSubActivities));
        
        // Close the modal
        $('#practicalLifeModal').modal('hide');
    });
    
    // Make sure the practical life modal is properly initialized
    $('#practicalLifeModal').on('shown.bs.modal', function () {
        // Auto-expand the top level of the tree
        setTimeout(function() {
            $('#practicalLifeFramework').collapse('show');
        }, 200);
    });



    // Sensorial Modal JavaScript
$('#saveSensorialSelections').on('click', function() {
    var selectedSubActivities = [];
    
    $('.sensorial-checkbox:checked').each(function() {
        var subActivityTitle = $(this).val();
        var activityId = $(this).data('activity-id');
        var activityTitle = $(this).data('activity-title');
        var subActivityTitle = $(this).data('sub-activity-title');
        
        selectedSubActivities.push({
            subActivityTitle: subActivityTitle,
            activityId: activityId,
            activityTitle: activityTitle
        });
    });
    
      // Format the selected activities for display in the input field
var formattedText = '';
if (selectedSubActivities.length > 0) {
    // Group by activity
    var groupedActivities = {};
    
    selectedSubActivities.forEach(function(item) {
        if (!groupedActivities[item.activityTitle]) {
            groupedActivities[item.activityTitle] = [];
        }
        groupedActivities[item.activityTitle].push(item.subActivityTitle);
    });
    
    // Create the formatted string with each activity on a new line
    var activityStrings = [];
    for (var activity in groupedActivities) {
        // Start with bold activity title
        var activityString = "**" + activity + "** - \n";
        
        // Add sub-activities as bulleted list
        groupedActivities[activity].forEach(function(subActivity) {
            activityString += "**• **" + subActivity + ".\n";
        });
        
        activityStrings.push(activityString.trim());
    }
    
    formattedText = activityStrings.join('\n');
}
    
    // Set the formatted text in the input field
    $('#sensorial').val(formattedText);
    
    // Store the raw data in a hidden input for form submission
    if (!$('#sensorialData').length) {
        $('<input>').attr({
            type: 'hidden',
            id: 'sensorialData',
            name: 'sensorialData'
        }).appendTo('form');
    }
    $('#sensorialData').val(JSON.stringify(selectedSubActivities));
    
    // Close the modal
    $('#sensorialModal').modal('hide');
});

// Make sure the sensorial modal is properly initialized
$('#sensorialModal').on('shown.bs.modal', function () {
    // Auto-expand the top level of the tree
    setTimeout(function() {
        $('#sensorialFramework').collapse('show');
    }, 200);
});



// Math Modal JavaScript
$('#saveMathSelections').on('click', function() {
    var selectedSubActivities = [];
    
    $('.math-checkbox:checked').each(function() {
        var subActivityTitle = $(this).val();
        var activityId = $(this).data('activity-id');
        var activityTitle = $(this).data('activity-title');
        var subActivityTitle = $(this).data('sub-activity-title');
        
        selectedSubActivities.push({
            subActivityTitle: subActivityTitle,
            activityId: activityId,
            activityTitle: activityTitle
        });
    });
    
       // Format the selected activities for display in the input field
var formattedText = '';
if (selectedSubActivities.length > 0) {
    // Group by activity
    var groupedActivities = {};
    
    selectedSubActivities.forEach(function(item) {
        if (!groupedActivities[item.activityTitle]) {
            groupedActivities[item.activityTitle] = [];
        }
        groupedActivities[item.activityTitle].push(item.subActivityTitle);
    });
    
    // Create the formatted string with each activity on a new line
    var activityStrings = [];
    for (var activity in groupedActivities) {
        // Start with bold activity title
        var activityString = "**" + activity + "** - \n";
        
        // Add sub-activities as bulleted list
        groupedActivities[activity].forEach(function(subActivity) {
            activityString += "**• **" + subActivity + ".\n";
        });
        
        activityStrings.push(activityString.trim());
    }
    
    formattedText = activityStrings.join('\n');
}
    
    // Set the formatted text in the input field
    $('#math').val(formattedText);
    
    // Store the raw data in a hidden input for form submission
    if (!$('#mathData').length) {
        $('<input>').attr({
            type: 'hidden',
            id: 'mathData',
            name: 'mathData'
        }).appendTo('form');
    }
    $('#mathData').val(JSON.stringify(selectedSubActivities));
    
    // Close the modal
    $('#mathModal').modal('hide');
});

// Make sure the math modal is properly initialized
$('#mathModal').on('shown.bs.modal', function () {
    // Auto-expand the top level of the tree
    setTimeout(function() {
        $('#mathFramework').collapse('show');
    }, 200);
});


// Language Modal JavaScript
$('#saveLanguageSelections').on('click', function() {
    var selectedSubActivities = [];
    
    $('.language-checkbox:checked').each(function() {
        var subActivityTitle = $(this).val();
        var activityId = $(this).data('activity-id');
        var activityTitle = $(this).data('activity-title');
        var subActivityTitle = $(this).data('sub-activity-title');
        
        selectedSubActivities.push({
            subActivityTitle: subActivityTitle,
            activityId: activityId,
            activityTitle: activityTitle
        });
    });
    
     // Format the selected activities for display in the input field
var formattedText = '';
if (selectedSubActivities.length > 0) {
    // Group by activity
    var groupedActivities = {};
    
    selectedSubActivities.forEach(function(item) {
        if (!groupedActivities[item.activityTitle]) {
            groupedActivities[item.activityTitle] = [];
        }
        groupedActivities[item.activityTitle].push(item.subActivityTitle);
    });
    
    // Create the formatted string with each activity on a new line
    var activityStrings = [];
    for (var activity in groupedActivities) {
        // Start with bold activity title
        var activityString = "**" + activity + "** - \n";
        
        // Add sub-activities as bulleted list
        groupedActivities[activity].forEach(function(subActivity) {
            activityString += "**• **" + subActivity + ".\n";
        });
        
        activityStrings.push(activityString.trim());
    }
    
    formattedText = activityStrings.join('\n');
}
    
    // Set the formatted text in the input field
    $('#language').val(formattedText);
    
    // Store the raw data in a hidden input for form submission
    if (!$('#languageData').length) {
        $('<input>').attr({
            type: 'hidden',
            id: 'languageData',
            name: 'languageData'
        }).appendTo('form');
    }
    $('#languageData').val(JSON.stringify(selectedSubActivities));
    
    // Close the modal
    $('#languageModal').modal('hide');
});

// Make sure the language modal is properly initialized
$('#languageModal').on('shown.bs.modal', function () {
    // Auto-expand the top level of the tree
    setTimeout(function() {
        $('#languageFramework').collapse('show');
    }, 200);
});



// Culture Modal JavaScript
$('#saveCultureSelections').on('click', function() {
    var selectedSubActivities = [];
    
    $('.culture-checkbox:checked').each(function() {
        var subActivityTitle = $(this).val();
        var activityId = $(this).data('activity-id');
        var activityTitle = $(this).data('activity-title');
        var subActivityTitle = $(this).data('sub-activity-title');
        
        selectedSubActivities.push({
            subActivityTitle: subActivityTitle,
            activityId: activityId,
            activityTitle: activityTitle
        });
    });
    
   // Format the selected activities for display in the input field
var formattedText = '';
if (selectedSubActivities.length > 0) {
    // Group by activity
    var groupedActivities = {};
    
    selectedSubActivities.forEach(function(item) {
        if (!groupedActivities[item.activityTitle]) {
            groupedActivities[item.activityTitle] = [];
        }
        groupedActivities[item.activityTitle].push(item.subActivityTitle);
    });
    
    // Create the formatted string with each activity on a new line
    var activityStrings = [];
    for (var activity in groupedActivities) {
        // Start with bold activity title
        var activityString = "**" + activity + "** - \n";
        
        // Add sub-activities as bulleted list
        groupedActivities[activity].forEach(function(subActivity) {
            activityString += "**• **" + subActivity + ".\n";
        });
        
        activityStrings.push(activityString.trim());
    }
    
    formattedText = activityStrings.join('\n');
}
    
    // Set the formatted text in the input field
    $('#culture').val(formattedText);
    
    // Store the raw data in a hidden input for form submission
    if (!$('#cultureData').length) {
        $('<input>').attr({
            type: 'hidden',
            id: 'cultureData',
            name: 'cultureData'
        }).appendTo('form');
    }
    $('#cultureData').val(JSON.stringify(selectedSubActivities));
    
    // Close the modal
    $('#cultureModal').modal('hide');
});

// Make sure the culture modal is properly initialized
$('#cultureModal').on('shown.bs.modal', function () {
    // Auto-expand the top level of the tree
    setTimeout(function() {
        $('#cultureFramework').collapse('show');
    }, 200);
});



});
</script>

<script>
    $(document).ready(function () {
    // Detect if running in RDP environment
    const isRDP = navigator.userAgent.includes('Windows') && 
                  (window.screen.colorDepth <= 16 || 
                   window.devicePixelRatio < 1 ||
                   navigator.connection && navigator.connection.type === 'other');

    // Disable animations in RDP environment
    if (isRDP) {
        $.fn.modal.Constructor.Default.animation = false;
        $('.modal').removeClass('fade');
    }

    // Toggle icons with improved RDP handling
    $(document).on('show.bs.collapse', function (e) {
        let $target = $(e.target);
        let $icon = $target.prev().find('.fa');
        
        $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
        
        // Enhanced height calculation for RDP
        if (isRDP) {
            // Disable CSS transitions temporarily
            $target.css('transition', 'none');
            
            // Set height immediately for RDP
            setTimeout(function () {
                let scrollHeight = $target.get(0).scrollHeight;
                $target.css({
                    'height': scrollHeight + 'px',
                    'transition': 'none'
                });
                
                // Force reflow
                $target.get(0).offsetHeight;
                
                // Re-enable transitions after a delay
                setTimeout(function() {
                    $target.css('transition', '');
                }, 50);
            }, 0);
        } else {
            // Normal behavior for non-RDP systems
            setTimeout(function () {
                $target.css('height', $target.get(0).scrollHeight + 'px');
            }, 10);
        }
    });

    $(document).on('hide.bs.collapse', function (e) {
        let $target = $(e.target);
        let $icon = $target.prev().find('.fa');
        
        $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
        
        if (isRDP) {
            // Immediate collapse for RDP
            $target.css({
                'height': '0px',
                'transition': 'none'
            });
            
            setTimeout(function() {
                $target.css('transition', '');
            }, 50);
        }
    });

    // Enhanced modal height recalculation
    $(document).on('shown.bs.collapse hidden.bs.collapse', function (e) {
        if (isRDP) {
            // Force modal to recalculate its position and size
            let $modal = $('#eylfModal');
            if ($modal.hasClass('show')) {
                $modal.modal('handleUpdate');
                
                // Additional positioning fix for RDP
                setTimeout(function() {
                    $modal.css({
                        'display': 'block',
                        'opacity': '1'
                    });
                }, 10);
            }
        } else {
            $('#eylfModal').modal('handleUpdate');
        }
    });

    // Additional RDP fixes
    if (isRDP) {
        // Override Bootstrap's collapse behavior
        $(document).on('click', '[data-toggle="collapse"]', function(e) {
            e.preventDefault();
            let target = $(this).attr('data-target');
            let $target = $(target);
            
            if ($target.hasClass('show')) {
                $target.removeClass('show').css('height', '0px');
            } else {
                $target.addClass('show').css('height', $target.get(0).scrollHeight + 'px');
            }
        });
    }

    // Modal show event handling for RDP
    $('#eylfModal').on('show.bs.modal', function () {
        if (isRDP) {
            $(this).css({
                'animation': 'none',
                'transition': 'none'
            });
        }
    });

    // Force visibility for RDP systems
    $('#eylfModal').on('shown.bs.modal', function () {
        if (isRDP) {
            $(this).css({
                'display': 'block !important',
                'opacity': '1 !important'
            });
        }
    });
});

</script>

@include('layout.footer')
@stop