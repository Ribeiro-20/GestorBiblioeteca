<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'autor',
        'isbn',
        'categoria',
        'ano_publicacao',
        'imagem',
        'estado'
    ];

    protected $casts = [
        'ano_publicacao' => 'integer',
    ];

    /**
     * Relação com empréstimos
     */
    public function emprestimos()
    {
        return $this->hasMany(Emprestimo::class);
    }

    /**
     * Relação com reservas
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    /**
     * Verifica se o livro está disponível
     */
    public function isDisponivel()
    {
        return $this->estado === 'disponivel';
    }

    /**
     * Obtém o empréstimo ativo do livro
     */
    public function emprestimoAtivo()
    {
        return $this->hasOne(Emprestimo::class)->where('estado', 'ativo');
    }
}
