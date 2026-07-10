<?php
$pageTitle = "Quản lý Đánh giá | Glow Admin";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .admin-content { padding: 30px; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .table th { background-color: #f8f9fa; font-weight: 600; }
        .star-rating { color: #ffc107; }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <?php require_once 'views/admin/includes/sidebar.php'; ?>
            
            <div class="col-md-10 bg-light">
                <div class="admin-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0"><i class="fas fa-star me-2 text-warning"></i> Quản lý Đánh giá</h3>
                    </div>
                    
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Sản phẩm</th>
                                            <th>Khách hàng</th>
                                            <th>Đánh giá</th>
                                            <th>Nội dung</th>
                                            <th>Ngày gửi</th>
                                            <th>Trạng thái</th>
                                            <th class="text-end pe-4">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($reviews)): ?>
                                            <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có đánh giá nào</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($reviews as $r): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold">
                                                    <a href="index.php?controller=product&action=detail&id=<?php echo $r['product_id']; ?>" target="_blank" class="text-dark text-decoration-none">
                                                        <?php echo htmlspecialchars($r['product_name']); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo htmlspecialchars($r['fullname']); ?></td>
                                                <td>
                                                    <div class="star-rating">
                                                        <?php for($i=1; $i<=5; $i++): ?>
                                                            <i class="fas fa-star <?php echo ($i <= $r['rating']) ? '' : 'text-light'; ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                </td>
                                                <td style="max-width: 250px;" class="text-truncate" title="<?php echo htmlspecialchars($r['comment']); ?>">
                                                    <?php echo htmlspecialchars($r['comment']); ?>
                                                </td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($r['created_at'])); ?></td>
                                                <td>
                                                    <?php if ($r['status'] == 1): ?>
                                                        <span class="badge bg-success rounded-pill px-3">Hiển thị</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary rounded-pill px-3">Đã ẩn</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="index.php?controller=admin&action=toggleReview&id=<?php echo $r['id']; ?>" class="btn btn-sm <?php echo ($r['status']==1)?'btn-outline-secondary':'btn-outline-success'; ?> rounded-circle me-1" title="<?php echo ($r['status']==1)?'Ẩn':'Hiển thị'; ?>">
                                                        <i class="fas <?php echo ($r['status']==1)?'fa-eye-slash':'fa-eye'; ?>"></i>
                                                    </a>
                                                    <a href="index.php?controller=admin&action=deleteReview&id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
