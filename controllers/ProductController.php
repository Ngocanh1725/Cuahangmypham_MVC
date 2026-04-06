<?php
require_once 'models/ProductModel.php';

class ProductController {
    private $productModel;

    public function __construct($db) {
        $this->productModel = new ProductModel($db);
    }

    public function index() {
        // 1. Lấy tham số filter và keyword từ URL
        $currentFilter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

        // 2. Thiết lập Tiêu đề động
        $pageTitle = "Dành Riêng Cho Bạn";
        $subTitle = "Sản phẩm nổi bật";

        if (!empty($keyword)) {
            // Đổi tiêu đề nếu người dùng đang tìm kiếm
            $pageTitle = "Kết quả tìm kiếm"; 
            $subTitle = "Từ khóa: '" . htmlspecialchars($keyword) . "'";
        } elseif ($currentFilter == 'promotion') {
            $pageTitle = "Khuyến Mãi Hot"; 
            $subTitle = "Deal hời không thể bỏ lỡ";
        } elseif ($currentFilter == 'new') {
            $pageTitle = "Sản Phẩm Mới Nhất"; 
            $subTitle = "Vừa cập bến";
        } elseif ($currentFilter == 'skincare') {
            $pageTitle = "Chăm Sóc Da"; 
            $subTitle = "Làn da rạng rỡ";
        } elseif ($currentFilter == 'makeup') {
            $pageTitle = "Trang Điểm"; 
            $subTitle = "Tự tin tỏa sáng";
        } elseif ($currentFilter == 'perfume') {
            $pageTitle = "Nước Hoa Cao Cấp"; 
            $subTitle = "Hương thơm quyến rũ";
        } elseif ($currentFilter == 'hairbody') {
            $pageTitle = "Cơ Thể & Tóc"; 
            $subTitle = "Nuôi dưỡng toàn diện";
        }

        // 3. Gọi Model lấy dữ liệu, truyền CẢ 2 tham số: lọc và từ khóa
        $products = $this->productModel->getProductsByFilter($currentFilter, $keyword);

        // 4. Gọi View hiển thị
        require_once 'views/products/index.php';
    }
}
?>