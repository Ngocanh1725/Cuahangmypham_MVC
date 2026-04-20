<?php 
$extraCSS = "
<style>
    .brand-hero {
        position: relative;
        background-color: #f8f9fa;
        margin-bottom: 60px;
    }
    .brand-banner img {
        width: 100%;
        height: 350px;
        object-fit: cover;
    }
    .brand-info-box {
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        border-radius: 16px;
        padding: 20px 40px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        width: 80%;
        max-width: 800px;
    }
    .brand-logo-img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
    
    /* Tái sử dụng CSS của bộ lọc (views/products/index.php) */
    .filter-sidebar { background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #f0f0f0; }
    .filter-group { border-bottom: 1px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 15px; }
    .filter-title { font-weight: 700; color: #1f2937; margin-bottom: 15px; }
    .filter-search-box { position: relative; margin-bottom: 15px; }
    .filter-search-box input { width: 100%; padding: 8px 10px 8px 35px; border: 1px solid #e5e7eb; border-radius: 6px; outline: none; }
    .filter-search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
    .filter-list { list-style: none; padding: 0; margin: 0; max-height: 250px; overflow-y: auto; }
    .filter-item { margin-bottom: 12px; }
    .filter-item input[type=\"checkbox\"] { margin-right: 10px; accent-color: var(--brand-dark, #be185d); }
    .hidden-item { display: none; }
    .btn-view-more { color: #2563eb; background: none; border: none; padding: 0; font-size: 0.85rem; text-decoration: underline; margin-top: 5px; }
</style>
";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<!-- Phần Header của Thương hiệu (Banner + Logo) -->
<div class="brand-hero">
    <div class="brand-banner">
        <img src="<?php echo htmlspecialchars($brandInfo['banner']); ?>" alt="<?php echo htmlspecialchars($brandInfo['name']); ?> Banner">
    </div>
    <div class="brand-info-box">
        <img src="<?php echo htmlspecialchars($brandInfo['logo']); ?>" alt="<?php echo htmlspecialchars($brandInfo['name']); ?> Logo" class="brand-logo-img">
        <h2 class="fw-bold mb-1 text-dark text-uppercase"><?php echo htmlspecialchars($brandInfo['name']); ?></h2>
        <div class="brand-stats fw-medium">
            <span><?php echo $brandInfo['product_count']; ?> sản phẩm</span> | 
            <span><?php echo htmlspecialchars($brandInfo['sales_count']); ?></span>
        </div>
        <p class="brand-desc"><?php echo htmlspecialchars($brandInfo['description']); ?></p>
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
                    <div class="filter-group">
                        <div class="filter-title">Loại sản phẩm</div>
                        <div class="filter-search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="searchInput" data-target="catList" placeholder="Tìm...">
                        </div>
                        <ul class="filter-list" id="catList">
                            <?php 
                            $catIndex = 0;
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
                            <?php $catIndex++; endforeach; ?>
                        </ul>
                        <button type="button" class="btn-view-more" data-target="catList">Xem thêm</button>
                    </div>

                    <button type="submit" class="btn text-white w-100 mt-3 rounded-pill fw-bold" style="background-color: var(--brand-dark, #be185d);">Lọc kết quả</button>
                </form>
            </div>
        </div>

        <!-- LƯỚI SẢN PHẨM CỦA HÃNG -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark m-0">Sản phẩm từ <?php echo htmlspecialchars($brandInfo['name']); ?></h4>
                <select name="sort" form="filterForm" onchange="document.getElementById('filterForm').submit();" class="form-select w-auto shadow-sm" style="border-radius: 8px;">
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
                        <div class="col-md-4 col-6">
                            <div class="card border-0 product-card h-100 shadow-sm rounded-4 overflow-hidden" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" style="transition: transform 0.3s;">
                                <?php if ($isSale): 
                                    $discountPercent = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                                ?>
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2 z-3 px-2 py-1 rounded shadow-sm">-<?php echo $discountPercent; ?>%</span>
                                <?php endif; ?>

                                <div class="product-img-container" style="height: 250px;">
                                    <img src="<?php echo $imgUrl; ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                </div>
                                
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><?php echo htmlspecialchars($brandInfo['name']); ?></div>
                                    <h6 class="fw-bold mb-2 text-dark" style="height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </h6>
                                    
                                    <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-end">
                                        <div>
                                            <?php if ($isSale): ?>
                                                <div class="text-muted small text-decoration-line-through mb-1"><?php echo number_format($row['old_price']); ?>đ</div>
                                            <?php endif; ?>
                                            <div class="fw-bold fs-5" style="color: var(--brand-dark, #be185d);"><?php echo number_format($row['price']); ?>đ</div>
                                        </div>
                                        <a href="index.php?controller=cart&action=add&id=<?php echo $row['id']; ?>" class="btn btn-light text-danger rounded-circle shadow-sm" style="width: 40px; height: 40px;"><i class="fas fa-cart-plus mt-2"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
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

    // JS Giữ trạng thái tick checkbox sau khi reload URL
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