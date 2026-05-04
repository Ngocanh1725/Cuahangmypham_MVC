<?php 
$extraCSS = "
<style>
    .brand-hero {
        position: relative;
        background-color: #fdfaf7;
        margin-bottom: 60px;
        border-bottom: 1px solid #f0f0f0;
    }
    .brand-banner img {
        width: 100%;
        height: 350px;
        object-fit: cover;
        background-color: #e5e7eb;
    }
    .brand-info-box {
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        border-radius: 4px;
        padding: 20px 40px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        width: 80%;
        max-width: 800px;
    }
    .brand-logo-img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-top: -70px;
        margin-bottom: 15px;
        border: 2px solid #fff;
    }
    .brand-stats {
        font-size: 0.9rem;
        color: #4b5563;
        margin: 10px 0;
    }
    .brand-stats span {
        margin: 0 10px;
    }
    .brand-desc {
        font-size: 0.95rem;
        color: #6b7280;
        margin-bottom: 0;
    }
    
    /* CSS Bộ lọc */
    .filter-sidebar { background: var(--bg-card, #f8f8f8); padding: 25px; border-radius: 4px; border: none; }
    .filter-group { border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
    .filter-title { font-family: var(--font-heading, 'Playfair Display', serif); font-size: 1.15rem; color: var(--brand-primary, #7A1C1C); margin-bottom: 15px; font-weight: 600;}
    .filter-search-box { position: relative; margin-bottom: 15px; }
    .filter-search-box input { width: 100%; padding: 10px 10px 10px 35px; border: 1px solid #e5e7eb; border-radius: 4px; outline: none; background: white;}
    .filter-search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
    .filter-list { list-style: none; padding: 0; margin: 0; max-height: 250px; overflow-y: auto; }
    .filter-item { margin-bottom: 12px; }
    .filter-item input[type=\"checkbox\"] { margin-right: 10px; accent-color: var(--brand-primary, #7A1C1C); border-radius: 2px; }
    .hidden-item { display: none; }
    .btn-view-more { color: #6b7280; background: none; border: none; padding: 0; font-size: 0.85rem; text-decoration: underline; margin-top: 5px; }
    
    /* CSS Lưới Sản Phẩm Mới (Sửa lỗi vỡ khung) */
    .product-card { border: none; background: transparent; width: 100%; }
    .product-img-container { height: 250px; background-color: var(--bg-card, #f8f8f8); border-radius: 8px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; width: 100%; }
    .product-img-container img { max-height: 85%; max-width: 85%; object-fit: contain; transition: transform 0.5s ease; }
    .product-card:hover .product-img-container img { transform: scale(1.08); }
    .product-title-clamp { font-family: var(--font-heading, 'Playfair Display', serif); font-size: 1.1rem; font-weight: 600; color: var(--brand-primary, #7A1C1C); line-height: 1.4em; height: 2.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-bottom: 5px; }
    .btn-flat-wide { width: 100%; background-color: transparent; color: var(--brand-primary, #7A1C1C); border: 1px solid var(--brand-primary, #7A1C1C); padding: 10px 0; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; border-radius: 4px; transition: all 0.3s; text-align: center; display: block; margin-top: 10px; }
    .btn-flat-wide:hover { background-color: var(--brand-primary, #7A1C1C); color: white; text-decoration: none;}
</style>
";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 

// Xử lý cẩn thận đường dẫn ảnh để không bị vỡ giao diện
$bannerSrc = (!empty($brandInfo['banner']) && $brandInfo['banner'] != 'https://via.placeholder.com/1200x300?text=No+Banner') ? $brandInfo['banner'] : 'https://via.placeholder.com/1200x400?text='.urlencode($brandInfo['name']).'+Banner';
$logoSrc = (!empty($brandInfo['logo']) && $brandInfo['logo'] != 'https://via.placeholder.com/150?text=No+Logo') ? $brandInfo['logo'] : 'https://via.placeholder.com/150?text='.urlencode($brandInfo['name']).'+Logo';
?>

<!-- Phần Header của Thương hiệu (Banner + Logo) -->
<div class="brand-hero">
    <div class="brand-banner">
        <!-- Đã thêm onerror dự phòng nếu đường dẫn ảnh bị lỗi -->
        <img src="<?php echo htmlspecialchars($bannerSrc, ENT_QUOTES); ?>" onerror="this.src='https://via.placeholder.com/1200x400?text=Banner+Not+Found'" alt="<?php echo htmlspecialchars($brandInfo['name']); ?> Banner">
    </div>
    <div class="brand-info-box">
        <!-- Đã thêm onerror dự phòng nếu đường dẫn logo bị lỗi -->
        <img src="<?php echo htmlspecialchars($logoSrc, ENT_QUOTES); ?>" onerror="this.src='https://via.placeholder.com/150?text=Logo+Not+Found'" alt="<?php echo htmlspecialchars($brandInfo['name']); ?> Logo" class="brand-logo-img">
        <h2 class="fw-bold mb-1 text-uppercase" style="color: var(--brand-primary, #7A1C1C); font-family: var(--font-heading, 'Playfair Display', serif);"><?php echo htmlspecialchars($brandInfo['name']); ?></h2>
        <div class="brand-stats fw-medium">
            <span><?php echo $brandInfo['product_count'] ?? 0; ?> sản phẩm</span> | 
            <span><?php echo htmlspecialchars($brandInfo['sales_count'] ?? '0 lượt mua'); ?></span>
        </div>
        <p class="brand-desc"><?php echo htmlspecialchars($brandInfo['description'] ?? 'Thương hiệu mỹ phẩm cao cấp.'); ?></p>
    </div>
</div>

<div class="container py-5 mt-5">
    <div class="row">
        <!-- BỘ LỌC CỦA RIÊNG THƯƠNG HIỆU NÀY -->
        <div class="col-lg-3 mb-4">
            <div class="filter-sidebar shadow-sm">
                <form action="index.php" method="GET" id="filterForm">
                    <input type="hidden" name="controller" value="brand">
                    <input type="hidden" name="action" value="detail">
                    <!-- Giữ lại tên hãng trên URL để không bị mất kết quả -->
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($brandInfo['name']); ?>">

                    <!-- 1. Giá sản phẩm -->
                    <div class="filter-group">
                        <div class="filter-title">Giá sản phẩm</div>
                        <ul class="filter-list">
                            <li class="filter-item"><label><input type="checkbox" name="price[]" value="0-500000"> Dưới 500.000đ</label></li>
                            <li class="filter-item"><label><input type="checkbox" name="price[]" value="500000-1000000"> 500.000đ - 1.000.000đ</label></li>
                            <li class="filter-item"><label><input type="checkbox" name="price[]" value="1000000-1500000"> 1.000.000đ - 1.500.000đ</label></li>
                            <li class="filter-item"><label><input type="checkbox" name="price[]" value="1500000-"> Trên 1.500.000đ</label></li>
                        </ul>
                    </div>

                    <!-- 2. Loại sản phẩm -->
                    <div class="filter-group border-0 mb-0 pb-0">
                        <div class="filter-title">Loại sản phẩm</div>
                        <div class="filter-search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="searchInput" data-target="catList" placeholder="Tìm...">
                        </div>
                        <ul class="filter-list" id="catList">
                            <?php 
                            $catIndex = 0;
                            if (isset($filterCategories)) {
                                foreach($filterCategories as $cat): 
                                    $count = isset($catCounts[$cat]) ? $catCounts[$cat] : 0;
                                    $isHidden = $catIndex >= 6 ? 'hidden-item' : '';
                                ?>
                                    <li class="filter-item <?php echo $isHidden; ?>">
                                        <label>
                                            <input type="checkbox" name="category[]" value="<?php echo htmlspecialchars($cat); ?>">
                                            <?php echo htmlspecialchars($cat); ?> (<?php echo $count; ?>)
                                        </label>
                                    </li>
                                <?php $catIndex++; endforeach; 
                            } ?>
                        </ul>
                        <button type="button" class="btn-view-more" data-target="catList">Xem thêm</button>
                    </div>

                    <button type="submit" class="btn text-white w-100 mt-4 py-3 fw-bold" style="background-color: var(--brand-primary, #7A1C1C); border-radius: 4px;">Lọc kết quả</button>
                </form>
            </div>
        </div>

        <!-- LƯỚI SẢN PHẨM CỦA HÃNG -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-3">
                <h4 class="fw-bold m-0" style="font-family: var(--font-heading, 'Playfair Display', serif); color: var(--brand-primary, #7A1C1C);">Sản phẩm từ <?php echo htmlspecialchars($brandInfo['name']); ?></h4>
                <select name="sort" form="filterForm" onchange="document.getElementById('filterForm').submit();" class="form-select w-auto bg-transparent border-0 text-muted shadow-none" style="cursor: pointer;">
                    <option value="">Sắp xếp: Mới nhất</option>
                    <option value="price_asc">Giá: Thấp đến Cao</option>
                    <option value="price_desc">Giá: Cao đến Thấp</option>
                </select>
            </div>

            <div class="row g-4">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $row): 
                        $imgUrl = !empty($row['image']) ? $row['image'] : 'https://via.placeholder.com/400x400';
                        $isSale = (isset($row['old_price']) && $row['old_price'] > $row['price']);
                    ?>
                        <div class="col-md-4 col-6 mb-3 d-flex">
                            <div class="product-card h-100 d-flex flex-column">
                                <a href="index.php?controller=product&action=detail&id=<?php echo $row['id']; ?>" class="text-decoration-none">
                                    <div class="product-img-container">
                                        <?php if ($isSale): 
                                            $discountPercent = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                                        ?>
                                            <span class="badge-status position-absolute top-0 start-0 m-3 z-3 px-3 py-1 bg-white border rounded" style="color: var(--brand-primary, #7A1C1C); font-weight: 600; font-size: 0.75rem;">-<?php echo $discountPercent; ?>%</span>
                                        <?php endif; ?>
                                        <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
                                    </div>
                                </a>
                                
                                <div class="product-info text-center mt-3 flex-grow-1 d-flex flex-column px-1">
                                    <h3 class="product-title-clamp">
                                        <a href="index.php?controller=product&action=detail&id=<?php echo $row['id']; ?>" class="text-decoration-none" style="color: inherit;">
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </a>
                                    </h3>
                                    
                                    <p class="product-desc mb-2 text-muted text-truncate" style="font-size: 0.85rem;">
                                        <?php echo htmlspecialchars($row['category'] ?? 'Phân loại'); ?>
                                    </p>
                                    
                                    <div class="product-price mb-2" style="font-size: 1.05rem; color: var(--brand-primary, #7A1C1C); font-weight: 500;">
                                        <?php if ($isSale): ?>
                                            <span class="text-muted text-decoration-line-through me-2 fw-normal" style="font-size: 0.9rem;">
                                                <?php echo number_format($row['old_price']); ?>đ
                                            </span>
                                        <?php endif; ?>
                                        <?php echo number_format($row['price']); ?>đ
                                    </div>
                                    
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
                    <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                        <i class="fas fa-box-open fa-3x text-muted mb-3 opacity-50"></i>
                        <h5 class="text-muted">Không tìm thấy sản phẩm.</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
$extraJS = <<<EOT
<script>
document.addEventListener('DOMContentLoaded', function() {
    // JS Ẩn/hiện Xem thêm
    const viewMoreBtns = document.querySelectorAll('.btn-view-more');
    viewMoreBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const hiddenItems = document.getElementById(targetId).querySelectorAll('.hidden-item');
            if (this.innerText === 'Xem thêm') {
                hiddenItems.forEach(item => item.style.display = 'block');
                this.innerText = 'Thu gọn';
            } else {
                hiddenItems.forEach(item => item.style.display = 'none');
                this.innerText = 'Xem thêm';
            }
        });
    });

    // JS Tìm kiếm trong bộ lọc
    document.querySelectorAll('.searchInput').forEach(input => {
        input.addEventListener('keyup', function() {
            const filterText = this.value.toLowerCase().trim();
            const listId = this.getAttribute('data-target');
            const items = document.getElementById(listId).querySelectorAll('.filter-item');
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

    // JS Giữ trạng thái tick checkbox
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