<!-- 1. Dải thông báo (Announcement Bar) -->
<div class="rhode-announcement-bar">
    <span>✨ Miễn phí vận chuyển cho mọi đơn hàng từ 500k ✨</span>
</div>

<!-- 2. Navbar Lơ Lửng (Floating Navbar) -->
<div class="rhode-header-wrapper">
    <header class="rhode-navbar">
        
        <!-- Logo -->
        <a href="index.php" class="rhode-logo">
            glow.
        </a>

        <!-- Menu Điều Hướng (Desktop) -->
        <?php
        if (!class_exists('MenuModel')) {
            require_once 'models/MenuModel.php';
        }
        global $db;
        $menuModel = new MenuModel($db);
        $headerMenus = $menuModel->getMenuTree('header');
        ?>
        <nav class="rhode-nav-menu d-none d-lg-flex">
            <?php foreach ($headerMenus as $hmenu): ?>
                <?php if (!empty($hmenu['children'])): ?>
                    <div class="dropdown">
                        <a href="<?php echo htmlspecialchars($hmenu['url']); ?>" class="dropdown-toggle" data-bs-toggle="dropdown" target="<?php echo htmlspecialchars($hmenu['target']); ?>" style="border-bottom: none;">
                            <?php echo htmlspecialchars($hmenu['title']); ?>
                        </a>
                        <ul class="dropdown-menu border-0 shadow-sm mt-3" style="border-radius: 10px;">
                            <?php foreach ($hmenu['children'] as $child): ?>
                                <li><a class="dropdown-item py-2" href="<?php echo htmlspecialchars($child['url']); ?>" target="<?php echo htmlspecialchars($child['target']); ?>"><?php echo htmlspecialchars($child['title']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?php echo htmlspecialchars($hmenu['url']); ?>" target="<?php echo htmlspecialchars($hmenu['target']); ?>"><?php echo htmlspecialchars($hmenu['title']); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>

        <!-- Cụm Icon Bên Phải -->
        <div class="rhode-nav-icons">
            
            <!-- Nút Tìm Kiếm -->
            <a href="#" class="rhode-icon-btn" title="Tìm kiếm" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="fas fa-search"></i>
            </a>
            
            <!-- Nút Tài Khoản -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown d-inline-block">
                    <a href="#" class="rhode-icon-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Tài khoản">
                        <i class="far fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-3 border-0 shadow-lg" style="border-radius: 16px; padding: 10px;">
                        <li><h6 class="dropdown-header text-muted fw-bold">Chào, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h6></li>
                        <?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2): ?>
                            <li><a class="dropdown-item rounded-3 py-2" href="index.php?controller=admin&action=index"><i class="fas fa-tachometer-alt me-2 text-muted"></i> Trang Quản Trị</a></li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item rounded-3 py-2" href="index.php?controller=user&action=profile"><i class="fas fa-user-circle me-2 text-muted"></i> Hồ sơ cá nhân</a></li>
                        <li><a class="dropdown-item rounded-3 py-2" href="index.php?controller=user&action=orders"><i class="fas fa-box me-2 text-muted"></i> Lịch sử đơn hàng</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item rounded-3 py-2 text-danger fw-bold" href="index.php?controller=user&action=logout"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="index.php?controller=user&action=login" class="rhode-icon-btn" title="Đăng nhập">
                    <i class="far fa-user"></i>
                </a>
            <?php endif; ?>

            <!-- Nút Giỏ Hàng (Mở Offcanvas) -->
            <a href="#" class="rhode-icon-btn position-relative" title="Giỏ hàng" data-bs-toggle="offcanvas" data-bs-target="#miniCartOffcanvas" aria-controls="miniCartOffcanvas">
                <i class="fas fa-shopping-bag"></i>
                <span class="rhode-cart-badge d-none" id="miniCartCount">0</span>
            </a>

        </div>
    </header>
</div>

<!-- ==========================================
     GIỎ HÀNG TRƯỢT (OFFCANVAS MINI-CART)
     ========================================== -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="miniCartOffcanvas" aria-labelledby="miniCartLabel" style="width: 400px; border-left: none; box-shadow: -10px 0 30px rgba(0,0,0,0.05);">
    <div class="offcanvas-header border-bottom py-3">
        <h5 class="offcanvas-title fw-bold font-playfair" id="miniCartLabel">Giỏ Hàng Của Bạn</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column" style="background: #fdfdfd;">
        <!-- Container chứa list sản phẩm -->
        <div id="miniCartContent" class="flex-grow-1 overflow-auto p-3">
            <div class="text-center py-5">
                <div class="spinner-border text-brand" role="status"><span class="visually-hidden">Loading...</span></div>
            </div>
        </div>
        
        <!-- Footer giỏ hàng -->
        <div class="p-3 bg-white border-top shadow-sm" id="miniCartFooter" style="display:none;">
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted fw-bold">Tổng cộng:</span>
                <span class="fw-bold fs-5 text-dark" id="miniCartTotal">0đ</span>
            </div>
            <a href="index.php?controller=cart&action=checkout" class="btn btn-brand w-100 py-3 fw-bold rounded-3" style="font-size:1.1rem;">Tiến Hành Thanh Toán</a>
            <a href="index.php?controller=cart&action=index" class="btn btn-light w-100 mt-2 py-2 text-muted rounded-3">Xem chi tiết giỏ hàng</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const miniCartOffcanvas = document.getElementById('miniCartOffcanvas');
    if (miniCartOffcanvas) {
        miniCartOffcanvas.addEventListener('show.bs.offcanvas', function () {
            loadMiniCart();
        });
    }
    // Load count initially
    updateCartCount();
});

function loadMiniCart() {
    const content = document.getElementById('miniCartContent');
    const footer = document.getElementById('miniCartFooter');
    content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-brand" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    
    fetch('index.php?controller=cart&action=ajaxGetCart')
    .then(res => res.json())
    .then(data => {
        if (data.items.length === 0) {
            content.innerHTML = '<div class="text-center py-5 mt-5"><i class="fas fa-shopping-bag fa-3x text-muted mb-3 opacity-50"></i><p class="text-muted">Giỏ hàng của bạn đang trống.</p><a href="index.php?controller=product&action=index" class="btn btn-outline-dark mt-2 rounded-pill px-4">Mua sắm ngay</a></div>';
            footer.style.display = 'none';
        } else {
            let html = '';
            data.items.forEach(item => {
                html += `
                <div class="d-flex align-items-center mb-3 bg-white p-2 rounded-3 shadow-sm border">
                    <img src="${item.image}" alt="${item.name}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-1 fw-bold text-truncate" style="max-width: 180px; font-size: 0.95rem;">${item.name}</h6>
                        <div class="text-brand fw-bold mb-2" style="font-size: 0.9rem;">${new Intl.NumberFormat('vi-VN').format(item.price)}đ</div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="input-group input-group-sm" style="width: 100px;">
                                <button class="btn btn-outline-secondary px-2" type="button" onclick="updateMiniCartQty(${item.id}, ${item.qty - 1})">-</button>
                                <input type="text" class="form-control text-center px-1" value="${item.qty}" readonly>
                                <button class="btn btn-outline-secondary px-2" type="button" onclick="updateMiniCartQty(${item.id}, ${item.qty + 1})">+</button>
                            </div>
                            <button class="btn btn-link text-danger p-0" onclick="removeMiniCartItem(${item.id})"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                </div>`;
            });
            content.innerHTML = html;
            document.getElementById('miniCartTotal').innerText = new Intl.NumberFormat('vi-VN').format(data.total) + 'đ';
            footer.style.display = 'block';
        }
        updateBadge(data.count);
    })
    .catch(err => {
        content.innerHTML = '<div class="alert alert-danger m-3">Lỗi tải giỏ hàng!</div>';
    });
}

function updateMiniCartQty(id, qty) {
    if (qty < 1) return;
    fetch('index.php?controller=cart&action=ajaxUpdateCart', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&qty=${qty}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            loadMiniCart();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    });
}

function removeMiniCartItem(id) {
    fetch('index.php?controller=cart&action=ajaxRemoveItem', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            loadMiniCart();
        }
    });
}

function updateCartCount() {
    fetch('index.php?controller=cart&action=ajaxGetCartCount')
    .then(res => res.json())
    .then(data => {
        updateBadge(data.count);
    });
}

function updateBadge(count) {
    const badge = document.getElementById('miniCartCount');
    if (badge) {
        if (count > 0) {
            badge.innerText = count;
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }
}
</script>

<!-- ==========================================
     MODAL TÌM KIẾM
     ========================================== -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg border-0">
        <div class="modal-content rounded-4 border-0" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); padding: 20px;">
            <div class="modal-body p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="font-playfair fw-bold mb-0 text-dark">TÌM KIẾM SẢN PHẨM</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="GET" class="position-relative">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="index">
                    <input type="text" name="keyword" class="form-control form-control-lg border-0 border-bottom rounded-0 px-0 fs-4 text-dark bg-transparent shadow-none" placeholder="Bạn đang tìm gì hôm nay?" required style="border-color: #be185d !important; outline: none; box-shadow: none;">
                    <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y text-brand p-0 fs-4 border-0 bg-transparent">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <div class="mt-4 text-muted small">
                    <strong>Gợi ý:</strong> Serum, Toner, Kem dưỡng, Son môi...
                </div>
            </div>
        </div>
    </div>
</div>