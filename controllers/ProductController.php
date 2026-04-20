<?php
require_once 'models/ProductModel.php';

class ProductController {
    private $productModel;

    public function __construct($db) {
        $this->productModel = new ProductModel($db);
    }

    public function index() {
        // 1. Gom tất cả tham số từ URL (Checkbox, Dropdown Sắp xếp)
        $params = [
            'filter'   => isset($_GET['filter']) ? $_GET['filter'] : 'all',
            'keyword'  => isset($_GET['keyword']) ? trim($_GET['keyword']) : '',
            'price'    => isset($_GET['price']) ? $_GET['price'] : [],
            'category' => isset($_GET['category']) ? $_GET['category'] : [],
            'brand'    => isset($_GET['brand']) ? $_GET['brand'] : [],
            'volume'   => isset($_GET['volume']) ? $_GET['volume'] : [],
            'sort'     => isset($_GET['sort']) ? $_GET['sort'] : ''
        ];

        // 2. Thiết lập Tiêu đề động
        $pageTitle = "Dành Riêng Cho Bạn";
        $subTitle = "Sản phẩm nổi bật";

        if (!empty($params['keyword'])) {
            $pageTitle = "Kết quả tìm kiếm"; 
            $subTitle = "Từ khóa: '" . htmlspecialchars($params['keyword']) . "'";
        } elseif ($params['filter'] == 'promotion') {
            $pageTitle = "Khuyến Mãi Hot"; 
            $subTitle = "Deal hời không thể bỏ lỡ";
        } elseif ($params['filter'] == 'new') {
            $pageTitle = "Sản Phẩm Mới Nhất"; 
            $subTitle = "Vừa cập bến";
        } elseif ($params['filter'] == 'skincare') {
            $pageTitle = "Chăm Sóc Da"; 
            $subTitle = "Làn da rạng rỡ";
        } elseif ($params['filter'] == 'makeup') {
            $pageTitle = "Trang Điểm"; 
            $subTitle = "Tự tin tỏa sáng";
        } elseif ($params['filter'] == 'perfume') {
            $pageTitle = "Nước Hoa Cao Cấp"; 
            $subTitle = "Hương thơm quyến rũ";
        } elseif ($params['filter'] == 'hairbody') {
            $pageTitle = "Cơ Thể & Tóc"; 
            $subTitle = "Nuôi dưỡng toàn diện";
        }

        // --- TÍNH TOÁN SỐ LƯỢNG ĐỘNG CHO BỘ LỌC ---
        // 1. Khai báo danh sách các mục bộ lọc cần hiển thị
        $filterCategories = ['Bông cotton', 'Che khuyết điểm', 'Chì kẻ mắt', 'Chì kẻ mày', 'Cọ trang điểm', 'Dưỡng Môi', 'ELC', 'Highlighter', 'Kem nền', 'Phấn phủ', 'Son môi'];
        $filterBrands = ['Anessa', 'Bioderma', 'Cerave', 'Dior', 'Eucerin', 'La Roche-Posay', 'M.A.C', 'Maybelline', "L'Oreal"];
        $filterVolumes = ['100ml', '50ml', '250ml', '15g', '20g'];

        // 2. Lấy số lượng (Count) từ Database
        $catCounts = $this->productModel->getFilterCounts('category', $filterCategories);
        $brandCounts = $this->productModel->getFilterCounts('name', $filterBrands); // Tìm thương hiệu tương đối qua Tên SP
        $volCounts = $this->productModel->getFilterCounts('name', $filterVolumes); 

        // 3. Gọi Model xử lý lọc dữ liệu danh sách sản phẩm
        $products = $this->productModel->getProductsByAdvancedFilter($params);

        // Lưu lại $currentFilter để View hiển thị active
        $currentFilter = $params['filter'];

        // 4. Gọi View hiển thị
        require_once 'views/products/index.php';
    }
}
?>