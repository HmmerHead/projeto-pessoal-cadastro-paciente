<?php

namespace App\Http\Requests\Paciente;

use Illuminate\Foundation\Http\FormRequest;

class UpadateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|min:3|max:255',
            'nomeMae' => 'required|min:3|max:255',
            'cpf' => 'required',
            'nascimento' => 'required',
            'cns' => 'required',
            'foto' => 'required',
            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'complemento' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
        ];
    }
}
