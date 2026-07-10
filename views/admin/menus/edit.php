<?php
$pageTitle = "Sửa Menu | Glow Admin";
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
        .btn-brand { background: linear-gradient(135deg, #be185d 0%, #db2777 100%); color: white; border: none; }
        .btn-brand:hover { background: linear-gradient(135deg, #9d174d 0%, #be185d 100%); color: white; }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <?php require_once 'views/admin/includes/sidebar.php'; ?>
            
            <div class="col-md-10 bg-light">
                <div class="admin-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0"><i class="fas fa-edit me-2 text-pink"></i> Sửa Menu</h3>
                        <a href="index.php?controller=admin&action=menus" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>

                    <?php if (!empty($message)) echo $message; ?>

                    <div class="card">
                        <div class="card-body p-4">
                            <form action="index.php?controller=admin&action=editMenu&id=<?php echo $menu['id']; ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Tên Menu <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($menu['title']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">URL <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="url" value="<?php echo htmlspecialchars($menu['url']); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Vị trí hiển thị</label>
                                        <select class="form-select" name="position" id="positionSelect">
                                            <option value="header" <?php echo ($menu['position'] == 'header') ? 'selected' : ''; ?>>Header (Navbar)</option>
                                            <option value="footer" <?php echo ($menu['position'] == 'footer') ? 'selected' : ''; ?>>Footer (Chân trang)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Menu Cha</label>
                                        <select class="form-select" name="parent_id" id="parentSelect">
                                            <option value="">-- Không có (Menu gốc) --</option>
                                            <optgroup label="Header Menus" id="opt-header" <?php echo ($menu['position'] == 'footer') ? 'style="display:none;"' : ''; ?>>
                                                <?php foreach ($headerMenus as $hm): ?>
                                                    <?php if($hm['id'] != $menu['id']): ?>
                                                    <option value="<?php echo $hm['id']; ?>" <?php echo ($menu['parent_id'] == $hm['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($hm['title']); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="Footer Menus" id="opt-footer" <?php echo ($menu['position'] == 'header') ? 'style="display:none;"' : ''; ?>>
                                                <?php foreach ($footerMenus as $fm): ?>
                                                    <?php if($fm['id'] != $menu['id']): ?>
                                                    <option value="<?php echo $fm['id']; ?>" <?php echo ($menu['parent_id'] == $fm['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($fm['title']); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Mở liên kết</label>
                                        <select class="form-select" name="target">
                                            <option value="_self" <?php echo ($menu['target'] == '_self') ? 'selected' : ''; ?>>Cùng trang hiện tại (_self)</option>
                                            <option value="_blank" <?php echo ($menu['target'] == '_blank') ? 'selected' : ''; ?>>Tab mới (_blank)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Thứ tự sắp xếp</label>
                                        <input type="number" class="form-control" name="sort_order" value="<?php echo $menu['sort_order']; ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Trạng thái</label>
                                        <select class="form-select" name="status">
                                            <option value="1" <?php echo ($menu['status'] == 1) ? 'selected' : ''; ?>>Hiển thị</option>
                                            <option value="0" <?php echo ($menu['status'] == 0) ? 'selected' : ''; ?>>Ẩn</option>
                                        </select>
                                    </div>
                                </div>

                                <hr class="my-4">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-brand px-5 rounded-pill"><i class="fas fa-save me-2"></i>Cập nhật Menu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('positionSelect').addEventListener('change', function() {
            if(this.value === 'header') {
                document.getElementById('opt-header').style.display = '';
                document.getElementById('opt-footer').style.display = 'none';
            } else {
                document.getElementById('opt-header').style.display = 'none';
                document.getElementById('opt-footer').style.display = '';
            }
            document.getElementById('parentSelect').value = '';
        });
    </script>
</body>
</html>
