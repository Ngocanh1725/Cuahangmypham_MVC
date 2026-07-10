<?php
require_once 'models/ProductModel.php';

class ProductController {
    private $productModel;
    private $db;

    public function __construct($db) {
        $this->productModel = new ProductModel($db);
        $this->db = $db;
    }

    // ---------------------------------------------------------
    // 1. ACTION INDEX: Hiển thị Trang chủ & Trang Cửa hàng
    // ---------------------------------------------------------
    public function index() {
        // 1. Gom tất cả tham số từ URL
        $params = [
            'filter'   => isset($_GET['filter']) ? $_GET['filter'] : 'all',
            'keyword'  => isset($_GET['keyword']) ? trim($_GET['keyword']) : '',
            'price'    => isset($_GET['price']) ? $_GET['price'] : [],
            'category' => isset($_GET['category']) ? $_GET['category'] : [],
            'brand'    => isset($_GET['brand']) ? $_GET['brand'] : [],
            'sort'     => isset($_GET['sort']) ? $_GET['sort'] : ''
        ];

        // 2. Thiết lập Tiêu đề động
        $pageTitle = "Cửa Hàng Mỹ Phẩm";
        $subTitle = "Khám phá các sản phẩm nổi bật";

        if (!empty($params['keyword'])) {
            $pageTitle = "Kết quả tìm kiếm"; 
            $subTitle = "Từ khóa: '" . htmlspecialchars($params['keyword']) . "'";
        } elseif ($params['filter'] == 'flash_sale') {
            $pageTitle = "Flash Sale"; 
            $subTitle = "Săn deal chớp nhoáng";
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

        // 3. Lấy Dữ Liệu Động (Cho Bộ lọc & Trang Chủ)
        
        // Cập nhật danh mục động
        $categoriesData = $this->productModel->getUniqueCategories();
        $filterCategoryIds = array_column($categoriesData, 'id');
        
        // Cập nhật thương hiệu động
        require_once 'models/BrandModel.php';
        $brandModel = new BrandModel($this->db);
        $brandsData = $brandModel->getAllBrands();
        $filterBrandIds = array_column($brandsData, 'id');

        // Đếm số lượng sản phẩm cho bộ lọc
        $catCounts = $this->productModel->getFilterCounts('category_id', $filterCategoryIds);
        $brandCounts = $this->productModel->getFilterCounts('brand_id', $filterBrandIds); 

        // Lấy Banner
        $banners = $this->productModel->getActiveBanners();

        // Lấy Best Sellers
        $bestSellers = $this->productModel->getBestSellers(4);

        // Lấy Dữ liệu sản phẩm (cho Lưới)
        $products = $this->productModel->getProductsByAdvancedFilter($params);
        $currentFilter = $params['filter'];

        // Gọi View
        require_once 'views/products/index.php';
    }

    // ---------------------------------------------------------
    // 2. ACTION DETAIL: Hiển thị chi tiết một sản phẩm
    // ---------------------------------------------------------
    public function detail() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            header("Location: index.php");
            exit();
        }

        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            die("<div class='container py-5 text-center'><h2>Lỗi 404: Không tìm thấy sản phẩm!</h2><a href='index.php'>Quay về trang chủ</a></div>");
        }

        $pageTitle = $product['name'] . " - Glow Cosmetics";
        
        // Lấy sản phẩm liên quan
        $relatedProducts = $this->productModel->getRelatedProducts($product['category_id'], $id, 4);
        
        // Lấy danh sách đánh giá
        require_once 'models/ReviewModel.php';
        $reviewModel = new ReviewModel($this->db);
        $reviews = $reviewModel->getReviewsByProduct($id);
        $reviewStats = $reviewModel->getAverageRating($id);
        
        // Gọi View
        require_once 'views/products/detail.php';
    }

    // ---------------------------------------------------------
    // 3. ACTION ADD_REVIEW: Xử lý gửi đánh giá
    // ---------------------------------------------------------
    public function addReview() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=user&action=login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
            $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
            $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
            $user_id = $_SESSION['user_id'];
            
            if ($product_id > 0 && !empty($comment) && $rating >= 1 && $rating <= 5) {
                require_once 'models/ReviewModel.php';
                $reviewModel = new ReviewModel($this->db);
                $reviewModel->addReview($product_id, $user_id, $rating, $comment);
                
                // Set flash message
                $_SESSION['flash_message'] = "Cảm ơn bạn đã gửi đánh giá!";
            }
            
            header("Location: index.php?controller=product&action=detail&id=" . $product_id . "#reviews");
            exit();
        }
    }
}
?>