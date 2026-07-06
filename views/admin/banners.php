<?php 
$pageTitle = "Quản lý Banner Quảng Cáo - Glow Admin"; 
$extraCSS = "
<style>
.banner-thumb { width: 100px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
</style>";
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <h3 class="fw-bold text-dark">Quản lý Banner Quảng Cáo</h3>
                    <p class="text-muted">Thay đổi banner trượt hiển thị trên trang chủ</p>
                </div>
                <a href="index.php?controller=admin&action=addBanner" class="btn btn-brand shadow-sm">
                    <i class="fas fa-plus me-2"></i> Thêm Banner
                </a>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 table-custom">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">ID</th>
                                <th class="py-3">Hình ảnh Banner</th>
                                <th class="py-3">Phân loại (Vị trí)</th>
                                <th class="py-3">Tiêu đề (Nội bộ)</th>
                                <th class="py-3">Đường dẫn khi bấm (Link)</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="text-end pe-4 py-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($banners)): ?>
                                <?php foreach($banners as $row): 
                                    $statusBadge = ($row['status'] == 1) 
                                        ? '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Đang hiển thị</span>' 
                                        : '<span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Đang ẩn</span>';
                                ?>
                                    <tr>
                                        <td class='ps-4 fw-bold text-muted'>#<?php echo $row['id']; ?></td>
                                        <td><img src='<?php echo htmlspecialchars($row['image']); ?>' class='banner-thumb' alt='banner' onerror="this.src='https://via.placeholder.com/150x60'"></td>
                                        <td>
                                            <?php 
                                            $pos = $row['position'];
                                            if ($pos == 'hero') echo '<span class="badge bg-primary">Hero Banner</span>';
                                            elseif ($pos == 'bento_1') echo '<span class="badge bg-info text-dark">Box 1 (Lớn)</span>';
                                            elseif ($pos == 'bento_2') echo '<span class="badge bg-warning text-dark">Box 2 (Nhỏ)</span>';
                                            elseif ($pos == 'bento_3') echo '<span class="badge bg-success">Box 3 (Catalog)</span>';
                                            elseif ($pos == 'bento_4') echo '<span class="badge bg-danger">Box 4 (Blog)</span>';
                                            else echo '<span class="badge bg-secondary">Khác</span>';
                                            ?>
                                        </td>
                                        <td><div class='fw-bold text-dark'><?php echo htmlspecialchars($row['title']); ?></div></td>
                                        <td>
                                            <?php if(!empty($row['link'])): ?>
                                                <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank" class="text-primary small text-truncate d-inline-block" style="max-width: 150px;">
                                                    <i class="fas fa-link me-1"></i> Liên kết
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">Không có</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $statusBadge; ?></td>
                                        <td class='text-end pe-4'>
                                            <a href='index.php?controller=admin&action=editBanner&id=<?php echo $row['id']; ?>' class='btn btn-sm btn-light text-primary me-2 rounded-circle' title='Sửa'>
                                                <i class='fas fa-edit'></i>
                                            </a>
                                            <a href='index.php?controller=admin&action=deleteBanner&id=<?php echo $row['id']; ?>' onclick="return confirm('Bạn có chắc chắn muốn xóa banner này?');" class='btn btn-sm btn-light text-danger rounded-circle' title='Xóa'>
                                                <i class='fas fa-trash'></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='6' class='text-center py-5 text-muted'>Chưa có banner quảng cáo nào</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>