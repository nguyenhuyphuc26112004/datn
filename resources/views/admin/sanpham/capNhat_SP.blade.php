@extends('admin.layouts.master')

@section('title', 'Chỉnh sửa sản phẩm - ' . $product->ten)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/sanPham/edit.css') }}">
@endpush

@section('content')
    <div class="product-edit-header">
        <div class="product-edit-title-group">
            <h1><i class="fa-solid fa-pen-to-square"></i> Cập nhật thông tin sản phẩm</h1>
            <p>Chỉnh sửa hình ảnh, danh mục, đơn giá bán lẻ, trạng thái và mô tả của sản phẩm #{{ $product->id }}.</p>
        </div>
        <div>
            <a href="{{ route('admin.sanpham.index') }}" class="btn-reset-action">
                <i class="fa-solid fa-arrow-left"></i> Trở về danh sách
            </a>
        </div>
    </div>

    <div class="product-edit-card">
        <div class="product-edit-card-header">
            <span class="product-edit-id-badge">ID: #{{ $product->id }}</span>
            <span class="product-edit-card-title">Chỉnh sửa sản phẩm: {{ $product->ten }}</span>
        </div>

        <div class="product-edit-body">
            <form action="{{ route('admin.sanpham.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="product-form-grid">
                    
                    <!-- Tên sản phẩm -->
                    <div class="product-form-group">
                        <label for="tenSanPham">Tên sản phẩm <span class="required">*</span></label>
                        <input type="text" id="tenSanPham" name="tenSanPham" 
                               value="{{ old('tenSanPham', $product->ten) }}" 
                               placeholder="Nhập tên sản phẩm...">
                        @error('tenSanPham')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phân loại danh mục -->
                    <div class="product-form-group">
                        <label for="loai">Phân loại danh mục <span class="required">*</span></label>
                        <select id="loai" name="loai">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ old('loai', $product->loai) == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('loai')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Đơn vị tính -->
                    <div class="product-form-group">
                        <label for="don_vi">Đơn vị tính <span class="required">*</span></label>
                        <select id="don_vi" name="don_vi">
                            @foreach ($units as $unit)
                                <option value="{{ $unit }}" {{ old('don_vi', $product->don_vi) == $unit ? 'selected' : '' }}>
                                    {{ $unit }}
                                </option>
                            @endforeach
                        </select>
                        @error('don_vi')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Đơn giá bán lẻ -->
                    <div class="product-form-group">
                        <label for="gia">Đơn giá bán lẻ (VNĐ) <span class="required">*</span></label>
                        <input type="number" id="gia" name="gia" 
                            value="{{ old('gia', $product->gia) }}" min="0" step="any"
                            placeholder="Nhập giá bán...">
                        @error('gia')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tổng số lượng tồn kho -->
                    <div class="product-form-group">
                        <label for="soLuong">Tổng số lượng tồn kho</label>
                        <input type="number" id="soLuong" name="soLuong" readonly
                               value="{{ old('soLuong', $product->so_luong) }}"
                               class="input-readonly">
                        <small class="form-hint">Tự đồng bộ. Dùng tính năng <b>Nhập kho</b> để tăng.</small>
                    </div>

                    <!-- Hạn sử dụng gần nhất -->
                    <div class="product-form-group">
                        <label for="ngay_het_han">Hạn sử dụng gần nhất (FEFO)</label>
                        <input type="date" id="ngay_het_han" name="ngay_het_han" readonly
                               value="{{ old('ngay_het_han', $product->ngay_het_han) }}"
                               class="input-readonly">
                        <small class="form-hint">Tự động đồng bộ từ các lô hàng còn tồn.</small>
                    </div>

                    <!-- Trạng thái kinh doanh -->
                    <div class="product-form-group full-width">
                        <label for="trang_thai">Trạng thái kinh doanh <span class="required">*</span></label>
                        <select id="trang_thai" name="trang_thai">
                            <option value="1" {{ old('trang_thai', $product->trang_thai) == 1 ? 'selected' : '' }}>
                                ✅ Còn hàng / Hiển thị bán
                            </option>
                            <option value="0" {{ old('trang_thai', $product->trang_thai) == 0 ? 'selected' : '' }}>
                                ⛔ Hết hàng / Tạm ẩn 
                            </option>
                        </select>
                        @error('trang_thai')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Mô tả chi tiết -->
                    <div class="product-form-group full-width">
                        <label for="mo_ta">Mô tả chi tiết sản phẩm <span class="required">*</span></label>
                        <textarea id="mo_ta" name="mo_ta" rows="3" 
                                  placeholder="Nhập thông tin giới thiệu, nguồn gốc, xuất xứ hoặc hướng dẫn bảo quản hoa quả...">{{ old('mo_ta', $product->mo_ta) }}</textarea>
                        @error('mo_ta')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Hình ảnh minh họa -->
                    <div class="product-form-group full-width">
                        <label>Hình ảnh minh họa</label>
                        <div class="image-upload-box">
                            <div class="image-preview-wrapper">
                                @if($product->anh)
                                    <img id="previewAnh" src="{{ asset('uploads/' . $product->anh) }}" alt="Preview">
                                @else
                                    <img id="previewAnh" src="{{ asset('anh/logo.jpg') }}" alt="Preview">
                                @endif
                            </div>
                            <div class="image-input-content">
                                <input type="file" name="anhSanPham" accept="image/*" onchange="previewImage(this)">
                                <small class="form-hint" style="display: block; margin-top: 4px;">Để trống nếu giữ nguyên hình ảnh hiện tại (Định dạng: JPG, PNG, JPEG).</small>
                            </div>
                        </div>
                        @error('anhSanPham')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="product-form-actions">
                    <a href="{{ route('admin.sanpham.index') }}" class="btn-reset-action">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-cta-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
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
                document.getElementById('previewAnh').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush