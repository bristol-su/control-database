<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function()
{
    # Zapier Webhooks
    Route::post('hooks', 'ZapierWebhookController@subscribe');
    Route::delete('hooks/{id}', 'ZapierWebhookController@delete');
});
Route::middleware('auth:api')->namespace('API')->group(function()
{


    # Account Endpoints
    Route::prefix('accounts')->group(function () {

        # Resources
        Route::get('/', 'AccountAPIController@getAll');
        Route::get('/{account}', 'AccountAPIController@get');
        Route::post('/', 'AccountAPIController@create');
        Route::patch('/{account}', 'AccountAPIController@update');
        Route::delete('/{account}', 'AccountAPIController@delete');

        # Groups
        Route::get('/{account}/group', 'AccountAPIController@getGroup');
        Route::post('/{account}/group', 'AccountAPIController@linkGroup');
    });

    # Group Endpoints
    Route::prefix('groups')->group(function () {

        # Resources
        Route::post('/search', 'GroupAPIController@search');
        Route::get('/', 'GroupAPIController@getAll');
        Route::get('/{group}', 'GroupAPIController@get');
        Route::post('/', 'GroupAPIController@create');
        Route::patch('/{group}', 'GroupAPIController@update');
        Route::delete('/{group}', 'GroupAPIController@delete');

        # Group Tag
        Route::get('/{group}/group_tags', 'GroupAPIController@getGroupTags');
        Route::post('/{group}/group_tags', 'GroupAPIController@linkGroupTags');
        Route::delete('/{group}/group_tags/{group_tag}', 'GroupAPIController@deleteGroupTags');

        # Accounts
        Route::get('/{group}/accounts', 'GroupAPIController@getAccounts');
        Route::post('/{group}/accounts', 'GroupAPIController@linkAccounts');
        Route::delete('/{group}/accounts/{account}', 'GroupAPIController@deleteAccounts');

        # Students
        Route::get('/{group}/students', 'GroupAPIController@getStudents');
        Route::post('/{group}/students', 'GroupAPIController@linkStudents');
        Route::delete('/{group}/students/{student}', 'GroupAPIController@deleteStudents');

        # Positions (Committee Members)
        Route::get('/{group}/position_student_groups', 'GroupAPIController@getPositionStudentGroups');

    });

    # GroupTag Endpoints
    Route::prefix('group_tags')->group(function () {

        # Resources
        Route::get('/', 'GroupTagAPIController@getAll');
        Route::get('/{group_tag}', 'GroupTagAPIController@get');
        Route::post('/', 'GroupTagAPIController@create');
        Route::patch('/{group_tag}', 'GroupTagAPIController@update');
        Route::delete('/{group_tag}', 'GroupTagAPIController@delete');

        # Group
        Route::get('/{group_tag}/groups', 'GroupTagAPIController@getGroups');
        Route::post('/{group_tag}/groups', 'GroupTagAPIController@linkGroups');
        Route::delete('/{group_tag}/groups/{group}', 'GroupTagAPIController@deleteGroups');

        # Group Tag Category
        Route::get('/{group_tag}/group_tag_category', 'GroupTagAPIController@getGroupTagCategory');
        Route::post('/{group_tag}/group_tag_category', 'GroupTagAPIController@linkGroupTagCategory');
    });

    # GroupTagCategory Endpoints
    Route::prefix('group_tag_categories')->group(function () {

        # Resources
        Route::get('/', 'GroupTagCategoryAPIController@getAll');
        Route::get('/{group_tag_category}', 'GroupTagCategoryAPIController@get');
        Route::post('/', 'GroupTagCategoryAPIController@create');
        Route::patch('/{group_tag_category}', 'GroupTagCategoryAPIController@update');
        Route::delete('/{group_tag_category}', 'GroupTagCategoryAPIController@delete');

        # Group Tags
        Route::get('/{group_tag_category}/group_tags', 'GroupTagCategoryAPIController@getGroupTags');
        Route::post('/{group_tag_category}/group_tags', 'GroupTagCategoryAPIController@linkGroupTags');
        Route::delete('/{group_tag_category}/group_tags/{group_tag}', 'GroupTagCategoryAPIController@deleteGroupTags');

    });

    # Student Endpoints
    Route::prefix('students')->group(function () {

        # Resources
        Route::post('/search', 'StudentAPIController@search');
        Route::get('/', 'StudentAPIController@getAll');
        Route::get('/{student}', 'StudentAPIController@get');
        Route::post('/', 'StudentAPIController@create');
        Route::patch('/{student}', 'StudentAPIController@update');
        Route::delete('/{student}', 'StudentAPIController@delete');

        # Student Tags
        Route::get('/{student}/student_tags', 'StudentAPIController@getStudentTags');
        Route::post('/{student}/student_tags', 'StudentAPIController@linkStudentTags');
        Route::delete('/{student}/student_tags/{student_tag}', 'StudentAPIController@deleteStudentTags');

        # Groups
        Route::get('/{student}/groups', 'StudentAPIController@getGroups');
        Route::post('/{student}/groups', 'StudentAPIController@linkGroups');
        Route::delete('/{student}/groups/{group}', 'StudentAPIController@deleteGroups');

        # Positions
        Route::get('/{student}/position_student_groups', 'StudentAPIController@getPositionStudentGroups');
        Route::post('/{student}/position_student_groups', 'StudentAPIController@linkPositionStudentGroups');
        Route::delete('/{student}/position_student_groups/{position}', 'StudentAPIController@deletePositionStudentGroups');

    });

    # Student Endpoints
    Route::prefix('positions')->group(function () {

        # Resources
        Route::get('/', 'PositionAPIController@getAll');
        Route::get('/{position}', 'PositionAPIController@get');
        Route::post('/', 'PositionAPIController@create');
        Route::patch('/{position}', 'PositionAPIController@update');
        Route::delete('/{position}', 'PositionAPIController@delete');

        # Students
        Route::get('/{position}/students', 'PositionAPIController@getStudents');
        Route::post('/{position}/students', 'PositionAPIController@linkStudents');
        Route::delete('/{position}/students/{student}', 'PositionAPIController@deleteStudents');

    });

    # StudentTag Endpoints
    Route::prefix('student_tags')->group(function () {

        # Resources
        Route::get('/', 'StudentTagAPIController@getAll');
        Route::get('/{student_tag}', 'StudentTagAPIController@get');
        Route::post('/', 'StudentTagAPIController@create');
        Route::patch('/{student_tag}', 'StudentTagAPIController@update');
        Route::delete('/{student_tag}', 'StudentTagAPIController@delete');

        # Students
        Route::get('/{student_tag}/students', 'StudentTagAPIController@getStudents');
        Route::post('/{student_tag}/students', 'StudentTagAPIController@linkStudents');
        Route::delete('/{student_tag}/students/{student}', 'StudentTagAPIController@deleteStudents');

        # Sudent Tag Categories
        Route::get('/{student_tag}/student_tag_category', 'StudentTagAPIController@getStudentTagCategory');
        Route::post('/{student_tag}/student_tag_category', 'StudentTagAPIController@linkStudentTagCategory');
    });

    # StudentTagCategory Endpoints
    Route::prefix('student_tag_categories')->group(function () {

        # Resources
        Route::get('/', 'StudentTagCategoryAPIController@getAll');
        Route::get('/{student_tag_category}', 'StudentTagCategoryAPIController@get');
        Route::post('/', 'StudentTagCategoryAPIController@create');
        Route::patch('/{student_tag_category}', 'StudentTagCategoryAPIController@update');
        Route::delete('/{student_tag_category}', 'StudentTagCategoryAPIController@delete');

        # Student Tags
        Route::get('/{student_tag_category}/student_tags', 'StudentTagCategoryAPIController@getStudentTags');
        Route::post('/{student_tag_category}/student_tags', 'StudentTagCategoryAPIController@linkStudentTags');
        Route::delete('/{student_tag_category}/student_tags/{student_tag}', 'StudentTagCategoryAPIController@deleteStudentTags');
    });

    # Position Student Group Endpoints
    Route::prefix('position_student_groups')->group(function () {

        # Resources
        Route::get('/', 'PositionStudentGroupAPIController@getAll');
        Route::get('/{position_student_group}', 'PositionStudentGroupAPIController@get');
        Route::post('/', 'PositionStudentGroupAPIController@create');
    });
});
