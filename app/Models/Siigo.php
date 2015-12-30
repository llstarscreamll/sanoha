<?php

namespace sanoha\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Siigo extends Model {

	/**
     * The timestamps.
     * 
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'fecha'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'siigo';

    /**
     * 
     * @param Request $request
     * @return array
     */
	public function getDataToExportExcel($request)
	{
		// el modelo
		$nits = new Siigo;

		// si se especificaron nits, agrego la clausula
		dd(count($request->get('nit')) > 0, empty($request->get('nit')), $request->get('nit'));
		if(count($request->get('nit')) > 0){
			$nits = Siigo::whereIn('tercero', $request->get('nit'))->distinct()->lists('nombre_tercero', 'tercero')->all();
		}
		else{
			$nits = Siigo::distinct()->get(['nombre_tercero', 'tercero'])->lists('nombre_tercero', 'tercero')->all();
		}

		// obtengo los nits
		dd($nits);
		//dd(Siigo::distinct()->get(['nombre_tercero', 'tercero'])->lists('nombre_tercero', 'tercero')->all());

		// el array a devolver que contiene los datos organizados y calculados
		$data = array();

		// el rango de fechas
		$startDate = Carbon::createFromFormat('Y-m-d', $request->get('from'));
		$endDate = Carbon::createFromFormat('Y-m-d', $request->get('to'));
		// fechas para comparar
		$start = intval($startDate->year.$startDate->format('m'));
		$end = intval($endDate->year.$endDate->format('m'));
		//
		$dates = [
			$startDate->startOfMonth()->toDateString(),
			$startDate->endOfMonth()->toDateString(),
		];

		// contador
		$count = 0;

		// recorro las fechas dadas
		while ($start <= $end) {

			// hago los cálculos con los meses requeridos
			foreach ($nits as $key => $nit) {
				//$key = 74270659;
				// el nit del proveedor
				$data[$count]['fecha'] = $startDate->toDateString();
				$data[$count]['nit'] = $key;
				$data[$count]['nombre'] = trim($nit);
				// valor de la liquidación
				$data[$count]['liquidacion'] = $this->calculateLiquidationValue($key, $dates);
				// valor de los descuentos
				$data[$count]['descuentos'] = $this->calculateDiscountsValue($key, $dates);
				// valor de los pagos
				$data[$count]['pagos'] = $this->calculatePaymentsValue($key, $dates);
				// el saldo total
				$data[$count]['saldo'] = $data[$count]['liquidacion'] - $data[$count]['descuentos'] - $data[$count]['pagos'];

				$count++;
			}

			// incremento los parámetros de fecha
			$dates = [
				$startDate->modify('first day of next month')->toDateString(),
				$startDate->modify('last day of this month')->toDateString(),
			];

			$start = intval($startDate->format('Ym'));
		}

		return $data;
	}

	/**
	 * Obtiene los cuentas del 22000000 a 23999999 de un nit específico y en
	 * un rango de fechas espefífico
	 * 
	 * @param array $dates
	 * @param integer $nit
	 * @return array
	 */
	private function getDocIds($nit, $dates)
	{
		$results = Siigo::where('tercero', $nit) // el nit del proveedor en cuestion
			->whereBetween('fecha', $dates) // el mes requerido
			->where(function($q){ // las cuentas de 22000000 a 23999999
				$q->whereRaw('(CAST(cta as unsigned)) between 22000000 and 23999999')
					->where('detalle', 'not like', 'cancela factura%') // y las cuentas que no sean de cancelar
					->where('debitos', 0); // donde los débitos sean 0
			})
			->get();

		$payments = array();

		foreach ($results as $key => $result) {
			if(! empty($doc = trim($result->DOCUMENTO_CRUCE)))
				$payments[] = $doc;
		}

		return $payments;
	}

	/**
	 * Calcula el valor de los pagos hechos al proveedor a partor de:
	 * - rango de tiempo (desde Y-m-d hasta Y-m-d)
	 * - sumando la columna "débitos" de las cuentas del 22000000 al 23999999
	 * 
	 * Se debe tener en cuenta que el pago en ocaciones no se hace en el mismo mes,
	 * se puede hacer tiempo despúes, se deja aquí un tiempo de tolerancia de dos años.
	 * 
	 * @param integer $nit
	 * @return integer
	 */
	private function calculatePaymentsValue($nit, $dates)
	{
		// el valor total de los descuentos
		$payments = 0;

		// obtengo lo documentos del mes nada mas
		$docId = $this->getDocIds($nit, $dates);

		// modifico la fecha fin del filtro para hallar pagos futuros de hasta dos años
		$dates[1] = Carbon::createFromFormat('Y-m-d', $dates[1])->addYears(2)->endOfMonth()->toDateString();

		// obtengo los datos asociados al nit
		$results = Siigo::where('tercero', $nit) // el nit del proveedor en cuestion
			->whereBetween('fecha', $dates) // el mes requerido
			->where(function($q){ // las cuentas de 22000000 a 23999999
				$q->whereRaw('(CAST(cta as unsigned)) between 22000000 and 23999999');
			})
			->get();


		// recorro los resultados y sumo los valores de la columna "creditos"
		foreach ($results as $key => $result) {

			if ( in_array(trim($result->DOCUMENTO_CRUCE), $docId) ){

				$payments += intval($result->DEBITOS);

			}
			
		/*if($dates[0] == '2015-11-01')
			dd($dates, $results->toArray(), $docId, $payments);*/

		}

		return $payments;
	}

	/**
	 * Calcula el valor de los descuentos de un proveedor a partor de:
	 * - rango de tiempo (desde Y-m-d hasta Y-m-d)
	 * - sumando la columna "créditos" de las cuentas 130505 a 133015 cuando la columna 
	 * 	 "comprobantes" empieza con P001, P003 y/o P004
	 * 
	 * @param integer $nit
	 * @return integer
	 */
	private function calculateDiscountsValue($nit, $dates)
	{
		// el valor total de los descuentos
		$discounts = 0;

		// obtengo los datos asociados al nit
		$results = Siigo::where('tercero', $nit) // el nit del proveedor en cuestion
			->whereBetween('fecha', $dates) // el mes requerido
			->where(function($q){ // las cuentas de 13050500 a 13301599
				$q->whereRaw('(CAST(cta as unsigned)) between 13050500 and 13301599');
			})
			->where(function($q){ // los comprobantes que empiecen por P-001, P-003, P-004
				$q->where('comprobante', 'like', 'P-001%')
					->orWhere('comprobante', 'like', 'P-002%')
					->orWhere('comprobante', 'like', 'P-003%')
					->orWhere('comprobante', 'like', 'P-004%');
			})
			->get();

		// recorro los resultados y sumo los valores de la columna "creditos"
		foreach ($results as $key => $result) {
			
			$discounts += intval($result->CREDITOS);

		}

		return $discounts;
	}

	/**
	 * Calcula el valor final de la liquidación de un proveedor a partir de:
	 * - rango de tiempo (desde Y-m-d hasta Y-m-d)
	 * - sumando los valores de la columna "débitos" de las cuantas que empiezan con 73,52,14, llamamos a este valor A
	 * - sumando los valores de las columnas "créditos" de las cuentas que empiezan con 2365, llamamos a este valor B
	 * - el resultado de la liquidación es  A - B
	 * 
	 * @param int $nit
	 * @return int
	 */
	private function calculateLiquidationValue($nit, $dates)
	{
		$A = 0; // el minuendo de la operación, débitos de 73*,52*,14*
		$B = 0;	// el sustraendo de la operación, créditos de 2365*

		// obtengo los datos asociados al nit
		$results = Siigo::where('tercero', $nit) // el nit del proveedor en cuestion
			->whereBetween('fecha', $dates) // el mes requerido
			->where(function($q){ // las cuentas que inicien por 73, o 52, o 41
				$q->where('cta', 'like', '73%')
				->orWhere('cta', 'like', '52%')
				->orWhere('cta', 'like', '14%')
				->orWhere('cta', 'like', '23%'); // para las cuentas que hay que restar
			})
			->get();

		// si se han encontrado resultados, hago los cálculos
		if(count($results) > 0){

			foreach ($results as $key => $result) {
				
				// sumos los valores para A
				$cta = substr($result->CTA, 0, 2);
				if ($cta == '73' || $cta == '52' || $cta == '14'){
					$A += intval($result->DEBITOS); // sumo lo de la columna débitos
				}

				// sumo los valores para B
				$cta = substr($result->CTA, 0, 3);
				if($cta == '236' || $cta == '233'){
					$B += intval($result->CREDITOS); // sumo la columna créditos
				}



			}

		}

		return $A - $B;
	}

}
