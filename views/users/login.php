<?php 
$pageTitle = "Đăng Nhập - Glow Beauty"; 
include 'views/layout/header.php'; 
?>
<style>
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        padding: 40px 0;
    }
    .auth-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }
    .auth-bg {
        background-image: url('https://images.unsplash.com/photo-1596462502278-27bf85033e5a?q=80&w=2071&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        min-height: 500px;
        position: relative;
    }
    .auth-bg::after {
        content: '';
        position: absolute;
        top:0; left:0; right:0; bottom:0;
        background: rgba(0,0,0,0.2);
    }
    .auth-content {
        padding: 50px;
        background: #fff;
    }
    .auth-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: #333;
        margin-bottom: 30px;
    }
    .form-control {
        border-radius: 12px;
        padding: 15px 20px;
        border: 1px solid #eee;
        background: #fafafa;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: var(--brand-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.1);
        background: #fff;
    }
    .btn-auth {
        background: var(--brand-color);
        color: white;
        border-radius: 12px;
        padding: 15px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    .btn-auth:hover {
        background: #e65c5c;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 107, 107, 0.2);
    }
</style>

<div class="auth-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card auth-card">
                    <div class="row g-0">
                        <div class="col-md-6 auth-bg d-none d-md-block">
                        </div>
                        <div class="col-md-6 auth-content d-flex align-items-center">
                            <div class="w-100">
                                <h3 class="auth-title text-center">Chào mừng trở lại!</h3>
                                <p class="text-center text-muted mb-4">Vui lòng đăng nhập để tiếp tục mua sắm và nhận ưu đãi riêng.</p>

                                <?php if(!empty($message)) echo $message; ?>

                                <form method="POST" action="index.php?controller=user&action=login">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Nhập địa chỉ email của bạn" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-uppercase text-muted d-flex justify-content-between">
                                            <span>Mật khẩu</span>
                                            <a href="#" class="text-brand text-decoration-none text-lowercase">Quên mật khẩu?</a>
                                        </label>
                                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-auth w-100 mb-4">Đăng Nhập</button>
                                    
                                    <p class="text-center mb-0">
                                        Bạn chưa có tài khoản? 
                                        <a href="index.php?controller=user&action=register" class="text-brand fw-bold text-decoration-none border-bottom border-brand">Đăng ký ngay</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'views/layout/footer.php'; ?>