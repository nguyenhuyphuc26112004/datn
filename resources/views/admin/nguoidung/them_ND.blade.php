@extends('admin.layouts.master')

@section('title', 'Thêm người dùng mới')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/nguoiDung/add.css') }}">
@endpush

@section('content')
    <div class="user-add-header">
        <div class="user-add-title-group">
            <h1><i class="fa-solid fa-user-plus"></i> Thêm người dùng mới</h1>
            <p>Tạo mới tài khoản người dùng hoặc nhân viên quản lý trong hệ thống.</p>
        </div>
        <div>
            <a href="{{ route('admin.user.index') }}" class="btn-reset-action">
                <i class="fa-solid fa-arrow-left"></i> Trở về danh sách
            </a>
        </div>
    </div>

    <div class="user-add-card">
        <div class="user-add-card-header">
            <span class="user-add-card-title">Thông tin tài khoản mới</span>
        </div>

        <div class="user-add-body">
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf

                <div class="user-form-grid">
                    <div class="user-form-group full-width">
                        <label for="ho_ten">
                            Họ và tên <span class="required">*</span>
                        </label>
                        <input type="text" id="ho_ten" name="ho_ten" value="{{ old('ho_ten') }}" 
                               placeholder="Nhập họ và tên đầy đủ...">
                        @error('ho_ten')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="user-form-group full-width">
                        <label for="email">
                            Địa chỉ Email <span class="required">*</span>
                        </label>
                        <input type="text" id="email" name="email" value="{{ old('email') }}" 
                               placeholder="Nhập địa chỉ email...">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="user-form-group">
                        <label for="ten_dang_nhap">
                            Tên đăng nhập <span class="required">*</span>
                        </label>
                        <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" value="{{ old('ten_dang_nhap') }}" 
                               placeholder="Tên đăng nhập...">
                        @error('ten_dang_nhap')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="user-form-group">
                        <label for="mat_khau">
                            Mật khẩu <span class="required">*</span>
                        </label>
                        <input type="password" id="mat_khau" name="mat_khau" 
                               placeholder="Mật khẩu tối thiểu 8 ký tự...">
                        @error('mat_khau')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="user-form-group">
                        <label for="gioi_tinh">
                            Giới tính <span class="required">*</span>
                        </label>
                        <select id="gioi_tinh" name="gioi_tinh">
                            <option value="Nam" {{ old('gioi_tinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ old('gioi_tinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                        @error('gioi_tinh')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="user-form-group">
                        <label for="id_vai_tro">
                            Vai trò hệ thống <span class="required">*</span>
                        </label>
                        <select id="id_vai_tro" name="id_vai_tro">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('id_vai_tro') == $role->id ? 'selected' : '' }}>
                                    {{ $role->ten }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_vai_tro')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="user-form-actions">
                    <a href="{{ route('admin.user.index') }}" class="btn-reset-action">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-cta-primary">
                        <i class="fa-solid fa-user-plus"></i> Tạo người dùng
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection