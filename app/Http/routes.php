<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::group(['middleware' => 'auth'], function(){
    
    
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    
    /**
     * Reporte de Actividades Mineras
     */
    Route::get('activityReport/project/{short_name?}', [
        'as'    =>  'activityReport.activitiesFromCostCenter',
        'uses'  =>  'ActivityReportController@costCenterActivities'
    ]);
    Route::get('activityReport/selectCostCenter', [
        'as'    =>  'activityReport.selectCostCenter',
        'uses'  =>  'ActivityReportController@selectCostCenterView'
    ]);
    Route::resource('activityReport', 'ActivityReportController');
    
});