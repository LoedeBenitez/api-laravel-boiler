<?php

namespace Database\Seeders\Auth;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CredentialModel;
use App\Models\UserModel;
use Illuminate\Database\Seeder;

class CredentialUserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'credential_id' => 1,
                'email' => "admin@flowc.com",
                'employee_id' => '0000',
                'prefix' => null,
                'suffix' => null,
                'first_name' => 'Admin',
                'middle_name' => null,
                'last_name' => 'Flowc',
                'position' => null
            ],
        ];



        foreach ($users as $value) {
            CredentialModel::create([
                'email' => $value['email'],
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            UserModel::create([
                'credential_id' => $value['credential_id'],
                'email' => $value['email'],
                'employee_id' => $value['employee_id'],
                'prefix' => $value['prefix'],
                'suffix' => $value['suffix'],
                'first_name' => $value['first_name'],
                'middle_name' => $value['middle_name'],
                'last_name' => $value['last_name'],
                'position' => $value['position']
            ]);
        }
    }
}
