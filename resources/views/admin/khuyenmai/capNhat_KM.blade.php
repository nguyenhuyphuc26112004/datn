@extends('admin.layouts.master')

@section('title', 'Sửa mã khuyến mãi')

@push('styles')
<style>
    .form-card { background: #fff; border-radius: 14px; border: 1px solid var(--border-color); padding: 28px 32px; margin-bottom: 24px; }
    .form-section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #64748b; margin-bottom: 18px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 13px; font-weight: 600; color: #374151; }
    .form-group label span.required { color: #dc2626; margin-left: 2px; }
    .form-control { padding: 10px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-family: inherit; color: #0f172a; transition: border-color 0.15s; outline: none; }
    .form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .form-control.is-invalid { border-color: #dc2626; }
    .invalid-feedback { font-size: 12px; color: #dc2626; }
    .form-hint { font-size: 12px; color: #94a3b8; }
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; }
    .ma-badge-static { display: inline-block; padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; font-size: 22px; font-weight: 800; letter-spacing: 2px; font-family: monospace; }
    .input-group-addon { display: flex; align-items: center; }
    .input-group-addon input { border-radius: 8px 0 0 8px; border-right: none; flex: 1; }
    .input-group-addon .addon { padding: 10px 12px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; font-size: 13px; font-weight: 600; color: #64748b; }
</style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa mã khuyến mãi</h1>
            <p>Cập nhật thông tin, thời hạn và trạng thái của mã khuyến mãi.</p>
        </div>
        <a href="{{ route('admin.khuyenmai.index') }}" class="btn-reset-action">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if($errors->any())
        <div class="alert-box alert-box-error">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div>
                <strong>Vui lòng kiểm tra lại:</strong>
                <ul style="margin: 4px 0 0 16px; padding: 0;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Hiển thị mã (không cho sửa) -->
    <div style="background: #fff; border-radius: 14px; border: 1px solid var(--border-color); padding: 20px 28px; margin-bottom: 24px; display: flex; align-items: center; gap: 20px;">
        <span class="ma-badge-static">{{ $khuyenMai->ma_khuyen_mai }}</span>
        <div>
            <p style="margin: 0; font-size: 13px; color: #64748b;">Mã khuyến mãi (không thể thay đổi)</p>
            <p style="margin: 4px 0 0; font-size: 13px;">
                Đã dùng: <strong style="color: #d97706;">{{ $khuyenMai->da_dung }}</strong> / {{ $khuyenMai->so_luong }} lượt
            </p>
        </div>
    </div>

    <form action="{{ route('admin.khuyenmai.update', $khuyenMai->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Thông tin cơ bản -->
        <div class="form-card">
            <div class="form-section-title"><i class="fa-solid fa-circle-info" style="color: #6366f1;"></i> Thông tin chương trình</div>

            <div class="form-grid" style="margin-bottom: 20px;">
                <div class="form-group">
                    <label for="ten_chuong_trinh">Tên chương trình <span class="required">*</span></label>
                    <input type="text" id="ten_chuong_trinh" name="ten_chuong_trinh"
                           class="form-control {{ $errors->has('ten_chuong_trinh') ? 'is-invalid' : '' }}"
                           value="{{ old('ten_chuong_trinh', $khuyenMai->ten_chuong_trinh) }}"
                           placeholder="VD: Khuyến mãi Hè 2026">
                    @error('ten_chuong_trinh')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="trang_thai">Trạng thái</label>
                    <select id="trang_thai" name="trang_thai" class="form-control">
                        <option value="1" {{ old('trang_thai', $khuyenMai->trang_thai) == 1 ? 'selected' : '' }}>✅ Đang kích hoạt</option>
                        <option value="0" {{ old('trang_thai', $khuyenMai->trang_thai) == 0 ? 'selected' : '' }}>⛔ Vô hiệu hóa</option>
                    </select>
                </div>
            </div>

            <!-- Loại giảm (chỉ đọc vì đã được tạo) -->
            <div class="form-grid-3">
                <div class="form-group">
                    <label>Loại giảm</label>
                    <input type="text" class="form-control" readonly style="background: #f8fafc;"
                           value="{{ $khuyenMai->loai_giam === 'phan_tram' ? 'Giảm theo % (Phần trăm)' : 'Giảm tiền mặt trực tiếp' }}">
                    <input type="hidden" name="loai_giam" value="{{ $khuyenMai->loai_giam }}">
                </div>

                <div class="form-group">
                    <label for="gia_tri_giam">Giá trị giảm <span class="required">*</span></label>
                    <div class="input-group-addon">
                        <input type="number" id="gia_tri_giam" name="gia_tri_giam"
                               class="form-control {{ $errors->has('gia_tri_giam') ? 'is-invalid' : '' }}"
                               value="{{ old('gia_tri_giam', $khuyenMai->gia_tri_giam) }}" min="1" step="0.01">
                        <span class="addon">{{ $khuyenMai->loai_giam === 'phan_tram' ? '%' : 'đ' }}</span>
                    </div>
                    @error('gia_tri_giam')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" @if($khuyenMai->loai_giam !== 'phan_tram') style="opacity: 0.4; pointer-events: none;" @endif>
                    <label for="giam_toi_da">Giảm tối đa (đ)</label>
                    <input type="number" id="giam_toi_da" name="giam_toi_da"
                           class="form-control"
                           value="{{ old('giam_toi_da', $khuyenMai->giam_toi_da) }}" min="1"
                           placeholder="Bỏ trống = không giới hạn">
                    <span class="form-hint">Chỉ áp dụng cho giảm %</span>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <div class="form-group" style="max-width: 300px;">
                    <label for="don_hang_toi_thieu">Đơn tối thiểu (đ)</label>
                    <input type="number" id="don_hang_toi_thieu" name="don_hang_toi_thieu"
                           class="form-control"
                           value="{{ old('don_hang_toi_thieu', $khuyenMai->don_hang_toi_thieu) }}" min="0">
                </div>
            </div>
        </div>

        <!-- Số lượng và thời gian -->
        <div class="form-card">
            <div class="form-section-title"><i class="fa-solid fa-calendar-days" style="color: #6366f1;"></i> Số lượng & Thời gian</div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label for="so_luong">Tổng số lượt <span class="required">*</span></label>
                    <input type="number" id="so_luong" name="so_luong"
                           class="form-control {{ $errors->has('so_luong') ? 'is-invalid' : '' }}"
                           value="{{ old('so_luong', $khuyenMai->so_luong) }}" min="{{ $khuyenMai->da_dung }}">
                    @error('so_luong')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <span class="form-hint">Tối thiểu: {{ $khuyenMai->da_dung }} (đã dùng)</span>
                </div>

                <div class="form-group">
                    <label for="ngay_bat_dau">Ngày bắt đầu <span class="required">*</span></label>
                    <input type="datetime-local" id="ngay_bat_dau" name="ngay_bat_dau"
                           class="form-control {{ $errors->has('ngay_bat_dau') ? 'is-invalid' : '' }}"
                           value="{{ old('ngay_bat_dau', $khuyenMai->ngay_bat_dau->format('Y-m-d\TH:i')) }}">
                    @error('ngay_bat_dau')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ngay_ket_thuc">Ngày kết thúc <span class="required">*</span></label>
                    <input type="datetime-local" id="ngay_ket_thuc" name="ngay_ket_thuc"
                           class="form-control {{ $errors->has('ngay_ket_thuc') ? 'is-invalid' : '' }}"
                           value="{{ old('ngay_ket_thuc', $khuyenMai->ngay_ket_thuc->format('Y-m-d\TH:i')) }}">
                    @error('ngay_ket_thuc')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.khuyenmai.index') }}" class="btn-reset-action">Hủy bỏ</a>
            <button type="submit" class="btn-cta-primary">
                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
            </button>
        </div>
    </form>
@endsection
