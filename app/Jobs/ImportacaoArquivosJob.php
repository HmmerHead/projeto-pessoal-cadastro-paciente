<?php

namespace App\Jobs;

use Core\UseCase\Paciente\PacienteUseCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;

class ImportacaoArquivosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected array $records, protected PacienteUseCase $paciente)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->paciente->salvarPaciente($this->records);
    }

    public function tags(): array
    {
        return ['csv-import', 'dados-paciente-'.$this->records['nome']];
    }
}
