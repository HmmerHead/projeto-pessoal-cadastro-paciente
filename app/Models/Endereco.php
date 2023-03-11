<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Endereco extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'endereco';

    protected $fillable = [
        'id',
        'cep',
        'endereço',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'paciente_id',
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'fotoPaciente' => 'string',
        'cep' => 'string',
        'endereço' => 'string',
        'numero' => 'string',
        'complemento' => 'string',
        'bairro' => 'string',
        'cidade' => 'string',
        'estado' => 'string',
        'paciente_id' => 'string',
    ];

    public function Paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }
}
