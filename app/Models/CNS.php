<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CNS extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'CNS';

    protected $fillable = [
        'id',
        'cnsPaciente',
        'paciente_id'
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'cnsPaciente' => 'string',
    ];

    public function Paciente(): BelongsTo
    {
        return $this->belongsTo(CNS::class);
    }
}
