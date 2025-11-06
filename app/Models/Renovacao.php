<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renovacao extends Model
{
    use HasFactory;

    protected $table = 'renovacoes';

    protected $fillable = [
        'emprestimo_id',
        'data_renovacao',
        'nova_data_limite'
    ];

    protected $casts = [
        'data_renovacao' => 'date',
        'nova_data_limite' => 'date',
    ];

    /**
     * Relação com empréstimo
     */
    public function emprestimo()
    {
        return $this->belongsTo(Emprestimo::class);
    }
}
