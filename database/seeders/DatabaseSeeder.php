<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Artisan::call('shield:generate', ['--all' => true, '--panel' => 'admin', '--no-interaction' => true]);

        $role = Role::findOrCreate('super_admin');

        $user = User::create([
            'name' => 'Admin',
            'email' => '1@gmail.com',
            'password' => bcrypt('1'),
        ]);

        $user->assignRole($role);
    }
}
