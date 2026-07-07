<?php 
$pageTitle = "Thêm Banner - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
    .crop-container { 
        max-height: 450px; 
        overflow: hidden; 
        background: #f0f0f0; 
        border-radius: 12px; 
        border: 2px dashed #ccc;
        display: none;
    }
    .crop-container img { 
        max-width: 100%; 
        display: block; 
    }
    .crop-preview-box {
        display: none;
        margin-top: 15px;
    }
    .crop-preview-box img {
        max-height: 180px;
        border-radius: 12px;
        border: 2px solid #c97878;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .crop-toolbar {
        display: none;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 12px;
        padding: 12px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eee;
    }
    .crop-toolbar .btn { font-size: 0.85rem; }
    .aspect-ratio-btns .btn.active {
        background-color: #c97878 !important;
        border-color: #c97878 !important;
        color: #fff !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>

        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <h3 class="fw-bold text-dark">Thêm Banner Quảng Cáo Mới</h3>
                    <p class="text-muted">Tải lên hình ảnh và thông tin cho banner hiển thị trên trang chủ</p>
                </div>
                <a href="index.php?controller=admin&action=banners" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại
                </a>
            </div>

            <?php if (!empty($message)): ?>
                <?php echo $message; ?>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form id="bannerForm" action="index.php?controller=admin&action=addBanner" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="cropped_image" id="cropped_image">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Tiêu đề (Title) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="Ví dụ: Glow Your Way">
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Hình ảnh (Image) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Chọn hình ảnh chất lượng cao. Bạn có thể cắt/chỉnh sửa sau khi chọn.</small>
                        </div>

                        <!-- Khu vực cắt ảnh -->
                        <div class="mb-3 crop-container" id="cropContainer">
                            <img id="cropImage" src="" alt="Crop Image">
                        </div>

                        <!-- Thanh công cụ cắt ảnh -->
                        <div class="crop-toolbar" id="cropToolbar">
                            <div class="d-flex align-items-center gap-2 mb-2 w-100">
                                <span class="fw-bold text-muted small"><i class="fas fa-crop-alt me-1"></i> Tỉ lệ:</span>
                                <div class="aspect-ratio-btns btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" data-ratio="2.333">21:9 (Hero)</button>
                                    <button type="button" class="btn btn-outline-secondary" data-ratio="2">2:1 (Bento 1)</button>
                                    <button type="button" class="btn btn-outline-secondary" data-ratio="1.5">3:2 (Bento 3, 4)</button>
                                    <button type="button" class="btn btn-outline-secondary" data-ratio="1">1:1 (Bento 2)</button>
                                    <button type="button" class="btn btn-outline-secondary active" data-ratio="0">Tự do</button>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRotateLeft"><i class="fas fa-undo"></i> Xoay trái</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRotateRight"><i class="fas fa-redo"></i> Xoay phải</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnFlipH"><i class="fas fa-arrows-alt-h"></i> Lật ngang</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnFlipV"><i class="fas fa-arrows-alt-v"></i> Lật dọc</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnReset"><i class="fas fa-sync"></i> Đặt lại</button>
                                <button type="button" class="btn btn-success btn-sm" id="btnCrop"><i class="fas fa-check"></i> Xác nhận cắt</button>
                            </div>
                        </div>

                        <!-- Preview ảnh đã cắt -->
                        <div class="crop-preview-box" id="cropPreview">
                            <label class="form-label fw-bold text-success"><i class="fas fa-check-circle me-1"></i> Ảnh đã cắt xong:</label>
                            <div>
                                <img id="croppedPreviewImg" src="" alt="Cropped Preview">
                            </div>
                            <button type="button" class="btn btn-outline-warning btn-sm mt-2" id="btnRecrop"><i class="fas fa-edit me-1"></i> Cắt lại</button>
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label fw-bold">Link liên kết (Tùy chọn)</label>
                            <input type="text" class="form-control" id="link" name="link" placeholder="VD: index.php?controller=product">
                            <small class="text-muted">Đường dẫn khi khách hàng click vào banner này.</small>
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label fw-bold">Vị trí hiển thị trên Trang chủ <span class="text-danger">*</span></label>
                            <select class="form-select border-primary" id="position" name="position" required>
                                <option value="hero_slider">Hero Slider (Ảnh chính trên cùng - nhiều slide)</option>
                                <option value="exclusive">Phân Phối Độc Quyền (4 banner dọc)</option>
                                <option value="toptrend">Top Trend Hôm Nay (4 Banner dọc)</option>
                                <option value="promo_row">Khuyến Mãi Ngang (3 Banner ngang)</option>
                                <option value="bento_1">Bento Box 1 (Khối ảnh nghệ thuật lớn)</option>
                                <option value="bento_2">Bento Box 2 (Khối Combo / Nền hồng)</option>
                                <option value="bento_3">Bento Box 3 (Khối Catalog ảnh dài)</option>
                                <option value="bento_4">Bento Box 4 (Khối Tạp chí / Blog)</option>
                            </select>
                            <small class="text-muted">Chọn vị trí để banner hiển thị đúng layout trên trang chủ.</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Mô tả ngắn (Description)</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Đoạn văn bản nhỏ dưới tiêu đề (Nếu có, thường dùng cho Hero Banner)"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill"><i class="fas fa-save me-2"></i> Lưu lại</button>
                            <a href="index.php?controller=admin&action=banners" class="btn btn-outline-secondary px-4 py-2 rounded-pill"><i class="fas fa-times me-2"></i> Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cropper = null;
    let isCropped = false;
    const imageInput = document.getElementById('image');
    const cropImage = document.getElementById('cropImage');
    const cropContainer = document.getElementById('cropContainer');
    const cropToolbar = document.getElementById('cropToolbar');
    const cropPreview = document.getElementById('cropPreview');
    const croppedPreviewImg = document.getElementById('croppedPreviewImg');
    const croppedImageInput = document.getElementById('cropped_image');

    // Khi chọn file ảnh
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            cropImage.src = event.target.result;
            cropContainer.style.display = 'block';
            cropToolbar.style.display = 'flex';
            cropPreview.style.display = 'none';
            isCropped = false;
            croppedImageInput.value = '';

            // Hủy cropper cũ nếu có
            if (cropper) cropper.destroy();

            // Khởi tạo Cropper mới
            cropper = new Cropper(cropImage, {
                viewMode: 1,
                dragMode: 'move',
                responsive: true,
                autoCropArea: 0.9,
                background: true,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: true,
            });
        };
        reader.readAsDataURL(file);
    });

    // Nút chọn tỉ lệ
    document.querySelectorAll('.aspect-ratio-btns .btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.aspect-ratio-btns .btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const ratio = parseFloat(this.dataset.ratio);
            if (cropper) {
                cropper.setAspectRatio(ratio === 0 ? NaN : ratio);
            }
        });
    });

    // Xoay trái
    document.getElementById('btnRotateLeft').addEventListener('click', function() {
        if (cropper) cropper.rotate(-90);
    });

    // Xoay phải
    document.getElementById('btnRotateRight').addEventListener('click', function() {
        if (cropper) cropper.rotate(90);
    });

    // Lật ngang
    document.getElementById('btnFlipH').addEventListener('click', function() {
        if (cropper) {
            const data = cropper.getData();
            cropper.scaleX(data.scaleX === -1 ? 1 : -1);
        }
    });

    // Lật dọc
    document.getElementById('btnFlipV').addEventListener('click', function() {
        if (cropper) {
            const data = cropper.getData();
            cropper.scaleY(data.scaleY === -1 ? 1 : -1);
        }
    });

    // Đặt lại
    document.getElementById('btnReset').addEventListener('click', function() {
        if (cropper) cropper.reset();
    });

    // Xác nhận cắt
    document.getElementById('btnCrop').addEventListener('click', function() {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({
            maxWidth: 1920,
            maxHeight: 1080,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        const base64 = canvas.toDataURL('image/jpeg', 0.92);
        croppedImageInput.value = base64;
        croppedPreviewImg.src = base64;
        
        cropContainer.style.display = 'none';
        cropToolbar.style.display = 'none';
        cropPreview.style.display = 'block';
        isCropped = true;
    });

    // Cắt lại
    document.getElementById('btnRecrop').addEventListener('click', function() {
        cropContainer.style.display = 'block';
        cropToolbar.style.display = 'flex';
        cropPreview.style.display = 'none';
        isCropped = false;
        croppedImageInput.value = '';
    });

    // Tự động chọn tỉ lệ theo vị trí để ảnh không bị lệch khuyết
    document.getElementById('position').addEventListener('change', function() {
        if (!cropper) return;
        const pos = this.value;
        let ratio = NaN;
        if (pos === 'hero') ratio = 21/9; // ~2.333
        else if (pos === 'bento_1') ratio = 2/1;
        else if (pos === 'bento_2') ratio = 1/1;
        else if (pos === 'bento_3' || pos === 'bento_4') ratio = 3/2; // 1.5
        
        cropper.setAspectRatio(ratio);

        // Cập nhật nút active
        document.querySelectorAll('.aspect-ratio-btns .btn').forEach(b => {
            b.classList.remove('active');
            if (parseFloat(b.dataset.ratio).toFixed(1) === ratio.toFixed(1)) {
                b.classList.add('active');
            }
        });
    });
});
</script>

<?php include 'views/layout/footer.php'; ?>