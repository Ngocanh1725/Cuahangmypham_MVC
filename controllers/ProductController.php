<?php
require_once 'models/ProductModel.php';
//Quản lý việc hiển thị trang chủ, 
//trang danh sách sản phẩm (với bộ lọc) và trang chi tiết sản phẩm cho khách hàng.
class ProductController {
    private $productModel;

    public function __construct($db) {
        $this->productModel = new ProductModel($db);
    }

    // ---------------------------------------------------------
    // 1. ACTION INDEX: Hiển thị danh sách sản phẩm & Trang chủ
    // ---------------------------------------------------------
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

        // [MỚI] Lấy danh sách Banner để hiển thị ra trang chủ
        $banners = $this->productModel->getActiveBanners();

        // 3. Gọi Model xử lý lọc dữ liệu danh sách sản phẩm
        $products = $this->productModel->getProductsByAdvancedFilter($params);

        // Lưu lại $currentFilter để View hiển thị active
        $currentFilter = $params['filter'];

        // 4. Gọi View hiển thị
        require_once 'views/products/index.php';
    }

    // ---------------------------------------------------------
    // 2. ACTION DETAIL: Hiển thị chi tiết một sản phẩm (Fix Lỗi 404)
    // ---------------------------------------------------------
    public function detail() {
        // Lấy ID sản phẩm từ URL (vd: index.php?controller=product&action=detail&id=1)
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            // Nếu không có ID hợp lệ, quay về trang chủ
            header("Location: index.php");
            exit();
        }

        // Gọi Model để lấy dữ liệu sản phẩm theo ID
        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            die("<div style='text-align:center; padding: 100px; font-family: sans-serif;'>
                    <h1 style='color: #7A1C1C; font-size: 4rem;'>404</h1>
                    <h2>Không tìm thấy sản phẩm!</h2>
                    <p>Sản phẩm bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
                    <a href='index.php' style='display: inline-block; margin-top: 20px; padding: 10px 25px; background: #7A1C1C; color: white; text-decoration: none; border-radius: 4px;'>Quay về trang chủ</a>
                 </div>");
        }

        // Truyền tiêu đề cho thẻ <title> trên trình duyệt
        $pageTitle = $product['name'] . " - Glow Cosmetics";
        
        // Gọi View hiển thị trang chi tiết sản phẩm
        require_once 'views/products/detail.php';
    }
}
?>