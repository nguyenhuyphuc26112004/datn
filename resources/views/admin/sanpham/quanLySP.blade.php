@extends('admin.layouts.master')

@section('title', 'Quản lý sản phẩm')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/sanPham/list.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-boxes-stacked"></i> Quản lý sản phẩm</h1>
            <p>Quản lý kho hàng, thông tin sản phẩm, đơn giá, hạn sử dụng và trạng thái phân phối.</p>
        </div>
        
        <a href="{{ route('admin.sanpham.create', request()->query()) }}" class="btn-cta-primary">
            <i class="fa-solid fa-plus"></i> Thêm sản phẩm mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert-box alert-box-success">
            <i class="fa-solid fa-circle-check product-list-alert-icon"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-box alert-box-error">
            <i class="fa-solid fa-triangle-exclamation product-list-alert-icon"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Quick Summary Counters -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-wrapper product-list-stat-icon--total">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tổng sản phẩm</span>
                <span class="stat-value">{{ $products->total() ?? count($products) }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper product-list-stat-icon--available">
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
            <div class="stat-icon-wrapper product-list-stat-icon--out">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Hết hàng (Trang này)</span>
                <span class="stat-value">
                    {{ $products->filter(function($p) { return ($p->trang_thai ?? 0) == 0 || $p->so_luong <= 0; })->count() }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper product-list-stat-icon--expiry">
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
        <div class="card-header-tools product-list-header-tools">
            <div class="product-list-toolbar">
                <form action="{{ route('admin.sanpham.index') }}" method="GET" class="search-wrapper product-list-search">
                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                    @if(request('loai'))
                        <input type="hidden" name="loai" value="{{ request('loai') }}">
                    @endif
                    <div class="search-input-box product-list-search-box">
                        <i class="fa-solid fa-magnifying-glass product-list-search-icon"></i>
                        <input type="text" name="search" placeholder="Nhập ID, tên hoặc loại sản phẩm..." value="{{ request('search') }}" class="product-list-search-input">
                    </div>
                    <button type="submit" class="btn-search-action product-list-search-button">
                        <i class="fa-solid fa-filter"></i> Tìm kiếm
                    </button>
                    @if(request('search') || request('filter') || request('loai'))
                        <a href="{{ route('admin.sanpham.index') }}" class="btn-reset-action product-list-reset-button">
                            <i class="fa-solid fa-rotate-left"></i> Đặt lại
                        </a>
                    @endif
                </form>

                <div class="result-count-badge product-list-result-count">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Trang <strong>{{ $products->currentPage() ?? 1 }}</strong> / <strong>{{ $products->lastPage() ?? 1 }}</strong> (Tổng: <strong>{{ $products->total() ?? count($products) }}</strong> sản phẩm)</span>
                </div>
            </div>            
        </div>
        <div class="filter-tabs-wrapper product-list-filter-tabs">
            <a href="{{ route('admin.sanpham.index', ['search' => request('search'), 'loai' => request('loai')]) }}"
                class="tab-filter-btn product-list-filter-tab product-list-filter-tab--all {{ request('filter') == '' ? 'active' : '' }}">
                Tất cả
            </a>

            <a href="{{ route('admin.sanpham.index', ['filter' => 'con_han', 'search' => request('search'), 'loai' => request('loai')]) }}"
                class="tab-filter-btn product-list-filter-tab product-list-filter-tab--good {{ request('filter') == 'con_han' ? 'active' : '' }}">
                Còn hạn tốt
            </a>

            <a href="{{ route('admin.sanpham.index', ['filter' => 'sap_het_han', 'search' => request('search'), 'loai' => request('loai')]) }}"
                class="tab-filter-btn product-list-filter-tab product-list-filter-tab--near {{ request('filter') == 'sap_het_han' ? 'active' : '' }}">
                Sắp hết hạn
            </a>

            <a href="{{ route('admin.sanpham.index', ['filter' => 'qua_han', 'search' => request('search'), 'loai' => request('loai')]) }}"
                class="tab-filter-btn product-list-filter-tab product-list-filter-tab--expired {{ request('filter') == 'qua_han' ? 'active' : '' }}">
                Đã quá hạn
            </a>

            <a href="{{ route('admin.sanpham.index', ['filter' => 'het_hang', 'search' => request('search'), 'loai' => request('loai')]) }}"
                class="tab-filter-btn product-list-filter-tab product-list-filter-tab--out {{ request('filter') == 'het_hang' ? 'active' : '' }}">
                Hết hàng
            </a>

            @foreach($categories as $category)
                <a href="{{ route('admin.sanpham.index', ['filter' => request('filter'), 'search' => request('search'), 'loai' => $category]) }}"
                    class="tab-filter-btn product-list-filter-tab product-list-filter-tab--all {{ request('loai') === $category ? 'active' : '' }}">
                    {{ $category }}
                </a>
            @endforeach
        </div>

        <!-- Table Content -->
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="product-list-column-id">ID</th>
                        <th class="product-list-column-product">Sản phẩm & Danh mục</th>
                        <th>Giá bán</th>
                        <th class="product-list-column-centered">Hạn sử dụng</th>
                        <th class="product-list-column-expiry">Cảnh báo HSD</th>
                        <th class="product-list-column-stock">Tồn kho</th>
                        <th class="product-list-column-status">Trạng thái</th>
                        <th class="product-list-column-actions">Hành động</th>
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
                                <div class="product-list-product-cell">
                                    <div class="product-list-image">
                                        @if($product->anh)
                                            <img src="{{ asset('uploads/' . $product->anh) }}" alt="{{ $product->ten }}" onerror="this.src='{{ asset('anh/logo.jpg') }}'">
                                        @else
                                            <img src="{{ asset('anh/logo.jpg') }}" alt="No image">
                                        @endif
                                    </div>
                                    <div class="product-list-product-details">
                                        <div class="product-list-product-name">{{ $product->ten }}</div>
                                        <div class="product-list-product-unit">
                                            Đơn vị: <strong>{{ $product->don_vi ?? 'Kg' }}</strong>
                                        </div>
                                        <div class="product-list-product-category">
                                            {{ $product->loai ?? 'Trái cây' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="product-list-price" title="Giá bán lẻ">
                                    {{ auth()->user()->isAdmin() ? 'Bán:' : '' }} {{ number_format($product->gia, 0, ',', '.') }} đ
                                </span>
                            </td>
                            <td class="product-list-cell-centered">
                                @if($product->ngay_het_han)
                                    <span class="product-list-expiry-date" title="Hạn sử dụng lô gần nhất (FEFO)">
                                        {{ \Carbon\Carbon::parse($product->ngay_het_han)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="product-list-no-expiry">Không có</span>
                                @endif
                            </td>
                            <td class="product-list-cell-centered">
                                @if($isExpired)
                                    <span class="product-list-badge product-list-badge--expired">
                                        Đã quá hạn
                                    </span>
                                @elseif($isNearExpiry)
                                    <span class="product-list-badge product-list-badge--near">
                                        Sắp hết hạn
                                    </span>
                                @elseif($product->ngay_het_han)
                                    <span class="product-list-badge product-list-badge--good">
                                        Còn hạn
                                    </span>
                                @else
                                    <span class="product-list-muted">-</span>
                                @endif
                            </td>
                            <td class="product-list-cell-centered">
                                @if(($product->so_luong ?? 0) > 10)
                                    <span class="product-list-stock product-list-stock--available">
                                        {{ $product->so_luong }}
                                    </span>
                                @elseif(($product->so_luong ?? 0) > 0)
                                    <span class="product-list-stock product-list-stock--low">
                                        {{ $product->so_luong }}
                                    </span>
                                @else
                                    <span class="product-list-stock product-list-stock--empty">
                                        0
                                    </span>
                                @endif
                            </td>
                            <td class="product-list-cell-centered">
                                @if(($product->trang_thai ?? 1) == 1 && ($product->so_luong ?? 0) > 0)
                                    <span class="role-badge product-list-status product-list-status--available">
                                        Còn hàng
                                    </span>
                                @else
                                    <span class="role-badge product-list-status product-list-status--out">
                                        Hết hàng
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-group product-list-actions">
                                    <button type="button" class="btn-action product-list-import-button"
                                            onclick="openImportModal({{ $product->id }}, '{{ addslashes($product->ten) }}')" title="Nhập thêm hàng">
                                        <i class="fa-solid fa-boxes-stacked"></i> Nhập kho
                                    </button>

                                    <a class="btn-action btn-action-view product-list-action-button" href="{{ route('admin.sanpham.show', ['id' => $product->id] + request()->query()) }}" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye"></i> Xem
                                    </a>
                                    <a class="btn-action btn-action-edit product-list-action-button" href="{{ route('admin.sanpham.edit', ['id' => $product->id] + request()->query()) }}" title="Chỉnh sửa">
                                        <i class="fa-solid fa-pen-to-square"></i> Sửa
                                    </a>
                                    <button type="button" class="btn-action btn-action-delete product-list-delete-button" title="Xóa sản phẩm"
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
                                    @if(request('search') || request('filter') || request('loai'))
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

        @if($products->hasPages())
            <div class="card-footer-pagination">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

    <div id="importStockModal" class="modal-overlay">
        <div class="modal-card product-list-import-modal">
            <div class="product-list-import-icon">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            
            <h3 class="product-list-import-title">Nhập thêm Lô hàng vào kho</h3>
            <p class="modal-desc product-list-import-description">Sản phẩm: <strong id="importProductName" class="product-list-strong"></strong></p>

            <div class="product-list-import-notice">
                <i class="fa-solid fa-circle-info"></i> Lô mới được lưu độc lập, không ghi đè hạn của lô cũ. Hệ thống tự động xuất hàng theo FEFO (hết hạn sớm xuất trước).
            </div>

            <form id="importStockForm" method="POST" action="">
                @csrf
                <div class="product-list-import-fields">
                    <div>
                        <label class="product-list-form-label">Số lượng nhập <span class="product-list-required">*</span></label>
                        <input type="number" name="so_luong_nhap" min="1" required placeholder="Ví dụ: 30"
                            class="product-list-form-input">
                    </div>

                    <div>
                        <label class="product-list-form-label">Giá nhập của lô này (VNĐ) <span class="product-list-required">*</span></label>
                        <input type="number" id="importGiaNhap" name="gia_nhap_moi" min="0" step="any" required placeholder="Nhập giá mua theo hóa đơn..."
                            class="product-list-form-input">
                    </div>

                    <div>
                        <label class="product-list-form-label">Hạn sử dụng lô này <span class="product-list-required">*</span></label>
                        <input type="date" name="ngay_het_han_lo_moi" required
                            class="product-list-form-input">
                    </div>
                </div>

                <div class="modal-btn-actions">
                    <button type="button" class="modal-btn btn-modal-cancel" onclick="closeImportModal()">
                        Hủy bỏ
                    </button>
                    <button type="submit" class="modal-btn btn-modal-confirm product-list-import-confirm">
                        <i class="fa-solid fa-check"></i> Xác nhận nhập lô
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="deleteProductModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-icon-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            
            <h3>Xác nhận xóa sản phẩm</h3>
            <p class="modal-desc">Vui lòng kiểm tra kỹ thông tin ràng buộc trước khi thực hiện thao tác xóa.</p>

            <div id="modalAlert" class="product-list-delete-alert">
                <i class="fa-solid fa-circle-exclamation product-list-delete-alert-icon"></i>
                <div>
                    <strong>Không thể xóa sản phẩm này!</strong>
                    <p class="product-list-delete-alert-message">Sản phẩm này hiện đang nằm trong <b id="orderCount" class="product-list-delete-alert-count">0</b> đơn hàng trong hệ thống. Để bảo đảm tính chính xác của dữ liệu lịch sử và doanh thu, bạn không thể xóa sản phẩm này.</p>
                </div>
            </div>

            <div id="modalInfo" class="modal-box-info product-list-delete-info">
                Bạn có chắc chắn muốn xóa sản phẩm <strong id="productNameInfo" class="product-list-strong"></strong>?
                <div class="product-list-delete-warning">
                    <i class="fa-solid fa-circle-info"></i> Hành động này sẽ xóa vĩnh viễn sản phẩm và các giỏ hàng liên quan khỏi cơ sở dữ liệu.
                </div>
            </div>

            <div class="modal-btn-actions">
                <button type="button" class="modal-btn btn-modal-cancel" onclick="closeDeleteProductModal()">
                    Hủy bỏ
                </button>

                <form id="deleteProductForm" method="POST" action="" class="product-list-delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnConfirmDelete" class="modal-btn btn-modal-confirm product-list-delete-confirm">
                        <i class="fa-solid fa-trash-can"></i> Xóa vĩnh viễn
                    </button>
                </form>

                <button type="button" id="btnDisabledDelete" class="modal-btn product-list-delete-disabled" disabled>
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

        form.action = `/admin/san-pham/${productId}/nhap-kho${window.location.search}`;
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

        deleteForm.action = `/admin/san-pham/${productId}/xoa${window.location.search}`;

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