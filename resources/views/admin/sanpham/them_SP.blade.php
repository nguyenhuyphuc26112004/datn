@extends('admin.layouts.master')

@section('title', 'Thêm sản phẩm mới')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/sanPham/add.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="product-add-header">
        <div class="product-add-title-group">
            <h1><i class="fa-solid fa-plus-circle"></i> Thêm sản phẩm mới</h1>
            <p>Tạo mới mặt hàng trong danh mục, cấu hình đơn giá bán lẻ, giá nhập, mô tả, hạn sử dụng lô đầu tiên và hình ảnh sản phẩm.</p>
        </div>
        <div>
            <a href="{{ route('admin.sanpham.index', request()->query()) }}" class="btn-reset-action">
                <i class="fa-solid fa-arrow-left"></i> Trở về danh sách
            </a>
        </div>
    </div>

    <!-- Create Product Card -->
    <div class="product-add-card">
        <div class="product-add-card-header">
            <span class="product-add-card-title">Thông tin sản phẩm mới</span>
        </div>

        <div class="product-add-body">
            <form action="{{ route('admin.sanpham.store', request()->query()) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="product-form-grid">
                    
                    <div class="product-form-group">
                        <label for="ten_san_pham">
                            Tên sản phẩm <span class="required">*</span>
                        </label>
                        <input type="text" id="ten_san_pham" name="ten_san_pham" value="{{ old('ten_san_pham') }}"
                               placeholder="Ví dụ: Táo Envy New Zealand...">
                        @error('ten_san_pham')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="product-form-group">
                        <label for="loai">
                            Phân loại danh mục <span class="required">*</span>
                        </label>
                        <select id="loai" name="loai">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('loai') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('loai')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- HÀNG 2: Số lượng nhập kho ban đầu & Đơn vị tính -->
                    <div class="product-form-group">
                        <label for="so_luong">
                            Số lượng nhập kho ban đầu <span class="required">*</span>
                        </label>
                        <input type="number" id="so_luong" name="so_luong" value="{{ old('so_luong') }}" min="1"
                               placeholder="Ví dụ: 50">
                        @error('so_luong')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="product-form-group">
                        <label for="don_vi">
                            Đơn vị tính <span class="required">*</span>
                        </label>
                        <select id="don_vi" name="don_vi">
                            @foreach($units as $unit)
                                <option value="{{ $unit }}" {{ old('don_vi') == $unit ? 'selected' : '' }}>
                                    {{ $unit }}
                                </option>
                            @endforeach
                        </select>
                        @error('don_vi')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- HÀNG 3: Giá nhập & Giá bán lẻ niêm yết -->
                    <div class="product-form-group">
                        <label for="giaNhap">
                            Giá nhập lô đầu tiên (VNĐ) <span class="required">*</span>
                        </label>
                        <input type="number" id="giaNhap" name="gia_nhap" value="{{ old('gia_nhap') }}" min="0" step="1000"
                               placeholder="Ví dụ: 30000">
                        @error('gia_nhap')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="product-form-group">
                        <label for="gia">
                            Đơn giá bán lẻ niêm yết (VNĐ) <span class="required">*</span>
                        </label>
                        <input type="number" id="gia" name="gia" value="{{ old('gia') }}" min="1000" step="1000"
                               placeholder="Ví dụ: 50000">
                        @error('gia')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="batch-initial-box full-width">
                        <div class="batch-initial-header">
                            <span class="batch-initial-title">
                                <i class="fa-solid fa-boxes-stacked" style="color: #2563eb;"></i> Thông tin Lô hàng ban đầu
                            </span>
                        </div>
                        <div class="batch-initial-grid">
                            <div class="product-form-group">
                                <label for="ma_lo">Mã lô hàng (Tự động tạo)</label>
                                <input type="text" id="ma_lo" name="ma_lo" value="Hệ thống tự sinh"
                                       class="batch-input-readonly" disabled>
                            </div>

                            <div class="product-form-group">
                                <label for="ngay_het_han">
                                    Hạn sử dụng của lô <span class="required">*</span>
                                </label>
                                <input type="date" id="ngay_het_han" name="ngay_het_han" value="{{ old('ngay_het_han') }}">
                                @error('ngay_het_han')
                                    <span class="error-small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- HÀNG 4: Mô tả chi tiết sản phẩm -->
                    <div class="product-form-group full-width">
                        <label for="mo_ta">
                            Mô tả chi tiết sản phẩm <span class="required">*</span>
                        </label>
                        <textarea id="mo_ta" name="mo_ta" rows="4" 
                                  placeholder="Nhập thông tin giới thiệu, nguồn gốc, xuất xứ hoặc hướng dẫn bảo quản hoa quả...">{{ old('mo_ta') }}</textarea>
                        @error('mo_ta')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="product-form-group full-width">
                        <label>
                            Hình ảnh sản phẩm <span class="required">*</span>
                        </label>
                        <div style="display: flex; align-items: center; gap: 16px; background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid var(--border-color);">
                            <div id="previewContainer" style="width: 70px; height: 70px; border-radius: 10px; overflow: hidden; border: 1px solid #cbd5e1; flex-shrink: 0; background: #ffffff; display: none;">
                                <img id="previewAnh" src="#" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>

                            <div style="flex: 1;">
                                <input type="file" name="anh_san_pham" accept="image/*" onchange="previewImage(this)" required style="font-size: 13px; color: #64748b;">
                                <small style="display: block; color: #94a3b8; margin-top: 4px;">Chọn tập tin hình ảnh từ máy tính (Định dạng: JPG, PNG, JPEG).</small>
                            </div>
                        </div>
                        @error('anh_san_pham')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="product-form-actions">
                    <a href="{{ route('admin.sanpham.index', request()->query()) }}" class="btn-reset-action">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-cta-primary">
                        <i class="fa-solid fa-plus"></i> Thêm sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var previewImg = document.getElementById('previewAnh');
                var previewContainer = document.getElementById('previewContainer');
                
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block'; // Hiện khung ảnh lên khi đã chọn file
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush