<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReEnrolment extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_name',
        'child_dob',
        'parent_email',
        'current_days',
        'requested_days',
        'session_option',
        'kinder_program',
        'finishing_child_name',
        'last_day',
        'holiday_dates'
    ];

    protected $casts = [
        'child_dob' => 'date',
        'last_day' => 'date',
        'current_days' => 'array',
        'requested_days' => 'array',
    ];

    /**
     * Get formatted current days
     */
    public function getCurrentDaysFormattedAttribute()
    {
        return $this->current_days ? implode(', ', $this->current_days) : '';
    }

    /**
     * Get formatted requested days
     */
    public function getRequestedDaysFormattedAttribute()
    {
        return $this->requested_days ? implode(', ', $this->requested_days) : '';
    }

    /**
     * Get session option display name
     */
    public function getSessionOptionDisplayAttribute()
    {
        $options = [
            '9_hours' => '9 Hours (8:30am - 5:30pm)',
            '10_hours_8_6' => '10 Hours (8:00am - 6:00pm)',
            '10_hours_8_30_6_30' => '10 Hours (8:30am - 6:30pm)',
            'full_day' => 'Full Day (7:00am - 6:30pm)'
        ];

        return $options[$this->session_option] ?? '';
    }

    /**
     * Get kinder program display name
     */
    public function getKinderProgramDisplayAttribute()
    {
        $programs = [
            '3_year_old' => '3-year-old Kinder',
            '4_year_old' => '4-year-old Kinder',
            'unfunded' => 'Unfunded Kinder (3-5 years)',
            'not_attending' => 'Not attending Kinder at Nextgen'
        ];

        return $programs[$this->kinder_program] ?? '';
    }
}
