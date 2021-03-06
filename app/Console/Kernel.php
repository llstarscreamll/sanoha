<?php namespace sanoha\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'sanoha\Console\Commands\Inspire',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		/*$schedule->command('inspire')
				 ->hourly();*/
		// para las tareas añadidas a la cola cada 5 minutos
		$schedule->command('queue:work')->everyFiveMinutes();
		// para el backup de la base de datos
		$schedule->exec('bash .backup.sh')->twiceDaily();
	}

}
