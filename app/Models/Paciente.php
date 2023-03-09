<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paciente';
    
    protected $fillable = [
        'id',
        'nome',
        'nomeMae',
        'cpf',
        'nascimento',
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'nome' => 'string',
        'nomeMae' => 'string',
        'cpf' => 'string',
        'nascimento' => 'datetime',
    ];

    public function CNS(): HasOne
    {
        return $this->hasOne(CNS::class, 'paciente_id');
    }
}
