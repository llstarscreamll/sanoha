<?php namespace sanoha\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'sanoha\Http\Middleware\VerifyCsrfToken',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' 				=> 'sanoha\Http\Middleware\Authenticate',
		'auth.basic' 		=> 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' 			=> 'sanoha\Http\Middleware\RedirectIfAuthenticated',
		'checkPermmisions'	=> 'sanoha\Http\Middleware\CheckPermissions',
		'checkCostCenter'	=> 'sanoha\Http\Middleware\CheckCostCenter',
	];

}
