<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\App;

$app = require __DIR__ . '/bootstrap/app.php';

App::make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => 'password123',
    'role' => 'admin',
]);

echo "User created\n";
