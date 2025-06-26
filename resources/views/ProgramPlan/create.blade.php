@extends('layout.master')
@section('title', 'Create Program Plan')
@section('parentPageTitle', 'Dashboard')

@section('content')
<div class="text-zero top-right-button-container d-flex justify-content-end" style="margin-right: 20px;margin-top: -60px;">
    <h5></h5>
    <div style="margin:5px;">
    <button class="btn btn-outline-info" id="addActivityBtn">Add Activity</button>&nbsp;
        <button class="btn btn-outline-info" id="addSubActivityBtn">Add Sub-Activity</button>
</div>
    </div>
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
        <input type="hidden" name="plan_id" value="<?= $plan_data->id ?>">
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
                
    <div class="form-group mb-4">
        <label for="room">Select Room</label>
        <select class="form-control" id="room" name="room" required>
            <option value="">Select Room</option>
             @foreach($rooms as $room)
                <option value="<?= $room->id ?>" <?= (isset($plan_data) && $plan_data->room_id == $room->id) ? 'selected' : '' ?>><?= $room->name ?></option>
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
                            <input type="text" class="form-control" name="focus_area" placeholder="Focus Area" value="<?= isset($plan_data) ? $plan_data->focus_area : '' ?>">
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
                            <input type="text" class="form-control" name="art_craft" value="<?= isset($plan_data) ? $plan_data->art_craft : '' ?>" placeholder="Art & Craft">

                            <input type="hidden" class="form-control mt-2" name="art_craft_experiences" value="<?= isset($plan_data) ? $plan_data->art_craft_experiences : '' ?>" placeholder="Planned experiences">
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
                            <label for="outdoor_experiences">Outdoor Experiences <span style="color:blueviolet;font-weight:bold;"> (Add Experiences seprated by Comma ",") </span></label>
                            <textarea class="form-control" id="outdoor_experiences" name="outdoor_experiences" rows="3" placeholder="1st Experiences, 2nd Experiences, 3rd Experiences etc..."><?= isset($plan_data) ? $plan_data->outdoor_experiences : '' ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="inquiry_topic">Inquiry Topic</label>
                            <textarea class="form-control" id="inquiry_topic" name="inquiry_topic"  rows="3"><?= isset($plan_data) ? $plan_data->inquiry_topic : '' ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="sustainability_topic">Sustainability Topic</label>
                            <textarea class="form-control" id="sustainability_topic" name="sustainability_topic" rows="3"><?= isset($plan_data) ? $plan_data->sustainability_topic : '' ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="special_events">Special Events <span style="color:blueviolet;font-weight:bold;"> (Add multiple events seprated by Comma ",") </span> </label>
                            <textarea class="form-control" id="special_events" name="special_events" rows="3" placeholder="14th March- Holi, 18th March- Global Recycling Day, 21st March- Harmony Day etc..."><?= isset($plan_data) ? $plan_data->special_events : '' ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="children_voices">Children's Voices</label>
                            <textarea class="form-control" id="children_voices" name="children_voices"  rows="3"><?= isset($plan_data) ? $plan_data->children_voices : '' ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="families_input">Families Input</label>
                            <textarea class="form-control" id="families_input" name="families_input"  rows="3"><?= isset($plan_data) ? $plan_data->families_input : '' ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="group_experience">Group Experience</label>
                            <textarea class="form-control" id="group_experience" name="group_experience" rows="3"><?= isset($plan_data) ? $plan_data->group_experience : '' ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="spontaneous_experience">Spontaneous Experience</label>
                            <textarea class="form-control" id="spontaneous_experience" name="spontaneous_experience" rows="3"><?= isset($plan_data) ? $plan_data->spontaneous_experience : '' ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="mindfulness_experiences">Mindfulness Experiences</label>
                            <textarea class="form-control" id="mindfulness_experiences" name="mindfulness_experiences"  rows="3"><?= isset($plan_data) ? $plan_data->mindfulness_experiences : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <?php if(isset($plan_data) && $plan_data): ?>
                   
                    <div class="form-group">
        <button type="submit" class="btn btn-info" id="updateBtn">Update</button>
       &nbsp;&nbsp;&nbsp; <button type="button" class="btn btn-info" style="background-color:#2eefb7;border-color:#2eefb7;color:black;" id="saveAsNewBtn">Save as New Data</button>
    </div>

                <?php else: ?>
                    <div class="form-group">
                    <button type="submit" class="btn btn-info">Submit</button>
                </div>
                <?php endif; ?>
                
            </form>
        </div>
    </div>
</div>


</main>




<!-- EYLF Modal -->
<div class="modal fade" id="eylfModal" tabindex="-1" role="dialog" aria-labelledby="eylfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eylfModalLabel">Select EYLF</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height:500px;overflow-y:auto;">
                <div class="eylf-tree">
                    <ul class="list-group">
                        <!-- Main EYLF Framework -->
                        <li class="list-group-item eylf-framework">
                            <div class="d-flex align-items-center">
                                <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#eylfFramework">
                                    <i class="fa fa-chevron-right"></i>
                                </span>
                                <span>Early Years Learning Framework (EYLF) - Australia (V2.0 2022)</span>
                            </div>
                            
                            <!-- EYLF Framework content -->
                            <div id="eylfFramework" class="collapse mt-2">
                                <ul class="list-group">
                                    <!-- EYLF Learning Outcomes -->
                                    <li class="list-group-item eylf-outcomes-container">
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#eylfOutcomes">
                                                <i class="fa fa-chevron-right"></i>
                                            </span>
                                            <span>EYLF Learning Outcomes</span>
                                        </div>
                                        
                                        <!-- List of all outcomes -->
                                        <div id="eylfOutcomes" class="collapse mt-2">
                                            <ul class="list-group">
                                                <?php foreach ($eylf_outcomes as $outcome) : ?>
                                                <li class="list-group-item eylf-outcome">
                                                    <div class="d-flex align-items-center">
                                                        <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#outcome<?= $outcome->id ?>">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                        <span><?= $outcome->title ?> - <?= $outcome->name ?></span>
                                                    </div>
                                                    
                                                    <!-- Activities for this outcome -->
                                                    <div id="outcome<?= $outcome->id ?>" class="collapse mt-2">
                                                        <ul class="list-group">
                                                            <?php foreach ($outcome->activities as $activity) : ?>
                                                            <li class="list-group-item eylf-activity">
                                                                <div class="form-check">
                                                                    <input class="form-check-input eylf-activity-checkbox"
                                                                           type="checkbox"
                                                                           value="<?= $activity->id ?>"
                                                                           id="activity<?= $activity->id ?>"
                                                                           data-outcome-id="<?= $outcome->id ?>"
                                                                           data-outcome-title="<?= $outcome->title ?>"
                                                                           data-outcome-name="<?= $outcome->name ?>"
                                                                           data-activity-title="<?= $activity->title ?>">
                                                                    <label class="form-check-label" for="activity<?= $activity->id ?>">
                                                                        <?= $activity->title ?>
                                                                    </label>
                                                                </div>
                                                            </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </li>
                                    
                                    <!-- You can add EYLF Practices and EYLF Principles here if needed -->
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEylfSelections">Save selections</button>
            </div>
        </div>
    </div>
</div>



  <!-- Modal Structure -->
  <div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="activityModalLabel">Add New Activity</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="activityForm">
          <div class="mb-3">
            <label for="subjectSelect" class="form-label">Montessori Subject</label>
            <select class="form-control" id="subjectSelect" name="idSubject" required>
              <option value="" selected disabled>Select a subject</option>
              <!-- Options will be loaded via AJAX -->
            </select>
          </div>
          <div class="mb-3">
            <label for="activityTitle" class="form-label">Activity Title</label>
            <input type="text" class="form-control" id="activityTitle" name="title" required>
            <!-- Success message will appear here -->
            <div class="alert alert-success mt-2" id="successMessage" style="display: none;">
              Activity added successfully!
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="saveActivityBtn">Save Activity</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal Structure -->
<div class="modal fade" id="subActivityModal" tabindex="-1" aria-labelledby="subActivityModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subActivityModalLabel">Add New Sub-Activity</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="subActivityForm">
          <div class="mb-3">
            <label for="subjectSelectForSub" class="form-label">Montessori Subject</label>
            <select class="form-control" id="subjectSelectForSub" name="idSubject" required>
              <option value="" selected disabled>Select a subject</option>
              <!-- Options will be loaded via AJAX -->
            </select>
          </div>
          <div class="mb-3">
            <label for="activitySelect" class="form-label">Activity</label>
            <select class="form-control" id="activitySelect" name="idActivity" required disabled>
              <option value="" selected disabled>Select a subject first</option>
              <!-- Options will be loaded via AJAX based on subject selection -->
            </select>
          </div>
          <div class="mb-3">
            <label for="subActivityTitle" class="form-label">Sub-Activity Title</label>
            <input type="text" class="form-control" id="subActivityTitle" name="title" required>
            <!-- Success message will appear here -->
            <div class="alert alert-success mt-2" id="subActivitySuccessMessage" style="display: none;">
              Sub-Activity added successfully!
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="saveSubActivityBtn">Save Sub-Activity</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>





<!-- Practical Life Modal -->
<div class="modal fade" id="practicalLifeModal" tabindex="-1" role="dialog" aria-labelledby="practicalLifeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="practicalLifeModalLabel">Select Practical Life Activities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height:500px;overflow-y:auto;">
                <div class="practical-life-tree">
                    <ul class="list-group">
                        <!-- Main Practical Life Framework -->
                        <li class="list-group-item practical-life-framework">
                            <div class="d-flex align-items-center">
                                <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#practicalLifeFramework">
                                    <i class="fa fa-chevron-right"></i>
                                </span>
                                <span>Practical Life (Montessori)</span>
                            </div>
                            
                            <!-- Practical Life Framework content -->
                            <div id="practicalLifeFramework" class="collapse mt-2">
                                <ul class="list-group">
                                    <?php foreach ($montessori_subjects as $subject) : ?>
                                        <?php if ($subject->name === 'Practical Life') : ?>
                                          
                                            <?php foreach ($subject->activities as $activity) : ?>
                                            <li class="list-group-item practical-life-activity">
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#activity<?= $activity->idActivity ?>">
                                                        <i class="fa fa-chevron-right"></i>
                                                    </span>
                                                    <span><?= $activity->title ?></span>
                                                </div>
                                                
                                                <!-- Sub-activities for this activity -->
                                                <div id="activity<?= $activity->idActivity ?>" class="collapse mt-2">
                                                    <ul class="list-group">
                                                      
                                                        <?php 
                                                         $subActivityCounter = 0;
                                                          
                                                        foreach ($activity->subActivities
 as $sub_activity) : 
                                                        ?>
                                                           
                                                        <li class="list-group-item practical-life-sub-activity">
                                                            <div class="form-check">
                                                                <input class="form-check-input practical-life-checkbox"
                                                                       type="checkbox"
                                                                       value="<?= $sub_activity->title ?>"
                                                                       id="subActivity<?= $activity->idActivity ?>_<?= $subActivityCounter ?>"
                                                                       data-activity-id="<?= $activity->idActivity ?>"
                                                                       data-activity-title="<?= $activity->title ?>"
                                                                       data-sub-activity-title="<?= $sub_activity->title ?>">
                                                                <label class="form-check-label" for="subActivity<?= $activity->idActivity ?>_<?= $subActivityCounter ?>">
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
                <button type="button" class="btn btn-primary" id="savePracticalLifeSelections">Save selections</button>
            </div>
        </div>
    </div>
</div>


<!-- Sensorial Modal -->
<div class="modal fade" id="sensorialModal" tabindex="-1" role="dialog" aria-labelledby="sensorialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sensorialModalLabel">Select Sensorial Activities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height:500px;overflow-y:auto;">
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
                            
                            <!-- Sensorial Framework content -->
                            <div id="sensorialFramework" class="collapse mt-2">
                                <ul class="list-group">
                                    <?php foreach ($montessori_subjects as $subject) : ?>
                                        <?php if ($subject->name === 'Sensorial') : ?>
                                            <?php foreach ($subject->activities as $activity) : ?>
                                            <li class="list-group-item sensorial-activity">
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#sensorialActivity<?= $activity->idActivity ?>">
                                                        <i class="fa fa-chevron-right"></i>
                                                    </span>
                                                    <span><?= $activity->title ?></span>
                                                </div>
                                                
                                                <!-- Sub-activities for this activity -->
                                                <div id="sensorialActivity<?= $activity->idActivity ?>" class="collapse mt-2">
                                                    <ul class="list-group">
                                                        <?php 
                                                         $subActivityCounter = 0;
                                                        foreach ($activity->subActivities
 as $sub_activity) : 
                                                        ?>
                                                           
                                                        <li class="list-group-item sensorial-sub-activity">
                                                            <div class="form-check">
                                                                <input class="form-check-input sensorial-checkbox"
                                                                       type="checkbox"
                                                                       value="<?= $sub_activity->title ?>"
                                                                       id="sensorialSubActivity<?= $activity->idActivity ?>_<?= $subActivityCounter ?>"
                                                                       data-activity-id="<?= $activity->idActivity ?>"
                                                                       data-activity-title="<?= $activity->title ?>"
                                                                       data-sub-activity-title="<?= $sub_activity->title ?>">
                                                                <label class="form-check-label" for="sensorialSubActivity<?= $activity->idActivity ?>_<?= $subActivityCounter ?>">
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
                <button type="button" class="btn btn-primary" id="saveSensorialSelections">Save selections</button>
            </div>
        </div>
    </div>
</div>


<!-- Math Modal -->
<div class="modal fade" id="mathModal" tabindex="-1" role="dialog" aria-labelledby="mathModalLabel" aria-hidden="true">
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
<div class="modal fade" id="languageModal" tabindex="-1" role="dialog" aria-labelledby="languageModalLabel" aria-hidden="true">
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
<div class="modal fade" id="cultureModal" tabindex="-1" role="dialog" aria-labelledby="cultureModalLabel" aria-hidden="true">
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




@endsection


@push('scripts')
    <!-- all the script here of this page only -->
<script>
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
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '{{ route("LessonPlanList.get_room_users") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            room_id: roomId,
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
          const csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{route ("LessonPlanList.get_room_children") }}',
            method: 'POST',
                headers: {
            'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
            data: { room_id: roomId, center_id: centerId },
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
        console.log("Redirecting to:", response.redirect_url); // ✅ this works
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


<!-- JavaScript for Modal and AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const addActivityBtn = document.getElementById('addActivityBtn');
  const activityForm = document.getElementById('activityForm');
  const subjectSelect = document.getElementById('subjectSelect');
  const activityModal = new bootstrap.Modal(document.getElementById('activityModal'));
  const successMessage = document.getElementById('successMessage');
  
  // When the Add Activity button is clicked
  addActivityBtn.addEventListener('click', function() {
    // Fetch subjects via AJAX before opening the modal
    fetchSubjects();
  });
  
  // Function to fetch subjects from the database
  function fetchSubjects() {
    // Show loading state
    subjectSelect.innerHTML = '<option value="" selected disabled>Loading subjects...</option>';
    
    // AJAX call to get subjects
    $.ajax({
      url: "{{url('Observation/getSubjects') }}",
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        // Clear loading state
        subjectSelect.innerHTML = '<option value="" selected disabled>Select a subject</option>';
        
        // Add the fetched subjects to the select element
        data.forEach(function(subject) {
          const option = document.createElement('option');
          option.value = subject.idSubject;
          option.textContent = subject.name;
          subjectSelect.appendChild(option);
        });
        
        // Open the modal after data is loaded
        activityModal.show();
      },
      error: function(xhr, status, error) {
        console.error('Error fetching subjects:', error);
        alert('Failed to load subjects. Please try again.');
      }
    });
  }
  
  // Form submission handler
  activityForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const idSubject = subjectSelect.value;
    const title = document.getElementById('activityTitle').value;
     const csrfToken = $('meta[name="csrf-token"]').attr('content');
    // AJAX call to save the activity
    $.ajax({
      url: "{{ route('Observation.addActivity') }}",
      type: 'POST',
      data: {
        idSubject: idSubject,
        title: title
      },
                headers: {
            'X-CSRF-TOKEN': csrfToken
        },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          // Clear the title field
          document.getElementById('activityTitle').value = '';
          
          // Show success message below the input
          successMessage.style.display = 'block';
          
          // Hide success message after 3 seconds
          setTimeout(function() {
            successMessage.style.display = 'none';
          }, 3000);
        } else {
          alert('Failed to add activity: ' + response.message);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error adding activity:', error);
        alert('Failed to add activity. Please try again.');
      }
    });
  });
  
  // Ensure page refresh when modal is closed
  document.getElementById('activityModal').addEventListener('hidden.bs.modal', function() {
    location.reload();
  });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
  const addSubActivityBtn = document.getElementById('addSubActivityBtn');
  const subActivityForm = document.getElementById('subActivityForm');
  const subjectSelectForSub = document.getElementById('subjectSelectForSub');
  const activitySelect = document.getElementById('activitySelect');
  const subActivityModal = new bootstrap.Modal(document.getElementById('subActivityModal'));
  const subActivitySuccessMessage = document.getElementById('subActivitySuccessMessage');
  
  // When the Add Sub-Activity button is clicked
  addSubActivityBtn.addEventListener('click', function() {
    // Fetch subjects via AJAX before opening the modal
    fetchSubjectsForSubActivity();
  });
  
  // Function to fetch subjects from the database
  function fetchSubjectsForSubActivity() {
    // Show loading state
    subjectSelectForSub.innerHTML = '<option value="" selected disabled>Loading subjects...</option>';
     const csrfToken = $('meta[name="csrf-token"]').attr('content');
    // AJAX call to get subjects
    $.ajax({
      url: "{{ route('Observation.getSubjects') }}",
      type: 'GET',
      dataType: 'json',
               headers: {
            'X-CSRF-TOKEN': csrfToken
        },
      success: function(data) {
        // Clear loading state
        subjectSelectForSub.innerHTML = '<option value="" selected disabled>Select a subject</option>';
        
        // Add the fetched subjects to the select element
        data.forEach(function(subject) {
          const option = document.createElement('option');
          option.value = subject.idSubject;
          option.textContent = subject.name;
          subjectSelectForSub.appendChild(option);
        });
        
        // Open the modal after data is loaded
        subActivityModal.show();
      },
      error: function(xhr, status, error) {
        console.error('Error fetching subjects:', error);
        alert('Failed to load subjects. Please try again.');
      }
    });
  }
  
  // Function to fetch activities based on selected subject
  function fetchActivitiesBySubject(subjectId) {
    // Disable activity select and show loading
    activitySelect.disabled = true;
    activitySelect.innerHTML = '<option value="" selected disabled>Loading activities...</option>';
     const csrfToken = $('meta[name="csrf-token"]').attr('content');
    // AJAX call to get activities for the selected subject
    $.ajax({
      url: "{{ route('Observation.getActivitiesBySubject') }}",
      type: 'GET',
               headers: {
            'X-CSRF-TOKEN': csrfToken
        },
      data: { idSubject: subjectId },
      dataType: 'json',
      success: function(data) {
        // Clear loading state
        activitySelect.innerHTML = '<option value="" selected disabled>Select an activity</option>';
        
        if (data.length === 0) {
          activitySelect.innerHTML = '<option value="" selected disabled>No activities found for this subject</option>';
        } else {
          // Add the fetched activities to the select element
          data.forEach(function(activity) {
            const option = document.createElement('option');
            option.value = activity.idActivity;
            option.textContent = activity.title;
            activitySelect.appendChild(option);
          });
          
          // Enable the activity select
          activitySelect.disabled = false;
        }
      },
      error: function(xhr, status, error) {
        console.error('Error fetching activities:', error);
        activitySelect.innerHTML = '<option value="" selected disabled>Error loading activities</option>';
      }
    });
  }
  
  // When subject is selected, fetch related activities
  subjectSelectForSub.addEventListener('change', function() {
    const selectedSubjectId = this.value;
    if (selectedSubjectId) {
      fetchActivitiesBySubject(selectedSubjectId);
    } else {
      // Reset and disable activity select if no subject is selected
      activitySelect.innerHTML = '<option value="" selected disabled>Select a subject first</option>';
      activitySelect.disabled = true;
    }
  });
  
  // Form submission handler
  subActivityForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const idActivity = activitySelect.value;
    const title = document.getElementById('subActivityTitle').value;
    const subjectSelectForSub = document.getElementById('subjectSelectForSub').value;
     const csrfToken = $('meta[name="csrf-token"]').attr('content');
    // AJAX call to save the sub-activity
    $.ajax({
      url: "{{ url('Observation/addSubActivity') }} ",
      type: 'POST',
      data: {
        idActivity: idActivity,
        title: title,
        subjectSelectForSub:subjectSelectForSub
      },
              headers: {
            'X-CSRF-TOKEN': csrfToken
        },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          // Clear the title field
          document.getElementById('subActivityTitle').value = '';
          
          // Show success message below the input
          subActivitySuccessMessage.style.display = 'block';
          
          // Hide success message after 3 seconds
          setTimeout(function() {
            subActivitySuccessMessage.style.display = 'none';
          }, 3000);
        } else {
          alert('Failed to add sub-activity: ' + response.message);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error adding sub-activity:', error);
        alert('Failed to add sub-activity. Please try again.');
      }
    });
  });
  
  // Ensure page refresh when modal is closed
  document.getElementById('subActivityModal').addEventListener('hidden.bs.modal', function() {
    location.reload();
  });
});
</script>
<script>
  $(document).ready(function() {
    $('#activityModal').on('hidden.bs.modal', function () {
      location.reload();
    });
  });
</script>
<script>
  $(document).ready(function() {
    $('#subActivityModal').on('hidden.bs.modal', function () {
      location.reload();
    });
  });
</script>

@endpush

@include('layout.footer')