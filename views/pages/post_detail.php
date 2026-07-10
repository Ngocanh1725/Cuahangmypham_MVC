<?php 
$pageTitle = htmlspecialchars($post['title']) . " - Glow Magazine"; 
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 

// Format date
$createdAt = isset($post['created_at']) ? date('d/m/Y', strtotime($post['created_at'])) : 'Gần đây';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <!-- Nội dung chính -->
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="index.php?controller=page&action=blog" class="text-decoration-none text-muted">Tạp chí</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                </ol>
            </nav>

            <div class="mb-5 text-center">
                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mb-3">Glow Magazine</span>
                <h1 class="fw-bold display-5 mb-4" style="color: var(--brand-dark, #be185d);"><?= htmlspecialchars($post['title']) ?></h1>
                <div class="d-flex align-items-center justify-content-center text-muted">
                    <div class="d-flex align-items-center me-4">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="rounded-circle me-2" width="40" height="40" alt="Admin">
                        <span>Đăng bởi <strong>Ban Biên Tập</strong></span>
                    </div>
                    <div>
                        <i class="far fa-calendar-alt me-1"></i> <?= $createdAt ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($post['image'])): ?>
            <div class="mb-5 rounded-4 overflow-hidden shadow-sm">
                <img src="<?= htmlspecialchars($post['image']) ?>" class="img-fluid w-100" style="max-height: 500px; object-fit: cover;" alt="<?= htmlspecialchars($post['title']) ?>" onerror="this.src='https://via.placeholder.com/1000x500?text=No+Image';">
            </div>
            <?php endif; ?>

            <div class="post-content" style="font-size: 1.1rem; line-height: 1.8; color: #444;">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>

            <!-- Chia sẻ -->
            <div class="mt-5 pt-4 border-top d-flex align-items-center justify-content-between">
                <span class="fw-bold">Chia sẻ bài viết này:</span>
                <div>
                    <button class="btn btn-outline-primary rounded-circle me-2" style="width: 40px; height: 40px;"><i class="fab fa-facebook-f"></i></button>
                    <button class="btn btn-outline-info rounded-circle me-2" style="width: 40px; height: 40px;"><i class="fab fa-twitter"></i></button>
                    <button class="btn btn-outline-dark rounded-circle" style="width: 40px; height: 40px;"><i class="fas fa-link"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bài viết liên quan -->
<?php if (!empty($relatedPosts)): ?>
<div class="bg-light py-5 mt-5">
    <div class="container">
        <h4 class="fw-bold mb-4 text-center">Có thể bạn sẽ thích</h4>
        <div class="row justify-content-center g-4">
            <?php foreach ($relatedPosts as $rPost): 
                $timeAgo = "Gần đây";
                if (isset($rPost['created_at'])) {
                    $time = strtotime($rPost['created_at']);
                    $diff = time() - $time;
                    if ($diff < 3600) $timeAgo = floor($diff / 60) . " phút trước";
                    elseif ($diff < 86400) $timeAgo = floor($diff / 3600) . " giờ trước";
                    else $timeAgo = floor($diff / 86400) . " ngày trước";
                }
            ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <a href="index.php?controller=page&action=post&id=<?= $rPost['id'] ?>"><img src="<?= htmlspecialchars($rPost['image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($rPost['title']) ?>" onerror="this.src='https://via.placeholder.com/500x300?text=No+Image';"></a>
                    <div class="card-body p-4">
                        <h6 class="fw-bold mt-2"><a href="index.php?controller=page&action=post&id=<?= $rPost['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($rPost['title']) ?></a></h6>
                        <p class="text-muted small mt-2 mb-0"><?= htmlspecialchars(mb_substr($rPost['content'], 0, 80)) ?>...</p>
                    </div>
                    <div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
                        <small class="text-muted"><i class="far fa-clock me-1"></i> <?= $timeAgo ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layout/footer.php'; ?>
