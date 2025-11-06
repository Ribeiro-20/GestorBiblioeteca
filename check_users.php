<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== USUÁRIOS NA BASE DE DADOS ===\n\n";

$users = App\Models\User::select('id', 'nome', 'email', 'tipo')->get();

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Nome: {$user->nome}\n";
    echo "Email: {$user->email}\n";
    echo "Tipo: {$user->tipo}\n";
    echo str_repeat('-', 40) . "\n";
}

echo "\nTotal: " . $users->count() . " usuários\n";
