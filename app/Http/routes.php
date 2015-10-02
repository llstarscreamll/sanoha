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
    
Route::get('logs', [
        'as'    =>  'logs',
        'uses'  =>  'LogController@index'
    ]);

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::group(['middleware' => 'auth'], function(){
    
    
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('employee', 'EmployeeController');
    /**
     * Ordenes de Trabajo
     */
    Route::get('workOrder/{id}/mainReport', [
        'as'    =>  'workOrder.mainReport',
        'uses'  =>  'WorkOrderController@mainReportForm'
    ]);
    Route::put('workOrder/{id}/mainReport', [
        'as'    =>  'workOrder.mainReport',
        'uses'  =>  'WorkOrderController@mainReportStore'
    ]);
    Route::resource('workOrder', 'WorkOrderController');
    
    /**
     * Reporte de Novedades
     */
    // para setear el costo de centro de trabajo
    Route::get('noveltyReport/project/{short_name?}', [
        'as'    =>  'noveltyReport.setCostCenter',
        'uses'  =>  'NoveltyReportController@setCostCenter'
    ]);
    // la vista para seleccionar el centro de costo de trabajo
    Route::get('noveltyReport/selectCostCenter', [
        'as'    =>  'noveltyReport.selectCostCenter',
        'uses'  =>  'NoveltyReportController@selectCostCenterView'
    ]);
    // la vista en calendario de las novedades reportadas
    Route::get('noveltyReport/calendar', [
        'as'    =>  'noveltyReport.calendar',
        'uses'  =>  'NoveltyReportController@calendar'
    ]);
    Route::resource('noveltyReport', 'NoveltyReportController');
    
    /**
     * Reporte de Actividades Mineras
     */
    // para setear el costo de centro de trabajo
    Route::get('activityReport/project/{short_name?}', [
        'as'    =>  'activityReport.setCostCenter',
        'uses'  =>  'ActivityReportController@setCostCenter'
    ]);
    // la vista para seleccionar el centro de costo de trabajo
    Route::get('activityReport/selectCostCenter', [
        'as'    =>  'activityReport.selectCostCenter',
        'uses'  =>  'ActivityReportController@selectCostCenterView'
    ]);
    // la vista en calendario de las actividades mineras reportadas
    Route::get('activityReport/calendar', [
        'as'    =>  'activityReport.calendar',
        'uses'  =>  'ActivityReportController@calendar'
    ]);
    // reporte de actividades mineras individuales
    Route::get('activityReport/individual', [
        'as'    =>  'activityReport.individual',
        'uses'  =>  'ActivityReportController@individual'
    ]);
    Route::resource('activityReport', 'ActivityReportController');
    
});