<?php 
$pageTitle = "Quản lý Đơn hàng (MVC) - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <!-- Thanh Header bổ sung Đăng xuất / Về trang chủ trực tiếp -->
            <div class="d-flex justify-content-end align-items-center mb-4 gap-3 bg-white p-3 rounded-4 shadow-sm border">
                <span class="fw-bold text-dark me-auto ps-2">
                    <i class="fas fa-user-circle text-primary me-2 fs-5 align-middle"></i>
                    Xin chào, <?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Admin'; ?>
                </span>
                <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-store me-2"></i> Xem cửa hàng
                </a>
                <a href="index.php?controller=user&action=logout" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                </a>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <h3 class="fw-bold text-dark">Quản lý Đơn hàng</h3>
                    <p class="text-muted">Theo dõi và xử lý tiến độ giao hàng</p>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 table-custom">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">Mã Đơn</th>
                                <th class="py-3">Khách hàng</th>
                                <th class="py-3">Tổng tiền</th>
                                <th class="py-3">Ngày đặt</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="text-end pe-4 py-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach($orders as $row): 
                                    $statusClass = 'bg-secondary';
                                    if($row['status'] == 'Hoàn thành') $statusClass = 'bg-success';
                                    if($row['status'] == 'Đang giao') $statusClass = 'bg-info text-dark';
                                    if($row['status'] == 'Chuẩn bị hàng') $statusClass = 'bg-primary text-white';
                                    if($row['status'] == 'Chờ xử lý') $statusClass = 'bg-warning text-dark';
                                    if($row['status'] == 'Hủy') $statusClass = 'bg-danger';
                                ?>
                                    <tr>
                                        <td class='ps-4 fw-bold text-muted'>#ORD-<?php echo $row['id']; ?></td>
                                        <td class='fw-bold text-dark'><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                        <td class='fw-bold text-danger'><?php echo number_format($row['total_price']); ?>đ</td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                                        <td><span class='badge <?php echo $statusClass; ?> bg-opacity-75 px-3 py-2 rounded-pill'><?php echo $row['status']; ?></span></td>
                                        <td class='text-end pe-4'>
                                            <a href='index.php?controller=admin&action=orderDetail&id=<?php echo $row['id']; ?>' class='btn btn-sm btn-light text-primary me-2 rounded-circle' title='Xem chi tiết'>
                                                <i class='fas fa-eye'></i>
                                            </a>
                                            <div class='btn-group'>
                                                <button type='button' class='btn btn-sm btn-light border dropdown-toggle rounded-pill' data-bs-toggle='dropdown'>
                                                    <i class='fas fa-sync-alt me-1 text-primary'></i> Đổi trạng thái
                                                </button>
                                                <ul class='dropdown-menu dropdown-menu-end shadow border-0'>
                                                    <li><a class='dropdown-item py-2' href='index.php?controller=admin&action=updateOrderStatus&id=<?php echo $row['id']; ?>&status=Chờ xử lý'><i class="fas fa-clipboard-check text-secondary me-2"></i>Chờ xử lý</a></li>
                                                    <li><a class='dropdown-item py-2' href='index.php?controller=admin&action=updateOrderStatus&id=<?php echo $row['id']; ?>&status=Chuẩn bị hàng'><i class="fas fa-box-open text-warning me-2"></i>Chuẩn bị hàng</a></li>
                                                    <li><a class='dropdown-item py-2' href='index.php?controller=admin&action=updateOrderStatus&id=<?php echo $row['id']; ?>&status=Đang giao'><i class="fas fa-truck text-info me-2"></i>Đang giao</a></li>
                                                    <li><a class='dropdown-item py-2' href='index.php?controller=admin&action=updateOrderStatus&id=<?php echo $row['id']; ?>&status=Hoàn thành'><i class="fas fa-check-circle text-success me-2"></i>Hoàn thành</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class='dropdown-item py-2 text-danger' href='index.php?controller=admin&action=updateOrderStatus&id=<?php echo $row['id']; ?>&status=Hủy'><i class="fas fa-times-circle me-2"></i>Hủy đơn</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='6' class='text-center py-5 text-muted'>Chưa có đơn hàng nào</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>