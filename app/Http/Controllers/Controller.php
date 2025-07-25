<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Calculadora Samy Dana",
 *      description="API Calculadora Samy Dana",
 *      @OA\Contact(email="jeferson.i.silva@gmail.com"),
 * )
 */