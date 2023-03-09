<?php

namespace App\Http\Controllers;

use App\Http\Requests\Paciente\CreateRequest;
use App\Http\Requests\Paciente\UpadateRequest;
use App\Http\Resources\PacienteResource;
use App\Models\Paciente;
use Core\UseCase\Paciente\PacienteUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PacienteUseCase $paciente)
    {
        return $paciente->listarPacientes($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $pacienteRequest, PacienteUseCase $paciente)
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
    public function update(UpadateRequest $pacienteRequest, PacienteUseCase $paciente, $id)
    {

        $response = $paciente->alterarPaciente($pacienteRequest->toArray(), $id);

        return (new PacienteResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        //
    }
}
