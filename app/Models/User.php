<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'email',
        'password',
        'numero_cartao',
        'data_inscricao',
        'tipo',
    ];

    // Se não queres coluna remember_token no DB — impedimos que o guard tente usá-la:
    public function getRememberTokenName()
    {
        return null;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // noop: não guardar token
    }

    public function isAdmin() { return $this->tipo === 'admin'; }
    public function isBibliotecario() { return $this->tipo === 'bibliotecario'; }
    public function isLeitor() { return $this->tipo === 'leitor'; }
    
    /**
     * Verifica se pode gerenciar empréstimos (Admin ou Bibliotecário)
     */
    public function podeGerenciarEmprestimos()
    {
        return in_array($this->tipo, ['admin', 'bibliotecario']);
    }
    
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
}
