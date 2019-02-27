<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\GroupTag;
use App\Models\PositionStudentGroup;
use App\Models\Student;
use App\Models\StudentTag;
use App\Observers\GroupObserver;
use App\Observers\GroupTagObserver;
use App\Observers\StudentObserver;
use App\Observers\StudentTagObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Group::observe(GroupObserver::class);
        GroupTag::observe(GroupTagObserver::class);
        Student::observe(StudentObserver::class);
        StudentTag::observe(StudentTagObserver::class);

        # Default the position name
        PositionStudentGroup::creating(function ($psg) {
            if ($psg->position && !$psg->position_name) {
                $psg->position_name = $psg->position->name;
            }
            if (!is_int($psg->committee_year)) {
                $psg->committee_year = config('app.committee_year');
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
