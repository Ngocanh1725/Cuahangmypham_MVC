<?php $pageTitle = 'Sửa Bài Viết - Glow Admin'; include 'views/layout/header.php'; ?>
<div class="container-fluid"><div class="row">
    <?php include 'views/admin/includes/sidebar.php'; ?>
    <div class="col-md-10 p-4 bg-light min-vh-100">
        <h3 class="mb-4">Sửa Bài Viết</h3>
        <?php if (!empty($message)) echo $message; ?>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label>Tiêu đề</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Nội dung</label>
                        <textarea name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="1" <?php echo $post['status'] == 1 ? 'selected' : ''; ?>>Hiển thị</option>
                            <option value="0" <?php echo $post['status'] == 0 ? 'selected' : ''; ?>>Ẩn</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-brand">Cập nhật</button>
                    <a href="index.php?controller=admin&action=posts" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
    </div>
</div>
<?php include 'views/layout/footer.php'; ?>