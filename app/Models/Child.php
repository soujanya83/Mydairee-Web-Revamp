<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $table='child';

    protected $fillable=['name','lastname','dob','startDate','room','imageUrl','gender','status','daysAttending','createdBy','centerid'];

}
