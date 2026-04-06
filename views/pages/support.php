<?php 
$pageTitle = "Trung tâm hỗ trợ - Glow Cosmetics"; 
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>
<div class="container py-5 text-center" style="min-height: 60vh;">
    <i class="far fa-comments fa-5x text-secondary mb-4 mt-5"></i>
    <h2 class="fw-bold" style="color: var(--brand-dark);">Trung Tâm Hỗ Trợ Khách Hàng</h2>
    <p class="text-muted lead mb-4">Chúng tôi luôn ở đây để giúp đỡ bạn. Vui lòng chọn một chủ đề bên dưới.</p>
    
    <div class="row justify-content-center mt-5 g-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-4 rounded-4 h-100">
                <i class="fas fa-truck fs-1 text-primary mb-3"></i>
                <h5 class="fw-bold">Giao hàng</h5>
                <p class="small text-muted">Chính sách vận chuyển và thời gian nhận hàng.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-4 rounded-4 h-100">
                <i class="fas fa-undo-alt fs-1 text-success mb-3"></i>
                <h5 class="fw-bold">Đổi trả</h5>
                <p class="small text-muted">Hướng dẫn đổi trả hàng miễn phí trong 7 ngày.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-4 rounded-4 h-100">
                <i class="fas fa-headset fs-1 text-danger mb-3"></i>
                <h5 class="fw-bold">Liên hệ</h5>
                <p class="small text-muted">Hotline: 1900 xxxx<br>Email: cskh@glow.com</p>
            </div>
        </div>
    </div>
</div>
<?php include 'views/layout/footer.php'; ?>