<?php 
$pageTitle_Header = "Glow Cosmetics (MVC) - Vẻ đẹp tỏa sáng"; 
$extraCSS = "
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
</style>
";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<!-- Hero Section -->
<section class="hero-section" style="background-color: #fdf2f8; padding: 40px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-white text-danger shadow-sm px-3 py-2 rounded-pill mb-3 fw-bold">🔥 BST Mùa Hè 2024</span>
                <h1 class="display-5 fw-bold mb-3" style="color: #831843;">Đánh Thức Vẻ Đẹp Tự Nhiên Của Bạn</h1>
                <p class="text-secondary mb-4">Khám phá hơn 50+ sản phẩm chăm sóc da và trang điểm cao cấp.</p>
            </div>
            <div class="col-lg-6 text-center d-none d-lg-block">
                <img src="https://images.unsplash.com/photo-1618331835717-801e976710b2?q=80&w=800&auto=format&fit=crop" class="img-fluid rounded-4 shadow-sm" style="max-height: 250px; object-fit: cover; width: 80%;">
            </div>
        </div>
    </div>
</section>

<!-- Main Content (2 Cột) -->
<section id="products-section" class="container py-5 mt-2">
    <div class="row">
        
        <!-- CỘT TRÁI: BỘ LỌC SIDEBAR (Giữ nguyên như cũ) -->
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
// Javascript xử lý UX/UI cho bộ lọc và Popup Xem Nhanh
$extraJS = <<<EOT
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Popup Xem Nhanh
    const quickViewBtns = document.querySelectorAll('.btn-quick-view');
    const myModal = new bootstrap.Modal(document.getElementById('quickViewModal'));

    quickViewBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // Ngăn chặn thẻ <a> cha chuyển trang
            e.stopPropagation();

            // Lấy dữ liệu từ nút bấm
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

    // 2. Nút Xem thêm / Thu gọn bộ lọc
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

    // 3. Tìm kiếm trong bộ lọc
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

    // 4. Giữ trạng thái Checkbox
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