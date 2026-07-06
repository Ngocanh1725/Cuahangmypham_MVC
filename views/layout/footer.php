<style>
    /* Footer Style */
    .rhode-footer {
        background-color: #ffffff;
        padding: 80px 0 30px;
        margin-top: 60px;
        border-top: 1px solid var(--rhode-pink-light);
    }
    
    .footer-brand {
        font-family: var(--font-serif);
        font-size: 2.5rem;
        color: var(--rhode-pink-accent);
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .footer-title {
        font-family: var(--font-serif);
        font-size: 1.2rem;
        color: var(--text-main);
        margin-bottom: 25px;
        font-weight: 600;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: var(--text-light);
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .footer-links a:hover {
        color: var(--rhode-pink-accent);
        padding-left: 5px; /* Hiệu ứng trượt nhẹ khi hover */
    }

    /* Form đăng ký footer */
    .footer-newsletter .input-group {
        background: var(--rhode-bg-main);
        border-radius: var(--radius-pill);
        padding: 5px;
        border: 1px solid var(--rhode-pink-light);
    }

    .footer-newsletter input {
        border: none;
        background: transparent;
        padding: 10px 20px;
        box-shadow: none;
        font-size: 0.9rem;
    }
    
    .footer-newsletter input:focus {
        outline: none;
        box-shadow: none;
        background: transparent;
    }

    .footer-newsletter button {
        border-radius: var(--radius-pill) !important;
        padding: 10px 25px;
    }

    /* Social Icons */
    .social-icons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-icons a {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--rhode-bg-main);
        color: var(--text-main);
        border-radius: 50%;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .social-icons a:hover {
        background-color: var(--rhode-pink-accent);
        color: #fff;
        transform: translateY(-3px);
    }

    .footer-bottom {
        border-top: 1px solid rgba(0,0,0,0.05);
        padding-top: 20px;
        margin-top: 60px;
        font-size: 0.85rem;
        color: var(--text-light);
    }
</style>

<footer class="rhode-footer">
    <div class="container">
        <div class="row g-5">
            <!-- Cột 1: Thông tin thương hiệu -->
            <div class="col-lg-4 col-md-6">
                <a href="index.php" class="footer-brand">glow.</a>
                <p class="text-muted pe-lg-4" style="font-size: 0.95rem; line-height: 1.6;">
                    Triết lý làm đẹp tôn vinh sự tự nhiên. Cung cấp các dòng sản phẩm thuần chay, lành tính, giúp làn da bạn luôn căng mọng và rạng rỡ mỗi ngày.
                </p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-pinterest-p"></i></a>
                </div>
            </div>

            <!-- Cột 2: Cửa hàng -->
            <div class="col-lg-2 col-md-6">
                <h4 class="footer-title">Cửa hàng</h4>
                <ul class="footer-links">
                    <li><a href="index.php?controller=product&action=index">Tất cả sản phẩm</a></li>
                    <li><a href="index.php?controller=product&action=index&category[]=Chăm sóc da">Chăm sóc da</a></li>
                    <li><a href="index.php?controller=product&action=index&category[]=Trang điểm">Trang điểm</a></li>
                    <li><a href="index.php?controller=product&action=index&category[]=Phụ kiện">Phụ kiện làm đẹp</a></li>
                    <li><a href="index.php?controller=brand&action=index">Thương hiệu</a></li>
                </ul>
            </div>

            <!-- Cột 3: Hỗ trợ khách hàng -->
            <div class="col-lg-2 col-md-6">
                <h4 class="footer-title">Hỗ trợ</h4>
                <ul class="footer-links">
                    <li><a href="#">Tài khoản của tôi</a></li>
                    <li><a href="#">Tra cứu đơn hàng</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Câu hỏi thường gặp (FAQ)</a></li>
                    <li><a href="#">Liên hệ với chúng tôi</a></li>
                </ul>
            </div>

            <!-- Cột 4: Đăng ký nhận tin -->
            <div class="col-lg-4 col-md-6">
                <h4 class="footer-title">Đừng bỏ lỡ</h4>
                <p class="text-muted mb-3" style="font-size: 0.95rem;">Đăng ký để nhận thông tin về sản phẩm mới, xu hướng làm đẹp và các chương trình ưu đãi độc quyền.</p>
                <form class="footer-newsletter">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Email của bạn..." required>
                        <button class="btn rhode-btn-primary" type="button">Gửi</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dòng bản quyền cuối trang -->
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-2 mb-md-0">&copy; <?php echo date('Y'); ?> Glow Cosmetics. Đã đăng ký bản quyền.</p>
            <div class="payment-methods">
                <i class="fab fa-cc-visa fa-lg text-muted me-2"></i>
                <i class="fab fa-cc-mastercard fa-lg text-muted me-2"></i>
                <i class="fab fa-cc-paypal fa-lg text-muted me-2"></i>
                <i class="fab fa-cc-apple-pay fa-lg text-muted"></i>
            </div>
        </div>
    </div>
</footer>

<!-- Nhúng Bootstrap JS (nếu dự án của bạn đang dùng) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- FontAwesome (Dành cho Icon) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>