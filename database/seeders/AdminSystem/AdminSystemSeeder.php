<?php

namespace Database\Seeders\AdminSystem;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin\System\AdminSystemModel;
use Illuminate\Database\Seeder;

class AdminSystemSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $createdById = 1;
        $scmSystem = [
            [
                'name' => 'Sample System',
                'code' => 'SMPL-SYS',
            ],
        ];

        foreach ($scmSystem as $value) {
            AdminSystemModel::create([
                'name' => $value['name'],
                'code' => $value['code'],
                'created_by_id' => $createdById,
            ]);
        }
    }
}
