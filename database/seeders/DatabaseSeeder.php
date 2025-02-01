<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (App::environment('local')) {
            User::factory()
                ->count(10)
                ->sequence(function (Sequence $sequence) {
                    $name = $sequence->index === 0 ? 'Admin' : "User {$sequence->index}";
                    $email = $sequence->index === 0 ? 'admin' : "user-{$sequence->index}";

                    return [
                        'name' => $name,
                        'email' => "{$email}@example.com",
                    ];
                })
                ->create();
        }
    }
}
