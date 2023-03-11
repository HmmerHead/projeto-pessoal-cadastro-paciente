<?php

namespace App\Observers;

use App\Models\Endereco;
use Illuminate\Support\Facades\Cache;

class EnderecoObserver
{
    /**
     * Handle the Endereco "created" event.
     */
    public function created(Endereco $endereco): void
    {
        Cache::store('redis')->forget($endereco->paciente_id . $endereco->cep);
    }

    /**
     * Handle the Endereco "updated" event.
     */
    public function updated(Endereco $endereco): void
    {
        Cache::store('redis')->forget($endereco->paciente_id . $endereco->cep);
    }

    /**
     * Handle the Endereco "deleted" event.
     */
    public function deleted(Endereco $endereco): void
    {
        Cache::store('redis')->forget($endereco->paciente_id . $endereco->cep);
    }

    /**
     * Handle the Endereco "restored" event.
     */
    public function restored(Endereco $endereco): void
    {
        //
    }

    /**
     * Handle the Endereco "force deleted" event.
     */
    public function forceDeleted(Endereco $endereco): void
    {
        //
    }
}
