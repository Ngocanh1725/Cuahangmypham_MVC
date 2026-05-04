<?php
require_once 'models/BrandModel.php';
require_once 'models/ProductModel.php'; // Nhúng ProductModel để lấy sản phẩm
//Hiển thị danh sách thương hiệu theo chữ cái A-Z và trang riêng của từng hãng mỹ phẩm.
class BrandController {
    private $brandModel;
    private $db;

    public function __construct($db) {
        $this->brandModel = new BrandModel($db);
        $this->db = $db;
    }

    // Trang hiển thị danh sách A-Z
    public function index() {
        $brands = $this->brandModel->getAllBrands();
        $groupedBrands = [];
        $alphabet = range('A', 'Z');
        foreach ($alphabet as $char) {
            $groupedBrands[$char] = [];
        }
        $groupedBrands['#'] = [];

        foreach ($brands as $brand) {
            $firstChar = strtoupper(mb_substr($brand['name'], 0, 1, 'UTF-8'));
            if (in_array($firstChar, $alphabet)) {
                $groupedBrands[$firstChar][] = $brand;
            } else {
                $groupedBrands['#'][] = $brand;
            }
        }

        foreach ($groupedBrands as $key => $group) {
            if (empty($group)) unset($groupedBrands[$key]);
        }

        require_once 'views/brands/index.php';
    }

    // TRANG MỚI: Chi tiết 1 Thương Hiệu
    public function detail() {
        $brandName = isset($_GET['name']) ? trim($_GET['name']) : '';
        
        // 1. Lấy thông tin Banner, Logo của hãng
        $brandInfo = $this->brandModel->getBrandByName($brandName);
        
        if (!$brandInfo) {
            // Nếu không tìm thấy trong DB (có thể do chưa nhập liệu banner), tạo data giả lập để web không lỗi
            $brandInfo = [
                'name' => $brandName,
                'logo' => 'https://via.placeholder.com/150?text='.urlencode($brandName),
                'banner' => 'https://via.placeholder.com/1200x300?text='.urlencode($brandName).' +Banner',
                'description' => 'Thương hiệu mỹ phẩm cao cấp ' . $brandName,
                'sales_count' => '0 lượt mua',
                'product_count' => 0
            ];
        }

        // 2. Lấy sản phẩm của RIÊNG hãng này
        $productModel = new ProductModel($this->db);
        
        $params = [
            'brand'    => [$brandName], // Ép điều kiện lọc luôn luôn là hãng này
            'price'    => isset($_GET['price']) ? $_GET['price'] : [],
            'category' => isset($_GET['category']) ? $_GET['category'] : [],
            'sort'     => isset($_GET['sort']) ? $_GET['sort'] : ''
        ];

        // Lấy danh mục động cho bộ lọc
        $filterCategories = ['Bông cotton', 'Che khuyết điểm', 'Chì kẻ mắt', 'Chì kẻ mày', 'Cọ trang điểm', 'Dưỡng Môi', 'Kem nền', 'Phấn phủ', 'Son môi'];
        $catCounts = $productModel->getFilterCounts('category', $filterCategories);

        $products = $productModel->getProductsByAdvancedFilter($params);

        // 3. Gọi View
        $pageTitle_Header = $brandName . " - Glow Cosmetics";
        require_once 'views/brands/detail.php';
    }
}
?>