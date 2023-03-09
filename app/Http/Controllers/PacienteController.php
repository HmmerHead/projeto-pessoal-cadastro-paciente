<?php

namespace App\Http\Controllers;

use App\Http\Requests\Paciente\CreateRequest;
use App\Http\Requests\Paciente\UpadateRequest;
use App\Http\Resources\PacienteResource;
use App\Models\Paciente;
use Core\UseCase\Paciente\CreatePacienteUseCase;
use Illuminate\Http\Response;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd('dsadsa');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $pacienteRequest, CreatePacienteUseCase $paciente)
    {
        $response = $paciente->salvarPaciente($pacienteRequest->toArray());

        return (new PacienteResource($response))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpadateRequest $pacienteRequest, Paciente $paciente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        //
    }
}
