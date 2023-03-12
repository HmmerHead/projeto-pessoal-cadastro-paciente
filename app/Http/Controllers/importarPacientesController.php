<?php

namespace App\Http\Controllers;

use App\Jobs\ImportacaoArquivosJob;
use Core\UseCase\Paciente\PacienteUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Csv\Reader;

class importarPacientesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, PacienteUseCase $paciente)
    {
        try {
            $csv = Reader::createFromPath($request->file('file'), 'r');

            if (empty($csv->nth(1))) {
                throw new \Exception('CSV sem os dados');
            }

            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();

            foreach ($records as $record) {
                ImportacaoArquivosJob::dispatch($record, $paciente);
            }

            return response('Informações enviadas para processamento');
        } catch (\Exception $th) {
            return response('Erro no processamento: '. $th->getMessage(), Response::HTTP_REQUEST_TIMEOUT);
        }
    }
}
