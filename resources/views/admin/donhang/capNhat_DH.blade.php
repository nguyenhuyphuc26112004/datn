@extends('admin.layouts.master')

@section('title', 'Cập nhật đơn hàng #' . $donHang->id)

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-pen-to-square"></i> Cập nhật đơn hàng #{{ $donHang->id }}</h1>
            <p>Thay đổi địa chỉ nhận hàng, số điện thoại, trạng thái vận chuyển và trạng thái thanh toán.</p>
        </div>
        <div>
            <a href="{{ route('admin.donhang.index') }}" class="btn-reset-action">
                <i class="fa-solid fa-arrow-left"></i> Trở về danh sách
            </a>
        </div>
    </div>

    <!-- Edit Order Card -->
    <div class="card-container" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header-tools" style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="product-id-badge" style="font-size: 14px; padding: 6px 12px;">Mã đơn: #{{ $donHang->id }}</span>
                <strong style="font-size: 15px; color: #0f172a;">Khách hàng: {{ $donHang->ten }} ({{ $donHang->sdt }})</strong>
            </div>
            <strong style="color: #d80f29ff; font-size: 16px;">
                Tổng tiền: {{ number_format($donHang->tong_tien, 0, ',', '.') }} đ
            </strong>
        </div>

        <div style="padding: 28px;">
            <form action="{{ route('admin.donhang.update', $donHang->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="idCheck" value="{{ $donHang->id }}">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Địa chỉ nhận hàng -->
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="diaChi" style="font-size: 14px; font-weight: 600; color: #334155;">
                            Địa chỉ nhận hàng <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="diaChi" name="diaChi" value="{{ old('diaChi', $donHang->dia_chi) }}" required
                               style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: 10px; font-size: 14px; outline: none; font-family: inherit;"
                               placeholder="Nhập địa chỉ nhận hàng...">
                        @error('diaChi')
                            <span style="color: #ef4444; font-size: 12px; font-weight: 500;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Số điện thoại -->
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="sdt" style="font-size: 14px; font-weight: 600; color: #334155;">
                            Số điện thoại <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="sdt" name="sdt" value="{{ old('sdt', $donHang->sdt) }}" required
                               style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: 10px; font-size: 14px; outline: none; font-family: inherit;"
                               placeholder="Nhập số điện thoại...">
                        @error('sdt')
                            <span style="color: #ef4444; font-size: 12px; font-weight: 500;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Trạng thái vận chuyển -->
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="trangThai" style="font-size: 14px; font-weight: 600; color: #334155;">
                            Trạng thái vận chuyển <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="trangThai" name="trangThai" 
                                style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: 10px; font-size: 14px; outline: none; background: #ffffff; font-family: inherit;">
                            @php
                                $trangThaiList = ['Đang chờ duyệt', 'Đã xác nhận', 'Đang vận chuyển', 'Đã nhận hàng', 'Giao hàng thất bại', 'Đã hủy'];
                                $currentStatus = old('trangThai', $donHang->trang_thai);
                            @endphp
                            @foreach($trangThaiList as $status)
                                <option value="{{ $status }}" {{ $currentStatus == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Trạng thái thanh toán -->
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="trangThaiThanhToan" style="font-size: 14px; font-weight: 600; color: #334155;">
                            Trạng thái thanh toán <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="trangThaiThanhToan" name="trangThaiThanhToan" 
                                style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: 10px; font-size: 14px; outline: none; background: #ffffff; font-family: inherit;">
                            @php
                                $currentPaymentStatus = old('trangThaiThanhToan', $donHang->trang_thai_thanh_toan);
                            @endphp
                            <option value="Chưa thanh toán" {{ $currentPaymentStatus == 'Chưa thanh toán' ? 'selected' : '' }}>
                                Chưa thanh toán
                            </option>
                            <option value="Đã thanh toán" {{ $currentPaymentStatus == 'Đã thanh toán' ? 'selected' : '' }}>
                                Đã thanh toán
                            </option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('admin.donhang.index') }}" class="btn-reset-action">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-cta-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tự động đổi Trạng thái thanh toán khi chọn Đã nhận hàng -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectTrangThai = document.getElementById('trangThai');
            const selectThanhToan = document.getElementById('trangThaiThanhToan');

            if (selectTrangThai && selectThanhToan) {
                selectTrangThai.addEventListener('change', function () {
                    if (this.value === 'Đã nhận hàng') {
                        selectThanhToan.value = 'Đã thanh toán';
                    }
                });
            }
        });
    </script>
@endsection