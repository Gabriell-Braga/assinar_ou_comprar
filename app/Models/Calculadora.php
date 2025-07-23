<?php

namespace App\Models;

class Calculadora extends \Illuminate\Database\Eloquent\Model
{
    public function manutencao($params=[]) {

        $params = array_merge([
            'locaviaModelCode' => '',
            'period' => '',
            'franchise' => '',
        ], $params);
        
        // Importando dados
        $filename = realpath(resource_path('excel/manutencao.csv'));
        $rows = array_map('str_getcsv', file($filename));
        $header = false;

        foreach($rows as $i=>$row) {
            if ($i==0) {
                $header = array_map('trim', $row);
                unset($rows[$i]);
                continue;
            }
        }

        $_tonumber = function($value, $is_decimal=true) {
            $value = preg_replace('/[^0-9]/', '', $value);
            if ($is_decimal) {
                $value = $value? (intval($value)/100): 0;
            }
            return $value? $value: 0;
        };

        $franchise_period = $params['franchise'] * $params['period'];

        $return = [
            'franchise' => $params['franchise'],
            'period' => $params['period'],
            'franchise_period' => $franchise_period,
            'total' => 0,
            'total_monthly' => 0,
            'description' => '',
        ];

        // Convertendo strings para números
        foreach($rows as $i=>$row) {
            if ($params['locaviaModelCode'] != $row[1]) continue;

            $log = [];

            // colunas 2 ~ 18: valores km
            foreach(range(2, 18) as $i) {

                // se temos um km definido para este índice AND $km <= $periodo*$franchise AND temos um valor
                if ($km = $_tonumber($header[$i], false) AND $km <= $franchise_period AND $valor = $_tonumber($row[$i], true)) {
                    $log[] = [
                        'total' => $valor,
                        'valor' => trim($row[$i]),
                        'km' => trim($header[$i]),
                        'description' => trim("{$row[$i]} ({$header[$i]})"),
                    ];

                    if ($i==18) {
                        $pneu = $_tonumber($row[19]);
                        $log[] = [
                            'total' => $pneu,
                            'valor' => trim($pneu),
                            'km' => 0,
                            'description' => "Pneu ({$row[19]})",
                        ];
                    }
                }
            }

            $return['total'] = array_sum(array_column($log, 'total'));
            $return['total_monthly'] = ($return['total'] / $params['period']);
            $return['description'] = implode('<br>+ ', array_column($log, 'description'));
            $return['row'] = array_map(function($value, $index) use($header) {
                return "{$header[$index]}: {$value}";
            }, $row, array_keys($row));
            // $return['log'] = $log;
        }

        return $return;
    }

    public function depreciacao($marca, $period) {

        // CSV to array
        $filename = realpath(resource_path('excel/depreciacao.csv'));
        $rows = array_map('str_getcsv', file($filename));

        $_tonumber = function($value, $is_decimal=true) {
            $value = preg_replace('/[^0-9]/', '', $value);
            if ($is_decimal) {
                $value = $value? (intval($value)/100): 0;
            }
            return $value? $value: 0;
        };

        // Encontrando linha com marca==$slug
        $last = false;
        foreach([$marca, false] as $slug) {
            foreach($rows as $i => $row) {
                if ($slug==false AND $last) break;
                if ($slug != $row[0]) continue;
                if ($period >= intval($row[1])) {
                    $last = $row;
                }
            }
        }

        $return = [
            'montadora' => false,
            'period' => 0,
            'deprec_mes' => 0,
            'deprec_mes_str' => '',
            'deprec_contrato' => 0,
            'deprec_contrato_str' => '',
        ];

        if ($last) {
            $return['montadora'] = ($last[0]? $last[0]: 'Nenhuma');
            $return['period'] = intval($last[1]);
            $return['deprec_mes'] = $_tonumber($last[2]);
            $return['deprec_mes_str'] = $last[2];
            $return['deprec_contrato'] = $_tonumber($last[3]);
            $return['deprec_contrato_str'] = $last[3];
        }

        return $return;
    }
    
    public function anbima() {

        $curva = 'https://www.anbima.com.br/informacoes/est-termo/CZ-down.asp';
		$curva = \Illuminate\Support\Facades\Http::post($curva, [
            'idioma'=>'PT',
            'Dt_Ref'=>'',
            'saida'=>'csv',
        ])->body();

        $curva = explode("\n", $curva);
        $curva = explode(';', $curva[1]);
        $curva = is_array($curva)? $curva: [];

        $anbima = [
            'beta1' => (isset($curva[1])? $curva[1]: 0),
            'beta2' => (isset($curva[2])? $curva[2]: 0),
            'beta3' => (isset($curva[3])? $curva[3]: 0),
            'beta4' => (isset($curva[4])? $curva[4]: 0),
            'lambda1' => (isset($curva[5])? $curva[5]: 0),
            'lambda2' => (isset($curva[6])? $curva[6]: 0),
        ];
        
        $anbima = array_map(function($value) {
            return (float) str_replace(',', '.', trim($value));
        },  $anbima);
        
        return $anbima;
    }
    
    public function vehicleDetails($request=[]) {

        $request = array_merge([
            'slug' => "",
            'period' => 12,
            'franchise' => 1000,
            'upsellingOptions' => [],
		], $request);

        $vehicle = "https://apiportal-livre.unidas.com.br/api/Cms/vehicleDetail";
		$vehicle = \Illuminate\Support\Facades\Http::post($vehicle, $request)->json();
        
        return $vehicle;
    }
    
    public function fipe($request=[]) {
        $request = array_merge([
			'marca' => '',
			'codigo' => '',
			'ano' => '',
		], $request);

        $request['depreciacao'] = 0;
        $request['fipe'] = false;

        // Converte slugs de marca para ID da api Fipe
        if ($request['marca']=='volkswagen') { $request['marca'] = '59'; }
        else if ($request['marca']=='toyota') { $request['marca'] = '56'; }
        else if ($request['marca']=='honda') { $request['marca'] = '25'; }
        else if ($request['marca']=='fiat') { $request['marca'] = '21'; }
        else if ($request['marca']=='gm-chevrolet') { $request['marca'] = '23'; }
        else if ($request['marca']=='bmw') { $request['marca'] = '7'; }
        else if ($request['marca']=='jeep') { $request['marca'] = '29'; }
        else if ($request['marca']=='renault') { $request['marca'] = '48'; }
        else if ($request['marca']=='audi') { $request['marca'] = '6'; }

		// Dados de fipe
        $request['fipes'] = [];
        if ($request['marca'] AND $request['codigo']) {
            $fipes = "https://fipeapi.appspot.com/api/1/carros/veiculo/{$request['marca']}/{$request['codigo']}.json";
            $fipes = \Illuminate\Support\Facades\Http::get($fipes)->json();
            $fipes = is_array($fipes)? $fipes: [];
            $request['fipes'] = array_map(function($fipe) use($request) {
                $fipe_resp = "https://fipeapi.appspot.com/api/1/carros/veiculo/{$request['marca']}/{$request['codigo']}/{$fipe['fipe_codigo']}.json";
                $fipe_resp = \Illuminate\Support\Facades\Http::get($fipe_resp)->json();
                $fipe['preco'] = intval(preg_replace('/[^0-9]/', '', $fipe_resp['preco']))/100;
                return $fipe;
            }, $fipes);
        }

		$request['depreciacao'] = array_map(function($item) { return $item['preco']; }, $request['fipes']);
        $request['depreciacao'] = empty($request['depreciacao'])? 0: array_sum($request['depreciacao']) / count($request['depreciacao']);

		// Se não tem ano informado, pega o primeiro item da lista de anos
        if (!$request['ano'] AND isset($request['fipes'][0])) {
			$request['ano'] = $request['fipes'][0]['id'];
        }

		foreach($request['fipes'] as $fipe) {
            if ($fipe['id']==$request['ano']) {
                $request['fipe'] = $fipe;
            }
        }

        return $request;
    }

    public function simulation($params=[]) {
        $params = array_merge([
            'locaviaModelCode' => '',
            'period' => '',
            'franchise' => '',
            'tank' => false,
            'totalValue' => 0,
            'totalValueFinacing' => 0,
        ], $params);

        
        $simulation = "https://apiportal-livre.unidas.com.br/v1/Simulation";
        $simulation = \Illuminate\Support\Facades\Http::post($simulation, $params)->json();
        return (isset($simulation['success']) AND $simulation['success']==true)? $simulation['data']: false;
    }

    public function catalog($params=[]) {
        // dump('asd');
        // $catalog = 'https://apiportal-livre.unidas.com.br/api/Cms/Catalog';
		// $catalog = \Illuminate\Support\Facades\Http::post($catalog, ['partnershipPromotionalCode' => ''])->json();
        // $catalog = (isset($catalog['success']) AND $catalog['success']==true)? $catalog['data']: [];
        $catalog = [
            'items' => [
                'id' => uniqid(),
                'slug' => \Str::slug('Argo Drive 1.0 4P'),
                'title' => [
                    'rendered' => 'Argo Drive 1.0 4P'
                ],
                'acf' => [
                    'precificacao' => [
                        'fipeCode' => random_int(30,100),
                        'codigoLocavia' => 2480
                    ],
                    'informacoesModelo' => [
                        'marca' => [
                            'slug' => \Str::slug('Argo Drive 1.0 4P')
                        ]
                    ]
                ]
            ]
        ];
        // Só devem ser listados carros que tem código fipe3
        // $catalog = array_values(array_filter($catalog, function($item) {
        //     return (isset($item['acf']['precificacao']['fipeCode']) AND !!$item['acf']['precificacao']['fipeCode']);
        // }));

        // $catalog = array_map(function($item) {
        //     $item['price'] = 0;

        //     // $cache_minutos = 60*24*7;
        //     // $cache_key = 'simulation-'. md5(json_encode($cache_params));
        //     // $simulation = \Cache::remember($cache_key, $cache_minutos, function() {
        //     //     return $this->simulation([
        //     //         'locaviaModelCode' => $item['acf']['codigoLocavia'],
        //     //         'period' => 48,
        //     //         'franchise' => 1000,
        //     //     ]);
        //     // });

        //     // $simulation = $this->simulation([
        //     //     'locaviaModelCode' => $item['acf']['codigoLocavia'],
        //     //     'period' => 48,
        //     //     'franchise' => 1000,
        //     // ]);

        //     // if ($simulation) {
        //     //     $item['price'] = $simulation['financing']['totalValue'];
        //     // }
            
        //     // $item['price'] = $simulation;

        //     return $item;
        // }, $catalog);

        return $catalog;
    }
}