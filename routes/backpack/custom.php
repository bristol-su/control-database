<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    CRUD::resource('group', 'GroupCrudController');
    CRUD::resource('account', 'AccountCrudController');
    CRUD::resource('student', 'StudentCrudController');
    CRUD::resource('group_tag', 'GroupTagCrudController');
    CRUD::resource('student_tag', 'StudentTagCrudController');
    CRUD::resource('group_tag_category', 'GroupTagCategoryCrudController');
    CRUD::resource('student_tag_category', 'StudentTagCategoryCrudController');
}); // this should be the absolute last line of this file