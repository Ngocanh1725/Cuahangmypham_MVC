<?php
class ProductModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Lấy tất cả sản phẩm
    public function getAllProducts() {
        $sql = "SELECT * FROM products ORDER BY id DESC";
        $result = $this->conn->query($sql);
        
        $products = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        return $products;
    }

    // 2. Lấy thông tin 1 sản phẩm theo ID
    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        return $product;
    }

    // 3. Lấy danh sách banner quảng cáo hoạt động
    public function getActiveBanners() {
        $banners = [];
        try {
            $sql = "SELECT * FROM banners WHERE status = 1 ORDER BY id DESC";
            $result = $this->conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $banners[] = $row;
                }
            }
        } catch (Exception $e) {
            // Bỏ qua lỗi nếu bảng banners chưa được tạo
        }
        return $banners;
    }

    // 4. Lấy số lượng sản phẩm theo từng mục bộ lọc
    public function getFilterCounts($column, $keywords) {
        $counts = [];
        $allowedColumns = ['category', 'name'];
        if (!in_array($column, $allowedColumns)) {
            return $counts;
        }

        $sql = "SELECT COUNT(*) as total FROM products WHERE $column LIKE ?";
        $stmt = $this->conn->prepare($sql);

        foreach ($keywords as $kw) {
            $term = "%" . $kw . "%";
            $stmt->bind_param("s", $term);
            $stmt->execute();
            $counts[$kw] = $stmt->get_result()->fetch_assoc()['total'];
        }
        
        $stmt->close();
        return $counts;
    }

    // ---------------------------------------------------------
    // CÁC HÀM MỚI CHO BƯỚC 6 (Dữ liệu động)
    // ---------------------------------------------------------

    // 5. Lấy danh sách các danh mục duy nhất có trong CSDL
    public function getUniqueCategories() {
        $sql = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''";
        $result = $this->conn->query($sql);
        $categories = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row['category'];
            }
        }
        return $categories;
    }

    // 6. Lấy danh sách sản phẩm bán chạy (Trang chủ)
    public function getBestSellers($limit = 4) {
        $sql = "SELECT * FROM products WHERE status = 1 ORDER BY id ASC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { 
                $products[] = $row; 
            }
        }
        $stmt->close();
        return $products;
    }

    // 7. Lấy sản phẩm liên quan cùng danh mục (Trang chi tiết)
    public function getRelatedProducts($category, $exclude_id, $limit = 4) {
        $sql = "SELECT * FROM products WHERE category = ? AND id != ? AND status = 1 ORDER BY id DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $category, $exclude_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { 
                $products[] = $row; 
            }
        }
        $stmt->close();
        return $products;
    }

    // 8. XỬ LÝ BỘ LỌC NÂNG CAO (Trang Cửa Hàng)
    public function getProductsByAdvancedFilter($params) {
        $sql = "SELECT * FROM products WHERE 1=1 AND status = 1";
        $bindParams = [];
        $types = "";

        // Lọc từ khóa
        if (!empty($params['keyword'])) {
            $sql .= " AND (name LIKE ? OR category LIKE ?)";
            $types .= "ss";
            $searchParam = "%{$params['keyword']}%";
            $bindParams[] = $searchParam;
            $bindParams[] = $searchParam;
        }

        // Lọc Menu Navbar ngang
        if (!empty($params['filter']) && $params['filter'] != 'all') {
            $filter = $params['filter'];
            if ($filter == 'promotion') {
                $sql .= " AND old_price > price";
            } elseif ($filter == 'skincare') {
                $sql .= " AND category LIKE '%Chăm sóc da%'";
            } elseif ($filter == 'makeup') {
                $sql .= " AND category LIKE '%Trang điểm%'";
            } elseif ($filter == 'perfume') {
                $sql .= " AND category LIKE '%Nước hoa%'";
            } elseif ($filter == 'hairbody') {
                $sql .= " AND (category LIKE '%Tóc%' OR category LIKE '%Cơ thể%')";
            }
        }

        // Lọc theo khoảng giá
        if (!empty($params['price']) && is_array($params['price'])) {
            $priceConditions = [];
            foreach ($params['price'] as $range) {
                $parts = explode('-', $range); 
                if (count($parts) == 2) {
                    $min = (int)$parts[0];
                    $max = (int)$parts[1];
                    if ($max == 0) { 
                        $priceConditions[] = "(price >= $min)";
                    } else {
                        $priceConditions[] = "(price >= $min AND price <= $max)";
                    }
                }
            }
            if (!empty($priceConditions)) {
                $sql .= " AND (" . implode(" OR ", $priceConditions) . ")";
            }
        }

        // Lọc danh mục
        if (!empty($params['category']) && is_array($params['category'])) {
            $catConditions = [];
            foreach ($params['category'] as $cat) {
                $catConditions[] = "category LIKE ?";
                $types .= "s";
                $bindParams[] = "%" . $cat . "%";
            }
            if (!empty($catConditions)) {
                $sql .= " AND (" . implode(" OR ", $catConditions) . ")";
            }
        }

        // Lọc thương hiệu (tương đối qua tên SP)
        if (!empty($params['brand']) && is_array($params['brand'])) {
            $brandConditions = [];
            foreach ($params['brand'] as $brand) {
                $brandConditions[] = "name LIKE ?";
                $types .= "s";
                $bindParams[] = "%" . $brand . "%";
            }
            if (!empty($brandConditions)) {
                $sql .= " AND (" . implode(" OR ", $brandConditions) . ")";
            }
        }

        // Sắp xếp
        if (!empty($params['sort'])) {
            if ($params['sort'] == 'price_asc') {
                $sql .= " ORDER BY price ASC";
            } elseif ($params['sort'] == 'price_desc') {
                $sql .= " ORDER BY price DESC";
            } elseif ($params['sort'] == 'newest') {
                $sql .= " ORDER BY id DESC";
            } else {
                $sql .= " ORDER BY id DESC";
            }
        } else {
            $sql .= " ORDER BY id DESC"; 
            if (!empty($params['filter']) && $params['filter'] == 'new') {
                $sql .= " LIMIT 20";
            }
        }

        // Thực thi
        $stmt = $this->conn->prepare($sql);
        
        if (!empty($bindParams)) {
            $bindNames[] = $types;
            for ($i = 0; $i < count($bindParams); $i++) {
                $bindName = 'bind' . $i;
                $$bindName = $bindParams[$i];
                $bindNames[] = &$$bindName;
            }
            call_user_func_array([$stmt, 'bind_param'], $bindNames);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        $stmt->close();
        return $products;
    }
}
?>