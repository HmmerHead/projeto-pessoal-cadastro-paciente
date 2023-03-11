<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Foto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'foto';

    protected $fillable = [
        'id',
        'fotoPaciente',
        'paciente_id',
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'fotoPaciente' => 'string',
    ];

    public function Paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }
}
