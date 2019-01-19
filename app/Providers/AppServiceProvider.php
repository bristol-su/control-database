<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\GroupTag;
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
