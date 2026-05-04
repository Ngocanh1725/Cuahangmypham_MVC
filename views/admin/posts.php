<?php 
$pageTitle = "Quản lý Bài Viết - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <h3 class="fw-bold text-dark">Tạp chí làm đẹp (Posts)</h3>
                    <p class="text-muted">Quản lý nội dung bài viết, tin tức, blog</p>
                </div>
                <a href="#" class="btn btn-brand shadow-sm">
                    <i class="fas fa-plus me-2"></i> Viết bài mới
                </a>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 table-custom">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">ID</th>
                                <th class="py-3">Tiêu đề bài viết</th>
                                <th class="py-3">Ngày đăng</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="text-end pe-4 py-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($posts)): ?>
                                <?php foreach($posts as $row): ?>
                                    <tr>
                                        <td class="ps-4 text-muted">#<?php echo $row['id']; ?></td>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                        <td><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Đã xuất bản</span></td>
                                        <td class="text-end pe-4">
                                            <a href="#" class="btn btn-sm btn-light text-primary rounded-circle"><i class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-light text-danger rounded-circle"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-newspaper fa-3x text-muted mb-3 opacity-50"></i>
                                        <h5 class="text-muted">Chưa có bài viết nào. Hãy tạo bài viết đầu tiên!</h5>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>