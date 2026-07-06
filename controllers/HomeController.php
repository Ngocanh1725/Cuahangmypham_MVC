<?php
require_once 'models/ProductModel.php';

class HomeController {
    private $productModel;
    private $db;

    public function __construct($db) {
        $this->productModel = new ProductModel($db);
        $this->db = $db;
    }

    public function index() {
        // Thiết lập Tiêu đề trang
        $pageTitle = "Glow Cosmetics - Triết lý vẻ đẹp tự nhiên";
        
        // Lấy danh sách Banners và Sản phẩm bán chạy từ ProductModel
        $banners = $this->productModel->getActiveBanners();
        $bestSellers = $this->productModel->getBestSellers(4);

        // Gọi View Trang chủ
        require_once 'views/home/index.php';
    }
}
?>