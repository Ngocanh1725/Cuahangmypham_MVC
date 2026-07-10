<?php
$pageTitle = "Thêm Menu | Glow Admin";
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
                        <h3 class="fw-bold mb-0"><i class="fas fa-plus-circle me-2 text-pink"></i> Thêm Menu</h3>
                        <a href="index.php?controller=admin&action=menus" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>

                    <?php if (!empty($message)) echo $message; ?>

                    <div class="card">
                        <div class="card-body p-4">
                            <form action="index.php?controller=admin&action=addMenu" method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Tên Menu <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" required placeholder="Ví dụ: Trang chủ">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">URL <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="url" required placeholder="Ví dụ: index.php">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Vị trí hiển thị</label>
                                        <select class="form-select" name="position" id="positionSelect">
                                            <option value="header">Header (Navbar)</option>
                                            <option value="footer">Footer (Chân trang)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Menu Cha</label>
                                        <select class="form-select" name="parent_id" id="parentSelect">
                                            <option value="">-- Không có (Menu gốc) --</option>
                                            <optgroup label="Header Menus" id="opt-header">
                                                <?php foreach ($headerMenus as $hm): ?>
                                                    <option value="<?php echo $hm['id']; ?>"><?php echo htmlspecialchars($hm['title']); ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="Footer Menus" id="opt-footer" style="display:none;">
                                                <?php foreach ($footerMenus as $fm): ?>
                                                    <option value="<?php echo $fm['id']; ?>"><?php echo htmlspecialchars($fm['title']); ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Mở liên kết</label>
                                        <select class="form-select" name="target">
                                            <option value="_self">Cùng trang hiện tại (_self)</option>
                                            <option value="_blank">Tab mới (_blank)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Thứ tự sắp xếp</label>
                                        <input type="number" class="form-control" name="sort_order" value="0">
                                        <div class="form-text">Số nhỏ hơn sẽ hiển thị trước.</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Trạng thái</label>
                                        <select class="form-select" name="status">
                                            <option value="1">Hiển thị</option>
                                            <option value="0">Ẩn</option>
                                        </select>
                                    </div>
                                </div>

                                <hr class="my-4">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-brand px-5 rounded-pill"><i class="fas fa-save me-2"></i>Lưu Menu</button>
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
