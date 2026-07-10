<?php 
$pageTitle = "Quản lý Nhà Cung Cấp - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Quản lý Nhà Cung Cấp</h4>
                <a href="index.php?controller=admin&action=addSupplier" class="btn btn-brand"><i class="fas fa-plus"></i> Thêm Nhà Cung Cấp</a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Nhà Cung Cấp</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Địa chỉ</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($suppliers)): foreach($suppliers as $supplier): ?>
                                <tr>
                                    <td>#<?php echo $supplier['id']; ?></td>
                                    <td><strong class="text-dark"><?php echo htmlspecialchars($supplier['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($supplier['phone'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($supplier['email'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($supplier['address'] ?? ''); ?></td>
                                    <td>
                                        <a href="index.php?controller=admin&action=editSupplier&id=<?php echo $supplier['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                        <a href="index.php?controller=admin&action=deleteSupplier&id=<?php echo $supplier['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center py-4">Chưa có nhà cung cấp nào.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
