<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/*

As rotas são geradas diretamente à partir dos controllers.
O nome dos arquivos funcionam como prefixos.
Os métodos que serão transformados em rotas devem começar com any, get, post, etc...
Por exemplo, para gerar a rota GET: "/api/teste/lista-dados" e POST: "/api/teste/salva-dados":

class TesteController extends Controller {
    
    public function getListaDados() {
        ...
    }

    public function postSalvaDados() {
        ...
    }

}

*/


$routes = [];
$paths = [app_path(implode(DIRECTORY_SEPARATOR, ['Http', 'Controllers']))];
foreach((new \Symfony\Component\Finder\Finder)->in($paths)->files() as $file) {
    $filename = str_replace('.php', '', $file->getFilename());
    $class= "\App\Http\Controllers\\{$filename}";

    $prefix = (string) \Str::of(str_replace('Controller', '', $filename))->kebab();
    
    $r = new \ReflectionClass($class);
    foreach ($r->getMethods() as $rmethod) {
        $method_name = $rmethod->getName();

        $ignore = [
            'getValidationFactory',
            'getMiddleware',
        ];

        if (in_array($method_name, $ignore)) continue;
        
        $route = [];
        $route['method'] = 'get';
        $route['route'] = [$prefix];
        $route['name'] = '';
        $route['class'] = $class;
        $route['call'] = ['Illuminate\Support\Facades\Route', 'get'];
        $route['callParams'] = [];

        foreach(['any', 'get', 'post', 'put'] as $method) {
            if (\Str::startsWith($method_name, $method)) {
                $paths = [$prefix, (string) \Str::of(str_replace($method, '', $method_name))->studly()->kebab()];
                $routeName = implode('-', $paths);

                foreach($rmethod->getParameters() as $param) {
                    if (in_array($param->name, ['request'])) continue;
                    $paths[] = '{'. $param->name .'}';
                }
                $routePath = implode('/', $paths);

                $route = ['method' => $method];
                $route['name'] = $routeName;
                $route['call'] = ['\Illuminate\Support\Facades\Route', $method];
                $route['callParams'] = [$routePath, "{$class}@{$method_name}"];
                $route['eval'] = implode('::', $route['call']) ."('". implode("', '", $route['callParams']) ."')";
                
                call_user_func_array($route['call'], $route['callParams']);
            }
        }
    }
}
