<?php
/**
 * Trang Chủ - Phong cách Beauty Box
 * Sections: Hero Slider → Brands → Flash Sale → Exclusive → Xu Hướng → Mùa Hè → Top Trend → Blog
 */
include 'views/layout/header.php';
include 'views/layout/navbar.php';
?>

<!-- ============================
     STYLES RIÊNG CHO TRANG CHỦ
     ============================ -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
/* ---------- RESET / BASE ---------- */
.home-page { font-family: 'Inter', 'Nunito', sans-serif; background: #fff; }
.section-gap { margin-bottom: 48px; }
.container-home { max-width: 1200px; margin: 0 auto; padding: 0 16px; }

/* ---------- SECTION HEADER ---------- */
.section-header { text-align: center; margin-bottom: 24px; }
.section-title {
    font-size: 1.5rem; font-weight: 900; text-transform: uppercase;
    letter-spacing: 1px; color: #1a1a1a; margin-bottom: 0;
}
.section-subtitle { font-size: 0.9rem; color: #888; margin-top: 6px; }

/* ---------- TAB PILLS ---------- */
.tab-pills {
    display: flex; justify-content: center; gap: 10px;
    flex-wrap: wrap; margin-bottom: 20px;
}
.tab-pill {
    padding: 6px 22px; border-radius: 30px; font-size: 0.88rem;
    font-weight: 600; cursor: pointer; border: 1.5px solid #e0e0e0;
    background: #fff; color: #555; transition: all .2s ease;
    text-decoration: none; display: inline-block;
}
.tab-pill:hover, .tab-pill.active {
    background: #1a1a1a; color: #fff; border-color: #1a1a1a;
}

/* ---------- PRODUCT CARD ---------- */
.prod-card {
    background: #fff; border-radius: 14px; padding: 12px;
    text-align: center; position: relative; transition: all .25s ease;
    cursor: pointer; height: 100%; display: flex; flex-direction: column;
    border: 1px solid #f0f0f0;
}
.prod-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,.1); transform: translateY(-4px); border-color: #e8e8e8; }
.prod-card .wishlist-btn {
    position: absolute; top: 12px; right: 12px;
    background: rgba(255,255,255,.8); border: none; border-radius: 50%;
    width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
    color: #bbb; font-size: .9rem; transition: .2s; cursor: pointer; z-index: 2;
}
.prod-card .wishlist-btn:hover { color: #e91e63; background: #fff; }
.prod-card .discount-badge {
    position: absolute; top: 12px; left: 12px;
    background: #ff3b3b; color: #fff; font-size: .72rem; font-weight: 700;
    padding: 3px 8px; border-radius: 20px; z-index: 2;
}
.prod-img {
    width: 100%; height: 180px; object-fit: contain;
    margin-bottom: 12px; border-radius: 8px;
}
.prod-brand { font-size: .72rem; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: .5px; }
.prod-name {
    font-size: .88rem; color: #333; margin: 6px 0;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    flex-grow: 1; line-height: 1.4;
}
.prod-price-box { margin-top: auto; }
.prod-price { color: #d32f2f; font-weight: 800; font-size: 1rem; }
.prod-old-price { color: #bbb; font-size: .8rem; text-decoration: line-through; margin-left: 6px; }
.prod-rating { font-size: .78rem; color: #ffc107; margin-top: 4px; }
.prod-rating span { color: #999; }

/* ---------- SWIPER COMMON ---------- */
.swiper { padding: 8px 4px 32px !important; }
.swiper-button-next, .swiper-button-prev {
    background: #fff; width: 36px !important; height: 36px !important;
    border-radius: 50%; box-shadow: 0 2px 12px rgba(0,0,0,.15); color: #333 !important;
}
.swiper-button-next::after, .swiper-button-prev::after { font-size: .9rem !important; font-weight: 900 !important; }
.swiper-pagination-bullet-active { background: #1a1a1a !important; }

/* ---------- 1. HERO SLIDER ---------- */
.hero-swiper { width: 100%; }
.hero-swiper .swiper-wrapper { align-items: center; }
.hero-slide {
    width: 100%; aspect-ratio: 21/9; border-radius: 16px; overflow: hidden;
    position: relative; cursor: pointer;
}
@media(max-width: 768px) { .hero-slide { aspect-ratio: 16/9; } }
.hero-slide img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .5s ease;
}
.hero-slide:hover img { transform: scale(1.02); }
.hero-slide-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(120deg, rgba(0,0,0,.45) 0%, transparent 65%);
    display: flex; flex-direction: column; justify-content: center;
    padding: 40px 48px;
}
.hero-slide-overlay h2 { color: #fff; font-size: 2.2rem; font-weight: 900; line-height: 1.2; margin-bottom: 12px; text-shadow: 0 2px 8px rgba(0,0,0,.4); }
.hero-slide-overlay p { color: rgba(255,255,255,.9); font-size: 1rem; margin-bottom: 20px; max-width: 360px; }
.hero-slide-overlay .hero-cta {
    display: inline-block; background: #fff; color: #1a1a1a;
    padding: 10px 28px; border-radius: 30px; font-weight: 700; font-size: .88rem;
    text-decoration: none; transition: .2s;
}
.hero-slide-overlay .hero-cta:hover { background: #1a1a1a; color: #fff; }
@media(max-width: 576px) {
    .hero-slide-overlay { padding: 20px 20px; }
    .hero-slide-overlay h2 { font-size: 1.3rem; }
    .hero-slide-overlay p { display: none; }
}

/* ---------- 2. BRANDS ---------- */
.brand-bar { background: #f9f9f9; border-radius: 14px; padding: 20px; }
.brand-item {
    display: flex; flex-direction: column; align-items: center; gap: 12px;
    text-decoration: none; transition: .2s;
}
.brand-item:hover { transform: translateY(-3px); }
.brand-logo-box {
    width: 200px; height: 110px; border-radius: 16px; overflow: hidden;
    background: #fff; display: flex;
    align-items: center; justify-content: center; padding: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,.08);
}
.brand-logo-box img { max-width: 100%; max-height: 100%; object-fit: contain; filter: grayscale(30%); transition: .2s; }
.brand-item:hover .brand-logo-box img { filter: grayscale(0%); transform: scale(1.1); }
.brand-name-label { font-size: 1.05rem; color: #555; font-weight: 700; text-align: center; }

/* ---------- 3. FLASH SALE ---------- */
.flash-sale-section {
    background: linear-gradient(135deg, #e3f4ff 0%, #fff0f5 100%);
    border-radius: 18px; padding: 28px 24px; position: relative; overflow: hidden;
}
.flash-sale-section::before {
    content: ''; position: absolute; top: -40px; right: -40px;
    width: 200px; height: 200px; background: rgba(255,107,107,.08);
    border-radius: 50%;
}
.flash-header {
    display: flex; align-items: center; gap: 16px;
    flex-wrap: wrap; margin-bottom: 20px;
}
.flash-title-box { display: flex; align-items: center; gap: 10px; }
.flash-label {
    font-size: 1.5rem; font-weight: 900; letter-spacing: 2px;
    color: #ff3b3b; font-style: italic; text-shadow: 1px 1px 0 rgba(255,59,59,.3);
}
.flash-icon { font-size: 1.4rem; color: #ff9800; animation: flash-blink 1s infinite alternate; }
@keyframes flash-blink { from { opacity: 1; } to { opacity: .4; } }
.countdown {
    display: flex; align-items: center; gap: 6px;
    background: rgba(0,0,0,.06); padding: 8px 14px; border-radius: 30px;
}
.countdown-unit { text-align: center; }
.countdown-num {
    display: block; background: #1a1a1a; color: #fff; font-size: 1rem;
    font-weight: 800; padding: 4px 10px; border-radius: 8px; min-width: 36px;
    line-height: 1.2; font-variant-numeric: tabular-nums;
}
.countdown-label { font-size: .62rem; color: #888; display: block; margin-top: 2px; text-align: center; }
.countdown-sep { font-size: 1.1rem; font-weight: 800; color: #ff3b3b; margin-bottom: 12px; }
.flash-see-all {
    margin-left: auto; text-decoration: none;
    background: #1a1a1a; color: #fff;
    padding: 8px 20px; border-radius: 30px; font-size: .82rem; font-weight: 700;
    white-space: nowrap; transition: .2s;
    position: relative; z-index: 10;
}
.flash-see-all:hover { background: #ff3b3b; color: #fff; }

/* ---------- 4. EXCLUSIVE (Banner dọc) ---------- */
.exclusive-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
@media(max-width: 768px) { .exclusive-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 480px) { .exclusive-grid { grid-template-columns: 1fr 1fr; } }
.exclusive-card {
    border-radius: 14px; overflow: hidden; position: relative;
    cursor: pointer; text-decoration: none; display: block;
}
.exclusive-card img {
    width: 100%; aspect-ratio: 3/4; object-fit: cover;
    transition: transform .4s ease;
}
.exclusive-card:hover img { transform: scale(1.06); }
.exclusive-card-overlay {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,.7));
    padding: 40px 16px 16px;
}
.exclusive-card-overlay .excl-btn {
    display: inline-block; background: rgba(255,255,255,.95); color: #1a1a1a;
    padding: 7px 20px; border-radius: 30px; font-size: .78rem; font-weight: 700;
    margin-top: 8px; transition: .2s;
}
.exclusive-card:hover .excl-btn { background: #fff; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.2); }
.excl-title { color: #fff; font-size: .82rem; font-weight: 700; text-shadow: 0 1px 4px rgba(0,0,0,.5); }

/* ---------- 5. XU HUONG TABS ---------- */
.trend-tab-content { display: none; }
.trend-tab-content.active { display: block; }
.view-all-btn {
    display: block; width: fit-content; margin: 20px auto 0;
    border: 2px solid #1a1a1a; color: #1a1a1a; padding: 9px 34px;
    border-radius: 30px; font-weight: 700; font-size: .88rem;
    text-decoration: none; transition: .2s;
}
.view-all-btn:hover { background: #1a1a1a; color: #fff; }

/* ---------- 6. MÙA HÈ ---------- */
.summer-section {
    background: linear-gradient(135deg, #fff8e1 0%, #fff3e0 100%);
    border-radius: 18px; padding: 32px 24px;
}

/* ---------- 7. TOP TREND KEYWORDS ---------- */
.keyword-cloud {
    display: flex; flex-wrap: wrap; justify-content: center;
    gap: 10px; margin-bottom: 24px;
}
.keyword-tag {
    background: #f4f4f4; border: 1px solid #e8e8e8; color: #444;
    padding: 6px 18px; border-radius: 30px; font-size: .84rem;
    text-decoration: none; font-weight: 500; transition: .2s;
}
.keyword-tag:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
.toptrend-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
@media(max-width: 768px) { .toptrend-grid { grid-template-columns: repeat(2, 1fr); } }
.toptrend-card {
    border-radius: 14px; overflow: hidden; display: block;
    text-decoration: none; position: relative; cursor: pointer;
}
.toptrend-card img { width: 100%; aspect-ratio: 3/4; object-fit: cover; transition: .4s; }
.toptrend-card:hover img { transform: scale(1.05); }
.toptrend-see-btn {
    position: absolute; bottom: 14px; left: 50%; transform: translateX(-50%);
    background: rgba(255,255,255,.92); color: #1a1a1a;
    padding: 7px 22px; border-radius: 30px; font-size: .78rem; font-weight: 700;
    white-space: nowrap; transition: .2s;
}
.toptrend-card:hover .toptrend-see-btn { background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,.2); }

/* ---------- 8. BLOG ---------- */
.blog-tab-bar {
    display: flex; justify-content: center; gap: 28px;
    border-bottom: 1.5px solid #e8e8e8; margin-bottom: 24px; flex-wrap: wrap;
}
.blog-tab-link {
    padding-bottom: 10px; font-size: .88rem; font-weight: 600; color: #888;
    text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -1.5px;
    transition: .2s; cursor: pointer;
}
.blog-tab-link:hover, .blog-tab-link.active { color: #1a1a1a; border-bottom-color: #1a1a1a; }
.blog-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
@media(max-width: 768px) { .blog-grid { grid-template-columns: 1fr; } }
.blog-card { text-decoration: none; color: inherit; display: block; }
.blog-card img { width: 100%; aspect-ratio: 4/3; object-fit: cover; border-radius: 14px; margin-bottom: 14px; transition: .3s; }
.blog-card:hover img { transform: scale(1.02); }
.blog-card h4 { font-size: 1rem; font-weight: 700; line-height: 1.4; margin-bottom: 8px; color: #1a1a1a; }
.blog-card p { font-size: .85rem; color: #777; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6; }
</style>

<div class="home-page">

<!-- ======================================================
     SECTION 1: HERO SLIDER
     ====================================================== -->
<div class="container-home mb-4 mt-2">
<?php if (!empty($heroBanners)): ?>
<div class="swiper hero-swiper">
    <div class="swiper-wrapper">
        <?php foreach ($heroBanners as $banner): ?>
        <div class="swiper-slide">
            <a href="<?= htmlspecialchars($banner['link'] ?? 'index.php?controller=product') ?>" class="hero-slide d-block">
                <img src="<?= htmlspecialchars($banner['image']) ?>" alt="<?= htmlspecialchars($banner['title'] ?? '') ?>">
                <?php if (!empty($banner['title'])): ?>
                <div class="hero-slide-overlay">
                    <h2><?= htmlspecialchars($banner['title']) ?></h2>
                    <?php if (!empty($banner['description'])): ?>
                    <p><?= htmlspecialchars($banner['description']) ?></p>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($banner['link'] ?? '#') ?>" class="hero-cta">Mua ngay →</a>
                </div>
                <?php endif; ?>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
<?php else: ?>
<!-- Hero placeholder nếu chưa có banner -->
<div style="background:linear-gradient(135deg,#f8d7e3,#d4e8f5);border-radius:16px;aspect-ratio:21/9;display:flex;align-items:center;justify-content:center;">
    <div style="text-align:center;">
        <h2 style="font-size:2rem;font-weight:900;color:#333;">Chào mừng đến Glow Cosmetics</h2>
        <p style="color:#666;margin:12px 0;">Hàng nghìn sản phẩm làm đẹp chính hãng</p>
        <a href="index.php?controller=product" style="background:#1a1a1a;color:#fff;padding:12px 32px;border-radius:30px;text-decoration:none;font-weight:700;">Khám phá ngay →</a>
    </div>
</div>
<?php endif; ?>
</div>

<!-- ======================================================
     SECTION 2: BRANDS BAR
     ====================================================== -->
<?php if (!empty($brandsList)): ?>
<div class="container-home section-gap">
    <div class="brand-bar">
        <div class="swiper brand-swiper">
            <div class="swiper-wrapper">
                <?php 
                $pastelColors = ['#fdf2f8', '#fff0e6', '#e6f7ff', '#f4f1ea', '#edf2f7', '#ebf8ff', '#f0fff4', '#fff5f5'];
                $colorIndex = 0;
                foreach ($brandsList as $brand): 
                    $bgColor = $pastelColors[$colorIndex % count($pastelColors)];
                    $colorIndex++;
                ?>
                <div class="swiper-slide" style="width:auto!important;">
                    <a href="index.php?controller=brand&action=detail&name=<?= urlencode($brand['name']) ?>" class="brand-item">
                        <div class="brand-logo-box" style="background-color: <?= $bgColor ?>; border: none;">
                            <?php if (!empty($brand['logo'])): ?>
                                <img src="<?= htmlspecialchars($brand['logo']) ?>" alt="<?= htmlspecialchars($brand['name']) ?>" style="mix-blend-mode: multiply;">
                            <?php else: ?>
                                <span style="font-size:1.8rem;font-weight:900;color:#333;text-align:center;letter-spacing:-0.5px;"><?= htmlspecialchars($brand['name']) ?></span>
                            <?php endif; ?>
                        </div>
                        <span class="brand-name-label"><?= htmlspecialchars($brand['name']) ?></span>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ======================================================
     SECTION 3: FLASH SALE
     ====================================================== -->
<?php if (!empty($flashSaleProducts)): ?>
<div class="container-home section-gap">
    <div class="flash-sale-section">
        <!-- Header Flash Sale -->
        <div class="flash-header">
            <div class="flash-title-box">
                <span class="flash-icon"><i class="fas fa-bolt"></i></span>
                <span class="flash-label">FLASH SALE</span>
            </div>
            <!-- Đồng hồ đếm ngược -->
            <div class="countdown">
                <div class="countdown-unit"><span class="countdown-num" id="fs-h">00</span><span class="countdown-label">giờ</span></div>
                <span class="countdown-sep">:</span>
                <div class="countdown-unit"><span class="countdown-num" id="fs-m">00</span><span class="countdown-label">phút</span></div>
                <span class="countdown-sep">:</span>
                <div class="countdown-unit"><span class="countdown-num" id="fs-s">00</span><span class="countdown-label">giây</span></div>
            </div>
            <a href="index.php?controller=product&filter=promotion" class="flash-see-all">Xem tất cả</a>
        </div>
        <!-- Slider sản phẩm Flash Sale -->
        <div class="swiper flash-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($flashSaleProducts as $p): ?>
                <div class="swiper-slide">
                    <a href="index.php?controller=product&action=detail&id=<?= $p['id'] ?>" class="text-decoration-none">
                        <div class="prod-card">
                            <?php if (!empty($p['discount_pct']) && $p['discount_pct'] > 0): ?>
                                <span class="discount-badge">-<?= $p['discount_pct'] ?>%</span>
                            <?php endif; ?>
                                                        <?php if(isset($p['stock']) && $p['stock'] <= 0): ?>
                                <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.5); z-index: 10; display:flex; align-items:center; justify-content:center;">
                                    <span style="background: #6c757d; color: #fff; padding: 6px 14px; font-weight: bold; border-radius: 20px; font-size: 0.8rem; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">HẾT HÀNG</span>
                                </div>
                            <?php endif; ?>
                            <button class="wishlist-btn" type="button"><i class="far fa-heart"></i></button>
                            <img src="<?= htmlspecialchars($p['image'] ?? 'https://via.placeholder.com/300x300?text=No+Image') ?>" alt="" class="prod-img">
                            <div class="prod-brand"><?= htmlspecialchars($p['brand_name'] ?? 'GLOW') ?></div>
                            <div class="prod-name"><?= htmlspecialchars($p['name']) ?></div>
                            <div class="prod-price-box">
                                <span class="prod-price"><?= number_format($p['price'], 0, ',', '.') ?>đ</span>
                                <?php if (!empty($p['old_price']) && $p['old_price'] > 0): ?>
                                    <span class="prod-old-price"><?= number_format($p['old_price'], 0, ',', '.') ?>đ</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // -------------------------------------------------------------
    // Countdown Flash Sale
    // -------------------------------------------------------------
    <?php if (!empty($flashSaleEnd)): ?>
        const flashSaleEndStr = "<?= htmlspecialchars($flashSaleEnd) ?>";
        const countDownDate = new Date(flashSaleEndStr).getTime();

        if (!isNaN(countDownDate)) {
            const x = setInterval(function() {
                const now = new Date().getTime();
                const distance = countDownDate - now;

                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("fs-h").innerText = "00";
                    document.getElementById("fs-m").innerText = "00";
                    document.getElementById("fs-s").innerText = "00";
                } else {
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)) + Math.floor(distance / (1000 * 60 * 60 * 24)) * 24;
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("fs-h").innerText = hours.toString().padStart(2, '0');
                    document.getElementById("fs-m").innerText = minutes.toString().padStart(2, '0');
                    document.getElementById("fs-s").innerText = seconds.toString().padStart(2, '0');
                }
            }, 1000);
        }
    <?php endif; ?>
});
</script>
<?php endif; ?>

<!-- ======================================================
     SECTION 4: PHÂN PHỐI ĐỘC QUYỀN
     ====================================================== -->
<?php if (!empty($exclusiveBanners)): ?>
<div class="container-home section-gap">
    <div class="section-header">
        <h2 class="section-title">Phân Phối Độc Quyền</h2>
        <p class="section-subtitle">Sản phẩm chính hãng, cam kết chất lượng</p>
    </div>
    <div class="exclusive-grid">
        <?php foreach (array_slice($exclusiveBanners, 0, 4) as $eb): ?>
        <a href="<?= htmlspecialchars($eb['link'] ?? '#') ?>" class="exclusive-card">
            <img src="<?= htmlspecialchars($eb['image']) ?>" alt="<?= htmlspecialchars($eb['title'] ?? '') ?>">
            <div class="exclusive-card-overlay">
                <?php if (!empty($eb['title'])): ?>
                    <div class="excl-title"><?= htmlspecialchars($eb['title']) ?></div>
                <?php endif; ?>
                <span class="excl-btn">XEM NGAY</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- ======================================================
     SECTION 5: XU HƯỚNG LÀM ĐẸP (TABS)
     ====================================================== -->
<div class="container-home section-gap">
    <div class="section-header">
        <h2 class="section-title">Xu Hướng Làm Đẹp</h2>
    </div>
    <!-- Tabs -->
    <div class="tab-pills" id="trend-tabs">
        <a class="tab-pill active" data-target="tab-cham_soc_da" href="#">Chăm sóc da</a>
        <a class="tab-pill" data-target="tab-trang_diem" href="#">Trang điểm</a>
        <a class="tab-pill" data-target="tab-co_the" href="#">Cơ thể</a>
        <a class="tab-pill" data-target="tab-toc" href="#">Tóc</a>
    </div>
    <!-- Tab Contents -->
    <?php
    $tabIds = [
        'cham_soc_da' => 'Chăm sóc da',
        'trang_diem'  => 'Trang điểm',
        'co_the'      => 'Cơ thể',
        'toc'         => 'Tóc',
    ];
    $firstTab = true;
    foreach ($tabIds as $tabKey => $tabLabel):
        $tabProducts = $trendingTabProducts[$tabKey] ?? [];
    ?>
    <div class="trend-tab-content <?= $firstTab ? 'active' : '' ?>" id="tab-<?= $tabKey ?>">
        <?php if (!empty($tabProducts)): ?>
        <div class="swiper trend-swiper-<?= $tabKey ?>">
            <div class="swiper-wrapper">
                <?php foreach ($tabProducts as $p): ?>
                <div class="swiper-slide">
                    <a href="index.php?controller=product&action=detail&id=<?= $p['id'] ?>" class="text-decoration-none">
                        <div class="prod-card">
                                                        <?php if(isset($p['stock']) && $p['stock'] <= 0): ?>
                                <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.5); z-index: 10; display:flex; align-items:center; justify-content:center;">
                                    <span style="background: #6c757d; color: #fff; padding: 6px 14px; font-weight: bold; border-radius: 20px; font-size: 0.8rem; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">HẾT HÀNG</span>
                                </div>
                            <?php endif; ?>
                            <button class="wishlist-btn" type="button"><i class="far fa-heart"></i></button>
                            <img src="<?= htmlspecialchars($p['image'] ?? 'https://via.placeholder.com/300x300') ?>" alt="" class="prod-img">
                            <div class="prod-brand"><?= htmlspecialchars($p['brand_name'] ?? 'GLOW') ?></div>
                            <div class="prod-name"><?= htmlspecialchars($p['name']) ?></div>
                            <div class="prod-price-box">
                                <span class="prod-price"><?= number_format($p['price'], 0, ',', '.') ?>đ</span>
                                <?php if (!empty($p['old_price']) && $p['old_price'] > 0): ?>
                                    <span class="prod-old-price"><?= number_format($p['old_price'], 0, ',', '.') ?>đ</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
        <?php else: ?>
        <p class="text-center text-muted py-4">Chưa có sản phẩm trong danh mục này.</p>
        <?php endif; ?>
        <a href="index.php?controller=product" class="view-all-btn">Xem tất cả</a>
    </div>
    <?php $firstTab = false; endforeach; ?>
</div>

<!-- ======================================================
     SECTION 6: GỢI Ý CHĂM DA MÙA HÈ
     ====================================================== -->
<?php if (!empty($summerProducts)): ?>
<div class="container-home section-gap">
    <div class="summer-section">
        <div class="section-header" style="margin-bottom:20px;">
            <h2 class="section-title">Gợi Ý Chăm Da Mùa Hè</h2>
            <p class="section-subtitle">Giữ làn da khỏe đẹp dưới nắng hè</p>
        </div>
        <div class="swiper summer-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($summerProducts as $p): ?>
                <div class="swiper-slide">
                    <a href="index.php?controller=product&action=detail&id=<?= $p['id'] ?>" class="text-decoration-none">
                        <div class="prod-card">
                                                        <?php if(isset($p['stock']) && $p['stock'] <= 0): ?>
                                <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.5); z-index: 10; display:flex; align-items:center; justify-content:center;">
                                    <span style="background: #6c757d; color: #fff; padding: 6px 14px; font-weight: bold; border-radius: 20px; font-size: 0.8rem; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">HẾT HÀNG</span>
                                </div>
                            <?php endif; ?>
                            <button class="wishlist-btn" type="button"><i class="far fa-heart"></i></button>
                            <img src="<?= htmlspecialchars($p['image'] ?? 'https://via.placeholder.com/300x300') ?>" alt="" class="prod-img">
                            <div class="prod-brand"><?= htmlspecialchars($p['brand_name'] ?? 'GLOW') ?></div>
                            <div class="prod-name"><?= htmlspecialchars($p['name']) ?></div>
                            <div class="prod-price-box">
                                <span class="prod-price"><?= number_format($p['price'], 0, ',', '.') ?>đ</span>
                                <?php if (!empty($p['old_price']) && $p['old_price'] > 0): ?>
                                    <span class="prod-old-price"><?= number_format($p['old_price'], 0, ',', '.') ?>đ</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
        <a href="index.php?controller=product" class="view-all-btn">Xem tất cả</a>
    </div>
</div>
<?php endif; ?>

<!-- ======================================================
     SECTION 7: TOP TREND HÔM NAY
     ====================================================== -->
<div class="container-home section-gap">
    <div class="section-header">
        <h2 class="section-title">Top Trend Hôm Nay</h2>
    </div>
    <!-- Keyword Tags -->
    <div class="keyword-cloud">
        <a href="index.php?controller=product&keyword=son+peripera" class="keyword-tag">son peripera</a>
        <a href="index.php?controller=product&keyword=cushion+clio" class="keyword-tag">cushion clio</a>
        <a href="index.php?controller=product&keyword=mặt+nạ" class="keyword-tag">mặt nạ</a>
        <a href="index.php?controller=product&keyword=sữa+rửa+mặt" class="keyword-tag">sữa rửa mặt</a>
        <a href="index.php?controller=product&keyword=kem+chống+nắng" class="keyword-tag">kem chống nắng</a>
    </div>
    <!-- Banner dọc 4 cột -->
    <?php if (!empty($topTrendBanners)): ?>
    <div class="toptrend-grid">
        <?php foreach (array_slice($topTrendBanners, 0, 4) as $tb): ?>
        <a href="<?= htmlspecialchars($tb['link'] ?? '#') ?>" class="toptrend-card">
            <img src="<?= htmlspecialchars($tb['image']) ?>" alt="<?= htmlspecialchars($tb['title'] ?? '') ?>">
            <span class="toptrend-see-btn">XEM NGAY</span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Fallback: hiển thị sản phẩm xu hướng dạng banner -->
    <div class="toptrend-grid">
        <?php foreach (array_slice($trendingTabProducts['trang_diem'] ?? [], 0, 4) as $p): ?>
        <a href="index.php?controller=product&action=detail&id=<?= $p['id'] ?>" class="toptrend-card" style="background:#f5f5f5;border-radius:14px;">
            <img src="<?= htmlspecialchars($p['image'] ?? '') ?>" alt="" style="width:100%;aspect-ratio:3/4;object-fit:contain;padding:20px;">
            <span class="toptrend-see-btn">XEM NGAY</span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ======================================================
     SECTION 8: GÓC ĐẸP BEAUTY BOX (BLOG)
     ====================================================== -->
<?php if (!empty($latestPosts)): ?>
<div class="container-home section-gap">
    <div class="section-header">
        <h2 class="section-title">Góc Đẹp Beauty Box</h2>
    </div>
    <!-- Blog Nav Tabs -->
    <div class="blog-tab-bar">
        <a class="blog-tab-link active" href="#">Cách chăm sóc da</a>
        <a class="blog-tab-link" href="#">Góc review</a>
        <a class="blog-tab-link" href="#">Xu hướng trang điểm</a>
        <a class="blog-tab-link" href="#">Bí quyết khỏe đẹp</a>
        <a class="blog-tab-link" href="#">Tin tức</a>
    </div>
    <!-- Blog Grid 3 cột -->
    <div class="blog-grid">
        <?php foreach ($latestPosts as $post): ?>
        <a href="#" class="blog-card">
            <?php
                $postImg = !empty($post['image']) ? $post['image'] : 'https://via.placeholder.com/400x300?text=Beauty+Blog';
            ?>
            <img src="<?= htmlspecialchars($postImg) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
            <h4><?= htmlspecialchars($post['title']) ?></h4>
            <p><?= htmlspecialchars(strip_tags($post['content'])) ?></p>
        </a>
        <?php endforeach; ?>
    </div>
    <a href="#" class="view-all-btn" style="margin-top:28px;">Tất cả bài viết</a>
</div>
<?php endif; ?>

</div><!-- end .home-page -->

<!-- ======================================================
     SCRIPTS
     ====================================================== -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
(function() {
    // 1. Hero Slider
    new Swiper('.hero-swiper', {
        loop: true, autoplay: { delay: 4500, disableOnInteraction: false },
        speed: 700,
        pagination: { el: '.hero-swiper .swiper-pagination', clickable: true },
        navigation: { nextEl: '.hero-swiper .swiper-button-next', prevEl: '.hero-swiper .swiper-button-prev' },
    });

    // 2. Brand Swiper
    new Swiper('.brand-swiper', {
        slidesPerView: 'auto', spaceBetween: 16,
        navigation: { nextEl: '.brand-swiper .swiper-button-next', prevEl: '.brand-swiper .swiper-button-prev' },
        breakpoints: { 768: { spaceBetween: 20 } }
    });

    // 3. Flash Sale Swiper
    new Swiper('.flash-swiper', {
        slidesPerView: 2, spaceBetween: 12,
        navigation: { nextEl: '.flash-swiper .swiper-button-next', prevEl: '.flash-swiper .swiper-button-prev' },
        breakpoints: { 480: { slidesPerView: 3 }, 768: { slidesPerView: 4 }, 1024: { slidesPerView: 5 } }
    });

    // 5. Trend Tab Swipers
    var tabKeys = ['duong_da', 'trang_diem', 'mat_na', 'lam_sach'];
    tabKeys.forEach(function(k) {
        var el = document.querySelector('.trend-swiper-' + k);
        if (el) {
            new Swiper(el, {
                slidesPerView: 2, spaceBetween: 12,
                navigation: { nextEl: el.querySelector('.swiper-button-next'), prevEl: el.querySelector('.swiper-button-prev') },
                breakpoints: { 480: { slidesPerView: 3 }, 768: { slidesPerView: 4 }, 1024: { slidesPerView: 5 } }
            });
        }
    });

    // 6. Summer Swiper
    var sumEl = document.querySelector('.summer-swiper');
    if (sumEl) {
        new Swiper(sumEl, {
            slidesPerView: 2, spaceBetween: 12,
            navigation: { nextEl: sumEl.querySelector('.swiper-button-next'), prevEl: sumEl.querySelector('.swiper-button-prev') },
            breakpoints: { 480: { slidesPerView: 3 }, 768: { slidesPerView: 4 }, 1024: { slidesPerView: 5 } }
        });
    }

    // ----- Tab switching (Xu hướng) -----
    var tabBtns = document.querySelectorAll('#trend-tabs .tab-pill');
    tabBtns.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            tabBtns.forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            var target = btn.getAttribute('data-target');
            document.querySelectorAll('.trend-tab-content').forEach(function(c) { c.classList.remove('active'); });
            var el = document.getElementById(target);
            if (el) el.classList.add('active');
        });
    });

    // ----- Blog Tab visual only -----
    document.querySelectorAll('.blog-tab-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.blog-tab-link').forEach(function(l) { l.classList.remove('active'); });
            link.classList.add('active');
        });
    });

    // ----- Flash Sale Countdown -----
    // Đặt thời gian kết thúc: 24 giờ từ bây giờ (hoặc một mốc cố định)
    var endTime = new Date();
    endTime.setHours(endTime.getHours() + 23, 59, 59, 0);

    function updateCountdown() {
        var now = new Date();
        var diff = endTime - now;
        if (diff <= 0) { diff = 0; }
        var h = Math.floor(diff / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        var pad = function(n) { return n.toString().padStart(2, '0'); };
        var hEl = document.getElementById('fs-h');
        var mEl = document.getElementById('fs-m');
        var sEl = document.getElementById('fs-s');
        if (hEl) { hEl.textContent = pad(h); mEl.textContent = pad(m); sEl.textContent = pad(s); }
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);
})();
</script>

<?php include 'views/layout/footer.php'; ?>