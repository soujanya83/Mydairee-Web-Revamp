<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDetailsModel extends Model
{
    protected $table = "servicedetails";

    public $timestamps = false;

    protected $fillable = [
  'serviceName',
  'serviceApprovalNumber',
  'serviceStreet',
  'serviceSuburb',
  'serviceState',
  'servicePostcode',
  'contactTelephone',
  'contactMobile',
  'contactFax',
  'contactEmail',
  'providerContact',
  'providerTelephone',
  'providerMobile',
  'providerFax',
  'providerEmail',
  'supervisorName',
  'supervisorTelephone',
  'supervisorMobile',
  'supervisorFax',
  'supervisorEmail',
  'postalStreet',
  'postalSuburb',
  'postalState',
  'postalPostcode',
  'eduLeaderName',
  'eduLeaderTelephone',
  'eduLeaderEmail',
  'strengthSummary',
  'childGroupService',
  'personSubmittingQip',
  'educatorsData',
  'philosophyStatement',
  'centerid'
    ];

}
