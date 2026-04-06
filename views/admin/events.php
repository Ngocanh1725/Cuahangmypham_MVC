<?php 
$pageTitle = "Quản lý Sự kiện - Glow Admin"; 
include 'views/layout/header.php'; 
?>
<div class="container-fluid p-0" style="background-color: #f8fafc;">
    <div class="row g-0">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 p-md-5 min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0"><i class="fas fa-calendar-alt text-primary me-2"></i> Quản lý Sự kiện Store</h3>
                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addEventModal">
                    <i class="fas fa-plus me-2"></i> Thêm sự kiện mới
                </button>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
                <div class="alert alert-success rounded-pill border-0 shadow-sm"><i class="fas fa-check me-2"></i> Thêm sự kiện thành công!</div>
            <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-warning rounded-pill border-0 shadow-sm"><i class="fas fa-trash me-2"></i> Đã xóa sự kiện!</div>
            <?php endif; ?>

            <div class="row g-4">
                <?php if(!empty($events)): ?>
                    <?php foreach($events as $event): 
                        $dateObj = new DateTime($event['event_date']);
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                            <a href="index.php?controller=admin&action=deleteEvent&id=<?php echo $event['id']; ?>" class="position-absolute top-0 end-0 m-3 text-danger bg-white rounded-circle p-2 shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sự kiện này?');" title="Xóa">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 text-center me-3">
                                        <h4 class="fw-bold mb-0"><?php echo $dateObj->format('d'); ?></h4>
                                        <small class="text-uppercase fw-bold">T<?php echo $dateObj->format('m'); ?></small>
                                    </div>
                                    <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($event['title']); ?></h5>
                                </div>
                                <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt me-2 text-danger"></i><?php echo htmlspecialchars($event['location']); ?></p>
                                <p class="text-secondary small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo htmlspecialchars($event['description']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Chưa có sự kiện nào. Hãy tạo sự kiện đầu tiên!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Sự Kiện -->
<div class="modal fade" id="addEventModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-bottom-0 p-4 pb-0">
        <h5 class="modal-title fw-bold fs-4">Tạo Sự Kiện Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="index.php?controller=admin&action=addEvent" method="POST">
          <div class="modal-body p-4">
              <div class="mb-3">
                  <label class="form-label fw-bold text-muted small">Tên sự kiện</label>
                  <input type="text" name="title" class="form-control form-control-lg bg-light border-0" required placeholder="VD: Workshop Trang điểm...">
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold text-muted small">Ngày diễn ra</label>
                  <input type="date" name="event_date" class="form-control form-control-lg bg-light border-0" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold text-muted small">Địa điểm Store</label>
                  <input type="text" name="location" class="form-control form-control-lg bg-light border-0" required placeholder="Glow Store - Vincom...">
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold text-muted small">Mô tả ngắn</label>
                  <textarea name="description" class="form-control bg-light border-0" rows="3" required placeholder="Nhập nội dung giới thiệu..."></textarea>
              </div>
          </div>
          <div class="modal-footer border-top-0 p-4 pt-0">
            <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Hủy bỏ</button>
            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Lưu Sự Kiện</button>
          </div>
      </form>
    </div>
  </div>
</div>
<?php include 'views/layout/footer.php'; ?>