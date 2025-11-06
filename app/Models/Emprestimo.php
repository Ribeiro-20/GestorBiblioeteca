<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Emprestimo extends Model
{
    use HasFactory;

    protected $fillable = [
        'livro_id',
        'user_id',
        'data_emprestimo',
        'data_devolucao',
        'data_limite',
        'estado'
    ];

    protected $casts = [
        'data_emprestimo' => 'date',
        'data_devolucao' => 'date',
        'data_limite' => 'date',
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
     * Relação com renovações
     */
    public function renovacoes()
    {
        return $this->hasMany(Renovacao::class);
    }

    /**
     * Verifica se o empréstimo está atrasado
     */
    public function isAtrasado()
    {
        return $this->estado === 'ativo' && Carbon::now()->greaterThan($this->data_limite);
    }

    /**
     * Calcula dias de atraso
     */
    public function diasAtraso()
    {
        if (!$this->isAtrasado()) {
            return 0;
        }
        return Carbon::now()->diffInDays($this->data_limite);
    }
}
