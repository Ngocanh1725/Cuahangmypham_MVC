<?php
require_once 'models/ProductModel.php';
require_once 'models/BrandModel.php';
require_once 'models/AdminModel.php';

class HomeController {
    private $productModel;
    private $brandModel;
    private $adminModel;
    private $db;

    public function __construct($db) {
        $this->productModel = new ProductModel($db);
        $this->brandModel   = new BrandModel($db);
        $this->adminModel   = new AdminModel($db);
        $this->db = $db;
    }

    public function index() {
        $pageTitle = "Glow Cosmetics - Mỹ phẩm chính hãng";

        // --- SECTION 1: Hero Slider ---
        $heroBanners = $this->productModel->getBannersByPosition('hero_slider', 5);

        // --- SECTION 2: Brands (top 12 home) ---
        $brandsList = $this->brandModel->getHomeBrands(12);

        // --- SECTION 3: Flash Sale ---
        // Ưu tiên sản phẩm có flag is_flash_sale, nếu ít thì bổ sung từ old_price > price
        $flashSaleProducts = $this->productModel->getProductsByFlag('is_flash_sale', 10);
        if (count($flashSaleProducts) < 4) {
            $flashSaleProducts = $this->productModel->getFlashSaleProducts(10);
        }
        $settings = $this->adminModel->getAllSettings();
        $flashSaleEnd = $settings['flash_sale_end']['setting_value'] ?? '';

        // --- SECTION 4: Exclusive Brands banners ---
        $exclusiveBanners = $this->productModel->getBannersByPosition('exclusive', 4);

        // --- SECTION 5: Xu Hướng Làm Đẹp (tabs) ---
        $trendingTabProducts = [
            'duong_da'   => $this->productModel->getProductsByCategoryName('Dưỡng da', 8),
            'trang_diem' => $this->productModel->getProductsByCategoryName('Trang điểm', 8),
            'mat_na'     => $this->productModel->getProductsByCategoryName('Mặt nạ', 8),
            'lam_sach'   => $this->productModel->getProductsByCategoryName('Làm sạch', 8),
        ];
        // Fallback: nếu tab trống thì dùng sản phẩm chung
        $fallbackProducts = $this->productModel->getActiveProductsForHome(8);
        foreach ($trendingTabProducts as $k => $v) {
            if (empty($v)) $trendingTabProducts[$k] = $fallbackProducts;
        }

        // --- SECTION 6: Gợi Ý Mùa Hè ---
        $summerProducts = $this->productModel->getProductsByFlag('is_summer', 8);
        if (empty($summerProducts)) {
            $summerProducts = $this->productModel->getActiveProductsForHome(8);
        }

        // --- SECTION 7: Top Trend (banners dọc) ---
        $topTrendBanners = $this->productModel->getBannersByPosition('toptrend', 4);

        // --- SECTION 8: Góc Đẹp Blog ---
        require_once 'models/PostModel.php';
        $postModel = new PostModel($this->db);
        $allPosts    = $postModel->getAllPosts();
        $latestPosts = array_slice($allPosts, 0, 3);

        require_once 'views/home/index.php';
    }
}
?>