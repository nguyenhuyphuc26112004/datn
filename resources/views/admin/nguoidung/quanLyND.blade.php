@extends('admin.layouts.master')

@section('title', 'Quản lý người dùng')

@section('content')
    <!-- Page Title & Main Action -->
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-users-gear"></i> Quản lý người dùng</h1>
            <p>Quản lý danh sách thành viên, tài khoản phân quyền và trạng thái hoạt động.</p>
        </div>
        
        @if(Auth::check() && Auth::user()->id_vai_tro == 2)
            <a href="{{ route('admin.user.create') }}" class="btn-cta-primary">
                <i class="fa-solid fa-user-plus"></i> Thêm người dùng mới
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
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tổng hiển thị</span>
                <span class="stat-value">{{ $users->total() ?? count($users) }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fef2f2; color: #dc2626;">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Quản trị viên (Trang này)</span>
                <span class="stat-value">
                    {{ $users->where('id_vai_tro', 2)->count() }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #f0fdf4; color: #16a34a;">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Nhân viên (Trang này)</span>
                <span class="stat-value">
                    {{ $users->where('id_vai_tro', 3)->count() }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fdf4ff; color: #c026d3;">
                <i class="fa-solid fa-user-group"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Khách hàng (Trang này)</span>
                <span class="stat-value">
                    {{ $users->whereNotIn('id_vai_tro', [2, 3])->count() }}
                </span>
            </div>
        </div>
    </div>

    <!-- Card: Search & Table Data -->
    <div class="card-container">
        <!-- Search & Filters Toolbar -->
        <div class="card-header-tools">
            <form action="{{ route('admin.user.index') }}" method="GET" class="search-wrapper">
                <div class="search-input-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="searchTerm" placeholder="Tìm kiếm theo ID, họ tên, username, email..." value="{{ request('searchTerm') ?? $searchTerm ?? '' }}">
                </div>
                <button type="submit" class="btn-search-action">
                    <i class="fa-solid fa-filter"></i> Tìm kiếm
                </button>
                @if(request('searchTerm') || (isset($searchTerm) && $searchTerm != ''))
                    <a href="{{ route('admin.user.index') }}" class="btn-reset-action">
                        <i class="fa-solid fa-rotate-left"></i> Đặt lại
                    </a>
                @endif
            </form>

            <div class="result-count-badge">
                <i class="fa-solid fa-list-check"></i>
                <span>Trang <strong>{{ $users->currentPage() ?? 1 }}</strong> / <strong>{{ $users->lastPage() ?? 1 }}</strong> (Tổng: <strong>{{ $users->total() ?? count($users) }}</strong> người dùng)</span>
            </div>
        </div>

        <!-- Table Content -->
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 70px; text-align: center;">ID</th>
                        <th style="text-align: center;">Họ tên</th>
                        <th style="text-align: center;">Tên đăng nhập</th>
                        <th style="text-align: center;">Email liên hệ</th>
                        <th style="width: 100px; text-align: center;">Đơn hàng</th>
                        <th style="width: 120px; text-align: center;">Vai trò</th>
                        <th style="width: 125px; text-align: center;">Trạng thái</th>
                        <th style="width: 210px; text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php
                            $isUserLocked = isset($user->trang_thai) && (int)$user->trang_thai === 0;
                        @endphp
                        <tr>
                            <td>
                                <span class="product-id-badge">#{{ $user->id }}</span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #0f172a; font-size: 14px;">
                                    {{ $user->ho_ten ?? 'Chưa đặt tên' }}
                                </span>
                            </td>
                            <td>
                                <span style="color: #2563eb; font-weight: 600;">
                                    {{ $user->ten_dang_nhap }}
                                </span>
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" style="color: #475569; text-decoration: none;" title="Gửi email">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td style="text-align: center;">
                                @if(($user->orders_count ?? 0) > 0)
                                    <span style="display: inline-block; padding: 5px 10px; border-radius: 8px; font-size: 13px; font-weight: 600; background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a;" title="Đã có {{ $user->orders_count }} đơn hàng">
                                        {{ $user->orders_count }} đơn
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 5px 10px; border-radius: 8px; font-size: 13px; font-weight: 600; background-color: #f1f5f9; color: #64748b;">
                                        0 đơn
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if(isset($user->id_vai_tro))
                                    @if($user->id_vai_tro == 2)
                                        <span class="role-badge role-admin">Admin</span>
                                    @elseif($user->id_vai_tro == 3)
                                        <span class="role-badge role-staff">Nhân viên</span>
                                    @else
                                        <span class="role-badge role-user">Khách hàng</span>
                                    @endif
                                @else
                                    <span class="role-badge role-user">Khách hàng</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($isUserLocked)
                                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca;">
                                        <i class="fa-solid fa-lock" style="font-size: 10px;"></i> Đã khóa
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; background-color: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;">
                                        <i class="fa-solid fa-circle-check" style="font-size: 10px;"></i> Hoạt động
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-group" style="justify-content: flex-end;">
                                    <a class="btn-action btn-action-view" href="{{ route('admin.user.show', $user->id) }}" title="Xem hồ sơ chi tiết">
                                        <i class="fa-solid fa-eye"></i> Xem
                                    </a>
                                    @if(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isStaff()))
                                        <a class="btn-action btn-action-edit" href="{{ route('admin.user.edit', $user->id) }}" title="Chỉnh sửa thông tin">
                                            <i class="fa-solid fa-pen-to-square"></i>Sửa
                                        </a>
                                    @endif

                                    @if(Auth::check() && Auth::user()->isAdmin())
                                        <button type="button" class="btn-action btn-action-delete" title="Xóa tài khoản"
                                                onclick="openDeleteUserModal({{ $user->id }}, '{{ addslashes($user->ho_ten ?? $user->ten_dang_nhap) }}', {{ $user->orders_count ?? 0 }})">
                                            <i class="fa-solid fa-trash-can"></i>Xóa
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>   
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fa-solid fa-user-xmark"></i>
                                    </div>
                                    <h3>Không tìm thấy người dùng</h3>
                                    <p>Không có kết quả nào phù hợp với bộ lọc tìm kiếm của bạn. Hãy thử từ khóa khác.</p>
                                    @if(request('searchTerm'))
                                        <a href="{{ route('admin.user.index') }}" class="btn-reset-action">
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
        @if($users->hasPages())
            <div class="card-footer-pagination">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

    <!-- POPUP MODAL XÁC NHẬN XÓA -->
    <div id="deleteUserModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-icon-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            
            <h3>Xác nhận xóa tài khoản</h3>
            <p class="modal-desc">Vui lòng kiểm tra kỹ thông tin ràng buộc trước khi thực hiện thao tác xóa.</p>

            <div id="modalAlert" style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 14px 16px; text-align: left; margin-bottom: 24px; display: none; gap: 12px; font-size: 13px; color: #9f1239; line-height: 1.5;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 18px; color: #e11d48; margin-top: 2px; flex-shrink: 0;"></i>
                <div>
                    <strong>Không thể xóa tài khoản này!</strong>
                    <p style="margin-top: 4px;">Người dùng này hiện đang có <b id="orderCount" style="color: #be123c;">0</b> đơn hàng trong hệ thống. Để bảo đảm tính chính xác của dữ liệu lịch sử và doanh thu, bạn không thể xóa tài khoản này.</p>
                </div>
            </div>

            <div id="modalInfo" class="modal-box-info" style="display: none;">
                Bạn có chắc chắn muốn xóa tài khoản <strong id="userNameInfo" style="color: #0f172a;"></strong>?
                <div style="margin-top: 6px; font-size: 12px; color: #ef4444;">
                    <i class="fa-solid fa-circle-info"></i> Hành động này sẽ xóa vĩnh viễn tài khoản và giỏ hàng liên quan khỏi cơ sở dữ liệu.
                </div>
            </div>

            <div class="modal-btn-actions">
                <button type="button" class="modal-btn btn-modal-cancel" onclick="closeDeleteUserModal()">
                    Hủy bỏ
                </button>

                <form id="deleteUserForm" method="POST" action="" style="flex: 1; display: none;">
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
    function openDeleteUserModal(userId, userName, totalOrders) {
        const modal = document.getElementById('deleteUserModal');
        const modalAlert = document.getElementById('modalAlert');
        const modalInfo = document.getElementById('modalInfo');
        const deleteForm = document.getElementById('deleteUserForm');
        const btnDisabledDelete = document.getElementById('btnDisabledDelete');
        const orderCount = document.getElementById('orderCount');
        const userNameInfo = document.getElementById('userNameInfo');

        deleteForm.action = `/admin/nguoi-dung/${userId}/xoa`;

        if (totalOrders > 0) {
            orderCount.innerText = totalOrders;
            modalAlert.style.display = 'flex';
            modalInfo.style.display = 'none';
            deleteForm.style.display = 'none';
            btnDisabledDelete.style.display = 'inline-block';
        } else {
            userNameInfo.innerText = `#${userId} - ${userName}`;
            modalAlert.style.display = 'none';
            modalInfo.style.display = 'block';
            deleteForm.style.display = 'block';
            btnDisabledDelete.style.display = 'none';
        }

        modal.style.display = 'flex';
    }

    function closeDeleteUserModal() {
        document.getElementById('deleteUserModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('deleteUserModal');
        if (event.target === modal) {
            closeDeleteUserModal();
        }
    };
</script>
@endpush