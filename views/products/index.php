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

// Xử lý Lọc và Tìm kiếm (Mô phỏng logic gốc)
$currentCategory = isset($_GET['category']) && is_array($_GET['category']) ? $_GET['category'][0] : '';
$currentBrand = isset($_GET['brand']) ? $_GET['brand'] : '';
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
?>

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
                <h3 class="filter-title">Bộ lọc</h3>
                
                <!-- Tìm kiếm -->
                <div class="mb-4">
                    <form action="index.php" method="GET">
                        <input type="hidden" name="controller" value="product">
                        <input type="hidden" name="action" value="index">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" style="border-radius: var(--radius-pill) 0 0 var(--radius-pill); padding-left: 20px;" placeholder="Tìm sản phẩm..." value="<?php echo htmlspecialchars($searchKeyword); ?>">
                            <button class="btn rhode-btn-primary" style="border-radius: 0 var(--radius-pill) var(--radius-pill) 0; padding: 10px 20px;" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>

                <!-- Danh mục -->
                <div class="filter-group mb-4">
                    <h5>Danh mục</h5>
                    <a href="index.php?controller=product&action=index" class="filter-link <?php echo empty($currentCategory) ? 'active' : ''; ?>">Tất cả sản phẩm</a>
                    <?php 
                    // Mảng danh mục giả định (bạn cần thay bằng code lấy từ DB nếu có)
                    $categories = ['Chăm sóc da', 'Trang điểm', 'Làm sạch', 'Phụ kiện'];
                    foreach ($categories as $cat): ?>
                        <a href="index.php?controller=product&action=index&category[]=<?php echo urlencode($cat); ?>" class="filter-link <?php echo ($currentCategory == $cat) ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($cat); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Thương hiệu -->
                <div class="filter-group">
                    <h5>Thương hiệu</h5>
                    <a href="index.php?controller=product&action=index" class="filter-link <?php echo empty($currentBrand) ? 'active' : ''; ?>">Tất cả thương hiệu</a>
                    <?php 
                    if(isset($brands) && is_array($brands)): 
                        foreach($brands as $brand): ?>
                            <a href="index.php?controller=product&action=index&brand=<?php echo urlencode($brand['id']); ?>" class="filter-link <?php echo ($currentBrand == $brand['id']) ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($brand['name']); ?>
                            </a>
                    <?php endforeach; endif; ?>
                </div>
                
                <?php if(!empty($currentCategory) || !empty($currentBrand) || !empty($searchKeyword)): ?>
                    <div class="mt-4">
                         <a href="index.php?controller=product&action=index" class="rhode-btn-outline w-100" style="padding: 8px;">Xóa bộ lọc</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cột Danh sách sản phẩm (Phải) -->
        <div class="col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <span class="text-muted">
                    Hiển thị <strong><?php echo isset($products) ? count($products) : 0; ?></strong> sản phẩm
                </span>
                <!-- Có thể thêm Sort by (Sắp xếp) ở đây -->
            </div>

            <div class="row g-4">
                <?php if (isset($products) && !empty($products)): ?>
                    <?php foreach ($products as $item): 
                        $imgSrc = !empty($item['image']) ? $item['image'] : 'https://via.placeholder.com/400x400';
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="product-card">
                            <a href="index.php?controller=product&action=detail&id=<?php echo $item['id']; ?>" class="product-img-wrapper">
                                <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </a>
                            <div class="product-brand"><?php echo htmlspecialchars($item['brand_name'] ?? 'Thương hiệu'); ?></div>
                            <a href="index.php?controller=product&action=detail&id=<?php echo $item['id']; ?>" class="product-title">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </a>
                            
                            <div class="product-footer">
                                <span class="product-price"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</span>
                                <a href="index.php?controller=cart&action=add&id=<?php echo $item['id']; ?>" class="add-to-cart-btn" title="Thêm vào giỏ">
                                    <i class="fas fa-plus"></i>
                                </a>
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

<?php include 'views/layout/footer.php'; ?>