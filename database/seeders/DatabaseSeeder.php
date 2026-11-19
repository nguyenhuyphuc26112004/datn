<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\NguoiDung;
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

        // 2. Khởi tạo tài khoản Quản trị viên mặc định (nếu chưa có)
        if (!NguoiDung::where('ten_dang_nhap', 'admin')->exists()) {
            NguoiDung::create([
                'id_vai_tro'     => 2,
                'ho_ten'         => 'Quản Trị Viên',
                'gioi_tinh'      => 'nam',
                'email'          => 'admin@fruitshop.com',
                'ten_dang_nhap'  => 'admin',
                'mat_khau'       => Hash::make('Admin@123456'),
                'so_lan_sai'     => 0,
            ]);
        }
    }
}

