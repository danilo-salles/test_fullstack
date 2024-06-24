<?php

namespace Database\Seeders;

use App\Models\Niveis;
use App\Models\Desenvolvedor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::factory(10)->create();
        Niveis::factory()->create();
        Desenvolvedor::factory(50)->create();
    }
}
