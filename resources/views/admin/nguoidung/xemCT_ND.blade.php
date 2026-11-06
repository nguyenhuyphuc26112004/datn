@extends('admin.layouts.master')

@section('title', 'Chi tiết người dùng #' . $user->id)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/nguoiDung/read.css') }}">
@endpush

@section('content')
    <div class="user-read-header">
        <div class="user-read-title-group">
            <h1><i class="fa-solid fa-address-card"></i> Thông tin chi tiết tài khoản</h1>
            <p>Xem toàn bộ thông tin tài khoản và vai trò quyền hạn trong hệ thống.</p>
        </div>
        <div>
            <a href="{{ route('admin.user.index') }}" class="btn-reset-action">
                <i class="fa-solid fa-arrow-left"></i> Trở về danh sách
            </a>
        </div>
    </div>

    <div class="user-read-card">
        <div class="user-read-card-header">
            <div class="user-read-header-inner">
                <span class="user-read-id-badge">#ID: {{ $user->id }}</span>
                <strong class="user-read-card-title">Hồ sơ thành viên</strong>
            </div>
            @if(Auth::check() && Auth::user()->id_vai_tro == 2)
                <a href="{{ route('admin.user.edit', $user->id) }}" class="btn-action btn-action-edit">
                    <i class="fa-solid fa-pen-to-square"></i> Sửa thông tin
                </a>
            @endif
        </div>

        <div class="user-read-body">
            <table class="user-read-table">
                <tbody>
                    <tr>
                        <th>ID Người dùng</th>
                        <td><strong>#{{ $user->id }}</strong></td>
                    </tr>
                    <tr>
                        <th>Họ và tên</th>
                        <td>
                            <strong style="font-size: 15px; color: #0f172a;">{{ $user->ho_ten ?? 'Chưa thiết lập' }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Giới tính</th>
                        <td>
                            @if(isset($user->gioi_tinh))
                                @if($user->gioi_tinh == 'Nam')
                                    <span class="gender-badge-nam">Nam</span>
                                @elseif($user->gioi_tinh == 'Nữ')
                                    <span class="gender-badge-nu">Nữ</span>
                                @endif
                            @else
                                <span class="gender-empty">Chưa cập nhật</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Email liên hệ</th>
                        <td>
                            <a href="mailto:{{ $user->email }}" class="email-link">
                                {{ $user->email }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Tên đăng nhập</th>
                        <td>
                            <span class="username-tag">
                                {{ $user->ten_dang_nhap }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Mật khẩu</th>
                        <td>
                            <span class="password-masked">•••••••• (Được bảo mật)</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái hoạt động</th>
                        <td>
                            @if(isset($user->trang_thai) && (int)$user->trang_thai === 0)
                                <span class="status-badge-locked">
                                    <i class="fa-solid fa-lock" style="font-size: 12px;"></i> Đang bị khóa
                                </span>
                            @else
                                <span class="status-badge-active">
                                    <i class="fa-solid fa-circle-check" style="font-size: 12px;"></i> Đang hoạt động
                                </span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="user-read-actions">
                <a href="{{ route('admin.user.index') }}" class="btn-reset-action">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại
                </a>
                @if(Auth::check() && Auth::user()->id_vai_tro == 2)
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn-cta-primary">
                        <i class="fa-solid fa-pen-to-square"></i> Cập nhật người dùng
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection