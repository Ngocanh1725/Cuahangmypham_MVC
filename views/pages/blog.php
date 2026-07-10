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
    <?php if (!empty($posts)): 
        $heroPost = $posts[0];
    ?>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="row g-0">
            <div class="col-md-7">
                <img src="<?= htmlspecialchars($heroPost['image']) ?>" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 400px;" alt="<?= htmlspecialchars($heroPost['title']) ?>" onerror="this.src='https://via.placeholder.com/1000x600?text=No+Image';">
            </div>
            <div class="col-md-5 d-flex align-items-center p-5 bg-white">
                <div>
                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mb-3">Nổi bật</span>
                    <h3 class="fw-bold mb-3" style="color: var(--brand-dark, #be185d);"><a href="index.php?controller=page&action=post&id=<?= $heroPost['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($heroPost['title']) ?></a></h3>
                    <p class="text-muted mb-4"><?= htmlspecialchars(mb_substr($heroPost['content'], 0, 150)) ?>...</p>
                    <a href="index.php?controller=page&action=post&id=<?= $heroPost['id'] ?>" class="btn btn-outline-dark rounded-pill px-4">Đọc tiếp <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách bài viết -->
    <h4 class="fw-bold mb-4 border-bottom pb-2">Bài viết mới nhất</h4>
    <div class="row g-4">
        <?php for ($i = 1; $i < count($posts); $i++): 
            $post = $posts[$i];
            // Format time ago
            $timeAgo = "Gần đây";
            if (isset($post['created_at'])) {
                $time = strtotime($post['created_at']);
                $diff = time() - $time;
                if ($diff < 3600) $timeAgo = floor($diff / 60) . " phút trước";
                elseif ($diff < 86400) $timeAgo = floor($diff / 3600) . " giờ trước";
                else $timeAgo = floor($diff / 86400) . " ngày trước";
            }
        ?>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <a href="index.php?controller=page&action=post&id=<?= $post['id'] ?>"><img src="<?= htmlspecialchars($post['image']) ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="<?= htmlspecialchars($post['title']) ?>" onerror="this.src='https://via.placeholder.com/500x300?text=No+Image';"></a>
                <div class="card-body p-4">
                    <small class="text-primary fw-bold text-uppercase">Glow Magazine</small>
                    <h5 class="fw-bold mt-2"><a href="index.php?controller=page&action=post&id=<?= $post['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($post['title']) ?></a></h5>
                    <p class="text-muted small mt-2"><?= htmlspecialchars(mb_substr($post['content'], 0, 100)) ?>...</p>
                </div>
                <div class="card-footer bg-white border-0 px-4 pb-4">
                    <small class="text-muted"><i class="far fa-clock me-1"></i> <?= $timeAgo ?></small>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
    <?php else: ?>
        <div class="alert alert-info text-center mt-5">Hiện tại chưa có bài viết nào!</div>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>