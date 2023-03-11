<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ConsultaEnderecoViaCepController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $cep = $request->get('cep');
        if (!preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $cep)) {
            throw new Exception('CEP invalido');
        }

        if (!Cache::store('redis')->has($cep)) {
            $result = collect(Http::get("viacep.com.br/ws/{$cep}/json/")->json());
            Cache::store('redis')->put($cep, $result, 300);
        }

        $result = Cache::store('redis')->get($cep);

        return response($result);
    }
}
