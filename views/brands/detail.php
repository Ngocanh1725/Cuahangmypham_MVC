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
        background: #fdfbf7; /* Đổi màu nền từ trắng sang be nhạt sang trọng */
        border-radius: 8px;
        padding: 20px 40px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        width: 80%;
        max-width: 800px;
        border: 1px solid #f0ebe1;
    }
    .brand-logo-img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        margin-top: -70px;
        margin-bottom: 15px;
        border: 3px solid #fdfbf7; /* Khớp với nền mới của info-box */
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
    
    /* CSS Bộ lọc phong cách Beauty Box */
    .filter-sidebar { background: #fff; padding: 0; border: none; }
    .filter-main-title { font-size: 1.15rem; font-weight: 800; text-transform: uppercase; margin-bottom: 25px; letter-spacing: 0.5px; }
    .filter-group { border-bottom: 1px solid #f1f1f1; padding-bottom: 20px; margin-bottom: 20px; }
    .filter-group-header { display: flex; justify-content: space-between; align-items: center; cursor: pointer; margin-bottom: 15px; }
    .filter-group-title { font-weight: 700; font-size: 0.95rem; color: #1a1a1a; margin: 0; }
    .filter-group-icon { font-size: 0.8rem; color: #1a1a1a; transition: transform 0.3s; }
    .filter-group-icon.collapsed { transform: rotate(180deg); }
    .filter-content { overflow: hidden; transition: max-height 0.3s ease-out; }
    .filter-search-box { position: relative; margin-bottom: 15px; }
    .filter-search-box input { width: 100%; padding: 8px 10px 8px 32px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 0.85rem; outline: none; }
    .filter-search-box input:focus { border-color: #1a1a1a; }
    .filter-search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; font-size: 0.85rem; }
    .filter-list { list-style: none; padding: 0; margin: 0; }
    .filter-item { margin-bottom: 10px; display: flex; align-items: center; }
    .filter-checkbox { display: flex; align-items: center; cursor: pointer; font-size: 0.95rem; color: #1a1a1a; width: 100%; transition: color 0.2s;}
    .filter-checkbox:hover { color: #000; }
    .filter-item input[type=\"checkbox\"] { margin-right: 10px; accent-color: #1a1a1a; cursor: pointer; width: 1.1rem; height: 1.1rem; }
    .hidden-item { display: none !important; }
    .btn-view-more { background: none; border: none; padding: 0; text-decoration: underline; color: #1a1a1a; font-size: 0.85rem; margin-top: 5px; cursor: pointer; font-weight: 500; }
    .btn-view-more:hover { color: #000; }
    .filter-badge { font-size: 0.85rem; color: #6c757d; margin-left: 0.2rem; }
    
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

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="filter-main-title mb-0">Bộ lọc</h3>
                        <?php if(!empty($_GET['price']) || !empty($_GET['category'])): ?>
                            <a href="index.php?controller=brand&action=detail&name=<?php echo urlencode($brandInfo['name']); ?>" class="text-danger small fw-bold text-decoration-none" style="text-decoration: underline !important;">Xóa tất cả</a>
                        <?php endif; ?>
                    </div>

                    <!-- 1. Giá sản phẩm -->
                    <div class="filter-group">
                        <div class="filter-group-header toggle-accordion" data-target="filter-price">
                            <h5 class="filter-group-title">Giá sản phẩm</h5>
                            <i class="fas fa-chevron-up filter-group-icon"></i>
                        </div>
                        <div class="filter-content" id="filter-price">
                            <ul class="filter-list">
                                <?php 
                                $currentPrices = isset($_GET['price']) && is_array($_GET['price']) ? $_GET['price'] : [];
                                $newPriceRanges = [
                                    '0-500000' => 'Dưới 500.000đ',
                                    '500000-1000000' => '500.000đ - 1.000.000đ',
                                    '1000000-1500000' => '1.000.000đ - 1.500.000đ',
                                    '1500000-2000000' => '1.500.000đ - 2.000.000đ',
                                    '2000000-0' => 'Trên 2.000.000đ'
                                ];
                                foreach($newPriceRanges as $val => $label): 
                                    $isChecked = in_array($val, $currentPrices) ? 'checked' : '';
                                ?>
                                    <li class="filter-item">
                                        <label class="filter-checkbox w-100">
                                            <input type="checkbox" name="price[]" value="<?php echo $val; ?>" <?php echo $isChecked; ?> onchange="document.getElementById('filterForm').submit();">
                                            <?php echo $label; ?>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- 2. Loại sản phẩm -->
                    <div class="filter-group border-0 mb-0 pb-0">
                        <div class="filter-group-header toggle-accordion" data-target="filter-category">
                            <h5 class="filter-group-title">Loại sản phẩm</h5>
                            <i class="fas fa-chevron-up filter-group-icon"></i>
                        </div>
                        <div class="filter-content" id="filter-category">
                            <div class="filter-search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="searchInput" data-target="catList" placeholder="Tìm">
                            </div>
                            <ul class="filter-list" id="catList">
                                <?php 
                                $catIndex = 0;
                                $currentCategories = isset($_GET['category']) && is_array($_GET['category']) ? $_GET['category'] : [];
                                if (isset($filterCategories)) {
                                    foreach($filterCategories as $cat): 
                                        $count = isset($catCounts[$cat]) ? $catCounts[$cat] : 0;
                                        $isChecked = in_array($cat, $currentCategories) ? 'checked' : '';
                                        $isHidden = $catIndex >= 5 ? 'hidden-item' : '';
                                    ?>
                                        <li class="filter-item <?php echo $isHidden; ?>">
                                            <label class="filter-checkbox w-100">
                                                <input type="checkbox" name="category[]" value="<?php echo htmlspecialchars($cat); ?>" <?php echo $isChecked; ?> onchange="document.getElementById('filterForm').submit();">
                                                <span class="item-name"><?php echo htmlspecialchars($cat); ?></span>
                                                <span class="filter-badge">(<?php echo $count; ?>)</span>
                                            </label>
                                        </li>
                                    <?php $catIndex++; endforeach; 
                                } ?>
                            </ul>
                            <?php if($catIndex > 5): ?>
                                <button type="button" class="btn-view-more" data-target="catList">Xem thêm</button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <noscript>
                        <button type="submit" class="btn text-white w-100 mt-4 py-3 fw-bold" style="background-color: var(--brand-primary, #7A1C1C); border-radius: 4px;">Áp dụng bộ lọc</button>
                    </noscript>
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
    // Accordion Toggle
    const accordions = document.querySelectorAll('.toggle-accordion');
    accordions.forEach(acc => {
        acc.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetContent = document.getElementById(targetId);
            const icon = this.querySelector('.filter-group-icon');
            
            if (targetContent.style.maxHeight) {
                targetContent.style.maxHeight = null;
                icon.classList.add('collapsed');
            } else {
                targetContent.style.maxHeight = targetContent.scrollHeight + "px";
                icon.classList.remove('collapsed');
            }
        });
        
        // Initialize all as open
        const targetId = acc.getAttribute('data-target');
        const targetContent = document.getElementById(targetId);
        if(targetContent) {
            targetContent.style.maxHeight = targetContent.scrollHeight + "px";
        }
    });

    // View More Button
    const viewMoreBtns = document.querySelectorAll('.btn-view-more');
    viewMoreBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const hiddenItems = document.getElementById(targetId).querySelectorAll('.filter-item.hidden-item');
            
            if (this.innerText === 'Xem thêm') {
                hiddenItems.forEach(item => item.style.display = 'flex');
                this.innerText = 'Thu gọn';
            } else {
                hiddenItems.forEach(item => {
                    if(!item.querySelector('input').checked) {
                        item.style.display = 'none';
                    }
                });
                this.innerText = 'Xem thêm';
            }
            // Update accordion max height
            const parentContent = this.closest('.filter-content');
            if(parentContent && parentContent.style.maxHeight) {
                parentContent.style.maxHeight = parentContent.scrollHeight + "px";
            }
        });
    });

    // Ensure checked hidden items are always visible on load
    document.querySelectorAll('.filter-list').forEach(list => {
        const hiddenChecked = list.querySelectorAll('.hidden-item input:checked');
        hiddenChecked.forEach(input => {
            input.closest('.filter-item').style.display = 'flex';
        });
    });

    // Search in Filter
    document.querySelectorAll('.searchInput').forEach(input => {
        input.addEventListener('keyup', function() {
            const filterText = this.value.toLowerCase().trim();
            const targetId = this.getAttribute('data-target');
            const items = document.getElementById(targetId).querySelectorAll('.filter-item');
            
            items.forEach(item => {
                const text = item.querySelector('.item-name').innerText.toLowerCase();
                if (text.includes(filterText)) {
                    item.style.display = 'flex'; 
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Re-adjust accordion height
            const parentContent = document.getElementById(targetId).closest('.filter-content');
            if(parentContent) {
                parentContent.style.maxHeight = parentContent.scrollHeight + "px";
            }
        });
    });
});
</script>
EOT;
include 'views/layout/footer.php'; 
?>