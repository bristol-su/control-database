<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Group;
use App\Models\GroupTag;
use App\Models\GroupTagCategory;
use App\Models\Student;
use App\Models\StudentTag;
use App\Models\StudentTagCategory;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        Route::bind('trashed_account', function($id) {
            return Account::onlyTrashed()->findOrFail($id);
        });
        Route::bind('trashed_group', function($id) {
            return Group::onlyTrashed()->findOrFail($id);
        });
        Route::bind('trashed_student', function($id) {
            return Student::onlyTrashed()->findOrFail($id);
        });
        Route::bind('trashed_group_tag', function($id) {
            return GroupTag::onlyTrashed()->findOrFail($id);
        });
        Route::bind('trashed_group_tag_category', function($id) {
            return GroupTagCategory::onlyTrashed()->findOrFail($id);
        });
        Route::bind('trashed_student_tag', function($id) {
            return StudentTag::onlyTrashed()->findOrFail($id);
        });
        Route::bind('trashed_student_tag_category', function($id) {
            return StudentTagCategory::onlyTrashed()->findOrFail($id);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
