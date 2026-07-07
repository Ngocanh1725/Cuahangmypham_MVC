<?php 
$pageTitle = "Đăng Ký - Glow Beauty"; 
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
        background-image: url('https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?q=80&w=1974&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        min-height: 500px;
        position: relative;
    }
    .auth-bg::after {
        content: '';
        position: absolute;
        top:0; left:0; right:0; bottom:0;
        background: rgba(0,0,0,0.1);
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
        padding: 12px 20px;
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
    .btn-otp {
        border-radius: 12px;
        padding: 12px 15px;
        font-weight: 600;
    }
</style>

<div class="auth-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card auth-card">
                    <div class="row g-0 flex-md-row-reverse">
                        <div class="col-md-5 auth-bg d-none d-md-block">
                        </div>
                        <div class="col-md-7 auth-content d-flex align-items-center">
                            <div class="w-100">
                                <h3 class="auth-title text-center">Tạo Tài Khoản</h3>
                                <p class="text-center text-muted mb-4">Tham gia cùng chúng tôi để nhận đặc quyền thành viên và ưu đãi độc quyền.</p>

                                <?php if(!empty($message)) echo $message; ?>

                                <form method="POST" action="index.php?controller=user&action=register" id="registerForm">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Họ và tên <span class="text-danger">*</span></label>
                                            <input type="text" name="fullname" class="form-control" placeholder="VD: Nguyễn Văn A" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Số điện thoại <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" placeholder="09xxxxxxx" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                                    </div>
                                    
                                    <!-- Mô phỏng OTP -->
                                    <div class="mb-3" id="otpSection" style="display:none;">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Mã xác thực OTP <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="otp" class="form-control" placeholder="Nhập mã 6 số gửi về Email/SĐT">
                                            <button class="btn btn-outline-secondary" type="button" onclick="alert('Đã gửi lại mã OTP!')">Gửi lại</button>
                                        </div>
                                        <div class="form-text text-success mt-1"><i class="fas fa-check-circle me-1"></i>Đã gửi mã xác thực!</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" name="password" class="form-control" placeholder="Tạo mật khẩu" required minlength="6">
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required minlength="6">
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-light w-100 mb-3 border fw-bold text-dark py-3" id="btnSendOtp" onclick="showOtp()">
                                        <i class="fas fa-shield-alt me-2 text-success"></i> Xác thực & Tiếp tục
                                    </button>
                                    
                                    <button type="submit" class="btn btn-auth w-100 mb-4" id="btnSubmit" style="display:none;">Hoàn Tất Đăng Ký</button>
                                    
                                    <p class="text-center mb-0">
                                        Đã có tài khoản? 
                                        <a href="index.php?controller=user&action=login" class="text-brand fw-bold text-decoration-none border-bottom border-brand">Đăng nhập</a>
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

<script>
function showOtp() {
    let form = document.getElementById('registerForm');
    if(form.checkValidity()) {
        document.getElementById('otpSection').style.display = 'block';
        document.getElementById('btnSendOtp').style.display = 'none';
        document.getElementById('btnSubmit').style.display = 'block';
    } else {
        form.reportValidity();
    }
}
</script>

<?php include 'views/layout/footer.php'; ?>
