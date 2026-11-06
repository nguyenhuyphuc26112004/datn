@extends('admin.layouts.master')

@section('title', 'Thêm mã khuyến mãi')

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
    .invalid-feedback { font-size: 12px; color: #dc2626; margin-top: 2px; }
    .form-hint { font-size: 12px; color: #94a3b8; }
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; }
    .radio-group { display: flex; gap: 20px; flex-wrap: wrap; }
    .radio-option { display: flex; align-items: center; gap: 8px; padding: 10px 16px; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.15s; font-size: 14px; font-weight: 500; }
    .radio-option:hover { border-color: #6366f1; background: #faf5ff; }
    .radio-option input { accent-color: #6366f1; width: 16px; height: 16px; }
    .radio-option.selected { border-color: #6366f1; background: #f5f3ff; color: #4f46e5; }
    .preview-box { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 24px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 16px; }
    .preview-code { font-size: 26px; font-weight: 800; letter-spacing: 2px; font-family: monospace; }
    .preview-label { font-size: 13px; opacity: 0.85; margin-top: 4px; }
    .input-group-addon { display: flex; align-items: center; }
    .input-group-addon input { border-radius: 8px 0 0 8px; border-right: none; flex: 1; }
    .input-group-addon .addon { padding: 10px 12px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; font-size: 13px; font-weight: 600; color: #64748b; }
</style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-plus"></i> Tạo mã khuyến mãi mới</h1>
            <p>Thiết lập mã voucher giảm giá cho khách hàng áp dụng khi thanh toán đơn hàng.</p>
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

    <!-- Preview mã đang nhập -->
    <div class="preview-box">
        <i class="fa-solid fa-ticket-simple" style="font-size: 36px; opacity: 0.8;"></i>
        <div>
            <div class="preview-code" id="previewCode">NHẬP MÃ...</div>
            <div class="preview-label" id="previewLabel">Nhập thông tin bên dưới để xem trước</div>
        </div>
    </div>

    <form action="{{ route('admin.khuyenmai.store') }}" method="POST">
        @csrf

        <!-- Thông tin cơ bản -->
        <div class="form-card">
            <div class="form-section-title"><i class="fa-solid fa-circle-info" style="color: #6366f1;"></i> Thông tin cơ bản</div>

            <div class="form-grid" style="margin-bottom: 20px;">
                <div class="form-group">
                    <label for="ma_khuyen_mai">Mã khuyến mãi <span class="required">*</span></label>
                    <input type="text" id="ma_khuyen_mai" name="ma_khuyen_mai"
                           class="form-control {{ $errors->has('ma_khuyen_mai') ? 'is-invalid' : '' }}"
                           value="{{ strtoupper(old('ma_khuyen_mai')) }}"
                           placeholder="VD: GIAM20, HE2026, FREESHIP..."
                           style="text-transform: uppercase; letter-spacing: 1px; font-weight: 700;"
                           oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9_]/g, ''); updatePreview();">
                    @error('ma_khuyen_mai')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <span class="form-hint">Chỉ chữ IN HOA, số và dấu gạch dưới (_)</span>
                </div>

                <div class="form-group">
                    <label for="ten_chuong_trinh">Tên chương trình <span class="required">*</span></label>
                    <input type="text" id="ten_chuong_trinh" name="ten_chuong_trinh"
                           class="form-control {{ $errors->has('ten_chuong_trinh') ? 'is-invalid' : '' }}"
                           value="{{ old('ten_chuong_trinh') }}"
                           placeholder="VD: Khuyến mãi Hè 2026"
                           oninput="updatePreview();">
                    @error('ten_chuong_trinh')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Loại giảm -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Loại giảm giá <span class="required">*</span></label>
                <div class="radio-group">
                    <label class="radio-option {{ old('loai_giam', 'phan_tram') === 'phan_tram' ? 'selected' : '' }}" id="opt-phan-tram">
                        <input type="radio" name="loai_giam" value="phan_tram"
                               {{ old('loai_giam', 'phan_tram') === 'phan_tram' ? 'checked' : '' }}
                               onchange="toggleLoaiGiam('phan_tram')">
                        <i class="fa-solid fa-percent" style="color: #10b981;"></i> Giảm theo %
                    </label>
                    <label class="radio-option {{ old('loai_giam') === 'tien_mat' ? 'selected' : '' }}" id="opt-tien-mat">
                        <input type="radio" name="loai_giam" value="tien_mat"
                               {{ old('loai_giam') === 'tien_mat' ? 'checked' : '' }}
                               onchange="toggleLoaiGiam('tien_mat')">
                        <i class="fa-solid fa-dong-sign" style="color: #3b82f6;"></i> Giảm tiền trực tiếp
                    </label>
                </div>
            </div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label for="gia_tri_giam">Giá trị giảm <span class="required">*</span></label>
                    <div class="input-group-addon">
                        <input type="number" id="gia_tri_giam" name="gia_tri_giam"
                               class="form-control {{ $errors->has('gia_tri_giam') ? 'is-invalid' : '' }}"
                               value="{{ old('gia_tri_giam') }}" min="1" step="0.01"
                               placeholder="VD: 20">
                        <span class="addon" id="donViGiamLabel">%</span>
                    </div>
                    @error('gia_tri_giam')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" id="giamToiDaGroup">
                    <label for="giam_toi_da">Giảm tối đa (đ)</label>
                    <input type="number" id="giam_toi_da" name="giam_toi_da"
                           class="form-control"
                           value="{{ old('giam_toi_da') }}" min="1"
                           placeholder="VD: 50000 (bỏ trống = không giới hạn)">
                    <span class="form-hint">Áp dụng cho giảm theo %</span>
                </div>

                <div class="form-group">
                    <label for="don_hang_toi_thieu">Đơn tối thiểu (đ)</label>
                    <input type="number" id="don_hang_toi_thieu" name="don_hang_toi_thieu"
                           class="form-control"
                           value="{{ old('don_hang_toi_thieu', 0) }}" min="0"
                           placeholder="0 = Không yêu cầu">
                </div>
            </div>
        </div>

        <!-- Số lượng và thời gian -->
        <div class="form-card">
            <div class="form-section-title"><i class="fa-solid fa-calendar-days" style="color: #6366f1;"></i> Số lượng & Thời gian</div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label for="so_luong">Tổng số lượt phát hành <span class="required">*</span></label>
                    <input type="number" id="so_luong" name="so_luong"
                           class="form-control {{ $errors->has('so_luong') ? 'is-invalid' : '' }}"
                           value="{{ old('so_luong', 100) }}" min="1"
                           placeholder="VD: 100">
                    @error('so_luong')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ngay_bat_dau">Ngày bắt đầu <span class="required">*</span></label>
                    <input type="datetime-local" id="ngay_bat_dau" name="ngay_bat_dau"
                           class="form-control {{ $errors->has('ngay_bat_dau') ? 'is-invalid' : '' }}"
                           value="{{ old('ngay_bat_dau', now()->format('Y-m-d\TH:i')) }}">
                    @error('ngay_bat_dau')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ngay_ket_thuc">Ngày kết thúc <span class="required">*</span></label>
                    <input type="datetime-local" id="ngay_ket_thuc" name="ngay_ket_thuc"
                           class="form-control {{ $errors->has('ngay_ket_thuc') ? 'is-invalid' : '' }}"
                           value="{{ old('ngay_ket_thuc', now()->addDays(30)->format('Y-m-d\TH:i')) }}">
                    @error('ngay_ket_thuc')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.khuyenmai.index') }}" class="btn-reset-action">Hủy bỏ</a>
            <button type="submit" class="btn-cta-primary">
                <i class="fa-solid fa-floppy-disk"></i> Tạo mã khuyến mãi
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function updatePreview() {
        const ma = document.getElementById('ma_khuyen_mai').value || 'NHẬP MÃ...';
        const ten = document.getElementById('ten_chuong_trinh').value || 'Nhập thông tin bên dưới để xem trước';
        document.getElementById('previewCode').textContent = ma;
        document.getElementById('previewLabel').textContent = ten;
    }

    function toggleLoaiGiam(type) {
        const addonLabel = document.getElementById('donViGiamLabel');
        const giamToiDaGroup = document.getElementById('giamToiDaGroup');
        const optPT = document.getElementById('opt-phan-tram');
        const optTM = document.getElementById('opt-tien-mat');

        if (type === 'phan_tram') {
            addonLabel.textContent = '%';
            giamToiDaGroup.style.opacity = '1';
            giamToiDaGroup.style.pointerEvents = 'auto';
            optPT.classList.add('selected');
            optTM.classList.remove('selected');
        } else {
            addonLabel.textContent = 'đ';
            giamToiDaGroup.style.opacity = '0.4';
            giamToiDaGroup.style.pointerEvents = 'none';
            optTM.classList.add('selected');
            optPT.classList.remove('selected');
        }
    }

    // Init
    const initType = document.querySelector('input[name="loai_giam"]:checked')?.value || 'phan_tram';
    toggleLoaiGiam(initType);
</script>
@endpush
