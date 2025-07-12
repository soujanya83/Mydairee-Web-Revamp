<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use Illuminate\Support\Facades\View;
use PHPUnit\Framework\TestStatus\Incomplete;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $permissions = [];

            if (Auth::check()) {
                $user = Auth::user();

                // Grant all permissions if user is Superadmin or room_leader == 1
                if ($user->userType === 'Superadmin' || $user->room_leader == 1) {
                    $permissions = array_fill_keys([
                        // done
                        'addObservation',
                        'deleteObservation',
                        'updateObservation',
                        'viewAllObservation',
                        'addReflection',
                        'updatereflection',
                        'deletereflection',
                        'viewAllReflection',
                        'viewRoom',
                        'addRoom',
                        'viewDailyDiary',
                        'deleteRoom',
                        'addProgramPlan',
                        'editProgramPlan',
                        'viewProgramPlan',
                        'deleteProgramPlan',
                        'addAnnouncement',
                        'viewAllAnnouncement',
                        'addRecipe',
                        'deleteRecipe',
                        'updateRecipe',
                        'addMenu',
                        'addCenters',
                        'viewCenters',
                        'updateCenters',
                        'addParent',
                        'viewParent',
                        'updateParent',

                        // Incomplete
                        'addQIP',
                        'editQIP',
                        'deleteQIP',
                        'downloadQIP',
                        'printQIP',
                        'mailQIP',
                        'viewQip',
                        'editRoom',
                        'approveObservation',
                        'approveReflection',
                        'approveAnnouncement',
                        'deleteAnnouncement',
                        'updateAnnouncement',
                        'addSurvey',
                        'approveSurvey',
                        'deleteSurvey',
                        'updateSurvey',
                        'viewAllSurvey',
                        'approveRecipe',
                        'approveMenu',
                        'deleteMenu',
                        'updateMenu',
                        'updateDailyDiary',
                        'updateHeadChecks',
                        'updateAccidents',
                        'updateModules',
                        'addUsers',
                        'viewUsers',
                        'updateUsers',

                        'addChildGroup',
                        'viewChildGroup',
                        'updateChildGroup',
                        'updatePermission',
                        'addprogress',
                        'editprogress',
                        'viewprogress',
                        'editlesson',
                        'viewlesson',
                        'printpdflesson',
                        'assessment',
                        'addSelfAssessment',
                        'editSelfAssessment',
                        'deleteSelfAssessment',
                        'viewSelfAssessment'
                    ], 1);
                } else {
                    // Fetch from DB for logged in user
                    $userPermissions = DB::table('permissions')
                        ->where('userid', $user->id)
                        ->first();

                    if ($userPermissions) {
                        $permissions = collect($userPermissions)
                            ->except(['id', 'userid', 'centerid']) // remove unwanted fields
                            ->mapWithKeys(function ($value, $key) {
                                return [$key => (int) $value];
                            })->toArray();
                    }
                }
            }

            // Share globally
            View::share('permissions', $permissions);
            app()->singleton('userPermissions', fn() => $permissions);
        });
    }
}
