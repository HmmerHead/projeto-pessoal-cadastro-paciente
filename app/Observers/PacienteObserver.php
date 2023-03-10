<?php

namespace App\Observers;

use App\Models\Paciente;
use Illuminate\Support\Facades\Cache;

class PacienteObserver
{
    /**
     * Handle the Paciente "created" event.
     */
    public function created(Paciente $paciente): void
    {
        Cache::store('redis')->forget($paciente::CACHE_LISTA_COMPLETA_PACIENTES);
    }

    /**
     * Handle the Paciente "updated" event.
     */
    public function updated(Paciente $paciente): void
    {
        Cache::store('redis')->forget($paciente::CACHE_LISTA_COMPLETA_PACIENTES);
    }

    /**
     * Handle the Paciente "deleted" event.
     */
    public function deleted(Paciente $paciente): void
    {
        Cache::store('redis')->forget($paciente::CACHE_LISTA_COMPLETA_PACIENTES);
    }

    /**
     * Handle the Paciente "restored" event.
     */
    public function restored(Paciente $paciente): void
    {
        //
    }

    /**
     * Handle the Paciente "force deleted" event.
     */
    public function forceDeleted(Paciente $paciente): void
    {
        //
    }
}
