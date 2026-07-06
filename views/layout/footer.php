<!-- Main Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row g-4">
                
                <!-- Cột 1: Thông tin thương hiệu -->
                <div class="col-lg-3 col-md-6 pe-lg-4">
                    <a href="index.php" class="brand-logo d-inline-block mb-3 fs-3">glow.</a>
                    <p class="text-gray small mb-4" style="line-height: 1.6;">
                        Glow Cosmetics tự hào mang đến những sản phẩm làm đẹp chính hãng, thân thiện với làn da, giúp bạn tự tin bừng sáng vẻ đẹp tự nhiên mỗi ngày.
                    </p>
                    <div class="social-icons">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        <a href="#" title="Youtube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Cột 2: Quick Links (Liên kết nhanh) -->
                <div class="col-lg-3 col-md-6 ps-lg-5">
                    <h5 class="footer-title">Khám Phá</h5>
                    <ul class="footer-links">
                        <li><a href="index.php">Trang chủ</a></li>
                        <li><a href="index.php?controller=product&action=index">Cửa hàng mỹ phẩm</a></li>
                        <li><a href="index.php?controller=brand&action=index">Thương hiệu đối tác</a></li>
                        <li><a href="index.php?controller=page&action=blog">Tạp chí làm đẹp</a></li>
                    </ul>
                </div>

                <!-- Cột 3: Customer Service (Hỗ trợ) -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Hỗ Trợ Khách Hàng</h5>
                    <ul class="footer-links">
                        <li><a href="index.php?controller=page&action=support">Trung tâm hỗ trợ</a></li>
                        <li><a href="#">Chính sách giao hàng</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="index.php?controller=page&action=stores">Hệ thống cửa hàng</a></li>
                    </ul>
                </div>

                <!-- Cột 4: Newsletter (Bản tin) -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Đăng Ký Nhận Tin</h5>
                    <p class="text-gray small mb-3">Tham gia vào cộng đồng Glow để nhận ngay mã giảm giá 10% cho đơn hàng đầu tiên!</p>
                    <form class="newsletter-form d-flex">
                        <input type="email" class="form-control" placeholder="Nhập email của bạn..." required>
                        <button class="btn" type="button"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>

            </div>

            <!-- Dòng Copyright -->
            <div class="row mt-5">
                <div class="col-12 border-top pt-4 text-center text-gray small">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Glow Cosmetics. Đã đăng ký bản quyền. Được thiết kế dành riêng cho bạn.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Nhúng Thư viện Bootstrap JS cho dropdown và hiệu ứng -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Nơi nhúng JS phụ trợ nếu các trang khai báo biến $extraJS -->
    <?php if(isset($extraJS)) echo $extraJS; ?>
</body>
</html>