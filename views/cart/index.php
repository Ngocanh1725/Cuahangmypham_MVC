<?php 
$pageTitle = "Giỏ hàng của bạn - Glow Cosmetics"; 
// Load Layout
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: var(--brand-dark, #be185d);">Giỏ Hàng Của Bạn</h2>

    <?php if (isset($_SESSION['cart_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Lỗi!</strong> <?php echo htmlspecialchars($_SESSION['cart_error']); unset($_SESSION['cart_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['cart_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-check-circle me-2"></i>Thành công!</strong> <?php echo htmlspecialchars($_SESSION['cart_success']); unset($_SESSION['cart_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <div class="text-center bg-white p-5 rounded-4 shadow-sm border">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Giỏ hàng đang trống</h4>
            <p>Hãy tìm thêm những sản phẩm làm đẹp tuyệt vời nhé!</p>
            <a href="index.php" class="btn text-white rounded-pill px-4 mt-3" style="background-color: var(--brand-dark, #be185d);">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Cột Danh sách sản phẩm -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <form method="POST" action="index.php?controller=cart&action=update">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="text-muted table-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Đơn giá</th>
                                            <th class="text-center">Số lượng</th>
                                            <th>Tạm tính</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cartItems as $item): 
                                            // Xử lý ảnh
                                            $imgUrl = !empty($item['image']) && strpos($item['image'], 'http') !== false ? $item['image'] : $item['image'];
                                            if (empty($item['image'])) $imgUrl = 'https://via.placeholder.com/80';
                                        ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="<?php echo $imgUrl; ?>" class="shadow-sm border rounded-3" style="width: 70px; height: 70px; object-fit: cover;" alt="">
                                                        <div>
                                                            <h6 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                            <small class="text-muted"><?php echo htmlspecialchars($item['category']); ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-bold"><?php echo number_format($item['price']); ?>đ</td>
                                                <td class="text-center">
                                                    <input type="number" name="qty[<?php echo $item['id']; ?>]" value="<?php echo $item['qty']; ?>" min="0" class="form-control text-center mx-auto" style="width: 70px;">
                                                </td>
                                                <td class="fw-bold text-danger"><?php echo number_format($item['subtotal']); ?>đ</td>
                                                <td class="text-end">
                                                    <a href="index.php?controller=cart&action=remove&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="index.php" class="btn btn-light border rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i>Tiếp tục mua</a>
                                <button type="submit" class="btn btn-outline-secondary rounded-pill px-4"><i class="fas fa-sync-alt me-2"></i>Cập nhật giỏ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Cột Tổng tiền & Thanh toán -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-3">Tóm tắt đơn hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-bold"><?php echo number_format($totalPrice); ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí giao hàng</span>
                            <span class="text-success fw-bold">Miễn phí</span>
                        </div>
                        
                        <hr class="my-4 text-muted">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5 text-dark">Tổng cộng</span>
                            <span class="fw-bold fs-4" style="color: var(--brand-dark, #be185d);"><?php echo number_format($totalPrice); ?>đ</span>
                        </div>
                        
                        <a href="index.php?controller=cart&action=checkout" class="btn text-white w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm" style="background-color: var(--brand-dark, #be185d);">
                            Tiến hành thanh toán
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>