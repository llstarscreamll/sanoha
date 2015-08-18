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

Route::get('home', [
        'as'    =>  'home',
        'uses'  =>  'HomeController@index'
    ]);

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::group(['middleware' => 'auth'], function(){
    
    
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    
    /**
     * Reporte de Novedades
     */
     Route::get('noveltyReport/project/{short_name?}', [
        'as'    =>  'noveltyReport.setCostCenter',
        'uses'  =>  'NoveltyReportController@setCostCenter'
    ]);
    Route::get('noveltyReport/selectCostCenter', [
        'as'    =>  'noveltyReport.selectCostCenter',
        'uses'  =>  'NoveltyReportController@selectCostCenterView'
    ]);
    Route::resource('noveltyReport', 'NoveltyReportController');
    
    /**
     * Reporte de Actividades Mineras
     */
    Route::get('activityReport/project/{short_name?}', [
        'as'    =>  'activityReport.setCostCenter',
        'uses'  =>  'ActivityReportController@setCostCenter'
    ]);
    Route::get('activityReport/selectCostCenter', [
        'as'    =>  'activityReport.selectCostCenter',
        'uses'  =>  'ActivityReportController@selectCostCenterView'
    ]);
    Route::get('activityReport/calendar', [
        'as'    =>  'activityReport.calendar',
        'uses'  =>  'ActivityReportController@calendar'
    ]);
    Route::resource('activityReport', 'ActivityReportController');
    
});