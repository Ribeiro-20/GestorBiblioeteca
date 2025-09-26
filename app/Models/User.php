<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

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
}
