<?php 
$pageTitle = "Thêm Banner - Glow Admin"; 
$extraCSS = "
<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css\" />
<style>
    .img-preview-container { text-align: center; margin-top: 15px; display: none; }
    .img-preview { max-width: 100%; height: auto; border-radius: 12px; border: 2px dashed #ddd; }
    .img-container { max-width: 100%; max-height: 60vh; text-align: center;}
    .img-container img { display: block; max-width: 100%; }
</style>
";
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
                            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-plus-circle me-2 text-primary"></i>Thêm Banner Mới</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            
                            <form method="POST" enctype="multipart/form-data" id="bannerForm">
                                <input type="hidden" name="cropped_image" id="cropped_image" value="">

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tiêu đề (Tên gợi nhớ nội bộ)</label>
                                    <input type="text" name="title" class="form-control" required placeholder="VD: Khuyến mãi tết, Đại sứ thương hiệu...">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hình ảnh Banner <span class="text-danger">*</span></label>
                                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*" required>
                                    <div class="form-text text-primary"><i class="fas fa-crop-alt"></i> Hệ thống sẽ hỗ trợ bạn cắt ảnh theo tỷ lệ chuẩn sau khi chọn file.</div>
                                    
                                    <div class='img-preview-container' id="previewDiv">
                                        <img src='' id='finalPreview' class='img-preview' style='width: 100%; max-width: 600px; object-fit: cover;'>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Thương hiệu (Tùy chọn)</label>
                                        <input type="text" name="brand_name" class="form-control" placeholder="VD: Peripera, MAC...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Đại sứ thương hiệu (Tùy chọn)</label>
                                        <input type="text" name="ambassador" class="form-control" placeholder="VD: Jang Wonyoung...">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Link liên kết (Tùy chọn)</label>
                                    <input type="text" name="link" class="form-control" placeholder="index.php?controller=product&action=detail&id=1">
                                </div>

                                <!-- GIAO DIỆN NÚT SELECT (Chống lỗi không nhận giá trị) -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold d-block">Trạng thái hiển thị <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select form-select-lg border-2 shadow-sm" style="border-color: var(--brand-main); cursor: pointer;">
                                        <option value="1" class="text-success fw-bold" selected>🟢 Hiển thị trên Trang chủ</option>
                                        <option value="0" class="text-secondary fw-bold">⚫ Tạm ẩn</option>
                                    </select>
                                    <div class="form-text mt-2"><i class="fas fa-info-circle text-primary"></i> Chọn trạng thái mặc định khi vừa tạo xong banner.</div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=banners" class="btn btn-light px-4">Hủy</a>
                                    <button type="submit" class="btn btn-brand px-4">Đăng Banner</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Công cụ cắt ảnh -->
<div class="modal fade" id="cropModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-bottom-0 pb-0 p-4">
        <h5 class="modal-title fw-bold"><i class="fas fa-crop text-primary me-2"></i>Cắt chỉnh hình ảnh Banner</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="alert alert-info py-2 small"><i class="fas fa-info-circle me-1"></i> Khung cắt đã được khóa ở tỷ lệ ngang chuẩn (3:1).</div>
        <div class="img-container bg-light rounded-3 overflow-hidden">
            <img id="imageToCrop" src="">
        </div>
      </div>
      <div class="modal-footer border-top-0 pt-0 p-4">
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold" id="cropButton"><i class="fas fa-check me-2"></i>Cắt và Áp dụng</button>
      </div>
    </div>
  </div>
</div>

<?php 
$extraJS = "
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js\"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. JS TỰ ĐỘNG ĐỔI CHỮ KHI BẬT/TẮT NÚT CÔNG TẮC
    const statusSwitch = document.getElementById('statusSwitch');
    const statusLabel = document.getElementById('statusLabel');
    if (statusSwitch) {
        statusSwitch.addEventListener('change', function() {
            if (this.checked) {
                statusLabel.innerHTML = '<span class=\"text-success\"><i class=\"fas fa-eye me-1\"></i> Hiển thị trên Trang chủ</span>';
            } else {
                statusLabel.innerHTML = '<span class=\"text-secondary\"><i class=\"fas fa-eye-slash me-1\"></i> Đang tạm ẩn</span>';
            }
        });
    }

    // 2. CÔNG CỤ CẮT ẢNH
    let cropper;
    const imageInput = document.getElementById('imageInput');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropModalElement = document.getElementById('cropModal');
    const cropModal = new bootstrap.Modal(cropModalElement);
    const cropButton = document.getElementById('cropButton');
    const croppedImageInput = document.getElementById('cropped_image');
    const finalPreview = document.getElementById('finalPreview');
    const previewDiv = document.getElementById('previewDiv');

    imageInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            const reader = new FileReader();
            
            reader.onload = function(event) {
                imageToCrop.src = event.target.result;
                cropModal.show();
            };
            reader.readAsDataURL(file);
        }
    });

    cropModalElement.addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 3 / 1, 
            viewMode: 2,
            autoCropArea: 1,
            responsive: true,
            dragMode: 'move'
        });
    });

    cropModalElement.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (!croppedImageInput.value) {
            imageInput.value = '';
        }
    });

    cropButton.addEventListener('click', function() {
        if (!cropper) return;
        
        const canvas = cropper.getCroppedCanvas({
            width: 1200, 
            height: 400
        });

        const base64Image = canvas.toDataURL('image/jpeg', 0.9);

        croppedImageInput.value = base64Image;
        
        finalPreview.src = base64Image;
        previewDiv.style.display = 'block';

        cropModal.hide();
    });
});
</script>
";
include 'views/layout/footer.php'; 
?>