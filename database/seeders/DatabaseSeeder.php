<?php

namespace Database\Seeders;

use App\Enums\Role;
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
        $this->call([
            RoomTypeSeeder::class,
        ]);

        if (App::environment('local')) {
            User::factory()
                ->count(10)
                ->sequence(function (Sequence $sequence) {
                    $isAdmin = $sequence->index === 0;

                    $name = $isAdmin ? 'Admin' : "User {$sequence->index}";
                    $email = $isAdmin ? 'admin' : "user-{$sequence->index}";
                    $role = $isAdmin ? Role::ADMINISTRATOR : Role::USER;

                    return [
                        'name' => $name,
                        'email' => "{$email}@example.com",
                        'role' => $role,
                    ];
                })
                ->create();
        }
    }
}
