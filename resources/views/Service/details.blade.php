@extends('layout.master')
@section('title', 'Update Service Details')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
   <style>
        /* ===================== THEME SUPPORT (GLOBAL) ===================== */
        body[class*='theme-'] .top-right-button-container .btn-outline-info {
            border-color: var(--sd-accent, #176ba6) !important;
            color: var(--sd-accent, #176ba6) !important;
        }
        body[class*='theme-'] .top-right-button-container .btn-outline-info:hover,
        body[class*='theme-'] .top-right-button-container .btn-outline-info:focus {
            background-color: var(--sd-accent, #176ba6) !important;
            color: #fff !important;
        }
        body[class*='theme-'] .top-right-button-container .dropdown-menu .active,
        body[class*='theme-'] .top-right-button-container .dropdown-menu .active.font-weight-bold.text-info {
            background-color: var(--sd-accent, #176ba6) !important;
            color: #fff !important;
        }
        body[class*='theme-'] .btn-save {
            background: linear-gradient(135deg, var(--sd-accent, #176ba6) 0%, #764ba2 100%) !important;
            border: none !important;
            color: #fff !important;
        }
        body[class*='theme-'] .btn-save:hover,
        body[class*='theme-'] .btn-save:focus {
            background: var(--sd-accent, #176ba6) !important;
            color: #fff !important;
        }
        body[class*='theme-'] .section-header h4 {
            color: var(--sd-accent, #176ba6) !important;
        }
        body[class*='theme-'] .section-header h4 i {
            color: var(--sd-accent, #176ba6) !important;
        }
        body[class*='theme-'] .section-header::after {
            background: linear-gradient(90deg, var(--sd-accent, #176ba6), var(--sd-accent, #176ba6));
        }
        body[class*='theme-'] .form-label {
            color: var(--sd-accent, #176ba6) !important;
        }
        body[class*='theme-'] .philosophy-section {
            background: linear-gradient(135deg, var(--sd-accent, #176ba6) 0%, #00f2fe 100%) !important;
            color: #fff !important;
        }
        body[class*='theme-'] .philosophy-section .form-label {
            color: #fff !important;
        }
        /* =================== END THEME SUPPORT =================== */
        body {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            /* background: rgba(255, 255, 255, 0.95); */
            backdrop-filter: blur(10px);
            border-radius: 20px;
            /* box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); */
            margin: 20px 0;
            overflow: hidden;
            padding-bottom: 40px;
        }
        
        .form-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .form-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.3;
        }
        
        .form-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 300;
            position: relative;
            z-index: 1;
        }
        
        .form-header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }
        
        /* .form-body {
            /* padding: 40px; */
        /* } */ */
        
        .section-header {
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }
        
        .section-header h4 {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.4rem;
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .section-header h4 i {
            margin-right: 10px;
            color: #4facfe;
        }
        
        .section-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #4facfe, #00f2fe);
            border-radius: 2px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
            background: white;
            transform: translateY(-1px);
        }
        
        .form-control:hover {
            border-color: #4facfe;
            background: white;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        .section-divider {
            margin: 50px 0;
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, #ddd, transparent);
        }
        
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            color: white;
        }
        
        .btn-save:focus {
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.5);
            color: white;
        }
        
        .text-danger {
            font-size: 0.875rem;
            margin-top: 5px;
            display: block;
        }
        
        .card-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f3f4;
        }
        
        .philosophy-section {
            /* background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); */
            background: linear-gradient(135deg,rgb(198, 138, 208) 0%,rgb(121, 194, 240) 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .philosophy-section .form-label {
            color: white;
            font-weight: 600;
        }
        
        .philosophy-section .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .philosophy-section .form-control:focus {
            background: white;
            border-color: rgba(255, 255, 255, 0.8);
        }
        
                     .top-right-button-container{
    /* margin-top:50px; */
    margin-right: 20px;
    margin-top: -48px;
    display: flex;
    justify-content: end;
    /* d-lg-flex justify-content-end */
  }
        @media (max-width: 768px) {
            /* .form-body {
                padding: 20px;
            } */
             /* body{
              padding-inline:0;
             } */
                .top-right-button-container{
    /* margin-top:50px; */
    margin-right: 20px;
    margin-top: 10px;
    display: flex;
    justify-content: end;
    width: 100%;
    /* d-lg-flex justify-content-end */
  }
                .main-container {
            /* background: rgba(255, 255, 255, 0.95); */
            backdrop-filter: blur(10px);
            border-radius: 20px;
            /* box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); */
            margin: 20px 0;
            overflow: hidden;
            padding-inline: 0;
            padding-bottom: 40px;
        }
            
            .form-header h1 {
                font-size: 2rem;
            }
            
            .section-header h4 {
                font-size: 1.2rem;
            }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
<style>

        .main{
padding-block:5em;
/* padding-inline:2em; */
    }
    @media screen and (max-width: 600px) {
    .main{
padding-block:2em;
padding-inline:0;
    }
}
</style>
@endsection


@section('content')

<!-- new header content -->
<div class="text-zero top-right-button-container  ">
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
 <hr class="mt-lg-3 mt-sm-3 ">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-12">
                <div class="main-container fade-in">
                    <!-- <div class="form-header">
                        <h1><i class="fas fa-building"></i> Service Details</h1>
                        <p class="subtitle">Complete your service information below</p>
                    </div> -->
                    
                    <div class="form-body">
                        <form method="post" action="#" id="serviceDetailsForm">
                          @csrf

                            <!-- <input type="hidden" name="_token" value="_csrf_token"> -->
                            <input type="hidden" name="centerid" value="">

                            <!-- Service Information -->
                            <div class="card-section card">
                                <div class="section-header">
                                    <h4><i class="fas fa-info-circle"></i> Service Information</h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="serviceName" class="form-label">Service Name</label>
                                            <textarea class="form-control" id="serviceName" name="serviceName" rows="3" placeholder="Enter your service name...">{{ old('serviceName', $serviceDetails->serviceName ?? '') }}</textarea>
                                            <span class="text-danger">{{ $errors->first('serviceName') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="approvalNumber" class="form-label">Service Approval Number</label>
                                            <textarea class="form-control" id="approvalNumber" name="serviceApprovalNumber" rows="3" placeholder="Enter approval number...">{{ old('serviceApprovalNumber', $serviceDetails->serviceApprovalNumber ?? '') }}</textarea>
                                            <span class="text-danger">{{ $errors->first('serviceApprovalNumber') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Physical Location -->
                            <div class="card-section card">
                                <div class="section-header">
                                    <h4><i class="fas fa-map-marker-alt"></i> Physical Location of Service</h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="street" class="form-label">Street Address</label>
                                            <input type="text" class="form-control" id="street" name="serviceStreet" value="{{old('serviceStreet', $serviceDetails->serviceStreet ?? '')}}" placeholder="Enter street address">
                                            <span class="text-danger">{{ $errors->first('serviceStreet') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="suburb" class="form-label">Suburb</label>
                                            <input type="text" class="form-control" id="suburb" name="serviceSuburb" value="{{old('serviceSuburb', $serviceDetails->serviceSuburb ?? '')}}" placeholder="Enter suburb">
                                            <span class="text-danger">{{ $errors->first('serviceSuburb') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="state" class="form-label">State/Territory</label>
                                            <input type="text" class="form-control" id="state" name="serviceState" value="{{old('serviceState', $serviceDetails->serviceState ?? '')}}" placeholder="Enter state/territory">
                                            <span class="text-danger">{{ $errors->first('serviceState') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="postcode" class="form-label">Postcode</label>
                                            <input type="text" class="form-control" id="postcode" name="servicePostcode" value="{{old('servicePostcode', $serviceDetails->servicePostcode ?? '')}}" placeholder="Enter postcode">
                                            <span class="text-danger">{{ $errors->first('servicePostcode') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Details -->
                            <div class="card-section card">
                                <div class="section-header">
                                    <h4><i class="fas fa-phone"></i> Physical Location Contact Details</h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="telephone" class="form-label">Telephone</label>
                                            <input type="text" class="form-control" id="telephone" name="contactTelephone" value="{{old('contactTelephone', $serviceDetails->contactTelephone ?? '')}}" placeholder="Enter telephone number">
                                            <span class="text-danger">{{ $errors->first('contactTelephone') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">Mobile Phone</label>
                                            <input type="text" class="form-control" id="phone" name="contactMobile" value="{{old('contactMobile', $serviceDetails->contactMobile ?? '')}}" placeholder="Enter mobile number">
                                            <span class="text-danger">{{ $errors->first('contactMobile') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="fax" class="form-label">Fax</label>
                                            <input type="text" class="form-control" id="fax" name="contactFax" value="{{old('contactFax', $serviceDetails->contactFax ?? '')}}" placeholder="Enter fax number">
                                            <span class="text-danger">{{ $errors->first('contactFax') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="contactEmail" value="{{old('contactEmail', $serviceDetails->contactEmail ?? '')}}" placeholder="Enter email address">
                                            <span class="text-danger">{{ $errors->first('contactEmail') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Approved Provider -->
                            <div class="card-section card">
                                <div class="section-header">
                                    <h4><i class="fas fa-user-check"></i> Approved Provider</h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="primaryContact" class="form-label">Primary Contact</label>
                                            <input type="text" class="form-control" id="primaryContact" name="providerContact" value="{{old('providerContact', $serviceDetails->providerContact ?? '')}}" placeholder="Enter primary contact name">
                                            <span class="text-danger">{{ $errors->first('providerContact') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="providerTelephone" class="form-label">Telephone</label>
                                            <input type="text" class="form-control" id="providerTelephone" name="providerTelephone" value="{{old('providerTelephone', $serviceDetails->providerTelephone ?? '')}}" placeholder="Enter telephone number">
                                            <span class="text-danger">{{ $errors->first('providerTelephone') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="mobile" class="form-label">Mobile</label>
                                            <input type="text" class="form-control" id="mobile" name="providerMobile" value="{{old('providerMobile', $serviceDetails->providerMobile ?? '')}}" placeholder="Enter mobile number">
                                            <span class="text-danger">{{ $errors->first('providerMobile') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="providerFax" class="form-label">Fax</label>
                                            <input type="text" class="form-control" id="providerFax" name="providerFax" value="{{old('providerFax', $serviceDetails->providerFax ?? '')}}" placeholder="Enter fax number">
                                            <span class="text-danger">{{ $errors->first('providerFax') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="providerEmail" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="providerEmail" name="providerEmail" value="{{old('providerEmail', $serviceDetails->providerEmail ?? '')}}" placeholder="Enter email address">
                                            <span class="text-danger">{{ $errors->first('providerEmail') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nominated Supervisor -->
                            <div class="card-section card">
                                <div class="section-header">
                                    <h4><i class="fas fa-user-tie"></i> Nominated Supervisor</h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="supervisorName" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="supervisorName" name="supervisorName" value="{{old('supervisorName', $serviceDetails->supervisorName ?? '')}}" placeholder="Enter supervisor name">
                                            <span class="text-danger">{{ $errors->first('supervisorName') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="supervisorTelephone" class="form-label">Telephone</label>
                                            <input type="text" class="form-control" id="supervisorTelephone" name="supervisorTelephone" value="{{old('supervisorTelephone', $serviceDetails->supervisorTelephone ?? '')}}" placeholder="Enter telephone number">
                                            <span class="text-danger">{{ $errors->first('supervisorTelephone') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="supervisorMobile" class="form-label">Mobile</label>
                                            <input type="text" class="form-control" id="supervisorMobile" name="supervisorMobile" value="{{old('supervisorMobile', $serviceDetails->supervisorMobile ?? '')}}" placeholder="Enter mobile number">
                                            <span class="text-danger">{{ $errors->first('supervisorMobile') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="supervisorFax" class="form-label">Fax</label>
                                            <input type="text" class="form-control" id="supervisorFax" name="supervisorFax" value="{{old('supervisorFax', $serviceDetails->supervisorFax ?? '')}}" placeholder="Enter fax number">
                                            <span class="text-danger">{{ $errors->first('supervisorFax') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="supervisorEmail" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="supervisorEmail" name="supervisorEmail" value="{{old('supervisorEmail', $serviceDetails->supervisorEmail ?? '')}}" placeholder="Enter email address">
                                            <span class="text-danger">{{ $errors->first('supervisorEmail') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Postal Address -->
                            <div class="card-section card" >
                                <div class="section-header">
                                    <h4><i class="fas fa-envelope"></i> Postal Address (if different from physical)</h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="postalStreet" class="form-label">Street Address</label>
                                            <input type="text" class="form-control" id="postalStreet" name="postalStreet" value="{{old('postalStreet', $serviceDetails->postalStreet ?? '')}}" placeholder="Enter postal street address">
                                            <span class="text-danger">{{ $errors->first('postalStreet') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="postalSuburb" class="form-label">Suburb</label>
                                            <input type="text" class="form-control" id="postalSuburb" name="postalSuburb" value="{{old('postalSuburb', $serviceDetails->postalSuburb ?? '')}}" placeholder="Enter postal suburb">
                                            <span class="text-danger">{{ $errors->first('postalSuburb') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="postalState" class="form-label">State/Territory</label>
                                            <input type="text" class="form-control" id="postalState" name="postalState" value="{{old('postalState', $serviceDetails->postalState ?? '')}}" placeholder="Enter postal state/territory">
                                            <span class="text-danger">{{ $errors->first('postalState') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="postalPostcode" class="form-label">Postcode</label>
                                            <input type="text" class="form-control" id="postalPostcode" name="postalPostcode" value="{{old('postalPostcode', $serviceDetails->postalPostcode ?? '')}}" placeholder="Enter postal postcode">
                                            <span class="text-danger">{{ $errors->first('postalPostcode') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Educational Leader -->
                            <div class="card-section card">
                                <div class="section-header">
                                    <h4><i class="fas fa-graduation-cap"></i> Educational Leader</h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="eduLeaderName" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="eduLeaderName" name="eduLeaderName" value="{{old('eduLeaderName', $serviceDetails->eduLeaderName ?? '')}}" placeholder="Enter educational leader name">
                                            <span class="text-danger">{{ $errors->first('eduLeaderName') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="eduLeaderTelephone" class="form-label">Telephone</label>
                                            <input type="text" class="form-control" id="eduLeaderTelephone" name="eduLeaderTelephone" value="{{old('eduLeaderTelephone', $serviceDetails->eduLeaderTelephone ?? '')}}" placeholder="Enter telephone number">
                                            <span class="text-danger">{{ $errors->first('eduLeaderTelephone') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="eduLeaderEmail" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="eduLeaderEmail" name="eduLeaderEmail" value="{{old('eduLeaderEmail', $serviceDetails->eduLeaderEmail ?? '')}}" placeholder="Enter email address">
                                            <span class="text-danger">{{ $errors->first('eduLeaderEmail') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="card-section card" >
                                <div class="section-header">
                                    <h4><i class="fas fa-info"></i> Additional Information About Your Service</h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="strengthSummary" class="form-label">Summary of strengths for Educational Program and practice</label>
                                            <textarea class="form-control" id="strengthSummary" rows="4" name="strengthSummary" placeholder="Describe your service's educational strengths...">{{old('strengthSummary', $serviceDetails->strengthSummary ?? '')}}</textarea>
                                            <span class="text-danger">{{ $errors->first('strengthSummary') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="childGroupService" class="form-label">How are the children grouped at your service?</label>
                                            <textarea class="form-control" id="childGroupService" rows="4" name="childGroupService" placeholder="Describe how children are grouped...">{{old('childGroupService', $serviceDetails->childGroupService ?? '')}}</textarea>
                                            <span class="text-danger">{{ $errors->first('childGroupService') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="personSubmittingQip" class="form-label">Name and position of person(s) responsible for submitting</label>
                                            <textarea class="form-control" id="personSubmittingQip" rows="4" name="personSubmittingQip" placeholder="Enter name and position...">{{old('personSubmittingQip', $serviceDetails->personSubmittingQip ?? '')}}</textarea>
                                            <span class="text-danger">{{ $errors->first('personSubmittingQip') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="educatorsData" class="form-label">Number of educators registered</label>
                                            <textarea class="form-control" id="educatorsData" rows="4" name="educatorsData" placeholder="Enter educator information...">{{old('educatorsData', $serviceDetails->educatorsData ?? '')}}</textarea>
                                            <span class="text-danger">{{ $errors->first('educatorsData') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Philosophy -->
                            <div class="philosophy-section card">
                                <div class="section-header">
                                    <h4 style="color:#fff !important;"><i class="fas fa-lightbulb" style="color:#fff !important;"></i> Service Statement of Philosophy</h4>
                                </div>
                                <div class="form-group">
                                    <label for="philosophyStatement" class="form-label text-dark" style="color:#fff !important;">Insert your service's statement of philosophy here</label>
                                    <textarea class="form-control" id="philosophyStatement" rows="6" name="philosophyStatement" placeholder="Enter your service's philosophy statement...">{{ old('philosophyStatement', $serviceDetails->philosophyStatement ?? '') ? old('philosophyStatement', $serviceDetails->philosophyStatement ?? '') : '' }}</textarea>
                                    <span class="text-danger">{{ $errors->first('philosophyStatement') }}</span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-save">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@if (session('status'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: "Success!",
                text: "{{ session('status') }}",
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endif
@endsection
@push('scripts')
<!-- Add this in your <head> or before </body> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const alertBox = document.getElementById('statusAlert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.remove('show');
                alertBox.classList.add('fade');
                setTimeout(() => alertBox.remove(), 500); // fully remove after fade
            }, 2000); // 2 seconds
        }
    });
</script>

 <script>
        // Add smooth scrolling and form animations
        $(document).ready(function() {
            // Animate form sections on scroll
            $(window).scroll(function() {
                $('.card-section').each(function() {
                    var elementTop = $(this).offset().top;
                    var elementBottom = elementTop + $(this).outerHeight();
                    var viewportTop = $(window).scrollTop();
                    var viewportBottom = viewportTop + $(window).height();
                    
                    if (elementBottom > viewportTop && elementTop < viewportBottom) {
                        $(this).addClass('fade-in');
                    }
                });
            });
            
            // Form validation feedback
            $('.form-control').on('blur', function() {
                if ($(this).val() !== '') {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                }
            });
            
            // Smooth submit button animation
            $('.btn-save').on('click', function(e) {
                e.preventDefault(); // Prevent default form submission for demo
                
                var $btn = $(this);
                var $form = $('#serviceDetailsForm');
                
                // Basic validation
                var isValid = true;
                $form.find('input[required], textarea[required]').each(function() {
                    if ($(this).val().trim() === '') {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    }
                });
                
                if (!isValid) {
                    alert('Please fill in all required fields.');
                    return;
                }
                
                // Show loading state
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
                $btn.prop('disabled', true);
                
                // Simulate form submission
                setTimeout(function() {
                    // alert('Form submitted successfully! (This is a demo)');
                    $btn.html('<i class="fas fa-check"></i> Saved Successfully!');
                    $btn.removeClass('btn-save').addClass('btn-success');
                    
                    // Reset button after 2 seconds
                    setTimeout(function() {
                        $btn.html('<i class="fas fa-save"></i> Save Service Details');
                        $btn.prop('disabled', false);
                        $btn.removeClass('btn-success').addClass('btn-save');
                    }, 2000);
                }, 2000);
                
                // For actual Laravel implementation, remove the preventDefault() and setTimeout above
                // and uncomment the line below:
                $form.submit();
            });
            
            // Auto-resize textareas
            $('textarea').each(function() {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            }).on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            
            // Add floating labels effect
            $('.form-control').on('focus blur', function(e) {
                var $this = $(this);
                var label = $this.prev('label');
                if (e.type === 'focus' || $this.val().length > 0) {
                    label.addClass('active');
                } else if (e.type === 'blur' && $this.val().length === 0) {
                    label.removeClass('active');
                }
            });
            
            // Initialize labels for pre-filled fields
            $('.form-control').each(function() {
                if ($(this).val().length > 0) {
                    $(this).prev('label').addClass('active');
                }
            });
        });
        </script>
@endpush
@include('layout.footer')

