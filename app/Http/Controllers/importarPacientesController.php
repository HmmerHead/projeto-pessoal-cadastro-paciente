<?php

namespace App\Http\Controllers;

use League\Csv\Reader;
use App\Jobs\ImportacaoArquivosJob;
use Core\UseCase\Paciente\PacienteUseCase;
use Illuminate\Http\Request;

class importarPacientesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, PacienteUseCase $paciente)
    {
        
        $csv = Reader::createFromPath($request->file('file'), 'r');

        if (empty($csv->nth(1))){
            throw new \Exception('CSV sem os dados');
        }
        
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach ($records as $record) {        
            ImportacaoArquivosJob::dispatch($record, $paciente);
        }
    }
}
