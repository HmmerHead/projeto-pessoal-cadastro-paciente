<?php

namespace App\Http\Requests\Paciente;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'foto' => 'required|file',
        ];
    }
}
