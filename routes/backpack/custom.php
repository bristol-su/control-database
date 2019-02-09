<?php

use \Illuminate\Support\Facades\Route;

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
    CRUD::resource('position', 'PositionCrudController');
    CRUD::resource('group_tag', 'GroupTagCrudController');
    CRUD::resource('student_tag', 'StudentTagCrudController');
    CRUD::resource('group_tag_category', 'GroupTagCategoryCrudController');
    CRUD::resource('student_tag_category', 'StudentTagCategoryCrudController');
    CRUD::resource('position_student_group', 'PositionStudentGroupCrudController');

    # Permanently delete and restore routes.
    Route::delete('account/{trashed_account}/delete', 'AccountCrudController@delete');
    Route::post('account/{trashed_account}/restore', 'AccountCrudController@restore');

    Route::delete('student/{trashed_student}/delete', 'StudentCrudController@delete');
    Route::post('student/{trashed_student}/restore', 'StudentCrudController@restore');

    Route::delete('group/{trashed_group}/delete', 'GroupCrudController@delete');
    Route::post('group/{trashed_group}/restore', 'GroupCrudController@restore');

    Route::delete('student_tag/{trashed_student_tag}/delete', 'StudentTagCrudController@delete');
    Route::post('student_tag/{trashed_student_tag}/restore', 'StudentTagCrudController@restore');

    Route::delete('group_tag/{trashed_group_tag}/delete', 'GroupTagCrudController@delete');
    Route::post('group_tag/{trashed_group_tag}/restore', 'GroupTagCrudController@restore');

    Route::delete('student_tag_category/{trashed_student_tag_category}/delete', 'StudentTagCategoryCrudController@delete');
    Route::post('student_tag_category/{trashed_student_tag_category}/restore', 'StudentTagCategoryCrudController@restore');

    Route::delete('group_tag_category/{trashed_group_tag_category}/delete', 'GroupTagCategoryCrudController@delete');
    Route::post('group_tag_category/{trashed_group_tag_category}/restore', 'GroupTagCategoryCrudController@restore');

    Route::delete('position/{trashed_position}/delete', 'PositionCrudController@delete');
    Route::post('position/{trashed_position}/restore', 'PositionCrudController@restore');

    Route::delete('position_student_group/{trashed_position_student_group}/delete', function() {
        \Illuminate\Support\Facades\Log::info('Remove these buttons and change from deactivated to ex or sth');
    });
    Route::post('position_student_group/{trashed_position_student_group}/restore', 'PositionStudentGroupCrudController@restore');


}); // this should be the absolute last line of this file