<?php 
$pageTitle = "Sự kiện tại cửa hàng - Glow Cosmetics"; 
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>
<div class="container py-5" style="min-height: 60vh;">
    <div class="text-center mb-5 mt-4">
        <h5 class="fw-bold text-uppercase text-danger" style="letter-spacing: 2px;">Glow Events</h5>
        <h2 class="fw-bold display-6">Sự Kiện Sắp Diễn Ra</h2>
        <p class="text-muted">Đăng ký tham gia các buổi Workshop miễn phí tại hệ thống cửa hàng của chúng tôi.</p>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Dùng Vòng lặp in ra Dữ liệu thật từ Database -->
            <?php if(!empty($events)): ?>
                <?php foreach($events as $event): 
                    $dateObj = new DateTime($event['event_date']);
                ?>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="row g-0">
                        <div class="col-4 bg-light d-flex flex-column align-items-center justify-content-center border-end p-3">
                            <h2 class="fw-bold text-danger mb-0 display-4"><?php echo $dateObj->format('d'); ?></h2>
                            <h5 class="text-uppercase text-muted mb-0 fw-bold">Tháng <?php echo $dateObj->format('m'); ?></h5>
                        </div>
                        <div class="col-8 p-4 d-flex flex-column justify-content-center">
                            <h4 class="fw-bold mb-2" style="color: var(--brand-dark);"><?php echo htmlspecialchars($event['title']); ?></h4>
                            <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt me-2 text-danger"></i><?php echo htmlspecialchars($event['location']); ?></p>
                            <p class="mb-3 text-secondary"><?php echo htmlspecialchars($event['description']); ?></p>
                            <div>
                                <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">Đăng ký tham gia ngay <i class="fas fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-muted py-5">
                    <i class="far fa-calendar-times fa-4x mb-3 text-light"></i>
                    <h5 class="fw-bold text-secondary">Hiện tại chưa có sự kiện nào sắp diễn ra.</h5>
                    <p>Hãy quay lại sau bạn nhé!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'views/layout/footer.php'; ?>