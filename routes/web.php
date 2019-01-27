<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

# UnionCloud Helper Routes

Route::get('/unioncloud/get-all-groups', 'UnionCloudController@getAllGroups');

Route::post('/unioncloud/search-students', 'UnionCloudController@searchStudents');

Auth::routes();

Route::get('/home', function() {return redirect('/'); })->name('home');
Route::get('/dashboard', function() { return redirect('/'); })->name('dashboard');
Route::get('/', function() { return view('home'); });
