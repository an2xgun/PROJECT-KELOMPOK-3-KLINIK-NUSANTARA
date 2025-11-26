<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder lain (misal PoliklinikSeeder)
        $this->call([
            PoliklinikSeeder::class,
        ]);

        // Membuat 1 user default
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        // Jika butuh generate user banyak, bisa aktifkan ini
        // User::factory(10)->create();
    }
}
