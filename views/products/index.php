<?php 
$pageTitle_Header = "Glow Cosmetics - Vẻ đẹp tỏa sáng"; 
$extraCSS = "
<!-- Nhúng thư viện Swiper CSS -->
<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css\" />

<style>
    /* ==========================================
       1. CSS BỘ LỌC (SIDEBAR FILTER) TỐI GIẢN
       ========================================== */
    .filter-sidebar { background: var(--bg-card, #f8f8f8); padding: 25px; border-radius: 4px; border: none; }
    .filter-group { border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
    .filter-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    
    .filter-title { font-family: var(--font-heading, 'Playfair Display', serif); font-size: 1.15rem; color: var(--brand-primary, #7A1C1C); cursor: pointer; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; user-select: none; font-weight: 600;}
    .filter-title::after { content: '\\f106'; font-family: 'Font Awesome 5 Free'; font-weight: 900; transition: transform 0.3s ease; font-size: 0.9rem; color: var(--text-gray, #6b7280); }
    .filter-title.collapsed::after { transform: rotate(180deg); }
    
    .filter-search-box { position: relative; margin-bottom: 15px; }
    .filter-search-box input { width: 100%; padding: 10px 10px 10px 35px; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 0.85rem; outline: none; transition: border-color 0.2s; background: white;}
    .filter-search-box input:focus { border-color: var(--brand-primary, #7A1C1C); }
    .filter-search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.85rem; }
    
    .filter-list { list-style: none; padding: 0; margin: 0; }
    .filter-item { margin-bottom: 12px; }
    .filter-item label { display: flex; align-items: flex-start; cursor: pointer; font-size: 0.9rem; color: #4b5563; transition: color 0.2s; }
    .filter-item label:hover { color: var(--brand-primary, #7A1C1C); }
    .filter-item input[type=\"checkbox\"] { margin-top: 3px; margin-right: 10px; width: 16px; height: 16px; accent-color: var(--brand-primary, #7A1C1C); cursor: pointer; border-radius: 2px;}
    
    .btn-view-more { color: var(--text-gray, #6b7280); background: none; border: none; padding: 0; font-size: 0.85rem; font-weight: 500; text-decoration: underline; cursor: pointer; margin-top: 5px; transition: color 0.2s;}
    .btn-view-more:hover { color: var(--brand-primary, #7A1C1C); }
    .filter-item.hidden-item { display: none; }

    /* ==========================================
       2. HERO SWIPER TẠP CHÍ
       ========================================== */
    .hero-section-wrapper { margin-bottom: 40px; }
    .hero-swiper { width: 100%; padding-bottom: 40px;}
    
    .hero-swiper .swiper-slide { 
        aspect-ratio: 16/9; 
        overflow: hidden; 
        background: var(--brand-light, #fdfaf7); 
        position: relative;
    }
    @media (min-width: 992px) {
        .hero-swiper .swiper-slide { aspect-ratio: 21/7; }
    }
    
    .hero-swiper img { width: 100%; height: 100%; object-fit: cover; object-position: center; }
    
    .banner-overlay-text { 
        position: absolute; 
        top: 50%; 
        left: 8%; 
        transform: translateY(-50%); 
        max-width: 45%; 
        background: rgba(255,255,255,0.85); 
        padding: 40px; 
        border-radius: 4px;
        backdrop-filter: blur(4px);
    }
    .banner-brand-title { font-family: var(--font-heading, 'Playfair Display', serif); font-size: 3rem; color: var(--brand-primary, #7A1C1C); margin-bottom: 15px; font-weight: 700; line-height: 1.1; }
    .banner-ambassador { font-size: 1.1rem; color: var(--text-dark, #333); margin-bottom: 0;}
    
    .hero-swiper .swiper-button-next, .hero-swiper .swiper-button-prev { color: var(--brand-primary, #7A1C1C); background: white; width: 50px; height: 50px; border-radius: 50%; border: 1px solid #f0f0f0;}
    .hero-swiper .swiper-button-next:after, .hero-swiper .swiper-button-prev:after { font-size: 1.2rem; font-weight: 900; }
    .hero-swiper .swiper-pagination-bullet { background: #ccc; opacity: 1; transition: all 0.3s;}
    .hero-swiper .swiper-pagination-bullet-active { background: var(--brand-primary, #7A1C1C); transform: scale(1.3); }

    /* ==========================================
       3. LOGO BÁO CHÍ & THƯƠNG HIỆU NỔI BẬT
       ========================================== */
    .press-logos { display: flex; justify-content: center; align-items: center; gap: 50px; flex-wrap: wrap; padding: 40px 0; border-bottom: 1px solid #f0f0f0; margin-bottom: 40px; }
    .press-logos h4 { font-size: 1.6rem; color: var(--brand-primary, #7A1C1C); margin: 0; opacity: 0.9; font-family: var(--font-heading, 'Playfair Display', serif); letter-spacing: 2px; font-weight: 700;}
    
    .brand-pill {
        padding: 10px 25px;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        color: var(--text-dark, #333);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        background: white;
    }
    .brand-pill:hover {
        border-color: var(--brand-primary, #7A1C1C);
        color: var(--brand-primary, #7A1C1C);
        background: var(--brand-light, #fdfaf7);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(122, 28, 28, 0.08);
    }

    .mid-banner { text-align: center; padding: 40px 0 80px; }
    .mid-banner h2 { font-size: 5rem; font-weight: 400; margin-bottom: 15px; color: var(--brand-primary, #7A1C1C); line-height: 1.1;}
    .mid-banner p { font-size: 1.15rem; color: var(--brand-primary, #7A1C1C); font-weight: 500; }

    /* ==========================================
       4. BỐ CỤC SẢN PHẨM FIX LỖI TRÀN CHỮ
       ========================================== */
    .product-card {
        border: none;
        background: transparent;
        transition: none;
        width: 100%;
    }
    
    /* FIX: Cố định kích thước ảnh để không bị vỡ Layout */
    .product-img-container {
        height: 250px;
        background-color: var(--bg-card, #f8f8f8);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        width: 100%;
    }
    .product-img-container img {
        max-height: 85%;
        max-width: 85%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }
    .product-card:hover .product-img-container img {
        transform: scale(1.08);
    }
    
    .quick-view-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(253, 250, 247, 0.5);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: all 0.3s ease; backdrop-filter: blur(2px);
    }
    .product-card:hover .quick-view-overlay { opacity: 1; }
    
    /* FIX: Giới hạn độ dài chữ (Line Clamp) */
    .product-title-clamp {
        font-family: var(--font-heading, 'Playfair Display', serif);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--brand-primary, #7A1C1C);
        line-height: 1.4em;
        height: 2.8em; /* 2 dòng */
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 5px;
    }
    
    .btn-flat-wide {
        width: 100%;
        background-color: transparent;
        color: var(--brand-primary, #7A1C1C);
        border: 1px solid var(--brand-primary, #7A1C1C);
        padding: 10px 0;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 4px;
        transition: all 0.3s;
        text-align: center;
        display: block;
        margin-top: 10px;
    }
    .btn-flat-wide:hover { background-color: var(--brand-primary, #7A1C1C); color: white; text-decoration: none;}
</style>
";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<!-- ========================================================== -->
<!-- HERO SECTION: BANNER SWIPER CHUẨN TẠP CHÍ -->
<!-- ========================================================== -->
<section class="hero-section-wrapper">
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
                            <div class="banner-overlay-text d-none d-md-block shadow-sm">
                                <?php if (!empty($banner['brand_name'])): ?>
                                    <h4 class="banner-brand-title"><?php echo htmlspecialchars($banner['brand_name']); ?></h4>
                                <?php endif; ?>
                                <?php if (!empty($banner['ambassador'])): ?>
                                    <p class="banner-ambassador">Đại sứ thương hiệu: <br><strong><?php echo htmlspecialchars($banner['ambassador']); ?></strong></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Slide Dự phòng chuẩn Beauty Minimalist -->
                <div class="swiper-slide position-relative">
                    <img src="https://images.unsplash.com/photo-1618331835717-801e976710b2?q=80&w=1600&auto=format&fit=crop" alt="Hero Banner">
                    <div class="banner-overlay-text d-none d-md-block shadow-sm">
                        <h4 class="banner-brand-title">Vẻ đẹp <br><i>đồng hành</i> cùng bạn</h4>
                        <p class="banner-ambassador">Khám phá bí quyết lưu giữ nét thanh xuân.</p>
                        <a href="#" class="btn btn-brand mt-4 px-4 py-2 text-decoration-none" style="background: var(--brand-secondary, #ffcce0); color: var(--brand-primary, #7A1C1C);">Khám phá ngay</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- ========================================================== -->
<!-- BÁO CHÍ, THƯƠNG HIỆU NỔI BẬT & THÔNG ĐIỆP -->
<!-- ========================================================== -->
<div class="container">
    <div class="press-logos">
        <h4>VOGUE</h4>
        <h4>ELLE</h4>
        <h4>NYLON</h4>
        <h4 style="text-transform: lowercase;">goop</h4>
        <h4>COSMOPOLITAN</h4>
        <h4 style="font-style: italic;">STYLIST</h4>
    </div>
    
    <!-- THƯƠNG HIỆU NỔI BẬT -->
    <div class="featured-brands-section pb-4">
        <h4 class="text-center mb-4" style="font-family: var(--font-heading, 'Playfair Display', serif); color: var(--text-dark, #333); font-size: 1.5rem;">Thương Hiệu Nổi Bật</h4>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <?php 
            // Danh sách các thương hiệu gợi ý (Sẽ link tới trang danh mục thương hiệu tương ứng)
            $topBrands = ['Dior', 'M.A.C', 'Anessa', 'Bioderma', 'La Roche-Posay', "L'Oreal"];
            foreach($topBrands as $tb): 
            ?>
                <a href="index.php?controller=brand&action=detail&name=<?php echo urlencode($tb); ?>" class="brand-pill">
                    <?php echo htmlspecialchars($tb); ?>
                </a>
            <?php endforeach; ?>
            <a href="index.php?controller=brand&action=index" class="brand-pill" style="background: var(--brand-primary, #7A1C1C); color: white; border-color: var(--brand-primary, #7A1C1C);">
                Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <div class="mid-banner">
        <h2 class="serif-font">Lifeproof <br> <i style="font-style: italic;">beauty.</i></h2>
        <p>Dễ dàng sử dụng, bền màu suốt ngày dài,<br>thành phần thuần chay & an toàn.</p>
    </div>
</div>

<!-- ========================================================== -->
<!-- DANH SÁCH SẢN PHẨM & BỘ LỌC -->
<!-- ========================================================== -->
<section id="products-section" class="container py-4">
    <div class="row">
        
        <!-- CỘT TRÁI: BỘ LỌC SIDEBAR -->
        <div class="col-lg-3 mb-4">
            <div class="filter-sidebar">
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
                            <div class="filter-search-box"><i class="fas fa-search"></i><input type="text" class="searchInput" data-target="catList" placeholder="Tìm kiếm nhanh..."></div>
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

                    <button type="submit" class="btn w-100 mt-3 border-0 py-3 text-white" style="border-radius: 4px; background-color: var(--brand-primary, #7A1C1C);">Áp dụng bộ lọc</button>
                </form>
            </div>
        </div>

        <!-- CỘT PHẢI: LƯỚI SẢN PHẨM -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-3">
                <h3 class="section-title m-0 fw-bold" style="font-family: var(--font-heading, 'Playfair Display', serif); color: var(--brand-primary, #7A1C1C);"><?php echo isset($pageTitle) ? $pageTitle : 'Công thức làm đẹp'; ?></h3>
                <select name="sort" form="filterForm" onchange="document.getElementById('filterForm').submit();" class="form-select w-auto bg-transparent border-0 text-muted shadow-none" style="cursor: pointer;">
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
                        <!-- Chú ý: col-md-4 và d-flex flex-column giúp các thẻ cao bằng nhau -->
                        <div class="col-md-4 col-6 mb-3 d-flex">
                            <div class="product-card h-100 d-flex flex-column">
                                
                                <!-- Ảnh & Huy hiệu -->
                                <a href="index.php?controller=product&action=detail&id=<?php echo $row['id']; ?>" class="text-decoration-none">
                                    <div class="product-img-container">
                                        <?php if ($isSale): 
                                            $discountPercent = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                                        ?>
                                            <span class="badge-status position-absolute top-0 start-0 m-3 z-3 px-3 py-1 bg-white border rounded" style="color: var(--brand-primary, #7A1C1C); font-weight: 600; font-size: 0.75rem;">-<?php echo $discountPercent; ?>%</span>
                                        <?php elseif (isset($currentFilter) && $currentFilter == 'new'): ?>
                                            <span class="badge-status position-absolute top-0 start-0 m-3 z-3 px-3 py-1 bg-white border rounded" style="color: var(--brand-primary, #7A1C1C); font-weight: 600; font-size: 0.75rem;">Mới</span>
                                        <?php endif; ?>

                                        <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                        
                                        <!-- OVERLAY NÚT XEM NHANH -->
                                        <div class="quick-view-overlay">
                                            <button type="button" class="btn-quick-view btn px-4 py-2 bg-white border" style="color: var(--brand-primary, #7A1C1C); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
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
                                
                                <!-- Thông tin căn giữa, ép dùng flex để đẩy nút xuống đáy -->
                                <div class="product-info text-center mt-3 flex-grow-1 d-flex flex-column px-1">
                                    
                                    <!-- Tiêu đề: Khóa cứng chiều cao bằng line-clamp (2 dòng) -->
                                    <h3 class="product-title-clamp">
                                        <a href="index.php?controller=product&action=detail&id=<?php echo $row['id']; ?>" class="text-decoration-none" style="color: inherit;">
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </a>
                                    </h3>
                                    
                                    <!-- Danh mục: Khóa cứng 1 dòng (text-truncate) -->
                                    <p class="product-desc mb-2 text-muted text-truncate" style="font-size: 0.85rem;">
                                        <?php echo htmlspecialchars($row['category']); ?>
                                    </p>
                                    
                                    <div class="product-price mb-2" style="font-size: 1.05rem; color: var(--brand-primary, #7A1C1C); font-weight: 500;">
                                        <?php if ($isSale): ?>
                                            <span class="text-muted text-decoration-line-through me-2 fw-normal" style="font-size: 0.9rem;">
                                                <?php echo number_format($row['old_price']); ?>đ
                                            </span>
                                        <?php endif; ?>
                                        <?php echo number_format($row['price']); ?>đ
                                    </div>
                                    
                                    <!-- Nút Mua (mt-auto đẩy nút xuống dưới cùng) -->
                                    <div class="mt-auto pt-2 w-100">
                                        <a href="index.php?controller=cart&action=add&id=<?php echo $row['id']; ?>" class="btn-flat-wide">
                                            Thêm vào giỏ
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3 opacity-50"></i>
                        <h5 class="text-muted" style="font-weight: 400;">Không tìm thấy sản phẩm nào.</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- MODAL POPUP XEM NHANH SẢN PHẨM -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 8px;">
      <div class="modal-header border-0 pb-0 position-absolute top-0 end-0 z-3">
        <button type="button" class="btn-close bg-white p-2 shadow-sm m-2" style="border-radius: 4px;" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="row g-0">
            <div class="col-md-5 d-flex align-items-center justify-content-center p-4" style="background: var(--bg-card, #f8f8f8);">
                <img id="qv-img" src="" class="img-fluid" style="max-height: 400px; object-fit: contain;">
            </div>
            <div class="col-md-7 p-4 p-md-5 d-flex flex-column justify-content-center bg-white">
                <span id="qv-cat" class="text-muted text-uppercase mb-2 align-self-start" style="letter-spacing: 1px; font-size: 0.8rem;">Category</span>
                <h4 id="qv-name" class="fw-bold mb-3" style="font-family: var(--font-heading, 'Playfair Display', serif); color: var(--brand-primary, #7A1C1C); font-size: 2rem;">Tên sản phẩm</h4>
                
                <div class="d-flex align-items-end gap-3 mb-4">
                    <h2 id="qv-price" class="fw-bold mb-0" style="color: var(--text-dark, #333); font-size: 1.5rem;">0đ</h2>
                    <span id="qv-oldprice" class="text-muted text-decoration-line-through mb-1" style="font-size: 1.1rem;"></span>
                </div>
                
                <p class="text-muted small mb-4 lh-lg"><i class="fas fa-check text-success me-2"></i> Cam kết hàng chính hãng 100%<br><i class="fas fa-truck text-secondary me-2"></i> Miễn phí giao hàng cho hóa đơn trên 500k</p>

                <div class="d-flex gap-2 mt-auto">
                    <a id="qv-add-cart" href="#" class="btn flex-grow-1 text-white border-0 py-3" style="background-color: var(--brand-primary, #7A1C1C); border-radius: 4px; font-weight: 500; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                        Thêm vào giỏ
                    </a>
                    <a id="qv-detail" href="#" class="btn btn-outline-dark py-3 px-4" style="border-radius: 4px; font-weight: 500; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; border-color: #d1d5db;">
                        Chi tiết
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
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. KHỞI TẠO HIỆU ỨNG SWIPER CHO BANNER
    var swiper = new Swiper(".hero-swiper", {
        slidesPerView: 1,     
        spaceBetween: 0,        
        loop: true,              
        effect: "fade", 
        fadeEffect: { crossFade: true },
        autoplay: { delay: 5000, disableOnInteraction: false },
        pagination: { el: ".swiper-pagination", clickable: true },
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" }
    });

    // 2. Popup Xem Nhanh Sản Phẩm
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

    // 3. Nút Xem thêm / Thu gọn bộ lọc
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

    // 4. Tìm kiếm trong bộ lọc
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

    // 5. Giữ trạng thái Checkbox khi load trang
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