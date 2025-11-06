<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'livro_id',
        'user_id',
        'data_reserva',
        'estado'
    ];

    protected $casts = [
        'data_reserva' => 'date',
    ];

    /**
     * Relação com livro
     */
    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }

    /**
     * Relação com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica se a reserva está ativa
     */
    public function isAtiva()
    {
        return $this->estado === 'ativa';
    }
}
