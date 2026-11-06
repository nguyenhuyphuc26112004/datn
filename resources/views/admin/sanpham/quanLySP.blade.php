@extends('admin.layouts.master')

@section('title', 'Quản lý sản phẩm')

@section('content')
    <!-- Page Header Title & Main Action -->
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-boxes-stacked"></i> Quản lý sản phẩm</h1>
            <p>Quản lý kho hàng, thông tin sản phẩm, đơn giá, hạn sử dụng và trạng thái phân phối.</p>
        </div>
        
        {{-- Chỉ Admin mới thấy nút Thêm sản phẩm mới --}}
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.sanpham.create') }}" class="btn-cta-primary">
                <i class="fa-solid fa-plus"></i> Thêm sản phẩm mới
            </a>
        @endif
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="alert-box alert-box-success">
            <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-box alert-box-error">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Quick Summary Counters -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #eff6ff; color: #2563eb;">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tổng sản phẩm</span>
                <span class="stat-value">{{ $products->total() ?? count($products) }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #ecfdf5; color: #10b981;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Còn hàng (Trang này)</span>
                <span class="stat-value">
                    {{ $products->filter(function($p) { return ($p->trang_thai ?? 1) == 1 && $p->so_luong > 0; })->count() }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fef2f2; color: #dc2626;">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Hết hàng (Trang này)</span>
                <span class="stat-value">
                    {{ $products->filter(function($p) { return ($p->trang_thai ?? 0) == 0 || $p->so_luong <= 0; })->count() }}
                </span>
            </div>
        </div>

        <!-- ĐÃ THAY MỤC LOẠI SẢN PHẨM THÀNH SỐ SẢN PHẨM SẮP HẾT HẠN -->
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fffbeb; color: #d97706;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Sắp hết hạn (Trang này)</span>
                <span class="stat-value">
                    {{ 
                        $products->filter(function($item) {
                            if (!$item->ngay_het_han) return false;
                            $hsd = \Carbon\Carbon::parse($item->ngay_het_han);
                            $today = \Carbon\Carbon::today();
                            return !$hsd->lt($today) && $hsd->diffInDays($today, false) >= -2 && $hsd->gte($today);
                        })->count() 
                    }}
                </span>
            </div>
        </div>
    </div>

    <div class="card-container">
        <div class="card-header-tools" style="flex-direction: column; align-items: stretch; gap: 14px; background: #ffffff; padding: 16px 20px; border-bottom: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; width: 100%;">
                <form action="{{ route('admin.sanpham.index') }}" method="GET" class="search-wrapper" style="margin: 0; display: flex; gap: 8px; align-items: center;">
                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                    <div class="search-input-box" style="display: flex; align-items: center; background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 6px 12px; gap: 8px; width: 280px;">
                        <i class="fa-solid fa-magnifying-glass" style="color: #64748b;"></i>
                        <input type="text" name="searchTerm" placeholder="Nhập ID, tên hoặc loại sản phẩm..." value="{{ request('searchTerm') ?? $searchTerm ?? '' }}" style="border: none; outline: none; width: 100%; font-size: 13px;">
                    </div>
                    <button type="submit" class="btn-search-action" style="padding: 8px 14px; background: #0f172a; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                        <i class="fa-solid fa-filter"></i> Tìm kiếm
                    </button>
                    @if(request('searchTerm') || request('filter'))
                        <a href="{{ route('admin.sanpham.index') }}" class="btn-reset-action" style="padding: 8px 12px; background: #e2e8f0; color: #334155; border-radius: 8px; font-size: 13px; text-decoration: none; font-weight: 600;">
                            <i class="fa-solid fa-rotate-left"></i> Đặt lại
                        </a>
                    @endif
                </form>

                <div class="result-count-badge" style="font-size: 13px; color: #64748b;">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Trang <strong>{{ $products->currentPage() ?? 1 }}</strong> / <strong>{{ $products->lastPage() ?? 1 }}</strong> (Tổng: <strong>{{ $products->total() ?? count($products) }}</strong> sản phẩm)</span>
                </div>
            </div>            
        </div>
        <div class="filter-tabs-wrapper" style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <a href="{{ route('admin.sanpham.index', ['searchTerm' => request('searchTerm')]) }}" 
                class="tab-filter-btn {{ request('filter') == '' ? 'active' : '' }}"
                style="padding: 6px 14px; border-radius: 20px; background: {{ request('filter') == '' ? '#0f172a' : '#ffffff' }}; color: {{ request('filter') == '' ? '#fff' : '#334155' }}; border: 1px solid {{ request('filter') == '' ? '#0f172a' : '#cbd5e1' }}; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s;">
                Tất cả
            </a>
            
            <a href="{{ route('admin.sanpham.index', ['filter' => 'con_han', 'searchTerm' => request('searchTerm')]) }}" 
                class="tab-filter-btn {{ request('filter') == 'con_han' ? 'active' : '' }}"
                style="padding: 6px 14px; border-radius: 20px; background: {{ request('filter') == 'con_han' ? '#047857' : '#ffffff' }}; color: {{ request('filter') == 'con_han' ? '#fff' : '#334155' }}; border: 1px solid {{ request('filter') == 'con_han' ? '#047857' : '#cbd5e1' }}; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s;">
                Còn hạn tốt
            </a>

            <a href="{{ route('admin.sanpham.index', ['filter' => 'sap_het_han', 'searchTerm' => request('searchTerm')]) }}" 
                class="tab-filter-btn {{ request('filter') == 'sap_het_han' ? 'active' : '' }}"
                style="padding: 6px 14px; border-radius: 20px; background: {{ request('filter') == 'sap_het_han' ? '#b45309' : '#ffffff' }}; color: {{ request('filter') == 'sap_het_han' ? '#fff' : '#334155' }}; border: 1px solid {{ request('filter') == 'sap_het_han' ? '#b45309' : '#cbd5e1' }}; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s;">
                Sắp hết hạn
            </a>

            <a href="{{ route('admin.sanpham.index', ['filter' => 'qua_han', 'searchTerm' => request('searchTerm')]) }}" 
                class="tab-filter-btn {{ request('filter') == 'qua_han' ? 'active' : '' }}"
                style="padding: 6px 14px; border-radius: 20px; background: {{ request('filter') == 'qua_han' ? '#991b1b' : '#ffffff' }}; color: {{ request('filter') == 'qua_han' ? '#fff' : '#334155' }}; border: 1px solid {{ request('filter') == 'qua_han' ? '#991b1b' : '#cbd5e1' }}; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s;">
                Đã quá hạn
            </a>

            <a href="{{ route('admin.sanpham.index', ['filter' => 'het_hang', 'searchTerm' => request('searchTerm')]) }}" 
                class="tab-filter-btn {{ request('filter') == 'het_hang' ? 'active' : '' }}"
                style="padding: 6px 14px; border-radius: 20px; background: {{ request('filter') == 'het_hang' ? '#dc2626' : '#ffffff' }}; color: {{ request('filter') == 'het_hang' ? '#fff' : '#334155' }}; border: 1px solid {{ request('filter') == 'het_hang' ? '#dc2626' : '#cbd5e1' }}; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s;">
                Hết hàng
            </a>
        </div>

        <!-- Table Content -->
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 10px;">ID</th>
                        <th style="min-width: 220px;">Sản phẩm & Danh mục</th>
                        <th>Giá bán</th>
                        <th style="text-align: center;">Hạn sử dụng</th>
                        <th style="Width: 100px; text-align: center;">Cảnh báo HSD</th>
                        <th style="width: 90px; text-align: center;">Tồn kho</th>
                        <th style="width: 100px; text-align: center;">Trạng thái</th>
                        <th style="width: 280px; text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        @php
                            $isExpired = false;
                            $isNearExpiry = false;
                            if ($product->ngay_het_han) {
                                $hsd = \Carbon\Carbon::parse($product->ngay_het_han);
                                $today = \Carbon\Carbon::today();
                                if ($hsd->lt($today)) {
                                    $isExpired = true;
                                } elseif ($hsd->diffInDays($today, false) >= -2 && $hsd->gte($today)) {
                                    $isNearExpiry = true;
                                }
                            }
                        @endphp
                        <tr>
                            <td>
                                <span class="product-id-badge">#{{ $product->id }}</span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 48px; height: 48px; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc; flex-shrink: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                        @if($product->anh)
                                            <img src="{{ asset('uploads/' . $product->anh) }}" alt="{{ $product->ten }}" onerror="this.src='{{ asset('anh/logo.jpg') }}'" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('anh/logo.jpg') }}" alt="No image" style="width: 100%; height: 100%; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 3px;">
                                        <div style="font-weight: 600; color: #0f172a; font-size: 13.5px;">{{ $product->ten }}</div>
                                        <div style="font-size: 11.5px; color: #64748b;">
                                            Đơn vị: <strong>{{ $product->don_vi ?? 'Kg' }}</strong>
                                        </div>
                                        <div style="font-size: 11.5px; color: #2563eb; font-weight: 500;">
                                            {{ $product->loai ?? 'Trái cây' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <span style="font-weight: 700; color: #d80f29ff; font-size: 13px;" title="Giá bán lẻ">
                                        {{ auth()->user()->isAdmin() ? 'Bán:' : '' }} {{ number_format($product->gia, 0, ',', '.') }} đ
                                    </span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if($product->ngay_het_han)
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 3px;">
                                        <span style="font-size: 12px; font-weight: 600; color: #475569;" title="Hạn sử dụng lô gần nhất (FEFO)">
                                            {{ \Carbon\Carbon::parse($product->ngay_het_han)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @else
                                    <span style="font-size: 12px; color: #94a3b8; font-style: italic;">Không có</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($isExpired)
                                    <span style="font-size: 11.5px; font-weight: 600; padding: 4px 8px; border-radius: 6px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; display: inline-block;">
                                        Đã quá hạn
                                    </span>
                                @elseif($isNearExpiry)
                                    <span style="font-size: 11.5px; font-weight: 600; padding: 4px 8px; border-radius: 6px; background: #fffbeb; color: #b45309; border: 1px solid #fde68a; display: inline-block;">
                                        Sắp hết hạn
                                    </span>
                                @elseif($product->ngay_het_han)
                                    <span style="font-size: 11.5px; font-weight: 600; padding: 4px 8px; border-radius: 6px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; display: inline-block;">
                                        Còn hạn
                                    </span>
                                @else
                                    <span style="font-size: 11.5px; color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if(($product->so_luong ?? 0) > 10)
                                    <span style="font-size: 12.5px; font-weight: 600; padding: 4px 8px; border-radius: 6px; background-color: #ecfdf5; color: #059669;">
                                        {{ $product->so_luong }}
                                    </span>
                                @elseif(($product->so_luong ?? 0) > 0)
                                    <span style="font-size: 12.5px; font-weight: 600; padding: 4px 8px; border-radius: 6px; background-color: #fffbeb; color: #b45309;">
                                        {{ $product->so_luong }}
                                    </span>
                                @else
                                    <span style="font-size: 12.5px; font-weight: 600; padding: 4px 8px; border-radius: 6px; background-color: #fef2f2; color: #dc2626;">
                                        0
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if(($product->trang_thai ?? 1) == 1 && ($product->so_luong ?? 0) > 0)
                                    <span class="role-badge" style="background-color: #dcfce7; color: #166534; padding: 4px 8px; font-size: 11.5px; border: 1px solid #bbf7d0;">
                                        Còn hàng
                                    </span>
                                @else
                                    <span class="role-badge" style="background-color: #fee2e2; color: #991b1b; padding: 4px 8px; font-size: 11.5px; border: 1px solid #fecaca;">
                                        Hết hàng
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-group" style="justify-content: flex-end; gap: 4px;">
                                    <button type="button" class="btn-action" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 5px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 600;"
                                            onclick="openImportModal({{ $product->id }}, '{{ addslashes($product->ten) }}')" title="Nhập thêm hàng">
                                        <i class="fa-solid fa-boxes-stacked"></i> Nhập kho
                                    </button>

                                    <a class="btn-action btn-action-view" href="{{ route('admin.sanpham.show', $product->id) }}" style="padding: 5px 8px; font-size: 11.5px; display: inline-flex; align-items: center; gap: 4px;" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye"></i> Xem
                                    </a>
                                    <a class="btn-action btn-action-edit" href="{{ route('admin.sanpham.edit', $product->id) }}" style="padding: 5px 8px; font-size: 11.5px; display: inline-flex; align-items: center; gap: 4px;" title="Chỉnh sửa">
                                        <i class="fa-solid fa-pen-to-square"></i> Sửa
                                    </a>
                                    <button type="button" class="btn-action btn-action-delete" style="padding: 5px 8px; font-size: 11.5px;" title="Xóa sản phẩm"
                                            onclick="openDeleteProductModal({{ $product->id }}, '{{ addslashes($product->ten) }}', {{ $product->orders_count ?? 0 }})">
                                        <i class="fa-solid fa-trash-can"></i> Xóa
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <h3>Không tìm thấy sản phẩm nào</h3>
                                    <p>Không có kết quả nào phù hợp với bộ lọc tìm kiếm của bạn. Hãy thử từ khóa khác.</p>
                                    @if(request('searchTerm') || request('filter'))
                                        <a href="{{ route('admin.sanpham.index') }}" class="btn-reset-action">
                                            <i class="fa-solid fa-rotate-left"></i> Xóa bộ lọc tìm kiếm
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($products, 'hasPages') && $products->hasPages())
            <div class="card-footer-pagination" style="padding: 16px 20px; border-top: 1px solid var(--border-color);">
                {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

    <!-- ==================== POPUP MODAL NHẬP THÊM LÔ HÀNG VÀO KHO ==================== -->
    <div id="importStockModal" class="modal-overlay">
        <div class="modal-card" style="max-width: 500px;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 20px; margin: 0 auto 16px;">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Nhập thêm Lô hàng vào kho</h3>
            <p class="modal-desc" style="font-size: 13px; color: #64748b; margin-bottom: 16px;">Sản phẩm: <strong id="importProductName" style="color: #0f172a;"></strong></p>

            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 12px; color: #166534; text-align: left;">
                <i class="fa-solid fa-circle-info"></i> Lô mới được lưu độc lập, không ghi đè hạn của lô cũ. Hệ thống tự động xuất hàng theo FEFO (hết hạn sớm xuất trước).
            </div>

            <form id="importStockForm" method="POST" action="">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 12px; text-align: left; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 12.5px; font-weight: 600; color: #334155; display: block; margin-bottom: 5px;">Số lượng nhập <span style="color: #ef4444;">*</span></label>
                        <input type="number" name="so_luong_nhap" min="1" required placeholder="Ví dụ: 30"
                            style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    </div>

                    <div>
                        <label style="font-size: 12.5px; font-weight: 600; color: #334155; display: block; margin-bottom: 5px;">Giá nhập của lô này (VNĐ) <span style="color: #ef4444;">*</span></label>
                        <input type="number" id="importGiaNhap" name="gia_nhap_moi" min="0" step="any" required placeholder="Nhập giá mua theo hóa đơn..."
                            style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    </div>

                    <div>
                        <label style="font-size: 12.5px; font-weight: 600; color: #334155; display: block; margin-bottom: 5px;">Hạn sử dụng lô này <span style="color: #ef4444;">*</span></label>
                        <input type="date" name="ngay_het_han_moi" required
                            style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none; background: #fff;">
                    </div>
                </div>

                <div class="modal-btn-actions">
                    <button type="button" class="modal-btn btn-modal-cancel" onclick="closeImportModal()">
                        Hủy bỏ
                    </button>
                    <button type="submit" class="modal-btn btn-modal-confirm" style="background: #2563eb;">
                        <i class="fa-solid fa-check"></i> Xác nhận nhập lô
                    </button>
                </div>
            </form>
        </div>
    </div>

    

    <!-- ==================== POPUP MODAL XÁC NHẬN XÓA (CHỈ ADMIN) ==================== -->
    <div id="deleteProductModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-icon-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            
            <h3>Xác nhận xóa sản phẩm</h3>
            <p class="modal-desc">Vui lòng kiểm tra kỹ thông tin ràng buộc trước khi thực hiện thao tác xóa.</p>

            <div id="modalAlert" style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 14px 16px; text-align: left; margin-bottom: 24px; display: none; gap: 12px; font-size: 13px; color: #9f1239; line-height: 1.5;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 18px; color: #e11d48; margin-top: 2px; flex-shrink: 0;"></i>
                <div>
                    <strong>Không thể xóa sản phẩm này!</strong>
                    <p style="margin-top: 4px;">Sản phẩm này hiện đang nằm trong <b id="orderCount" style="color: #be123c;">0</b> đơn hàng trong hệ thống. Để bảo đảm tính chính xác của dữ liệu lịch sử và doanh thu, bạn không thể xóa sản phẩm này.</p>
                </div>
            </div>

            <div id="modalInfo" class="modal-box-info" style="display: none;">
                Bạn có chắc chắn muốn xóa sản phẩm <strong id="productNameInfo" style="color: #0f172a;"></strong>?
                <div style="margin-top: 6px; font-size: 12px; color: #ef4444;">
                    <i class="fa-solid fa-circle-info"></i> Hành động này sẽ xóa vĩnh viễn sản phẩm và các giỏ hàng liên quan khỏi cơ sở dữ liệu.
                </div>
            </div>

            <div class="modal-btn-actions">
                <button type="button" class="modal-btn btn-modal-cancel" onclick="closeDeleteProductModal()">
                    Hủy bỏ
                </button>

                <form id="deleteProductForm" method="POST" action="" style="flex: 1; display: none;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnConfirmDelete" class="modal-btn btn-modal-confirm" style="width: 100%;">
                        <i class="fa-solid fa-trash-can"></i> Xóa vĩnh viễn
                    </button>
                </form>

                <button type="button" id="btnDisabledDelete" class="modal-btn" style="background: #e2e8f0; color: #94a3b8; cursor: not-allowed; display: none;" disabled>
                    <i class="fa-solid fa-lock"></i> Đang bị khóa xóa
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openImportModal(productId, productName) {
        const modal = document.getElementById('importStockModal');
        const form = document.getElementById('importStockForm');
        const nameSpan = document.getElementById('importProductName');
        const priceInput = document.getElementById('importGiaNhap');

        form.action = `/admin/san-pham/${productId}/nhap-kho`;
        nameSpan.innerText = productName;
        priceInput.value = '';

        modal.style.display = 'flex';
    }

    function closeImportModal() {
        document.getElementById('importStockModal').style.display = 'none';
    }

    

    function openDeleteProductModal(productId, productName, totalOrders) {
        const modal = document.getElementById('deleteProductModal');
        if (!modal) return;

        const modalAlert = document.getElementById('modalAlert');
        const modalInfo = document.getElementById('modalInfo');
        const deleteForm = document.getElementById('deleteProductForm');
        const btnDisabledDelete = document.getElementById('btnDisabledDelete');
        const orderCount = document.getElementById('orderCount');
        const productNameInfo = document.getElementById('productNameInfo');

        deleteForm.action = `/admin/san-pham/${productId}/xoa`;

        if (totalOrders > 0) {
            orderCount.innerText = totalOrders;
            modalAlert.style.display = 'flex';
            modalInfo.style.display = 'none';
            deleteForm.style.display = 'none';
            btnDisabledDelete.style.display = 'inline-block';
        } else {
            productNameInfo.innerText = `#${productId} - ${productName}`;
            modalAlert.style.display = 'none';
            modalInfo.style.display = 'block';
            deleteForm.style.display = 'block';
            btnDisabledDelete.style.display = 'none';
        }

        modal.style.display = 'flex';
    }

    function closeDeleteProductModal() {
        const modal = document.getElementById('deleteProductModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    window.onclick = function(event) {
        const deleteModal = document.getElementById('deleteProductModal');
        const importModal = document.getElementById('importStockModal');
        const batchModal  = document.getElementById('batchListModal');
        if (deleteModal && event.target === deleteModal) {
            closeDeleteProductModal();
        }
        if (event.target === importModal) {
            closeImportModal();
        }
        if (batchModal && event.target === batchModal) {
            closeBatchModal();
        }
    };

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeDeleteProductModal();
            closeImportModal();
            closeBatchModal();
        }
    });
</script>
@endpush