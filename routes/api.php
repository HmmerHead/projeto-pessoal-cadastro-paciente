<?php

use App\Http\Controllers\ConsultaEnderecoViaCepController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('/pacientes', PacienteController::class);

Route::get('/ConsultaEnderecoViaCep', ConsultaEnderecoViaCepController::class);