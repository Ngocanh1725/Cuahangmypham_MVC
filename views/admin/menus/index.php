<?php
$pageTitle = "Quản lý Menu | Glow Admin";

// Hàm đệ quy hiển thị menu
function renderMenuRows($menus, $level = 0) {
    foreach ($menus as $m) {
        $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
        $icon = ($level > 0) ? '<i class="fas fa-level-up-alt fa-rotate-90 text-muted me-2"></i>' : '';
        $fw = ($level == 0) ? 'fw-bold text-dark' : 'text-muted';
        
        echo '<tr>';
        echo '<td class="ps-4 ' . $fw . '">' . $indent . $icon . htmlspecialchars($m['title']) . '</td>';
        echo '<td class="text-muted">' . htmlspecialchars($m['url']) . '</td>';
        echo '<td>';
        if ($m['target'] == '_blank') {
            echo '<span class="badge bg-info text-dark">Tab mới</span>';
        } else {
            echo '<span class="badge bg-light text-dark border">Tab hiện tại</span>';
        }
        echo '</td>';
        echo '<td>' . $m['sort_order'] . '</td>';
        echo '<td>';
        if ($m['status'] == 1) {
            echo '<span class="badge bg-success rounded-pill px-3">Hiển thị</span>';
        } else {
            echo '<span class="badge bg-secondary rounded-pill px-3">Đã ẩn</span>';
        }
        echo '</td>';
        echo '<td class="text-end pe-4">';
        echo '<a href="index.php?controller=admin&action=editMenu&id=' . $m['id'] . '" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Sửa"><i class="fas fa-edit"></i></a>';
        echo '<a href="index.php?controller=admin&action=deleteMenu&id=' . $m['id'] . '" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa" onclick="return confirm(\'Bạn có chắc chắn muốn xóa menu này?\');"><i class="fas fa-trash-alt"></i></a>';
        echo '</td>';
        echo '</tr>';
        
        if (!empty($m['children'])) {
            renderMenuRows($m['children'], $level + 1);
        }
    }
}

// Tách menu ra 2 mảng Header và Footer
$headerTree = array_filter($menus, function($m) { return $m['position'] == 'header' && $m['parent_id'] == null; });
$footerTree = array_filter($menus, function($m) { return $m['position'] == 'footer' && $m['parent_id'] == null; });

// Thực ra $menus từ getAllMenus() là mảng phẳng. Để gọi hàm đệ quy, ta nên gọi getMenuTree từ controller hoặc tự build cây.
// Nhưng vì ta đã có method getMenuTree trong model, tốt nhất nên gọi method đó.
// Tuy nhiên AdminController::menus() đang gọi getAllMenus().
// Ta sẽ tạo một logic nhỏ để build tree ở view cho tiện hoặc cứ dùng hàm buildTree ở đây.

function buildTree(array $elements, $parentId = null) {
    $branch = array();
    foreach ($elements as $element) {
        if ($element['parent_id'] == $parentId) {
            $children = buildTree($elements, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[] = $element;
        }
    }
    return $branch;
}

$tree = buildTree($menus);
$headerTree = array_filter($tree, function($m) { return $m['position'] == 'header'; });
$footerTree = array_filter($tree, function($m) { return $m['position'] == 'footer'; });

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .admin-content { padding: 30px; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .table th { background-color: #f8f9fa; font-weight: 600; }
        .btn-brand { background: linear-gradient(135deg, #be185d 0%, #db2777 100%); color: white; border: none; }
        .btn-brand:hover { background: linear-gradient(135deg, #9d174d 0%, #be185d 100%); color: white; }
        .nav-pills .nav-link.active { background-color: #be185d; }
        .nav-pills .nav-link { color: #6c757d; font-weight: 500; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <?php require_once 'views/admin/includes/sidebar.php'; ?>
            
            <div class="col-md-10 bg-light">
                <div class="admin-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0"><i class="fas fa-list me-2 text-pink"></i> Quản lý Menu (Advanced)</h3>
                        <a href="index.php?controller=admin&action=addMenu" class="btn btn-brand rounded-pill px-4">
                            <i class="fas fa-plus me-2"></i>Thêm Menu
                        </a>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-white pt-3 pb-0 border-0">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active px-4" id="pills-header-tab" data-bs-toggle="pill" data-bs-target="#pills-header" type="button" role="tab">Header Menu</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-4" id="pills-footer-tab" data-bs-toggle="pill" data-bs-target="#pills-footer" type="button" role="tab">Footer Menu</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content" id="pills-tabContent">
                                <!-- TAB HEADER -->
                                <div class="tab-pane fade show active" id="pills-header" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="ps-4">Tên Menu</th>
                                                    <th>URL</th>
                                                    <th>Mở trang</th>
                                                    <th>Thứ tự</th>
                                                    <th>Trạng thái</th>
                                                    <th class="text-end pe-4">Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(empty($headerTree)): ?>
                                                    <tr><td colspan="6" class="text-center py-4 text-muted">Không có menu nào</td></tr>
                                                <?php else: ?>
                                                    <?php renderMenuRows($headerTree); ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- TAB FOOTER -->
                                <div class="tab-pane fade" id="pills-footer" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="ps-4">Tên Menu</th>
                                                    <th>URL</th>
                                                    <th>Mở trang</th>
                                                    <th>Thứ tự</th>
                                                    <th>Trạng thái</th>
                                                    <th class="text-end pe-4">Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(empty($footerTree)): ?>
                                                    <tr><td colspan="6" class="text-center py-4 text-muted">Không có menu nào</td></tr>
                                                <?php else: ?>
                                                    <?php renderMenuRows($footerTree); ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
