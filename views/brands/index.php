<?php 
$pageTitle = "Tất cả thương hiệu - Glow Cosmetics"; 
$extraCSS = "
<style>
    .brand-alphabet-nav a {
        color: #1f2937;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0 10px;
        text-decoration: none;
        transition: color 0.2s;
    }
    .brand-alphabet-nav a:hover {
        color: var(--brand-dark, #be185d);
    }
    .brand-letter-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: #000;
    }
    .brand-item-link {
        color: #374151;
        text-decoration: none;
        font-size: 1rem;
        display: block;
        padding: 10px 0;
        transition: all 0.2s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .brand-item-link:hover {
        color: var(--brand-dark, #be185d);
        font-weight: 700;
        transform: translateX(5px);
    }
</style>
";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<div class="container py-5 bg-white rounded-4 shadow-sm mb-5 mt-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-uppercase" style="letter-spacing: 1px;">Tất cả thương hiệu</h2>
    </div>

    <!-- Thanh menu chữ cái A-Z -->
    <div class="brand-alphabet-nav d-flex flex-wrap justify-content-center border-bottom pb-4 mb-5">
        <?php foreach (range('A', 'Z') as $char): ?>
            <a href="#letter-<?php echo $char; ?>"><?php echo $char; ?></a>
        <?php endforeach; ?>
        <a href="#letter-hash">#</a>
    </div>

    <!-- Danh sách các thương hiệu nhóm theo chữ cái -->
    <div class="px-md-5">
        <?php if(!empty($groupedBrands)): ?>
            <?php foreach ($groupedBrands as $letter => $brandsList): ?>
                <div class="row border-bottom py-4 align-items-start" id="letter-<?php echo $letter == '#' ? 'hash' : $letter; ?>">
                    
                    <!-- Cột hiển thị chữ cái to bên trái -->
                    <div class="col-md-2 col-12 text-md-start text-center mb-3 mb-md-0">
                        <h2 class="brand-letter-title"><?php echo $letter; ?></h2>
                    </div>
                    
                    <!-- Lưới hiển thị danh sách thương hiệu bên phải -->
                    <div class="col-md-10 col-12">
                        <div class="row">
                            <?php foreach ($brandsList as $brand): ?>
                                <div class="col-md-3 col-6 mb-2">
                                    <!-- Link đã được sửa để trỏ về trang chi tiết thương hiệu -->
                                    <a href="index.php?controller=brand&action=detail&name=<?php echo urlencode($brand['name']); ?>" class="brand-item-link">
                                        <?php echo htmlspecialchars($brand['name']); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-box-open fa-3x mb-3"></i>
                <h5>Chưa có thương hiệu nào trong hệ thống.</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>