<?php 
$pageTitle = "Cập Nhật Thương Hiệu - Glow Admin"; 
ob_start();
?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <style>
        .img-preview { object-fit: contain; border-radius: 10px; border: 2px solid #eee; margin-top: 10px; }
        .crop-container { max-width: 100%; max-height: 400px; display: none; margin-top: 15px; border: 2px dashed #0d6efd; padding: 10px; border-radius: 8px; background: #f8f9fa; text-align: center; }
        .crop-container img { max-width: 100%; max-height: 350px; display: block; margin: 0 auto; }
        .crop-toolbar { display: none; margin-top: 15px; padding: 15px; background: #fff; border-radius: 8px; border: 1px solid #dee2e6; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .crop-preview-box { display: none; margin-top: 15px; padding: 15px; background: #e8f5e9; border-radius: 8px; border: 1px dashed #4caf50; }
        .crop-preview-box img { max-width: 100%; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
<?php
$extraCSS = ob_get_clean();
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="row justify-content-center">
                <div class="col-md-8 mt-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-edit me-2 text-primary"></i>Sửa Thương Hiệu #<?php echo $brand['id']; ?></h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($brand['logo'] ?? ''); ?>">
                                <input type="hidden" name="current_banner" value="<?php echo htmlspecialchars($brand['banner'] ?? ''); ?>">
                                <input type="hidden" name="cropped_logo" id="cropped_logo">
                                <input type="hidden" name="cropped_banner" id="cropped_banner">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên Thương Hiệu</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($brand['name']); ?>" required>
                                </div>
                                
                                <div class="row mb-3">
                                    <!-- LOGO -->
                                    <div class="col-md-6 border-end">
                                        <label class="form-label fw-bold">Thay Logo (Vuông 1:1)</label>
                                        <input type="file" name="logo" id="logoInput" class="form-control" accept="image/*">
                                        <div id="currentLogoBox">
                                            <?php 
                                                $logoSrc = !empty($brand['logo']) ? $brand['logo'] : 'https://via.placeholder.com/100';
                                                echo "<div class='mt-2'><img src='$logoSrc' class='img-preview bg-white' style='width: 100px; height: 100px;' onerror=\"this.src='https://via.placeholder.com/100'\"></div>";
                                            ?>
                                        </div>

                                        <!-- Cropper Logo -->
                                        <div class="crop-container" id="cropContainerLogo">
                                            <img id="cropImageLogo" src="" alt="Crop Logo">
                                        </div>
                                        <div class="crop-toolbar" id="cropToolbarLogo">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button type="button" class="btn btn-success btn-sm w-100" id="btnCropLogo"><i class="fas fa-check"></i> Cắt Logo</button>
                                            </div>
                                        </div>
                                        <div class="crop-preview-box" id="cropPreviewLogo">
                                            <label class="form-label fw-bold text-success"><i class="fas fa-check-circle me-1"></i> Đã cắt:</label>
                                            <div>
                                                <img id="croppedPreviewImgLogo" style="max-width:100px;" src="">
                                            </div>
                                            <button type="button" class="btn btn-outline-warning btn-sm mt-2 w-100" id="btnRecropLogo">Cắt lại</button>
                                        </div>
                                    </div>

                                    <!-- BANNER -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Thay Banner (Tỷ lệ 3:1)</label>
                                        <input type="file" name="banner" id="bannerInput" class="form-control" accept="image/*">
                                        <div id="currentBannerBox">
                                            <?php 
                                                $bannerSrc = !empty($brand['banner']) ? $brand['banner'] : 'https://via.placeholder.com/300x100';
                                                echo "<div class='mt-2'><img src='$bannerSrc' class='img-preview' style='width: 100%; height: 100px;' onerror=\"this.src='https://via.placeholder.com/300x100'\"></div>";
                                            ?>
                                        </div>

                                        <!-- Cropper Banner -->
                                        <div class="crop-container" id="cropContainerBanner">
                                            <img id="cropImageBanner" src="" alt="Crop Banner">
                                        </div>
                                        <div class="crop-toolbar" id="cropToolbarBanner">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button type="button" class="btn btn-success btn-sm w-100" id="btnCropBanner"><i class="fas fa-check"></i> Cắt Banner</button>
                                            </div>
                                        </div>
                                        <div class="crop-preview-box" id="cropPreviewBanner">
                                            <label class="form-label fw-bold text-success"><i class="fas fa-check-circle me-1"></i> Đã cắt:</label>
                                            <div>
                                                <img id="croppedPreviewImgBanner" style="max-height:100px;" src="">
                                            </div>
                                            <button type="button" class="btn btn-outline-warning btn-sm mt-2 w-100" id="btnRecropBanner">Cắt lại</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mô tả ngắn</label>
                                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($brand['description'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="mb-4 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_home" name="is_home" value="1" <?php echo (!isset($brand['is_home']) || $brand['is_home'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label fw-bold ms-2" for="is_home">Hiển thị ở dải trang chủ</label>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=brands" class="btn btn-light px-4">Hủy</a>
                                    <button type="submit" class="btn btn-brand px-4">Lưu cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupCropper(inputId, containerId, imageId, toolbarId, cropBtnId, previewBoxId, previewImgId, recropBtnId, hiddenInputId, aspectRatio) {
        let cropper = null;
        const input = document.getElementById(inputId);
        const container = document.getElementById(containerId);
        const image = document.getElementById(imageId);
        const toolbar = document.getElementById(toolbarId);
        const cropBtn = document.getElementById(cropBtnId);
        const previewBox = document.getElementById(previewBoxId);
        const previewImg = document.getElementById(previewImgId);
        const recropBtn = document.getElementById(recropBtnId);
        const hiddenInput = document.getElementById(hiddenInputId);
        
        // When a file is selected
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                // Show cropper UI, hide preview and original input box logic if needed
                container.style.display = 'block';
                toolbar.style.display = 'block';
                previewBox.style.display = 'none';
                hiddenInput.value = '';

                // Load image into cropper
                image.src = event.target.result;

                if (cropper) cropper.destroy();

                cropper = new Cropper(image, {
                    aspectRatio: aspectRatio,
                    viewMode: 1,
                    autoCropArea: 1,
                });
            };
            reader.readAsDataURL(file);
        });

        // Crop Button
        cropBtn.addEventListener('click', function() {
            if (!cropper) return;
            const canvas = cropper.getCroppedCanvas({
                width: aspectRatio === 1 ? 400 : 1200,
                height: aspectRatio === 1 ? 400 : 400
            });
            const base64Url = canvas.toDataURL('image/jpeg', 0.9);
            
            // Set data to hidden input and update preview
            hiddenInput.value = base64Url;
            previewImg.src = base64Url;
            
            // UI Toggle
            container.style.display = 'none';
            toolbar.style.display = 'none';
            previewBox.style.display = 'block';
        });

        // Recrop Button
        recropBtn.addEventListener('click', function() {
            container.style.display = 'block';
            toolbar.style.display = 'block';
            previewBox.style.display = 'none';
            hiddenInput.value = '';
        });
    }

    // Setup Cropper for Logo (1:1)
    setupCropper('logoInput', 'cropContainerLogo', 'cropImageLogo', 'cropToolbarLogo', 'btnCropLogo', 'cropPreviewLogo', 'croppedPreviewImgLogo', 'btnRecropLogo', 'cropped_logo', 1);
    
    // Setup Cropper for Banner (3:1)
    setupCropper('bannerInput', 'cropContainerBanner', 'cropImageBanner', 'cropToolbarBanner', 'btnCropBanner', 'cropPreviewBanner', 'croppedPreviewImgBanner', 'btnRecropBanner', 'cropped_banner', 3);
});
</script>

<?php include 'views/layout/footer.php'; ?>