<?php 
$pageTitle = "Hồ Sơ Cá Nhân - Glow Beauty"; 
include 'views/layout/header.php'; 
?>
<style>
    .profile-sidebar {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .member-card {
        border-radius: 16px;
        padding: 25px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        background: linear-gradient(135deg, <?= $user['color_code'] ?? '#8a6d3b' ?> 0%, <?= $user['color_code'] ?? '#cda45c' ?> 100%);
        box-shadow: 0 10px 20px <?= $user['color_code'] ?? '#8a6d3b' ?>40;
    }
    .member-card::after {
        content: '\f005';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: -20px;
        bottom: -30px;
        font-size: 150px;
        opacity: 0.1;
    }
    .tier-badge {
        background: rgba(255,255,255,0.2);
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 1px;
    }
    .form-control {
        border-radius: 12px;
        padding: 12px 20px;
        border: 1px solid #eee;
        background: #fdfdfd;
    }
    .form-control:focus {
        border-color: var(--brand-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.1);
    }
    .profile-nav-link {
        display: block;
        padding: 12px 20px;
        color: #555;
        border-radius: 10px;
        margin-bottom: 5px;
        font-weight: 500;
        transition: all 0.2s;
        text-decoration: none;
    }
    .profile-nav-link:hover, .profile-nav-link.active {
        background: #fff0f0;
        color: var(--brand-color);
    }
    .custom-file-upload {
        display: inline-block;
        padding: 8px 20px;
        cursor: pointer;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .custom-file-upload:hover {
        background: #e9ecef;
    }
</style>

<div class="bg-light py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="profile-sidebar text-center">
                    <img src="<?= !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($user['full_name']).'&background=random' ?>" alt="Avatar" class="profile-avatar">
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['full_name']) ?></h5>
                    <p class="text-muted small mb-4"><?= htmlspecialchars($user['email']) ?></p>

                    <div class="text-start">
                        <a href="index.php?controller=user&action=profile" class="profile-nav-link active"><i class="fas fa-user-circle me-2 w-20px"></i> Hồ sơ của tôi</a>
                        <a href="index.php?controller=user&action=orders" class="profile-nav-link"><i class="fas fa-shopping-bag me-2 w-20px"></i> Đơn hàng mua</a>
                        <a href="index.php?controller=user&action=logout" class="profile-nav-link text-danger mt-3"><i class="fas fa-sign-out-alt me-2 w-20px"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-9">
                <!-- Thẻ Thành Viên -->
                <div class="member-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="mb-2">
                            <span class="tier-badge"><i class="<?= htmlspecialchars($user['icon'] ?? 'fas fa-star') ?> me-1"></i> THÀNH VIÊN <?= strtoupper($user['tier_name'] ?? 'ĐỒNG') ?></span>
                        </div>
                        <h3 class="fw-bold mb-1 mt-3"><?= number_format($user['points'] ?? 0) ?> Điểm</h3>
                        <p class="mb-0 small opacity-75">Sử dụng điểm để đổi ưu đãi ở lần mua sau</p>
                    </div>
                    <div class="text-end">
                        <p class="mb-1 small opacity-75">Ưu đãi hạng</p>
                        <h4 class="fw-bold mb-0">Giảm <?= number_format($user['discount_percent'] ?? 0, 1) ?>%</h4>
                    </div>
                </div>

                <!-- Form Cập Nhật -->
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4">Thông Tin Cá Nhân</h4>
                        
                        <?php if(!empty($message)) echo $message; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Họ và tên</label>
                                    <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Email <span class="fw-normal text-lowercase">(Không thể đổi)</span></label>
                                    <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Ngày sinh</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="<?= htmlspecialchars($user['date_of_birth'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase d-block">Ảnh đại diện</label>
                                <label class="custom-file-upload">
                                    <input type="file" name="avatar" class="d-none" accept="image/*" onchange="document.getElementById('fileName').innerText = this.files[0].name">
                                    <i class="fas fa-cloud-upload-alt me-2"></i> Tải ảnh lên
                                </label>
                                <span id="fileName" class="ms-2 small text-muted"></span>
                            </div>

                            <hr class="my-4 border-light">

                            <h5 class="fw-bold mb-4">Thay Đổi Mật Khẩu</h5>
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control" placeholder="Để trống nếu không muốn đổi">
                                <small class="text-muted mt-1 d-block">Nhập mật khẩu mới nếu bạn muốn thay đổi mật khẩu hiện tại.</small>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-brand px-5 py-2 fw-bold rounded-pill shadow-sm">Lưu Thay Đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>