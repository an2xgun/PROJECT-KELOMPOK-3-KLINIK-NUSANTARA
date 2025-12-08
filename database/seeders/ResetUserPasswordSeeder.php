<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ResetUserPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset all user passwords to "password"
        User::query()->update([
            'password' => Hash::make('password'),
        ]);
        
        echo "All user passwords reset to 'password'\n";
    }
}
