<?php 
// Giao diện Trang Cửa hàng phong cách Rhode
$extraCSS = "
<style>
    /* Shop Header Banner */
    .shop-header {
        background-color: var(--rhode-pink-light);
        border-radius: var(--radius-card-lg);
        padding: 60px 40px;
        text-align: center;
        margin-bottom: 50px;
        margin-top: 20px;
    }

    /* Sidebar Filter */
    .filter-sidebar {
        background: #fff;
        border-radius: var(--radius-card-md);
        padding: 30px;
        box-shadow: var(--shadow-soft);
        position: sticky;
        top: 100px; /* Bám dính khi cuộn, dưới navbar */
    }
    .filter-title {
        font-family: var(--font-serif);
        color: var(--rhode-pink-accent);
        font-size: 1.5rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--rhode-pink-light);
    }
    .filter-group h5 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .filter-link {
        color: var(--text-main);
        display: block;
        padding: 8px 0;
        transition: all 0.3s;
        text-decoration: none;
    }
    .filter-link:hover, .filter-link.active {
        color: var(--rhode-pink-accent);
        padding-left: 10px;
        font-weight: 500;
    }

    /* Product Card */
    .product-card {
        background: #fff;
        border-radius: var(--radius-card-md);
        padding: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--rhode-pink-light);
    }
    .product-img-wrapper {
        border-radius: var(--radius-card-sm);
        overflow: hidden;
        margin-bottom: 20px;
        background-color: var(--rhode-bg-main); /* Nền cho ảnh trong suốt */
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-img-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }
    .product-card:hover .product-img-wrapper img {
        transform: scale(1.08);
    }
    .product-brand {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-light);
        margin-bottom: 5px;
    }
    .product-title {
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--text-main);
        margin-bottom: 10px;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-title:hover {
        color: var(--rhode-pink-accent);
    }
    .product-price {
        font-family: var(--font-sans);
        font-weight: 600;
        font-size: 1.2rem;
        color: var(--text-main);
    }
    .product-footer {
        margin-top: auto; /* Đẩy xuống đáy card */
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
    }
    .add-to-cart-btn {
        background: var(--rhode-bg-main);
        color: var(--rhode-pink-accent);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.3s;
    }
    .add-to-cart-btn:hover {
        background: var(--rhode-pink-accent);
        color: #fff;
    }
    
    /* Pagination */
    .pagination .page-item .page-link {
        border: none;
        color: var(--text-main);
        margin: 0 5px;
        border-radius: 50%;
        width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--rhode-pink-accent);
        color: #fff;
    }
</style>
";

include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 

// Xử lý Lọc và Tìm kiếm
$currentCategories = isset($_GET['category']) && is_array($_GET['category']) ? $_GET['category'] : [];
$currentBrands = isset($_GET['brand']) && is_array($_GET['brand']) ? $_GET['brand'] : [];
$currentPrices = isset($_GET['price']) && is_array($_GET['price']) ? $_GET['price'] : [];
$searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$currentSort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Mảng cấu hình cho Giá
$priceRanges = [
    '0-100000' => 'Dưới 100.000đ',
    '100000-300000' => '100.000đ - 300.000đ',
    '300000-500000' => '300.000đ - 500.000đ',
    '500000-0' => 'Trên 500.000đ'
];
?>
<style>
    /* Custom Checkbox/Radio Styles cho Sidebar */
    .filter-checkbox {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        cursor: pointer;
        color: #1a1a1a;
        font-size: 0.95rem;
        transition: color 0.2s;
    }
    .filter-checkbox:hover { color: #000; }
    .filter-checkbox input[type="checkbox"], 
    .filter-checkbox input[type="radio"] {
        accent-color: #1a1a1a;
        width: 1.1rem;
        height: 1.1rem;
        margin-right: 0.75rem;
        cursor: pointer;
    }
    .filter-badge {
        font-size: 0.85rem;
        color: #6c757d;
        margin-left: 0.2rem;
    }
    .sort-select {
        border-radius: var(--radius-pill);
        padding: 8px 20px;
        border: 1px solid #dee2e6;
        color: #495057;
        background-color: #fff;
        cursor: pointer;
        outline: none;
    }
    .sort-select:focus { border-color: var(--brand-main); box-shadow: 0 0 0 0.2rem rgba(219, 39, 119, 0.25); }
    
    /* Mới: Accordion Bộ lọc phong cách Beauty Box */
    .filter-sidebar { background: #fff; padding: 25px; border: 1px solid #f1f1f1; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.03); position: sticky; top: 90px; max-height: calc(100vh - 110px); overflow-y: auto; }
    /* Tùy chỉnh thanh cuộn cho sidebar mượt mà hơn */
    .filter-sidebar::-webkit-scrollbar { width: 5px; }
    .filter-sidebar::-webkit-scrollbar-track { background: transparent; }
    .filter-sidebar::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }
    .filter-sidebar::-webkit-scrollbar-thumb:hover { background: #ccc; }

    .filter-main-title { font-size: 1.25rem; font-weight: 800; text-transform: uppercase; margin-bottom: 25px; letter-spacing: 0.5px; color: var(--rhode-pink-accent); border-bottom: 2px solid var(--rhode-pink-light); padding-bottom: 15px;}
    .filter-group { border-bottom: 1px dashed #eee; padding-bottom: 20px; margin-bottom: 20px; }
    .filter-group-header { display: flex; justify-content: space-between; align-items: center; cursor: pointer; margin-bottom: 15px; transition: all 0.2s; }
    .filter-group-header:hover .filter-group-title { color: var(--rhode-pink-accent); }
    .filter-group-title { font-weight: 700; font-size: 1rem; color: #1a1a1a; margin: 0; transition: all 0.2s; }
    .filter-group-icon { font-size: 0.8rem; color: #1a1a1a; transition: transform 0.3s; }
    .filter-group-icon.collapsed { transform: rotate(180deg); }
    .filter-content { overflow: hidden; transition: max-height 0.3s ease-out; }
    .filter-search-box { position: relative; margin-bottom: 15px; }
    .filter-search-box input { width: 100%; padding: 8px 10px 8px 32px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 0.85rem; outline: none; }
    .filter-search-box input:focus { border-color: #1a1a1a; }
    .filter-search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; font-size: 0.85rem; }
    .filter-list { padding: 0; margin: 0; }
    .hidden-item { display: none !important; }
    .btn-view-more { background: none; border: none; padding: 0; text-decoration: underline; color: #1a1a1a; font-size: 0.85rem; margin-top: 5px; cursor: pointer; font-weight: 500; }
    .btn-view-more:hover { color: #000; }
</style>

<div class="container mt-4 mb-5">
    
    <!-- Header Banner -->
    <div class="shop-header">
        <h1 class="font-serif">The Glow Shop</h1>
        <p class="text-muted mx-auto" style="max-width: 600px;">
            Khám phá bộ sưu tập sản phẩm chăm sóc da và làm đẹp được chọn lọc kỹ lưỡng, giúp bạn tỏa sáng theo cách riêng của mình.
        </p>
    </div>

    <div class="row">
        <!-- Cột Sidebar Bộ Lọc (Trái) -->
        <div class="col-lg-3 mb-4">
            <div class="filter-sidebar">
                <form action="index.php" method="GET" id="filterForm">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="index">
                    <!-- Giữ lại tham số sort và filter khi submit form -->
                    <input type="hidden" name="sort" id="hiddenSort" value="<?php echo htmlspecialchars($currentSort); ?>">
                    <?php if(isset($currentFilter) && $currentFilter !== 'all'): ?>
                    <input type="hidden" name="filter" value="<?php echo htmlspecialchars($currentFilter); ?>">
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="filter-main-title mb-0">Bộ lọc</h3>
                        <?php if(!empty($currentCategories) || !empty($currentBrands) || !empty($currentPrices) || !empty($searchKeyword)): ?>
                            <a href="index.php?controller=product&action=index<?php echo (isset($currentFilter) && $currentFilter !== 'all') ? '&filter='.htmlspecialchars($currentFilter) : ''; ?>" class="text-danger small fw-bold text-decoration-none" style="text-decoration: underline !important;">Xóa tất cả</a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Lọc theo Giá -->
                    <div class="filter-group">
                        <div class="filter-group-header toggle-accordion" data-target="filter-price">
                            <h5 class="filter-group-title">Giá sản phẩm</h5>
                            <i class="fas fa-chevron-up filter-group-icon"></i>
                        </div>
                        <div class="filter-content" id="filter-price">
                            <ul class="filter-list">
                                <?php 
                                // Cập nhật danh sách giá theo Beauty Box
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

                    <!-- Danh mục -->
                    <div class="filter-group">
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
                                if(isset($categoriesData) && is_array($categoriesData)): 
                                    foreach($categoriesData as $cat): 
                                        $isChecked = in_array($cat['id'], $currentCategories) ? 'checked' : '';
                                        $isHidden = $catIndex >= 5 ? 'hidden-item' : '';
                                        $count = isset($catCounts[$cat['id']]) ? $catCounts[$cat['id']] : 0;
                                ?>
                                    <li class="filter-item <?php echo $isHidden; ?>">
                                        <label class="filter-checkbox w-100">
                                            <input type="checkbox" name="category[]" value="<?php echo htmlspecialchars($cat['id']); ?>" <?php echo $isChecked; ?> onchange="document.getElementById('filterForm').submit();">
                                            <span class="item-name"><?php echo htmlspecialchars($cat['name']); ?></span>
                                            <span class="filter-badge">(<?php echo $count; ?>)</span>
                                        </label>
                                    </li>
                                <?php $catIndex++; endforeach; endif; ?>
                            </ul>
                            <?php if($catIndex > 5): ?>
                                <button type="button" class="btn-view-more" data-target="catList">Xem thêm</button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Thương hiệu -->
                    <div class="filter-group border-0 mb-0 pb-0">
                        <div class="filter-group-header toggle-accordion" data-target="filter-brand">
                            <h5 class="filter-group-title">Thương hiệu</h5>
                            <i class="fas fa-chevron-up filter-group-icon"></i>
                        </div>
                        <div class="filter-content" id="filter-brand">
                            <div class="filter-search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="searchInput" data-target="brandList" placeholder="Tìm">
                            </div>
                            <ul class="filter-list" id="brandList">
                                <?php 
                                $brandIndex = 0;
                                if(isset($brandsData) && is_array($brandsData)): 
                                    foreach($brandsData as $brand): 
                                        $isChecked = in_array($brand['id'], $currentBrands) ? 'checked' : '';
                                        $isHidden = $brandIndex >= 5 ? 'hidden-item' : '';
                                        $count = isset($brandCounts[$brand['id']]) ? $brandCounts[$brand['id']] : 0;
                                ?>
                                    <li class="filter-item <?php echo $isHidden; ?>">
                                        <label class="filter-checkbox w-100">
                                            <input type="checkbox" name="brand[]" value="<?php echo htmlspecialchars($brand['id']); ?>" <?php echo $isChecked; ?> onchange="document.getElementById('filterForm').submit();">
                                            <span class="item-name"><?php echo htmlspecialchars($brand['name']); ?></span>
                                            <span class="filter-badge">(<?php echo $count; ?>)</span>
                                        </label>
                                    </li>
                                <?php $brandIndex++; endforeach; endif; ?>
                            </ul>
                            <?php if($brandIndex > 5): ?>
                                <button type="button" class="btn-view-more" data-target="brandList">Xem thêm</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Nút Lọc (Dành cho Mobile hoặc khi tắt JS) -->
                    <noscript>
                        <button type="submit" class="rhode-btn-primary w-100 mt-2">Áp dụng bộ lọc</button>
                    </noscript>
                </form>
            </div>
        </div>

        <!-- Cột Danh sách sản phẩm (Phải) -->
        <div class="col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <span class="text-muted">
                    Hiển thị <strong><?php echo isset($products) ? count($products) : 0; ?></strong> sản phẩm
                </span>
                
                <!-- Sort by (Sắp xếp) -->
                <div class="d-flex align-items-center gap-2">
                    <label class="text-muted small fw-bold text-nowrap d-none d-sm-block">Sắp xếp:</label>
                    <select class="sort-select" onchange="document.getElementById('hiddenSort').value = this.value; document.getElementById('filterForm').submit();">
                        <option value="newest" <?php echo $currentSort == 'newest' ? 'selected' : ''; ?>>Mới nhất</option>
                        <option value="price_asc" <?php echo $currentSort == 'price_asc' ? 'selected' : ''; ?>>Giá: Thấp đến Cao</option>
                        <option value="price_desc" <?php echo $currentSort == 'price_desc' ? 'selected' : ''; ?>>Giá: Cao xuống Thấp</option>
                    </select>
                </div>
            </div>

            <div class="row g-4">
                <?php if (isset($products) && !empty($products)): ?>
                    <?php foreach ($products as $item): 
                        $imgSrc = !empty($item['image']) ? $item['image'] : 'https://via.placeholder.com/400x400';
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="product-card position-relative">
                            <?php if(isset($item['old_price']) && $item['old_price'] > $item['price']): 
                                $pct = round((($item['old_price'] - $item['price']) / $item['old_price']) * 100);
                            ?>
                                <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px; z-index: 2;">-<?php echo $pct; ?>%</span>
                            <?php endif; ?>
                            <a href="index.php?controller=product&action=detail&id=<?php echo $item['id']; ?>" class="product-img-wrapper">
                                <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </a>
                            <div class="product-brand"><?php echo htmlspecialchars($item['brand_name'] ?? 'Thương hiệu'); ?></div>
                            <a href="index.php?controller=product&action=detail&id=<?php echo $item['id']; ?>" class="product-title">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </a>
                            
                            <div class="product-footer flex-wrap">
                                <div>
                                    <span class="product-price"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</span>
                                    <?php if(isset($item['old_price']) && $item['old_price'] > $item['price']): ?>
                                        <div class="small text-muted"><del><?php echo number_format($item['old_price'], 0, ',', '.'); ?>đ</del></div>
                                    <?php endif; ?>
                                </div>
                                <?php if(isset($item['stock']) && $item['stock'] <= 0): ?>
                                    <span class="text-danger small fw-bold mt-2">Hết hàng</span>
                                <?php else: ?>
                                    <a href="index.php?controller=cart&action=add&id=<?php echo $item['id']; ?>" class="add-to-cart-btn mt-2" title="Thêm vào giỏ">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <h4 class="text-muted font-serif">Không tìm thấy sản phẩm nào.</h4>
                        <p>Vui lòng thử lại với từ khóa hoặc bộ lọc khác.</p>
                        <a href="index.php?controller=product&action=index" class="rhode-btn-primary mt-3">Xem tất cả sản phẩm</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Phân trang (Pagination) -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="d-flex justify-content-center mt-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?controller=product&action=index&page=<?php echo $page-1; ?><?php echo !empty($searchKeyword) ? '&search='.urlencode($searchKeyword) : ''; ?>"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <?php endif; ?>

                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?controller=product&action=index&page=<?php echo $i; ?><?php echo !empty($searchKeyword) ? '&search='.urlencode($searchKeyword) : ''; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?controller=product&action=index&page=<?php echo $page+1; ?><?php echo !empty($searchKeyword) ? '&search='.urlencode($searchKeyword) : ''; ?>"><i class="fas fa-chevron-right"></i></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

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
                    // Remove hidden styles while searching to show results
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

<?php include 'views/layout/footer.php'; ?>