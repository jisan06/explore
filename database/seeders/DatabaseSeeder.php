<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'prefixname' => fake()->randomElement(['Mr', 'Mrs', 'Ms']),
            'firstname' => fake()->name(),
            'middlename' => fake()->name(),
            'lastname' => fake()->name(),
            'suffixname' => fake()->name(),
            'username' => fake()->unique()->name(),
            'email' => fake()->unique()->safeEmail(),
            'photo' => fake()->imageUrl(),
            'type' => fake()->text(),
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);
    }
}
