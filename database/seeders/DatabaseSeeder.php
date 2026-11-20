<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VaiTro;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Khởi tạo các Vai trò mặc định (chỉ id và ten khớp với CSDL thực tế)
        $roles = [
            ['id' => 1, 'ten' => 'USER'],
            ['id' => 2, 'ten' => 'ADMIN'],
            ['id' => 3, 'ten' => 'STAFF'],
        ];

        foreach ($roles as $role) {
            $vaiTro = VaiTro::find($role['id']);
            if (!$vaiTro) {
                $vaiTro = new VaiTro();
                $vaiTro->id = $role['id'];
            }

            $vaiTro->ten = $role['ten'];
            $vaiTro->save();
        }

    }
}

