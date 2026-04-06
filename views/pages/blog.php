<?php 
$pageTitle = "Tạp chí làm đẹp - Glow Magazine"; 
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h5 class="fw-bold text-uppercase" style="color: var(--brand-dark, #be185d); letter-spacing: 2px;">Glow Magazine</h5>
        <h2 class="fw-bold display-6 text-dark">Tạp Chí Làm Đẹp</h2>
        <p class="text-muted mt-2">Cập nhật xu hướng makeup, bí quyết skincare và những câu chuyện làm đẹp truyền cảm hứng.</p>
    </div>

    <!-- Bài viết nổi bật (Hero Post) -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="row g-0">
            <div class="col-md-7">
                <img src="https://images.unsplash.com/photo-1512496115841-a45e5eb6815c?q=80&w=1000&auto=format&fit=crop" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 400px;" alt="Xu hướng 2024">
            </div>
            <div class="col-md-5 d-flex align-items-center p-5 bg-white">
                <div>
                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mb-3">Xu hướng Makeup</span>
                    <h3 class="fw-bold mb-3" style="color: var(--brand-dark, #be185d);">Xu Hướng Trang Điểm "Glass Skin" Vẫn Lên Ngôi Năm 2024</h3>
                    <p class="text-muted mb-4">Làn da căng bóng như pha lê không còn là điều khó khăn nếu bạn nắm giữ 3 bí quyết chăm sóc da và lựa chọn kem nền dưới đây...</p>
                    <a href="#" class="btn btn-outline-dark rounded-pill px-4">Đọc tiếp <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách bài viết -->
    <h4 class="fw-bold mb-4 border-bottom pb-2">Bài viết mới nhất</h4>
    <div class="row g-4">
        <!-- Bài 1 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=500&auto=format&fit=crop" class="card-img-top" style="height: 220px; object-fit: cover;" alt="...">
                <div class="card-body p-4">
                    <small class="text-primary fw-bold text-uppercase">Skincare routine</small>
                    <h5 class="fw-bold mt-2">5 Sai lầm khi dùng Retinol khiến da bạn "biểu tình"</h5>
                    <p class="text-muted small mt-2">Đừng để "thần dược" chống lão hóa trở thành thảm họa vì những thói quen sai lầm này.</p>
                </div>
                <div class="card-footer bg-white border-0 px-4 pb-4">
                    <small class="text-muted"><i class="far fa-clock me-1"></i> 2 giờ trước</small>
                </div>
            </div>
        </div>
        
        <!-- Bài 2 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?q=80&w=500&auto=format&fit=crop" class="card-img-top" style="height: 220px; object-fit: cover;" alt="...">
                <div class="card-body p-4">
                    <small class="text-warning fw-bold text-uppercase">Review Sản Phẩm</small>
                    <h5 class="fw-bold mt-2">Top 10 Kem Chống Nắng "Chân Ái" Cho Da Dầu Mụn</h5>
                    <p class="text-muted small mt-2">Khô ráo, không bóng nhờn và đặc biệt không gây bít tắc lỗ chân lông. Cùng khám phá ngay!</p>
                </div>
                <div class="card-footer bg-white border-0 px-4 pb-4">
                    <small class="text-muted"><i class="far fa-clock me-1"></i> 1 ngày trước</small>
                </div>
            </div>
        </div>

        <!-- Bài 3 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=500&auto=format&fit=crop" class="card-img-top" style="height: 220px; object-fit: cover;" alt="...">
                <div class="card-body p-4">
                    <small class="text-success fw-bold text-uppercase">Thế Giới Nước Hoa</small>
                    <h5 class="fw-bold mt-2">Nghệ Thuật Xịt Nước Hoa Lưu Hương Suốt 24 Giờ</h5>
                    <p class="text-muted small mt-2">Vị trí nào trên cơ thể giúp mùi hương tỏa ra tinh tế và lâu phai nhất? Bí mật nằm ở bài viết này.</p>
                </div>
                <div class="card-footer bg-white border-0 px-4 pb-4">
                    <small class="text-muted"><i class="far fa-clock me-1"></i> 2 ngày trước</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>