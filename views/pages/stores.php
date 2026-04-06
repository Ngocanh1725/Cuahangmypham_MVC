<?php 
$pageTitle = "Hệ thống cửa hàng - Glow Cosmetics"; 
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h5 class="fw-bold text-uppercase" style="color: var(--brand-dark, #be185d); letter-spacing: 2px;">Trải nghiệm thực tế</h5>
        <h2 class="fw-bold display-6 text-dark">Hệ Thống Cửa Hàng Glow Store</h2>
        <p class="text-muted mt-2">Tìm cửa hàng gần bạn nhất để trải nghiệm sản phẩm và nhận tư vấn trực tiếp từ chuyên gia.</p>
    </div>

    <div class="row g-4">
        <!-- Cột Danh sách cửa hàng -->
        <div class="col-lg-4">
            
            <!-- Khu vực Hà Nội -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-map-marker-alt text-danger me-2"></i>Khu vực Hà Nội</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="p-3 bg-light rounded-4 mb-3 border shadow-sm" style="transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        <h6 class="fw-bold" style="color: var(--brand-dark, #be185d);">Glow Store - Vincom Bà Triệu</h6>
                        <p class="mb-1 text-muted small"><i class="fas fa-map-pin me-2 text-secondary"></i>Tầng 1, Vincom Center, 191 Bà Triệu, Q. Hai Bà Trưng</p>
                        <p class="mb-1 text-muted small"><i class="fas fa-phone-alt me-2 text-secondary"></i>Hotline: 024 1234 5678</p>
                        <p class="mb-0 text-success small fw-bold"><i class="far fa-clock me-2"></i>Mở cửa: 09:00 - 22:00</p>
                    </div>
                    
                    <div class="p-3 bg-light rounded-4 border shadow-sm" style="transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        <h6 class="fw-bold" style="color: var(--brand-dark, #be185d);">Glow Store - Cầu Giấy</h6>
                        <p class="mb-1 text-muted small"><i class="fas fa-map-pin me-2 text-secondary"></i>123 Xuân Thủy, Dịch Vọng Hậu, Q. Cầu Giấy</p>
                        <p class="mb-1 text-muted small"><i class="fas fa-phone-alt me-2 text-secondary"></i>Hotline: 024 8765 4321</p>
                        <p class="mb-0 text-success small fw-bold"><i class="far fa-clock me-2"></i>Mở cửa: 08:30 - 21:30</p>
                    </div>
                </div>
            </div>

            <!-- Khu vực TP.HCM -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-map-marker-alt text-danger me-2"></i>Khu vực TP. Hồ Chí Minh</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="p-3 bg-light rounded-4 border shadow-sm" style="transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        <h6 class="fw-bold" style="color: var(--brand-dark, #be185d);">Glow Store - Quận 1</h6>
                        <p class="mb-1 text-muted small"><i class="fas fa-map-pin me-2 text-secondary"></i>45 Nguyễn Huệ, P. Bến Nghé, Quận 1</p>
                        <p class="mb-1 text-muted small"><i class="fas fa-phone-alt me-2 text-secondary"></i>Hotline: 028 9999 8888</p>
                        <p class="mb-0 text-success small fw-bold"><i class="far fa-clock me-2"></i>Mở cửa: 09:00 - 22:00</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Cột Bản đồ Google Maps -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-2">
                <!-- Nhúng Google Maps iframe -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.096814183571!2d105.8498998153137!3d21.00881028599402!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab8a9228b0d1%3A0x675ec198642217c4!2sVincom%20Center%20B%C3%A0%20Tri%E1%BB%87u!5e0!3m2!1sen!2s!4v1652445100000!5m2!1sen!2s" width="100%" height="100%" style="border:0; min-height: 500px; border-radius: 12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>