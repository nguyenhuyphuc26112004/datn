@extends('admin.layouts.master')

@section('title', 'Cập nhật người dùng #' . $user->id)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/nguoiDung/edit.css') }}">
@endpush

@section('content')
    <div class="user-edit-header">
        <div class="user-edit-title-group">
            <h1><i class="fa-solid fa-user-pen"></i> Cập nhật thông tin người dùng</h1>
            <p>Chỉnh sửa họ tên, email, giới tính và phân quyền vai trò cho tài khoản #{{ $user->id }}.</p>
        </div>
        <div>
            <a href="{{ route('admin.user.index') }}" class="btn-reset-action">
                <i class="fa-solid fa-arrow-left"></i> Trở về danh sách
            </a>
        </div>
    </div>

    <div class="user-edit-card">
        <div class="user-edit-card-header">
            <div class="user-edit-header-inner">
                <span class="user-edit-id-badge">ID: #{{ $user->id }}</span>
                <span class="user-edit-card-title">Chỉnh sửa tài khoản ({{ $user->ten_dang_nhap }})</span>
            </div>
        </div>

        <div class="user-edit-body">
            <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="user-form-grid">
                    <div class="user-form-group">
                        <label for="ho_ten">
                            Họ và tên <span class="required">*</span>
                        </label>
                        <input type="text" id="ho_ten" name="ho_ten" value="{{ old('ho_ten', $user->ho_ten) }}" 
                               placeholder="Nhập họ và tên đầy đủ...">
                        @error('ho_ten')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="user-form-group">
                        <label for="email">
                            Địa chỉ Email <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                               placeholder="Nhập địa chỉ email...">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="user-form-group">
                        <label for="gioi_tinh">
                            Giới tính <span class="required">*</span>
                        </label>
                        <select id="gioi_tinh" name="gioi_tinh">
                            <option value="Nam" {{ old('gioi_tinh', $user->gioi_tinh) == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ old('gioi_tinh', $user->gioi_tinh) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
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
                                <option value="{{ $role->id }}" {{ old('id_vai_tro', $user->id_vai_tro) == $role->id ? 'selected' : '' }}>
                                    {{ $role->ten }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_vai_tro')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="user-form-group full-width">
                        <label for="trang_thai">
                            Trạng thái tài khoản <span class="required">*</span>
                        </label>
                        <select id="trang_thai" name="trang_thai">
                            <option value="1" {{ old('trang_thai', $user->trang_thai ?? 1) == 1 ? 'selected' : '' }}>
                                ✅ Hoạt động (Bình thường)
                            </option>
                            <option value="0" {{ old('trang_thai', $user->trang_thai ?? 1) == 0 ? 'selected' : '' }}>
                                🔒 Khóa tài khoản (Chặn đăng nhập)
                            </option>
                        </select>
                        @error('trang_thai')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="user-form-actions">
                    <a href="{{ route('admin.user.index') }}" class="btn-reset-action">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-cta-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection