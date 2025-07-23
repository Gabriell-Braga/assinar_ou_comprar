<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="User",
 *     description="Endpoints de usuário",
 * )
 */

class CalculadoraNovoController extends Controller
{
	public function values($defaults) {
		$input = request()->all();
        return array_merge($defaults, \Arr::only($input, array_keys($defaults)));
    }
	
	public function getSimulation() {
		$input = array_merge([
			'period' => 12,
			'franchise' => 1000,
			'slug' => '',
		], request()->all());
		
		$return = [];
		$return['price'] = 0;
		$return['parcelValue'] = 0;
		$return['parcelPromo'] = 0;
		$return['fipeCode'] = false;
		$return['maintenanceTotal'] = 0;
		$return['maintenanceMonthly'] = 0;
		$return['maintenanceDescription'] = '';
		$return['veiculo'] = false;
		$return['simulation'] = false;
		$return['maintenance'] = false;
		$return['fipe'] = false;
		$return['depreciacao'] = false;
		
		if ($return['veiculo'] = (new \App\Models\Calculadora)->getVeiculo($input)) {
			if (isset($return['veiculo']['acf'])) {

				$input['locaviaModelCode'] = $return['veiculo']['acf']['codigoLocavia'];
				$input['marca'] = $return['veiculo']['acf']['informacoesModelo']['marca']['slug'];

				$return['parcelValue'] = $return['veiculo']['acf']['precificacao']['value'];
				$return['parcelPromo'] = $return['veiculo']['acf']['precificacao']['valueWithDiscount'];

				/* Sim, eu listo todos os carros novamente para encontrar o veículo que quero, porque
				o endpoint /vehicleDetails não me dá o fipeCode :/ - Mas esse método utiliza cache. */
				$catalog = (new \App\Models\Calculadora)->getCatalog($input);
				foreach($catalog as $simulation) {
					if ($simulation['model']['slug']==$input['slug']) {
						$return['fipeCode'] = $simulation['model']['fipeCode'];
						$return['price'] = $simulation['cashPayment']['totalValue'];
						$return['simulation'] = $simulation;
						break;
					}
				}
				
				if ($return['maintenance'] = (new \App\Models\Calculadora)->getMaintenance($input)) {
					if (isset($return['maintenance']['total'])) {
						$return['maintenanceTotal'] = $return['maintenance']['total'];
						$return['maintenanceMonthly'] = $return['maintenance']['total_monthly'];
						$return['maintenanceDescription'] = $return['maintenance']['description'];
					}
				}

				$return['depreciacao'] = (new \App\Models\Calculadora)->getDepreciacao($input);
			}
		}


		return $return;
	}



	// get: api/calculadora/anbima
	public function getAnbima() {
		$cache_minutos = 60*24;
		$cache_key = 'vehicle-details-anbima';

		return \Cache::remember($cache_key, $cache_minutos, function() {
			return (new \App\Models\Calculadora)->anbima();
		});
	}

	// get: api/calculadora/catalog
	public function getCatalog() {
		$return = [];

		$cache_minutos = 60*24;
		$cache_key = 'vehicle-details-anbima';
		$return['anbima'] = \Cache::remember($cache_key, $cache_minutos, function() {
			return (new \App\Models\Calculadora)->anbima();
		});

		// dd( (new \App\Models\Calculadora)->catalog() );

		$cache_minutos = 60*24*7;
		$cache_params = request()->only('period', 'franchise');
		$cache_key = 'catalog-'. md5(json_encode($cache_params));

		$return['catalog'] = \Cache::remember($cache_key, $cache_minutos, function() use($cache_params) {
			return (new \App\Models\Calculadora)->catalog($cache_params);
		});

		return $return;
	}

	// get: api/calculadora/vehicle-detail
	public function getVehicleDetail() {
		$input = array_merge([
			'locaviaModelCode' => 0,
			'marca' => '',
			'codigo' => '',
			'ano' => '',
			'slug' => '',
			'period' => '',
			'franchise' => '',
		], request()->all());


		$return = [];


		// Simulation
		$cache_minutos = 60*24*7;
		$cache_params = request()->only('period', 'franchise', 'locaviaModelCode');
		$cache_key = 'vehicle-simulation-'. md5(json_encode($cache_params));

		$return['simulation'] =  \Cache::remember($cache_key, $cache_minutos, function() use($cache_params) {
			return (new \App\Models\Calculadora)->simulation($cache_params);
		});


		// Dados de manutenção
		$cache_minutos = 60*24*7;
		$cache_params = request()->only('locaviaModelCode', 'period', 'franchise');
		$cache_key = 'vehicle-manutencao-'. md5(json_encode($cache_params));

		$return['manutencao'] =  \Cache::remember($cache_key, $cache_minutos, function() use($cache_params) {
			return (new \App\Models\Calculadora)->manutencao($cache_params);
		});


		// Dados de depreciacao
		$cache_minutos = 60*24*7;
		$cache_params = request()->only('marca', 'period');
		$cache_key = 'vehicle-depreciacao-'. md5(json_encode($cache_params));

		$return['depreciacao'] =  \Cache::remember($cache_key, $cache_minutos, function() use($cache_params) {
			return (new \App\Models\Calculadora)->depreciacao($cache_params['marca'], $cache_params['period']);
		});


		// Dados de veículo com cache
		$cache_minutos = 60*24*7;
		$cache_params = request()->only('slug', 'period', 'franchise');
		$cache_key = 'vehicle-details-'. md5(json_encode($cache_params));

		$return['vehicle'] =  \Cache::remember($cache_key, $cache_minutos, function() use($cache_params) {
			return (new \App\Models\Calculadora)->vehicleDetails($cache_params);
		});



		// Dados de fipe com cache
		$cache_minutos = 60*24;
		$cache_params = request()->only('marca', 'codigo', 'ano');
		$cache_key = 'vehicle-details-fipe-'. md5(json_encode($cache_params));

		$return['fipe'] =  \Cache::remember($cache_key, $cache_minutos, function() use($cache_params) {
			return (new \App\Models\Calculadora)->fipe($cache_params);
		});



		// $simulationPost = [
		// 	'locaviaModelCode' => $input['locaviaModelCode'],
		// 	'period' => 48,
		// 	'franchise' => 1000,
		// 	'tank' => true,
		// 	'totalValue' => 0,
		// 	'totalValueFinacing' => 0,
		// ];

		// $resp = \Illuminate\Support\Facades\Http::withHeaders([
		// 	'Content-Type' => 'application/json-patch+json',
		// 	'accept' => '*/*',
		// ])->post('https://livre.unidas.com.br/v1/Simulation', $simulationPost);
		// $data['aaa'] = $resp->json();
		
		return $return;
	}


	public function getCacheClear() {
		return \Artisan::call('app:clear');
	}
}