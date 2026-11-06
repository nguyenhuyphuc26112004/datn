@extends('admin.layouts.master')

@section('title', 'Quản lý đơn hàng')

@push('styles')
<style>
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }

    .order-table th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 12px;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }

    .order-table td {
        padding: 12px 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }

    .order-table tr:last-child td {
        border-bottom: none;
    }

    .order-table tr:hover td {
        background-color: #f8fafc;
    }

    /* Badge trạng thái ngắn gọn, không icon thừa */
    .status-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        text-align: center;
    }

    .status-cho-duyet { background: #fef3c7; color: #92400e; }
    .status-dang-giao { background: #e0f2fe; color: #0284c7; }
    .status-hoan-tat  { background: #dcfce7; color: #166534; }
    .status-da-huy    { background: #fee2e2; color: #991b1b; }
    .status-tra-hang  { background: #ede9fe; color: #5b21b6; }
    .status-da-tra    { background: #f3e8ff; color: #7c3aed; }
.status-da-xac-nhan { background: #e0f7fa; color: #0369a1; }
.status-giao-hang-that-bai { background: #fee2e2; color: #991b1b; }

    .pay-paid   { background: #dcfce7; color: #15803d; }
    .pay-unpaid { background: #fef3c7; color: #b45309; }

    /* Nút thao tác nhỏ gọn */
    .action-btn-group {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
    }

    .btn-mini {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
        line-height: 1;
        gap: 4px;
    }

    .btn-mini-sp { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .btn-mini-sp:hover { background: #10b981; color: #ffffff; }

    .btn-mini-view { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
    .btn-mini-view:hover { background: #2563eb; color: #ffffff; }

    .btn-mini-edit { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
    .btn-mini-edit:hover { background: #d97706; color: #ffffff; }

    .btn-mini-disabled { background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; cursor: not-allowed; opacity: 0.8; }

    .btn-mini-delete { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }
    .btn-mini-delete:hover { background: #dc2626; color: #ffffff; }
</style>
@endpush

@section('content')
    <!-- Page Header Title -->
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-cart-flatbed"></i> Quản lý đơn hàng</h1>
            <p>Theo dõi quy trình đặt hàng, giao vận, thanh toán và thông tin khách mua.</p>
        </div>
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
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tổng đơn</span>
                <span class="stat-value">{{ $donHang->total() ?? count($donHang) }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fffbeb; color: #d97706;">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Chờ duyệt (Trang này)</span>
                <span class="stat-value">
                    {{ $donHang->where('trang_thai', 'Đang chờ duyệt')->count() }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #e0f2fe; color: #0284c7;">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Đang giao (Trang này)</span>
                <span class="stat-value">
                    {{ $donHang->where('trang_thai', 'Đang vận chuyển')->count() }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #ecfdf5; color: #059669;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Hoàn tất (Trang này)</span>
                <span class="stat-value">
                    {{ $donHang->where('trang_thai', 'Đã nhận hàng')->count() }}
                </span>
            </div>
        </div>
    </div>

    <div class="card-container">
        <div class="card-header-tools">
            <form action="{{ route('admin.donhang.index') }}" method="GET" class="search-wrapper">
                <div class="search-input-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Mã đơn (#ID), tên khách hàng hoặc SĐT..." value="{{ request('search') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                </div>
                <button type="submit" class="btn-search-action">Tìm kiếm</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.donhang.index') }}" class="btn-reset-action">Đặt lại</a>
                @endif
            </form>

            <div class="result-count-badge">
                <span>Trang <strong>{{ $donHang->currentPage() ?? 1 }}</strong> / <strong>{{ $donHang->lastPage() ?? 1 }}</strong> (Tổng: <strong>{{ $donHang->total() ?? count($donHang) }}</strong> đơn)</span>
            </div>
        </div>

        <!-- Filter Status Tabs -->
        <div class="filter-tabs-wrapper">
            <a href="{{ route('admin.donhang.index', ['search' => request('search')]) }}" 
               class="tab-filter-btn {{ request('status') == '' ? 'active' : '' }}">
                Tất cả
            </a>
            <a href="{{ route('admin.donhang.index', ['status' => 'Đang chờ duyệt', 'search' => request('search')]) }}" 
               class="tab-filter-btn {{ request('status') == 'Đang chờ duyệt' ? 'active' : '' }}">
                Đang chờ duyệt
            </a>
            <a href="{{ route('admin.donhang.index', ['status' => 'Đã xác nhận', 'search' => request('search')]) }}" 
               class="tab-filter-btn {{ request('status') == 'Đã xác nhận' ? 'active' : '' }}">
                Đã xác nhận
            </a>
            <a href="{{ route('admin.donhang.index', ['status' => 'Đang vận chuyển', 'search' => request('search')]) }}" 
               class="tab-filter-btn {{ request('status') == 'Đang vận chuyển' ? 'active' : '' }}">
                Đang vận chuyển
            </a>
            <a href="{{ route('admin.donhang.index', ['status' => 'Đã nhận hàng', 'search' => request('search')]) }}" 
               class="tab-filter-btn {{ request('status') == 'Đã nhận hàng' ? 'active' : '' }}">
                Đã nhận hàng
            </a>
            <a href="{{ route('admin.donhang.index', ['status' => 'Giao hàng thất bại', 'search' => request('search')]) }}" 
               class="tab-filter-btn {{ request('status') == 'Giao hàng thất bại' ? 'active' : '' }}" style="color: #c91c1c;">
                Giao hàng thất bại
            </a>
            <a href="{{ route('admin.donhang.index', ['status' => 'Yêu cầu trả hàng', 'search' => request('search')]) }}" 
               class="tab-filter-btn {{ request('status') == 'Yêu cầu trả hàng' ? 'active' : '' }}" style="color: #7c3aed;">
                Yêu cầu trả hàng
                @php $soTraHang = \Illuminate\Support\Facades\DB::table('don_hang')->where('trang_thai', 'Yêu cầu trả hàng')->count(); @endphp
                @if($soTraHang > 0)
                    <span style="background:#7c3aed; color:#fff; border-radius:10px; padding:1px 7px; font-size:11px; margin-left:4px;">{{ $soTraHang }}</span>
                @endif
            </a>
            <a href="{{ route('admin.donhang.index', ['status' => 'Đã trả hàng', 'search' => request('search')]) }}" 
               class="tab-filter-btn {{ request('status') == 'Đã trả hàng' ? 'active' : '' }}">
                Đã trả hàng
            </a>
            <a href="{{ route('admin.donhang.index', ['status' => 'Đã hủy', 'search' => request('search')]) }}" 
               class="tab-filter-btn {{ request('status') == 'Đã hủy' ? 'active' : '' }}">
                Đã hủy
            </a>
        </div>

        <!-- Table Content -->
        <div class="table-responsive">
            <table class="order-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Mã</th>
                        <th style="width: 170px;">Khách hàng</th>
                        <th style="text-align: center;">Tổng tiền</th>
                        <th style="width: 105px; text-align: center;">Phương thức</th>
                        <th style="text-align: center;">Vận chuyển</th>
                        <th style="text-align: center;">Thanh toán</th>
                        <th style="width: 170px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($donHang as $dH)
                        <tr>
                            <td>
                                <span class="product-id-badge">#{{ $dH->id }}</span>
                            </td>
                            <td>
                                <strong style="color: #0f172a; display: block;">{{ $dH->ten }}</strong>
                                <span style="color: #64748b; font-size: 12px;">{{ $dH->sdt }}</span>
                            </td>
                            <td style="text-align: center;">
                                <strong style="color: #d80f29ff; font-size: 14px;">
                                    {{ number_format($dH->tong_tien, 0, ',', '.') }} đ
                                </strong>
                            </td>
                            <td style="text-align: center;">
                                <span style="font-size: 12px; color: #475569; background: #f1f5f9; padding: 3px 8px; border-radius: 5px; font-weight: 600; display: inline-block;">
                                    {{ $dH->pt_thanh_toan ?? 'COD' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                @if($dH->trang_thai == 'Đang chờ duyệt')
                                    <span class="status-pill status-cho-duyet">Chờ duyệt</span>
                                @elseif($dH->trang_thai == 'Đang vận chuyển')
                                    <span class="status-pill status-dang-giao">Đang giao</span>
                                @elseif($dH->trang_thai == 'Đã nhận hàng')
                                    <span class="status-pill status-hoan-tat">Đã nhận hàng</span>
                                @elseif($dH->trang_thai == 'Đã hủy')
                                    <span class="status-pill status-da-huy">Đã hủy</span>
                                @elseif($dH->trang_thai == 'Yêu cầu trả hàng')
                                    <span class="status-pill status-tra-hang">Yêu cầu trả hàng</span>
                                @elseif($dH->trang_thai == 'Đã trả hàng')
                                    <span class="status-pill status-da-tra">Đã trả hàng</span>
                                @elseif($dH->trang_thai == 'Đã xác nhận')
                                    <span class="status-pill status-da-xac-nhan">Đã xác nhận</span>
                                @elseif($dH->trang_thai == 'Giao hàng thất bại')
                                    <span class="status-pill status-giao-hang-that-bai">Giao hàng thất bại</span>
                                @else
                                    <span class="status-pill" style="background: #f1f5f9; color: #475569;">{{ $dH->trang_thai }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($dH->trang_thai_thanh_toan == 'Đã thanh toán')
                                    <span class="status-pill pay-paid">Đã thanh toán</span>
                                @elseif($dH->trang_thai_thanh_toan == 'Chờ xác nhận tiền')
                                    <span class="status-pill" style="background: #fef3c7; color: #d97706; border: 1px solid #fde68a;">Chờ xác nhận tiền</span>
                                @elseif($dH->trang_thai_thanh_toan == 'Quá hạn thanh toán')
                                    <span class="status-pill" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca;">Quá hạn thanh toán</span>
                                @else
                                    <span class="status-pill pay-unpaid">Chưa thanh toán</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    <a class="btn-mini btn-mini-view" href="{{ route('admin.donhang.show', $dH->id) }}" title="Xem chi tiết đơn hàng và sản phẩm">
                                        <i class="fa-solid fa-eye"></i> Xem chi tiết
                                    </a>
                                    <a class="btn-mini" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;" 
                                       href="{{ route('admin.donhang.inHoaDon', $dH->id) }}" target="_blank" title="In phiếu đóng gói & giao hàng">
                                        <i class="fa-solid fa-print"></i> In
                                    </a>
                                    @if($dH->pt_thanh_toan === 'ONLINE' && $dH->trang_thai_thanh_toan === 'Chờ xác nhận tiền')
                                        <form action="{{ route('admin.donhang.xacNhanTien', $dH->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-mini" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;" 
                                                    title="Xác nhận đã nhận được tiền chuyển khoản"
                                                    onclick="return confirm('Bạn có chắc chắn đã nhận được tiền chuyển khoản cho đơn hàng #{{ $dH->id }} chưa?')">
                                                <i class="fa-solid fa-money-bill-wave"></i> Duyệt tiền
                                            </button>
                                        </form>
                                    @endif

                                    @if($dH->trang_thai !== 'Đã trả hàng')
                                        <a class="btn-mini btn-mini-edit" href="{{ route('admin.donhang.edit', $dH->id) }}" title="Cập nhật trạng thái">
                                            <i class="fa-solid fa-pen-to-square"></i> Sửa
                                        </a>
                                    @else
                                        <button class="btn-mini btn-mini-disabled" disabled title="Đơn hàng đã trả hàng, không thể sửa">
                                            <i class="fa-solid fa-lock"></i> Khóa
                                        </button>
                                    @endif
                                    
                                    @if ($dH->trang_thai == 'Yêu cầu trả hàng')
                                        <a class="btn-mini" 
                                           style="background: #ede9fe; color: #7c3aed; border: 1px solid #c4b5fd;"
                                           href="{{ route('admin.donhang.showXuLyTraHang', $dH->id) }}" 
                                           title="Xử lý yêu cầu trả hàng">
                                            <i class="fa-solid fa-rotate-left"></i> Xử lý
                                        </a>
                                    @endif

                                    @if ($dH->trang_thai == 'Đã hủy')
                                        <button type="button" class="btn-mini btn-mini-delete" title="Xóa đơn hàng đã hủy"
                                                onclick="openDeleteOrderModal({{ $dH->id }}, '{{ addslashes($dH->ten) }}')">
                                            Xóa
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </div>
                                    <h3>Không tìm thấy đơn hàng nào</h3>
                                    <p>Không có đơn hàng nào khớp với tìm kiếm hoặc bộ lọc trạng thái hiện tại.</p>
                                    @if(request('search') || request('status'))
                                        <a href="{{ route('admin.donhang.index') }}" class="btn-reset-action">
                                            Xóa bộ lọc
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
        @if(method_exists($donHang, 'hasPages') && $donHang->hasPages())
            <div class="card-footer-pagination">
                {{ $donHang->withQueryString()->links('pagination::bootstrap-4') }}           </div>
        @endif
    </div>

    <!-- ==================== POPUP MODAL XÁC NHẬN XÓA ĐƠN HÀNG ==================== -->
    <div id="deleteOrderModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-icon-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            
            <h3>Xác nhận xóa đơn hàng</h3>
            <p class="modal-desc">Hành động này sẽ xóa vĩnh viễn đơn hàng đã hủy và các bản ghi chi tiết liên quan.</p>

            <div class="modal-box-info">
                Bạn có chắc chắn muốn xóa vĩnh viễn đơn hàng <strong id="orderIdInfo" style="color: #0f172a;"></strong> của khách hàng <strong id="customerNameInfo" style="color: #0f172a;"></strong>?
            </div>

            <div class="modal-btn-actions">
                <button type="button" class="modal-btn btn-modal-cancel" onclick="closeDeleteOrderModal()">
                    Hủy bỏ
                </button>

                <form id="deleteOrderForm" method="POST" action="" style="flex: 1;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-btn btn-modal-confirm" style="width: 100%;">
                        Xác nhận xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openDeleteOrderModal(orderId, customerName) {
        const modal = document.getElementById('deleteOrderModal');
        const deleteForm = document.getElementById('deleteOrderForm');
        const orderIdInfo = document.getElementById('orderIdInfo');
        const customerNameInfo = document.getElementById('customerNameInfo');

        deleteForm.action = `/admin/don-hang/${orderId}/xoa`;
        orderIdInfo.innerText = `#${orderId}`;
        customerNameInfo.innerText = customerName;

        modal.style.display = 'flex';
    }

    function closeDeleteOrderModal() {
        document.getElementById('deleteOrderModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('deleteOrderModal');
        if (event.target === modal) {
            closeDeleteOrderModal();
        }
    };

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeDeleteOrderModal();
        }
    });
</script>
@endpush