<?php 
$pageTitle = "Quản lý Thương Hiệu - Glow Admin"; 
$extraCSS = "
<style>
.brand-logo-thumb { width: 60px; height: 60px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; background: #fff;}
.brand-banner-thumb { width: 120px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
</style>";
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <h3 class="fw-bold text-dark">Danh sách Thương Hiệu</h3>
                    <p class="text-muted">Quản lý đối tác và hiển thị gian hàng</p>
                </div>
                <a href="index.php?controller=admin&action=addBrand" class="btn btn-brand shadow-sm">
                    <i class="fas fa-plus me-2"></i> Thêm thương hiệu
                </a>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 table-custom">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">ID</th>
                                <th class="py-3">Logo</th>
                                <th class="py-3">Banner</th>
                                <th class="py-3">Tên Thương Hiệu</th>
                                <th class="py-3" style="max-width: 250px;">Mô tả</th>
                                <th class="text-end pe-4 py-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($brands)): ?>
                                <?php foreach($brands as $row): 
                                    $logo = !empty($row['logo']) ? $row['logo'] : 'https://via.placeholder.com/60?text=No+Logo';
                                    $banner = !empty($row['banner']) ? $row['banner'] : 'https://via.placeholder.com/120x50?text=No+Banner';
                                ?>
                                    <tr>
                                        <td class='ps-4 fw-bold text-muted'>#<?php echo $row['id']; ?></td>
                                        <td><img src='<?php echo htmlspecialchars($logo, ENT_QUOTES); ?>' class='brand-logo-thumb' alt='logo' onerror="this.src='https://via.placeholder.com/60?text=Error'"></td>
                                        <td><img src='<?php echo htmlspecialchars($banner, ENT_QUOTES); ?>' class='brand-banner-thumb' alt='banner' onerror="this.src='https://via.placeholder.com/120x50?text=Error'"></td>
                                        <td><div class='fw-bold text-dark fs-5'><?php echo htmlspecialchars($row['name']); ?></div></td>
                                        <td class='text-muted small text-truncate' style='max-width: 250px;' title="<?php echo htmlspecialchars($row['description']); ?>">
                                            <?php echo htmlspecialchars($row['description']); ?>
                                        </td>
                                        <td class='text-end pe-4'>
                                            <a href='index.php?controller=admin&action=editBrand&id=<?php echo $row['id']; ?>' class='btn btn-sm btn-light text-primary me-2 rounded-circle' title='Sửa'>
                                                <i class='fas fa-edit'></i>
                                            </a>
                                            <a href='index.php?controller=admin&action=deleteBrand&id=<?php echo $row['id']; ?>' onclick="return confirm('Xóa thương hiệu này? Toàn bộ ảnh sẽ bị xóa.');" class='btn btn-sm btn-light text-danger rounded-circle' title='Xóa'>
                                                <i class='fas fa-trash'></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='6' class='text-center py-5 text-muted'>Chưa có thương hiệu nào</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>