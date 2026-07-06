<?php 
/**
 * Trang Chủ - Phong cách Bento Box hiện đại
 * Tối ưu hóa hiển thị và bố cục theo chuẩn Rhode Skin
 * Dữ liệu banner được lấy từ CSDL thông qua biến $banners
 */

// Phân loại banners theo vị trí (position)
$heroBanners = [];
$bentoBanners = [];
if (!empty($banners)) {
    foreach ($banners as $b) {
        if ($b['position'] == 'hero') {
            $heroBanners[] = $b;
        } else {
            $bentoBanners[$b['position']] = $b;
        }
    }
}

// Fallback nếu chưa có hero banner nào
if (empty($heroBanners)) {
    $heroBanners[] = [
        'title' => 'Glow Your Way.',
        'description' => 'Khám phá bí quyết làn da căng bóng với dòng sản phẩm mới nhất.',
        'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1600',
        'link' => 'index.php?controller=product'
    ];
}

$extraCSS = "
<style>
    /* Biến thiết kế */
    :root {
        --bento-gap: 20px;
        --radius-bento: 30px;
    }

    /* Hero Carousel */
    .hero-carousel {
        margin-top: -80px;
        border-radius: 0 0 var(--radius-bento) var(--radius-bento);
        overflow: hidden;
    }
    .hero-carousel .carousel-item {
        height: 70vh;
        min-height: 500px;
        position: relative;
    }
    .hero-carousel .carousel-item img.hero-bg {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
    }
    .hero-overlay {
        position: relative;
        z-index: 2;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        padding: 50px;
        border-radius: var(--radius-bento);
        max-width: 450px;
        margin-left: 5%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .hero-carousel .carousel-indicators {
        bottom: 30px;
    }
    .hero-carousel .carousel-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(0,0,0,0.4);
        border: 2px solid #fff;
        margin: 0 5px;
    }
    .hero-carousel .carousel-indicators button.active {
        background-color: #c97878;
    }
    .hero-carousel .carousel-control-prev,
    .hero-carousel .carousel-control-next {
        width: 50px;
        height: 50px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,0.7);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .hero-carousel:hover .carousel-control-prev,
    .hero-carousel:hover .carousel-control-next {
        opacity: 1;
    }
    .hero-carousel .carousel-control-prev { left: 20px; }
    .hero-carousel .carousel-control-next { right: 20px; }
    .hero-carousel .carousel-control-prev-icon,
    .hero-carousel .carousel-control-next-icon {
        filter: invert(1) grayscale(100);
        width: 20px;
        height: 20px;
    }

    /* Bento Grid Layout */
    .bento-container {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: var(--bento-gap);
        padding: 20px 0;
    }
    
    .bento-card {
        border-radius: var(--radius-bento);
        overflow: hidden;
        background: #f8f8f8;
        position: relative;
        transition: transform 0.4s ease;
        display: flex;
        flex-direction: column;
    }
    
    .bento-card:hover { transform: translateY(-5px); }
    
    .bento-img { width: 100%; height: 100%; object-fit: cover; }
    
    /* Cấu hình các khối Bento bằng Aspect Ratio để không bị lệch/cắt ảnh */
    .col-span-9 { grid-column: span 9; aspect-ratio: 2.5 / 1; }
    .col-span-3 { grid-column: span 3; aspect-ratio: 5 / 6; }
    .col-span-6 { grid-column: span 6; aspect-ratio: 3 / 2; }
    .col-span-3-tall { grid-column: span 3; aspect-ratio: 3 / 4; }
    
    .bento-content {
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        height: 100%;
    }

    .bento-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }
    .bento-card-link:hover { color: inherit; }

    /* Responsive */
    @media (max-width: 992px) {
        .col-span-9, .col-span-3, .col-span-6, .col-span-3-tall { 
            grid-column: span 12; 
            aspect-ratio: auto;
            height: 350px;
        }
    }
</style>
";

include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<!-- Hero Carousel - Hiển thị tất cả banners có position='hero' -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
    
    <!-- Indicators (chấm tròn) -->
    <?php if (count($heroBanners) > 1): ?>
    <div class="carousel-indicators">
        <?php foreach ($heroBanners as $i => $hb): ?>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $i; ?>" <?php echo ($i === 0) ? 'class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo $i + 1; ?>"></button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Slides -->
    <div class="carousel-inner">
        <?php foreach ($heroBanners as $i => $hero): ?>
        <div class="carousel-item <?php echo ($i === 0) ? 'active' : ''; ?>">
            <img src="<?php echo htmlspecialchars($hero['image']); ?>" alt="<?php echo htmlspecialchars($hero['title']); ?>" class="hero-bg">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                <div class="hero-overlay">
                    <h1 class="h1 font-serif mb-4"><?php echo htmlspecialchars($hero['title']); ?></h1>
                    <?php if (!empty($hero['description'])): ?>
                        <p class="mb-4"><?php echo htmlspecialchars($hero['description']); ?></p>
                    <?php endif; ?>
                    <a href="<?php echo htmlspecialchars($hero['link'] ?? 'index.php?controller=product'); ?>" class="btn btn-dark rounded-pill px-4 py-2">Khám phá ngay</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Nút trái / phải -->
    <?php if (count($heroBanners) > 1): ?>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Trước</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Sau</span>
    </button>
    <?php endif; ?>
</div>

<div class="container mt-5">
    <!-- Bento Grid - Lấy từ banners có position='bento_1/2/3/4' -->
    <div class="bento-container">
        
        <!-- Ô 1: Ảnh lớn (bento_1) -->
        <div class="bento-card col-span-9">
            <?php if (isset($bentoBanners['bento_1'])): 
                $b1 = $bentoBanners['bento_1']; ?>
                <a href="<?php echo htmlspecialchars($b1['link'] ?? '#'); ?>" class="bento-card-link">
                    <img src="<?php echo htmlspecialchars($b1['image']); ?>" alt="<?php echo htmlspecialchars($b1['title']); ?>" class="bento-img">
                </a>
            <?php else: ?>
                <img src="https://images.unsplash.com/photo-1618331835717-801e976710b2?auto=format&fit=crop&w=800" alt="Feature" class="bento-img">
            <?php endif; ?>
        </div>
        
        <!-- Ô 2: Nút Xem thêm (bento_2) có thể chỉnh sửa từ Admin -->
        <?php 
            $b2 = $bentoBanners['bento_2'] ?? null;
            $btnText = !empty($b2['title']) ? $b2['title'] : 'XEM THÊM';
            $bgImg = !empty($b2['image']) ? $b2['image'] : '';
            // Lấy link của bento_2, nếu không có thì lấy link của bento_1
            $linkXemThem = !empty($b2['link']) ? $b2['link'] : (isset($bentoBanners['bento_1']) ? ($bentoBanners['bento_1']['link'] ?? '#') : 'index.php?controller=product');
        ?>
        <div class="bento-card col-span-3 d-flex align-items-center justify-content-center" style="position: relative; background: #f8f8f8; overflow: hidden;">
            <?php if ($bgImg): ?>
                <!-- Ảnh mờ đằng sau -->
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('<?php echo htmlspecialchars($bgImg); ?>'); background-size: cover; background-position: center; filter: blur(8px); opacity: 0.7; transform: scale(1.1);"></div>
            <?php endif; ?>
            
            <a href="<?php echo htmlspecialchars($linkXemThem); ?>" class="btn rounded-pill d-flex align-items-center justify-content-center" style="position: relative; z-index: 2; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.8); padding: 10px 25px; font-weight: 600; font-size: 0.95rem; color: #333; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                <?php echo htmlspecialchars($btnText); ?>
            </a>
        </div>

        <!-- Ô 3A: Sản phẩm lẻ 1 -->
        <div class="bento-card col-span-3-tall" style="background: #fff; border: 1px solid #eee;">
            <?php if (!empty($bestSellers[0])): $p1 = $bestSellers[0]; ?>
                <a href="index.php?controller=product&action=detail&id=<?php echo $p1['id']; ?>" class="bento-card-link d-flex flex-column">
                    <div style="height: 65%; overflow: hidden;">
                        <img src="<?php echo htmlspecialchars($p1['image']); ?>" alt="<?php echo htmlspecialchars($p1['name']); ?>" class="bento-img" style="object-fit: contain; padding: 15px; background: #f9f9f9;">
                    </div>
                    <div class="bento-content text-center" style="height: 35%; padding: 15px; justify-content: center; background: #fff;">
                        <h5 class="font-serif text-truncate mb-2" style="font-size: 1.05rem; color: #333;"><?php echo htmlspecialchars($p1['name']); ?></h5>
                        <p class="text-danger fw-bold mb-0" style="font-size: 1.1rem;"><?php echo number_format($p1['price'], 0, ',', '.'); ?>đ</p>
                    </div>
                </a>
            <?php else: ?>
                <div class="bg-light h-100 d-flex align-items-center justify-content-center text-muted">Sản phẩm 1</div>
            <?php endif; ?>
        </div>

        <!-- Ô 3B: Sản phẩm lẻ 2 -->
        <div class="bento-card col-span-3-tall" style="background: #fff; border: 1px solid #eee;">
            <?php if (!empty($bestSellers[1])): $p2 = $bestSellers[1]; ?>
                <a href="index.php?controller=product&action=detail&id=<?php echo $p2['id']; ?>" class="bento-card-link d-flex flex-column">
                    <div style="height: 65%; overflow: hidden;">
                        <img src="<?php echo htmlspecialchars($p2['image']); ?>" alt="<?php echo htmlspecialchars($p2['name']); ?>" class="bento-img" style="object-fit: contain; padding: 15px; background: #f9f9f9;">
                    </div>
                    <div class="bento-content text-center" style="height: 35%; padding: 15px; justify-content: center; background: #fff;">
                        <h5 class="font-serif text-truncate mb-2" style="font-size: 1.05rem; color: #333;"><?php echo htmlspecialchars($p2['name']); ?></h5>
                        <p class="text-danger fw-bold mb-0" style="font-size: 1.1rem;"><?php echo number_format($p2['price'], 0, ',', '.'); ?>đ</p>
                    </div>
                </a>
            <?php else: ?>
                <div class="bg-light h-100 d-flex align-items-center justify-content-center text-muted">Sản phẩm 2</div>
            <?php endif; ?>
        </div>

        <!-- Ô 4: Khối phụ phải (bento_4) -->
        <div class="bento-card col-span-6">
            <?php if (isset($bentoBanners['bento_4'])): 
                $b4 = $bentoBanners['bento_4']; ?>
                <a href="<?php echo htmlspecialchars($b4['link'] ?? '#'); ?>" class="bento-card-link">
                    <?php if (!empty($b4['image'])): ?>
                        <img src="<?php echo htmlspecialchars($b4['image']); ?>" alt="<?php echo htmlspecialchars($b4['title']); ?>" class="bento-img">
                    <?php endif; ?>
                    <div class="bento-content" style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.6)); color: white;">
                        <h3 class="font-serif"><?php echo htmlspecialchars($b4['title']); ?></h3>
                        <?php if (!empty($b4['description'])): ?>
                            <p><?php echo htmlspecialchars($b4['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php else: ?>
                <div class="bg-info h-100">
                    <div class="bento-content">
                        <h3 class="font-serif">Ưu đãi độc quyền</h3>
                        <p>Giảm 20% cho đơn hàng đầu tiên của bạn.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>