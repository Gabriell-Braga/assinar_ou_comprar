<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="User",
 *     description="Endpoints de usuário",
 * )
 */

class CalculadoraController extends Controller
{
	// get: api/calculadora/test
	public function getTest() {
		$return = [];
		// $return['manutencao'] = (new \App\Models\Calculadora)->manutencao('argo-drive-1-0-4p', 48, 3000);
		$return['depreciacao1'] = (new \App\Models\Calculadora)->depreciacao('blau', 12);
		$return['depreciacao2'] = (new \App\Models\Calculadora)->depreciacao('audi', 12);
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
		// dump((new \App\Models\Calculadora)->anbima());
		// dd( (new \App\Models\Calculadora)->catalog() );

		$cache_minutos = 60*24*7;
		$cache_key = 'catalog';
		$return['catalog'] = (new \App\Models\Calculadora)->catalog();
		// $return['catalog'] = \Cache::remember($cache_key, $cache_minutos, function() {
		// 	return (new \App\Models\Calculadora)->catalog();
		// });

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