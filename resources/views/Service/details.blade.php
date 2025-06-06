@extends('layout.master')
@section('title', 'Service Details')
@section('parentPageTitle', 'Dashboard')


@section('content')

<div class="row service-details" style="padding-block:5em;padding-inline:2em;">
    <form method="post">
  <div class="col-12 service-details-header">
    <div class="d-flex justify-content-between align-items-end flex-wrap">
 <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end gap-4">
  <h2 class="mb-0">Service Details</h2>
  <p class="mb-0 text-muted mx-md-4">
    Dashboard <span class="mx-2">|</span> <span>Service Details</span>
  </p>
</div>



   <div class="btn-group mr-1">
                                                            <div class="btn btn-outline-primary btn-lg dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> MELBOURNE CENTER </div>
                                                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(242px, 43px, 0px);">
                                                                    <a class="dropdown-item" href="https://mydiaree.com/ServiceDetails?centerid=1">
                                        MELBOURNE CENTER                                    </a>
                                                                    <a class="dropdown-item" href="https://mydiaree.com/ServiceDetails?centerid=2">
                                        CARRAMAR CENTER                                    </a>
                                                                    <a class="dropdown-item" href="https://mydiaree.com/ServiceDetails?centerid=3">
                                        BRISBANE CENTER                                    </a>
                                                            </div>
                                                    </div>
    </div>
    <hr class="mt-3">
  </div>

  <div class="col-12">
    <!-- SECTION: Service Info -->
    <h4>Service Details</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="serviceName" class="form-label">Service Name</label>
        <textarea class="form-control" id="serviceName" name="service_name" rows="3">service1234</textarea>
      </div>
      <div class="col-lg-6 mb-3">
        <label for="approvalNumber" class="form-label">Service Approval Number</label>
        <textarea class="form-control" id="approvalNumber" name="approval_number" rows="3">1234567</textarea>
      </div>
    </div>

    <!-- SECTION: Physical Address -->
    <h4>Primary Contacts at Service</h4>
    <p>Physical Location of Service</p>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="street" class="form-label">Street</label>
        <input type="text" class="form-control" id="street" name="street">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="suburb" class="form-label">Suburb</label>
        <input type="text" class="form-control" id="suburb" name="suburb">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="state" class="form-label">State/Territory</label>
        <input type="text" class="form-control" id="state" name="state">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="postcode" class="form-label">Postcode</label>
        <input type="text" class="form-control" id="postcode" name="postcode">
      </div>
    </div>

    <!-- SECTION: Contact Details -->
    <h4>Physical Location Contact Details</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="telephone" class="form-label">Telephone</label>
        <input type="text" class="form-control" id="telephone" name="telephone">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="fax" class="form-label">Fax</label>
        <input type="text" class="form-control" id="fax" name="fax">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
      </div>
    </div>

    <!-- SECTION: Approved Provider -->
    <h4>Approved Provider</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="primaryContact" class="form-label">Primary Contact</label>
        <input type="text" class="form-control" id="primaryContact" name="primary_contact">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="providerTelephone" class="form-label">Telephone</label>
        <input type="text" class="form-control" id="providerTelephone" name="provider_telephone">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="mobile" class="form-label">Mobile</label>
        <input type="text" class="form-control" id="mobile" name="mobile">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="providerFax" class="form-label">Fax</label>
        <input type="text" class="form-control" id="providerFax" name="provider_fax">
      </div>
      <div class="col-12 mb-3">
        <label for="providerEmail" class="form-label">Email</label>
        <input type="email" class="form-control" id="providerEmail" name="provider_email">
      </div>
    </div>

    <!-- SECTION: Nominated Supervisor -->
    <h4>Nominated Supervisor</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="supervisorName" class="form-label">Name</label>
        <input type="text" class="form-control" id="supervisorName" name="supervisor_name">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="supervisorTelephone" class="form-label">Telephone</label>
        <input type="text" class="form-control" id="supervisorTelephone" name="supervisor_telephone">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="supervisorMobile" class="form-label">Mobile</label>
        <input type="text" class="form-control" id="supervisorMobile" name="supervisor_mobile">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="supervisorFax" class="form-label">Fax</label>
        <input type="text" class="form-control" id="supervisorFax" name="supervisor_fax">
      </div>
      <div class="col-12 mb-3">
        <label for="supervisorEmail" class="form-label">Email</label>
        <input type="email" class="form-control" id="supervisorEmail" name="supervisor_email">
      </div>
    </div>

    <!-- SECTION: Postal Address -->
    <h4>Postal Address (if different from physical)</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="postalStreet" class="form-label">Street</label>
        <input type="text" class="form-control" id="postalStreet" name="postal_street">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="postalSuburb" class="form-label">Suburb</label>
        <input type="text" class="form-control" id="postalSuburb" name="postal_suburb">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="postalState" class="form-label">State/Territory</label>
        <input type="text" class="form-control" id="postalState" name="postal_state">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="postalPostcode" class="form-label">Postcode</label>
        <input type="text" class="form-control" id="postalPostcode" name="postal_postcode">
      </div>
    </div>

    <!-- SECTION: Educational Leader -->
    <h4>Educational Leader</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="leaderName" class="form-label">Name</label>
        <input type="text" class="form-control" id="leaderName" name="leader_name">
      </div>
      <div class="col-lg-6 mb-3">
        <label for="leaderTelephone" class="form-label">Telephone</label>
        <input type="text" class="form-control" id="leaderTelephone" name="leader_telephone">
      </div>
      <div class="col-12 mb-3">
        <label for="supervisorEmail" class="form-label">Email</label>
        <input type="email" class="form-control" id="supervisorEmail" name="supervisor_email">
      </div>
    </div>

    <!-- SECTION: Additional Info -->
    <h4>Additional Information About Your Service</h4>
    <div class="row">
      <div class="col-lg-6 mb-3">
        <label for="strengths" class="form-label">Summary of strengths for Educational Program and practice</label>
        <textarea class="form-control" id="strengths" rows="3" name="strengths">add program</textarea>
      </div>
      <div class="col-lg-6 mb-3">
        <label for="grouping" class="form-label">How are the children grouped at your service?</label>
        <textarea class="form-control" id="grouping" rows="3" name="grouping">how</textarea>
      </div>
      <div class="col-lg-6 mb-3">
        <label for="submitterDetails" class="form-label">Name and position of person(s) responsible for submitting</label>
        <textarea class="form-control" id="submitterDetails" rows="3" name="submitter">e.g. Cheryl Smith</textarea>
      </div>
      <div class="col-lg-6 mb-3">
        <label for="educatorCount" class="form-label">Number of educators registered</label>
        <textarea class="form-control" id="educatorCount" rows="3" name="educator_count"></textarea>
      </div>
    </div>

    <!-- SECTION: Service Philosophy -->
    <h4>Service Statement of Philosophy</h4>
    <div class="row">
      <div class="col-12 mb-3">
        <label for="philosophy" class="form-label">Insert your service’s statement of philosophy here.</label>
        <textarea class="form-control" id="philosophy" rows="3" name="philosophy"></textarea>
      </div>
    </div>
  </div>
  </form>
</div>


@include('layout.footer')
@stop
