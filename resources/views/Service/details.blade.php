@extends('layout.master')
@section('title', 'Update Service Details')
@section('parentPageTitle', 'Dashboard')



@section('content')
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<!-- new header content -->
<div class="text-zero top-right-button-container d-flex justify-content-end" style="margin-right: 20px;margin-top: -60px;">
    <div class="dropdown">
        <button class="btn btn-outline-info btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown" style="top:3% !important;left:13px !important;">
            @foreach($centers as $center)
                <a href="javascript:void(0);"
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-info' : '' }}"
                 style="background-color:white;"  data-id="{{ $center->id }}">
                    {{ $center->centerName }}
                </a>
            @endforeach
        </div>
    </div>
</div>
<!-- ends here -->

<div class="row service-details" style="padding-block:5em;padding-inline:2em;">
    <form method="post" action="{{ route('store.serviceDetails') }}">
      @csrf
      <input type="hidden" name="centerid" value="{{ isset($selectedCenterId) ? $selectedCenterId : '' }}">

  <div class="col-12 service-details-header">
    <div class="d-flex justify-content-between align-items-end flex-wrap">
 <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end gap-4">
  <h2 class="mb-0">Service Details</h2>
  <p class="mb-0 text-muted mx-md-4">
    Dashboard <span class="mx-2">|</span> <span>Service Details</span>
  </p>
</div>

<!-- Dropdown showing currently selected center -->
<!-- <div class="btn-group">
    <button type="button" class="btn btn-outline-primary btn-lg dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ $selectedCenter->centerName ?? 'Select Center' }}
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        @foreach($centers as $center)
            <a class="dropdown-item"
               href="{{ route('create.serviceDetails', ['centerid' => $center->id]) }}">
                {{ $center->centerName }}
            </a>
        @endforeach
    </div>
</div> -->



    </div>
    <hr class="mt-3">
  </div>

  <div class="col-12">
    <!-- SECTION: Service Info -->
    <h4>Service Details</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="serviceName" class="form-label">Service Name</label>
      <textarea class="form-control" id="serviceName" name="serviceName" rows="3">
    {{ old('serviceName', $serviceDetails->serviceName ?? '') }}
</textarea>

      @error('serviceName')
        <span class="text-danger">{{ $message }}</span>
      @enderror

      </div>
      <div class="col-lg-6 mb-3">
        <label for="approvalNumber" class="form-label">Service Approval Number</label>
       <textarea class="form-control" id="approvalNumber" name="serviceApprovalNumber" rows="3">
    {{ old('serviceApprovalNumber', $serviceDetails->serviceApprovalNumber ?? '') }}
</textarea>

         @error('serviceApprovalNumber')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
    </div>

    <!-- SECTION: Physical Address -->
    <h4>Primary Contacts at Service</h4>
    <p>Physical Location of Service</p>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="street" class="form-label">Street</label>
        <input type="text" class="form-control" id="street" name="serviceStreet" value="{{old('serviceStreet',  $serviceDetails->serviceStreet ?? '' )}}">
        @error('serviceStreet')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="suburb" class="form-label">Suburb</label>
        <input type="text" class="form-control" id="suburb" name="serviceSuburb" value="{{old('serviceSuburb',  $serviceDetails->serviceSuburb ?? '' )}}">
         @error('serviceSuburb')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="state" class="form-label">State/Territory</label>
        <input type="text" class="form-control" id="state" name="serviceState" value="{{old('serviceState' ,  $serviceDetails->serviceState ?? '' )}}">
         @error('serviceState')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="postcode" class="form-label">Postcode</label>
        <input type="text" class="form-control" id="postcode" name="servicePostcode" value="{{old('servicePostcode' ,  $serviceDetails->servicePostcode ?? '' )}}">
           @error('servicePostcode')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
    </div>

    <!-- SECTION: Contact Details -->
    <h4>Physical Location Contact Details</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="telephone" class="form-label">Telephone</label>
        <input type="text" class="form-control" id="telephone" name="contactTelephone" value="{{old('contactTelephone' ,  $serviceDetails->contactTelephone ?? '' )}}">
           @error('contactTelephone')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="contactMobile" value="{{old('contactMobile' ,  $serviceDetails->contactMobile ?? '' )}}">
           @error('contactMobile')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="fax" class="form-label">Fax</label>
        <input type="text" class="form-control" id="fax" name="contactFax" value="{{old('contactFax' ,  $serviceDetails->contactFax ?? '')}}">
           @error('contactFax')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="contactEmail" value="{{old('contactEmail' ,  $serviceDetails->contactEmail ?? '')}}">
            @error('contactEmail')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
    </div>

    <!-- SECTION: Approved Provider -->
    <h4>Approved Provider</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="primaryContact" class="form-label">Primary Contact</label>
        <input type="text" class="form-control" id="primaryContact" name="providerContact" value="{{old('providerContact' ,  $serviceDetails->providerContact ?? '')}}">
          @error('providerContact')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="providerTelephone" class="form-label">Telephone</label>
        <input type="text" class="form-control" id="providerTelephone" name="providerTelephone" value="{{old('providerTelephone' ,  $serviceDetails->providerTelephone ?? '')}}">
          @error('providerTelephone')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="mobile" class="form-label">Mobile</label>
        <input type="text" class="form-control" id="mobile" name="providerMobile" value="{{old('providerMobile' ,  $serviceDetails->providerMobile ?? '')}}">
         @error('providerTelephone')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="providerFax" class="form-label">Fax</label>
        <input type="text" class="form-control" id="providerFax" name="providerFax" value="{{old('providerFax' ,  $serviceDetails->providerFax ?? '')}}"> 
         @error('providerFax')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-12 mb-3">
        <label for="providerEmail" class="form-label">Email</label>
        <input type="email" class="form-control" id="providerEmail" name="providerEmail" value="{{old('providerEmail' ,  $serviceDetails->providerEmail ?? '')}}">
         @error('providerEmail')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
    </div>

    <!-- SECTION: Nominated Supervisor -->
    <h4>Nominated Supervisor</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="supervisorName" class="form-label">Name</label>
        <input type="text" class="form-control" id="supervisorName" name="supervisorName" value="{{old('supervisorName' ,  $serviceDetails->supervisorName ?? '' )}}">
         @error('supervisorName')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="supervisorTelephone" class="form-label">Telephone</label>
        <input type="text" class="form-control" id="supervisorTelephone" name="supervisorTelephone" value="{{old('supervisorTelephone' ,  $serviceDetails->supervisorTelephone ?? '' )}}">
         @error('supervisorTelephone')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="supervisorMobile" class="form-label">Mobile</label>
        <input type="text" class="form-control" id="supervisorMobile" name="supervisorMobile" value="{{old('supervisorMobile' ,  $serviceDetails->supervisorMobile ?? '')}}">
         @error('supervisorMobile')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="supervisorFax" class="form-label">Fax</label>
        <input type="text" class="form-control" id="supervisorFax" name="supervisorFax" value="{{old('supervisorFax' ,  $serviceDetails->supervisorFax ?? '')}}">
         @error('supervisorFax')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-12 mb-3">
        <label for="supervisorEmail" class="form-label">Email</label>
        <input type="email" class="form-control" id="supervisorEmail" name="supervisorEmail" value="{{old('supervisorEmail' ,  $serviceDetails->supervisorEmail ?? '')}}">
         @error('supervisorEmail')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
    </div>

    <!-- SECTION: Postal Address -->
    <h4>Postal Address (if different from physical)</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="postalStreet" class="form-label">Street</label>
        <input type="text" class="form-control" id="postalStreet" name="postalStreet" value="{{old('postalStreet' ,  $serviceDetails->postalStreet ?? '')}}">
         @error('postalStreet')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="postalSuburb" class="form-label">Suburb</label>
        <input type="text" class="form-control" id="postalSuburb" name="postalSuburb" value="{{old('postalSuburb' ,  $serviceDetails->postalSuburb ?? '')}}">
         @error('postalSuburb')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="postalState" class="form-label">State/Territory</label>
        <input type="text" class="form-control" id="postalState" name="postalState" value="{{old('postalState' ,  $serviceDetails->postalState ?? '')}}">
         @error('postalState')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="postalPostcode" class="form-label">Postcode</label>
        <input type="text" class="form-control" id="postalPostcode" name="postalPostcode" value="{{old('postalPostcode' ,  $serviceDetails->postalPostcode ?? '')}}">
         @error('postalPostcode')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
    </div>

    <!-- SECTION: Educational Leader -->
    <h4>Educational Leader</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="eduLeaderName" class="form-label">Name</label>
        <input type="text" class="form-control" id="eduLeaderName" name="eduLeaderName" value="{{old('eduLeaderName' ,  $serviceDetails->eduLeaderName ?? '')}}" >
         @error('eduLeaderName')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="eduLeaderTelephone" class="form-label">Telephone</label>
        <input type="text" class="form-control" id="eduLeaderTelephone" name="eduLeaderTelephone" value="{{old('eduLeaderTelephone' ,  $serviceDetails->eduLeaderTelephone ?? '')}}">
         @error('eduLeaderTelephone')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-12 mb-3">
        <label for="eduLeaderEmail" class="form-label">Email</label>
        <input type="email" class="form-control" id="eduLeaderEmail" name="eduLeaderEmail" value="{{old('eduLeaderEmail' ,  $serviceDetails->eduLeaderEmail ?? '')}}">
         @error('eduLeaderEmail')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
    </div>

    <!-- SECTION: Additional Info -->
    <h4>Additional Information About Your Service</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="strengthSummary" class="form-label">Summary of strengths for Educational Program and practice</label>
        <textarea class="form-control" id="strengthSummary" rows="3" name="strengthSummary">{{old('strengthSummary' ,  $serviceDetails->strengthSummary ?? '')}}</textarea>
         @error('strengthSummary')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="childGroupService" class="form-label">How are the children grouped at your service?</label>
        <textarea class="form-control" id="childGroupService" rows="3" name="childGroupService">{{old('childGroupService' ,  $serviceDetails->childGroupService ?? '')}}</textarea>
         @error('childGroupService')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="personSubmittingQip" class="form-label">Name and position of person(s) responsible for submitting</label>
        <textarea class="form-control" id="personSubmittingQip" rows="3" name="personSubmittingQip">{{old('personSubmittingQip' ,  $serviceDetails->personSubmittingQip ?? '' )}}</textarea>
         @error('personSubmittingQip')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
      <div class="col-lg-6 mb-3">
        <label for="educatorsData" class="form-label">Number of educators registered</label>
        <textarea class="form-control" id="educatorsData" rows="3" name="educatorsData">{{old('educatorsData' ,  $serviceDetails->educatorsData ?? '' )}}</textarea>
         @error('educatorsData')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
    </div>

    <!-- SECTION: Service Philosophy -->
    <h4>Service Statement of Philosophy</h4>
    <div class="row">
      <div class="col-12 mb-3">
        <label for="philosophyStatement" class="form-label">Insert your service’s statement of philosophy here.</label>
        <textarea class="form-control" id="philosophyStatement" rows="3" name="philosophyStatement">{{old('philosophyStatement' ,  $serviceDetails->philosophyStatement ?? '' )}}</textarea>
         @error('philosophyStatement')
        <span class="text-danger">{{ $message }}</span>
      @enderror
      </div>
    </div>
     <div class="col-1 float-right"> 
    <button type="submit" class="form-control btn-outline-info">save</button>
  </div>
  </div>

 
  </form>
</div>

@include('layout.footer')
@stop
