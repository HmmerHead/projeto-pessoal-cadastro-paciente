<?php

use App\Http\Controllers\ConsultaEnderecoViaCepController;
use App\Http\Controllers\importarPacientesController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/pacientes', PacienteController::class);

    Route::post('/importarPacientes', importarPacientesController::class);
});

Route::get('/ConsultaEnderecoViaCep', ConsultaEnderecoViaCepController::class);