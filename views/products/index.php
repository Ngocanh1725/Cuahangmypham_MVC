<?php 
$pageTitle_Header = "Glow Cosmetics (MVC) - Vẻ đẹp tỏa sáng"; 
$extraCSS = "
<!-- Nhúng thư viện Swiper CSS cho hiệu ứng kéo trượt chuyên nghiệp -->
<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css\" />

<style>
    /* CSS cho Layout Bộ Lọc */
    .filter-sidebar { background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #f0f0f0; }
    .filter-group { border-bottom: 1px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 15px; }
    .filter-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .filter-title { font-weight: 700; color: #1f2937; font-size: 1rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; user-select: none; }
    .filter-title::after { content: '\\f106'; font-family: 'Font Awesome 5 Free'; font-weight: 900; transition: transform 0.3s ease; font-size: 0.9rem; color: #6b7280; }
    .filter-title.collapsed::after { transform: rotate(180deg); }
    .filter-search-box { position: relative; margin-bottom: 15px; }
    .filter-search-box input { width: 100%; padding: 8px 10px 8px 35px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.85rem; outline: none; transition: border-color 0.2s; }
    .filter-search-box input:focus { border-color: var(--brand-dark, #be185d); }
    .filter-search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.85rem; }
    .filter-list { list-style: none; padding: 0; margin: 0; }
    .filter-item { margin-bottom: 12px; }
    .filter-item label { display: flex; align-items: flex-start; cursor: pointer; font-size: 0.9rem; color: #4b5563; transition: color 0.2s; }
    .filter-item label:hover { color: var(--brand-dark, #be185d); }
    .filter-item input[type=\"checkbox\"] { margin-top: 3px; margin-right: 10px; width: 16px; height: 16px; accent-color: var(--brand-dark, #be185d); cursor: pointer; }
    .btn-view-more { color: #2563eb; background: none; border: none; padding: 0; font-size: 0.85rem; font-weight: 500; text-decoration: underline; cursor: pointer; margin-top: 5px; }
    .btn-view-more:hover { color: var(--brand-dark, #be185d); }
    .filter-item.hidden-item { display: none; }

    /* CSS HIỆU ỨNG HOVER 'XEM NHANH' */
    .product-img-container { position: relative; overflow: hidden; }
    .quick-view-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.3);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: all 0.3s ease; backdrop-filter: blur(2px);
    }
    .product-card:hover .quick-view-overlay { opacity: 1; }
    .btn-quick-view {
        background: linear-gradient(90deg, #f59e0b, #db2777);
        color: white !important; border: none; padding: 10px 25px; border-radius: 25px;
        font-weight: bold; transform: translateY(20px); transition: transform 0.3s ease, box-shadow 0.3s;
        text-decoration: none; font-size: 0.9rem; cursor: pointer;
    }
    .product-card:hover .btn-quick-view { transform: translateY(0); }
    .btn-quick-view:hover { box-shadow: 0 5px 15px rgba(219, 39, 119, 0.4); }

    /* ======================================================== */
    /* TÙY CHỈNH CSS CHO BANNER SWIPER QUẢNG CÁO                */
    /* ======================================================== */
    .hero-section-wrapper {
        margin-top: 20px;
        margin-bottom: 40px;
    }
    .hero-swiper {
        width: 100%;
        padding-top: 10px;
        padding-bottom: 40px; 
    }
    
    .hero-swiper .swiper-slide {
        /* Bỏ height cố định, sử dụng Tỷ lệ khung hình (Aspect Ratio) */
        aspect-ratio: 16 / 9; /* Tỷ lệ cho điện thoại */
        border-radius: 16px; 
        overflow: hidden;
        background-color: #f8fafc; 
        transition: transform 0.4s ease, opacity 0.4s ease, box-shadow 0.4s ease;
        
        /* Hiệu ứng làm mờ và thu nhỏ cho các quảng cáo ở 2 bên */
        opacity: 0.4;
        transform: scale(0.9);
    }
    
    @media (min-width: 768px) {
        .hero-swiper .swiper-slide { 
            aspect-ratio: 21 / 9; /* Tỷ lệ cho Tablet */
        }
    }
    @media (min-width: 1200px) {
        .hero-swiper .swiper-slide { 
            aspect-ratio: 3 / 1; /* TỶ LỆ CHUẨN CHO PC: Bạn hãy tự cắt ảnh theo size 1200 x 400 pixel */
        } 
    }

    /* Quảng cáo ĐANG Ở GIỮA sẽ to rõ, nổi bật */
    .hero-swiper .swiper-slide-active {
        opacity: 1;
        transform: scale(1);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 2;
    }

    .hero-swiper .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Khi bạn tự cắt ảnh đúng size 1200x400, cover sẽ hiển thị vừa khít 100% không bị hở viền trắng hay cắt chữ */
        object-position: center;
    }

    /* CSS cho text nổi trên Banner (Đại sứ thương hiệu) */
    .banner-overlay-text {
        position: absolute;
        bottom: 25px;
        left: 40px;
        background: rgba(255, 255, 255, 0.9);
        padding: 15px 25px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        backdrop-filter: blur(5px);
        max-width: 60%;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.5s ease 0.3s;
        z-index: 5;
    }
    .hero-swiper .swiper-slide-active .banner-overlay-text {
        transform: translateY(0);
        opacity: 1;
    }
    .banner-brand-title {
        color: var(--brand-dark, #be185d);
        font-weight: 900;
        margin-bottom: 5px;
        font-size: 1.4rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .banner-ambassador {
        color: #374151;
        font-size: 1rem;
        margin-bottom: 0;
    }
    .banner-ambassador strong {
        color: #db2777;
    }
    
    /* Tùy chỉnh Nút điều hướng Trái/Phải */
    .hero-swiper .swiper-button-next,
    .hero-swiper .swiper-button-prev {
        background-color: rgba(255, 255, 255, 0.95);
        color: var(--brand-dark, #be185d);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.2s;
    }
    .hero-swiper .swiper-button-next:hover,
    .hero-swiper .swiper-button-prev:hover {
        background-color: var(--brand-dark, #be185d);
        color: white;
        transform: scale(1.1);
    }
    .hero-swiper .swiper-button-next:after,
    .hero-swiper .swiper-button-prev:after {
        font-size: 1.2rem;
        font-weight: 900;
    }

    /* Dấu chấm chuyển slide (Pagination) */
    .hero-swiper .swiper-pagination-bullet {
        background-color: #999;
        opacity: 0.6;
        width: 8px;
        height: 8px;
        transition: all 0.3s;
    }
    .hero-swiper .swiper-pagination-bullet-active {
        background-color: var(--brand-dark, #be185d);
        opacity: 1;
        transform: scale(1.5);
    }
</style>
";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<!-- ========================================================== -->
<!-- HERO SECTION: BANNER SWIPER HIỆU ỨNG THẤY QUẢNG CÁO 2 BÊN -->
<!-- ========================================================== -->
<section class="hero-section-wrapper container">
    <!-- Thẻ chứa Swiper -->
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            
            <?php if (!empty($banners)): ?>
                <?php foreach($banners as $banner): ?>
                    <div class="swiper-slide position-relative">
                        <?php if (!empty($banner['link'])): ?>
                            <a href="<?php echo htmlspecialchars($banner['link']); ?>" class="d-block w-100 h-100">
                                <img src="<?php echo htmlspecialchars($banner['image']); ?>" alt="<?php echo htmlspecialchars($banner['title']); ?>">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo htmlspecialchars($banner['image']); ?>" alt="<?php echo htmlspecialchars($banner['title']); ?>">
                        <?php endif; ?>

                        <!-- Text nổi (Thương hiệu & Đại sứ) -->
                        <?php if (!empty($banner['brand_name']) || !empty($banner['ambassador'])): ?>
                            <div class="banner-overlay-text d-none d-md-block">
                                <?php if (!empty($banner['brand_name'])): ?>
                                    <h4 class="banner-brand-title"><?php echo htmlspecialchars($banner['brand_name']); ?></h4>
                                <?php endif; ?>
                                <?php if (!empty($banner['ambassador'])): ?>
                                    <p class="banner-ambassador">Đại sứ thương hiệu: <strong><?php echo htmlspecialchars($banner['ambassador']); ?></strong></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                
                <!-- Lưu ý: Để hiệu ứng peeking (thấy 2 bên) vòng lặp mượt mà, thư viện cần ít nhất 3 slide. 
                     Nếu DB có ít hơn 3, tự động chèn thêm slide demo -->
                <?php if (count($banners) < 3): ?>
                    <div class="swiper-slide position-relative">
                        <img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=1600&auto=format&fit=crop" alt="Skin Care Demo">
                    </div>
                    <div class="swiper-slide position-relative">
                        <img src="https://images.unsplash.com/photo-1618331835717-801e976710b2?q=80&w=1600&auto=format&fit=crop" alt="Makeup Demo">
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <!-- Slide Dự phòng nếu CSDL chưa có banner -->
                <div class="swiper-slide position-relative">
                    <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?q=80&w=1600&auto=format&fit=crop" alt="Đại tiệc thương hiệu 1">
                </div>
                <div class="swiper-slide position-relative">
                    <img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=1600&auto=format&fit=crop" alt="Skincare mùa hè 2">
                </div>
                <div class="swiper-slide position-relative">
                    <img src="https://images.unsplash.com/photo-1618331835717-801e976710b2?q=80&w=1600&auto=format&fit=crop" alt="Xu hướng Makeup 3">
                </div>
            <?php endif; ?>

        </div>
        
        <!-- Nút điều khiển Trái/Phải -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        
        <!-- Các dấu chấm chỉ thị -->
        <div class="swiper-pagination"></div>
    </div>
</section>
<!-- ========================================================== -->


<!-- Main Content (2 Cột) -->
<section id="products-section" class="container py-4">
    <div class="row">
        
        <!-- CỘT TRÁI: BỘ LỌC SIDEBAR -->
        <div class="col-lg-3 mb-4">
            <div class="filter-sidebar shadow-sm">
                <form action="index.php" method="GET" id="filterForm">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="index">

                    <div class="filter-group">
                        <div class="filter-title" data-bs-toggle="collapse" data-bs-target="#filterPrice" aria-expanded="true">Giá sản phẩm</div>
                        <div class="collapse show" id="filterPrice">
                            <ul class="filter-list">
                                <li class="filter-item"><label><input type="checkbox" name="price[]" value="0-500000"> Dưới 500.000đ</label></li>
                                <li class="filter-item"><label><input type="checkbox" name="price[]" value="500000-1000000"> 500.000đ - 1.000.000đ</label></li>
                                <li class="filter-item"><label><input type="checkbox" name="price[]" value="1000000-1500000"> 1.000.000đ - 1.500.000đ</label></li>
                                <li class="filter-item"><label><input type="checkbox" name="price[]" value="1500000-2000000"> 1.500.000đ - 2.000.000đ</label></li>
                                <li class="filter-item"><label><input type="checkbox" name="price[]" value="2000000-"> Trên 2.000.000đ</label></li>
                            </ul>
                        </div>
                    </div>

                    <div class="filter-group">
                        <div class="filter-title" data-bs-toggle="collapse" data-bs-target="#filterCategory" aria-expanded="true">Loại sản phẩm</div>
                        <div class="collapse show" id="filterCategory">
                            <div class="filter-search-box"><i class="fas fa-search"></i><input type="text" class="searchInput" data-target="catList" placeholder="Tìm..."></div>
                            <ul class="filter-list" id="catList" style="max-height: 250px; overflow-y: auto; padding-right: 5px;">
                                <?php 
                                if(isset($filterCategories) && isset($catCounts)) {
                                    $catIndex = 0;
                                    foreach($filterCategories as $cat): 
                                        $count = isset($catCounts[$cat]) ? $catCounts[$cat] : 0;
                                        $isHidden = $catIndex >= 5 ? 'hidden-item' : '';
                                    ?>
                                        <li class="filter-item <?php echo $isHidden; ?>"><label><input type="checkbox" name="category[]" value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?> (<?php echo $count; ?>)</label></li>
                                    <?php $catIndex++; endforeach; 
                                } ?>
                            </ul>
                            <button type="button" class="btn-view-more" data-target="catList">Xem thêm</button>
                        </div>
                    </div>

                    <div class="filter-group">
                        <div class="filter-title" data-bs-toggle="collapse" data-bs-target="#filterBrand" aria-expanded="true">Thương hiệu</div>
                        <div class="collapse show" id="filterBrand">
                            <div class="filter-search-box"><i class="fas fa-search"></i><input type="text" class="searchInput" data-target="brandList" placeholder="Tìm thương hiệu..."></div>
                            <ul class="filter-list" id="brandList" style="max-height: 250px; overflow-y: auto; padding-right: 5px;">
                                <?php 
                                if(isset($filterBrands) && isset($brandCounts)) {
                                    $brandIndex = 0;
                                    foreach($filterBrands as $brand): 
                                        $count = isset($brandCounts[$brand]) ? $brandCounts[$brand] : 0;
                                        $isHidden = $brandIndex >= 5 ? 'hidden-item' : '';
                                    ?>
                                        <li class="filter-item <?php echo $isHidden; ?>"><label><input type="checkbox" name="brand[]" value="<?php echo htmlspecialchars($brand); ?>"><?php echo htmlspecialchars($brand); ?> (<?php echo $count; ?>)</label></li>
                                    <?php $brandIndex++; endforeach; 
                                } ?>
                            </ul>
                            <button type="button" class="btn-view-more" data-target="brandList">Xem thêm</button>
                        </div>
                    </div>

                    <button type="submit" class="btn text-white w-100 mt-3 rounded-pill fw-bold" style="background-color: var(--brand-dark, #be185d);">Áp dụng bộ lọc</button>
                </form>
            </div>
        </div>

        <!-- CỘT PHẢI: LƯỚI SẢN PHẨM -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-dark m-0"><?php echo isset($pageTitle) ? $pageTitle : 'Tất cả sản phẩm'; ?></h3>
                <select name="sort" form="filterForm" onchange="document.getElementById('filterForm').submit();" class="form-select w-auto shadow-sm" style="border-radius: 8px;">
                    <option value="">Sắp xếp: Mặc định</option>
                    <option value="price_asc">Giá: Thấp đến Cao</option>
                    <option value="price_desc">Giá: Cao đến Thấp</option>
                    <option value="newest">Mới nhất</option>
                </select>
            </div>

            <div class="row g-4">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $row): 
                        $imgUrl = !empty($row['image']) ? $row['image'] : 'https://via.placeholder.com/400x400?text=No+Image';
                        if (strpos($imgUrl, 'http') === false) { $imgUrl = $imgUrl; }
                        $isSale = (isset($row['old_price']) && $row['old_price'] > $row['price']);
                    ?>
                        <div class="col-md-4 col-6">
                            <div class="card border-0 product-card h-100 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                                
                                <?php if ($isSale): 
                                    $discountPercent = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                                ?>
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-3 z-3 px-3 py-2 rounded-pill shadow-sm">-<?php echo $discountPercent; ?>%</span>
                                <?php elseif (isset($currentFilter) && $currentFilter == 'new'): ?>
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-3 z-3 px-3 py-2 rounded-pill shadow-sm">Mới</span>
                                <?php endif; ?>

                                <!-- CLICK VÀO ẢNH ĐỂ VÀO TRANG CHI TIẾT -->
                                <a href="index.php?controller=product&action=detail&id=<?php echo $row['id']; ?>" class="text-decoration-none">
                                    <div class="product-img-container position-relative" style="height: 250px; overflow: hidden;">
                                        <img src="<?php echo $imgUrl; ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                        
                                        <!-- OVERLAY VÀ NÚT XEM NHANH -->
                                        <div class="quick-view-overlay">
                                            <button type="button" class="btn-quick-view" 
                                                data-id="<?php echo $row['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                data-price="<?php echo number_format($row['price']); ?>"
                                                data-oldprice="<?php echo $isSale ? number_format($row['old_price']) : ''; ?>"
                                                data-img="<?php echo $imgUrl; ?>"
                                                data-cat="<?php echo htmlspecialchars($row['category']); ?>">
                                                Xem nhanh
                                            </button>
                                        </div>
                                    </div>
                                </a>
                                
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="text-muted small mb-1"><?php echo htmlspecialchars($row['category']); ?></div>
                                    
                                    <!-- CLICK VÀO TÊN ĐỂ VÀO TRANG CHI TIẾT -->
                                    <a href="index.php?controller=product&action=detail&id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                        <h6 class="fw-bold mb-2 product-title-hover" style="height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </h6>
                                    </a>
                                    
                                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-end">
                                        <div>
                                            <?php if ($isSale): ?>
                                                <div class="text-muted small text-decoration-line-through mb-1">
                                                    <?php echo number_format($row['old_price']); ?>đ
                                                </div>
                                            <?php endif; ?>
                                            <div class="fw-bold fs-5" style="color: var(--brand-dark, #be185d);">
                                                <?php echo number_format($row['price']); ?>đ
                                            </div>
                                        </div>
                                        <a href="index.php?controller=cart&action=add&id=<?php echo $row['id']; ?>" class="btn btn-light text-danger rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: all 0.2s;">
                                            <i class="fas fa-cart-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</h5>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</section>

<!-- MODAL POPUP XEM NHANH SẢN PHẨM -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
      <div class="modal-header border-0 pb-0 position-absolute top-0 end-0 z-3">
        <button type="button" class="btn-close bg-white rounded-circle p-2 shadow-sm m-2" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="row g-0">
            <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4">
                <img id="qv-img" src="" class="img-fluid rounded-3 shadow-sm" style="max-height: 400px; object-fit: contain;">
            </div>
            <div class="col-md-7 p-4 p-md-5 d-flex flex-column justify-content-center">
                <span id="qv-cat" class="badge bg-secondary bg-opacity-10 text-secondary mb-2 align-self-start">Category</span>
                <h4 id="qv-name" class="fw-bold text-dark mb-3">Tên sản phẩm</h4>
                
                <div class="d-flex align-items-end gap-3 mb-4">
                    <h2 id="qv-price" class="fw-bold mb-0" style="color: var(--brand-dark, #be185d);">0đ</h2>
                    <span id="qv-oldprice" class="text-muted text-decoration-line-through fs-5 mb-1"></span>
                </div>
                
                <p class="text-muted small mb-4"><i class="fas fa-check-circle text-success me-1"></i> Cam kết hàng chính hãng 100%<br><i class="fas fa-truck text-primary me-1"></i> Miễn phí giao hàng toàn quốc</p>

                <div class="d-flex gap-2 mt-auto">
                    <a id="qv-add-cart" href="#" class="btn flex-grow-1 text-white fw-bold rounded-pill shadow-sm py-2" style="background-color: var(--brand-main, #db2777);">
                        <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ
                    </a>
                    <a id="qv-detail" href="#" class="btn btn-outline-dark fw-bold rounded-pill px-4 py-2">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
// Javascript xử lý UX/UI và Khởi tạo Swiper Banner
$extraJS = <<<EOT
<!-- Thư viện Swiper JS để chạy hiệu ứng kéo vuốt mượt mà -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ========================================================== 
    // 1. KHỞI TẠO HIỆU ỨNG SWIPER CHO BANNER
    // ========================================================== 
    var swiper = new Swiper(".hero-swiper", {
        slidesPerView: 1.25,     // Hiển thị 1 slide trọn vẹn ở giữa, và hé 1 phần nhỏ của 2 slide bên cạnh
        centeredSlides: true,    // Chỉnh slide đang xem ra giữa khung hình
        spaceBetween: 20,        // Khoảng cách giữa các quảng cáo
        loop: true,              // Vuốt lặp lại vô tận
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            // Máy tính bảng & PC: Thấy các mép quảng cáo bên cạnh rõ hơn
            768: {
                slidesPerView: 1.4,
                spaceBetween: 30,
            }
        }
    });

    // ========================================================== 
    // 2. Popup Xem Nhanh Sản Phẩm
    // ========================================================== 
    const quickViewBtns = document.querySelectorAll('.btn-quick-view');
    const myModal = new bootstrap.Modal(document.getElementById('quickViewModal'));

    quickViewBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); 
            e.stopPropagation();

            document.getElementById('qv-img').src = this.getAttribute('data-img');
            document.getElementById('qv-name').innerText = this.getAttribute('data-name');
            document.getElementById('qv-cat').innerText = this.getAttribute('data-cat');
            document.getElementById('qv-price').innerText = this.getAttribute('data-price') + 'đ';
            
            const oldPrice = this.getAttribute('data-oldprice');
            document.getElementById('qv-oldprice').innerText = oldPrice ? oldPrice + 'đ' : '';

            const pId = this.getAttribute('data-id');
            document.getElementById('qv-add-cart').href = 'index.php?controller=cart&action=add&id=' + pId;
            document.getElementById('qv-detail').href = 'index.php?controller=product&action=detail&id=' + pId;

            myModal.show();
        });
    });

    // ========================================================== 
    // 3. Nút Xem thêm / Thu gọn bộ lọc
    // ========================================================== 
    document.querySelectorAll('.btn-view-more').forEach(btn => {
        btn.addEventListener('click', function() {
            const hiddenItems = document.getElementById(this.getAttribute('data-target')).querySelectorAll('.hidden-item');
            if (this.innerText === 'Xem thêm') {
                hiddenItems.forEach(item => item.style.display = 'block');
                this.innerText = 'Thu gọn';
            } else {
                hiddenItems.forEach(item => item.style.display = 'none');
                this.innerText = 'Xem thêm';
            }
        });
    });

    // ========================================================== 
    // 4. Tìm kiếm trong bộ lọc
    // ========================================================== 
    document.querySelectorAll('.searchInput').forEach(input => {
        input.addEventListener('keyup', function() {
            const filterText = this.value.toLowerCase().trim();
            const items = document.getElementById(this.getAttribute('data-target')).querySelectorAll('.filter-item');
            const btnMore = this.parentElement.parentElement.querySelector('.btn-view-more');
            
            if(btnMore) btnMore.style.display = filterText ? 'none' : 'block';

            items.forEach(item => {
                const text = item.querySelector('label').innerText.toLowerCase();
                const isHidden = item.classList.contains('hidden-item');
                if(text.includes(filterText)) item.style.display = 'block';
                else item.style.display = 'none';
                
                if(!filterText && isHidden && btnMore && btnMore.innerText === 'Xem thêm') item.style.display = 'none';
            });
        });
    });

    // ========================================================== 
    // 5. Giữ trạng thái Checkbox khi load trang
    // ========================================================== 
    const urlParams = new URLSearchParams(window.location.search);
    document.querySelectorAll('#filterForm input[type="checkbox"]').forEach(cb => {
        if (urlParams.getAll(cb.name).includes(cb.value)) cb.checked = true;
    });
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect && urlParams.has('sort')) sortSelect.value = urlParams.get('sort');
});
</script>
EOT;
include 'views/layout/footer.php'; 
?>