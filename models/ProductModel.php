<?php
// Tập trung vào các truy vấn liên quan đến sản phẩm: lấy danh sách, lọc sản phẩm theo tiêu chí (giá, loại, hãng), 
//và lấy thông tin chi tiết một sản phẩm.
class ProductModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

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

    // HÀM MỚI: Xử lý bộ lọc nâng cao (Giá, Loại, Thương hiệu, Dung tích)
    public function getProductsByAdvancedFilter($params) {
        $sql = "SELECT * FROM products WHERE 1=1";
        $bindParams = [];
        $types = "";

        // 1. Tìm kiếm bằng thanh Search
        if (!empty($params['keyword'])) {
            $sql .= " AND (name LIKE ? OR category LIKE ?)";
            $types .= "ss";
            $searchParam = "%{$params['keyword']}%";
            $bindParams[] = $searchParam;
            $bindParams[] = $searchParam;
        }

        // 2. Lọc theo các Tab menu ngang (Khuyến mãi, Skincare, Makeup...)
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

        // 3. LỌC GIÁ SẢN PHẨM (Từ Checkbox)
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

        // 4. LỌC LOẠI SẢN PHẨM
        if (!empty($params['category']) && is_array($params['category'])) {
            $catConditions = [];
            foreach ($params['category'] as $cat) {
                $keyword = $cat; 
                if ($cat == 'BoChamSoc') $keyword = 'Chăm sóc';
                if ($cat == 'BongCotton') $keyword = 'Bông';
                if ($cat == 'ChamSocCoThe') $keyword = 'Cơ thể';
                if ($cat == 'ChamSocSucKhoe') $keyword = 'Sức khỏe';
                if ($cat == 'CheKhuyetDiem') $keyword = 'Khuyết điểm';
                
                $catConditions[] = "category LIKE ?";
                $types .= "s";
                $bindParams[] = "%" . $keyword . "%";
            }
            if (!empty($catConditions)) {
                $sql .= " AND (" . implode(" OR ", $catConditions) . ")";
            }
        }

        // 5. LỌC THƯƠNG HIỆU
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

        // 6. LỌC DUNG TÍCH
        if (!empty($params['volume']) && is_array($params['volume'])) {
            $volConditions = [];
            foreach ($params['volume'] as $vol) {
                $volConditions[] = "name LIKE ?";
                $types .= "s";
                $bindParams[] = "%" . $vol . "%";
            }
            if (!empty($volConditions)) {
                $sql .= " AND (" . implode(" OR ", $volConditions) . ")";
            }
        }

        // 7. SẮP XẾP SẢN PHẨM
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

    // HÀM MỚI: Lấy danh sách banner quảng cáo hoạt động
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
            // Bỏ qua lỗi nếu bảng banners chưa được tạo trong Database
        }
        return $banners;
    }

    // HÀM MỚI: Lấy thông tin chi tiết 1 sản phẩm theo ID (Fix lỗi 404)
    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        // Thực thi câu lệnh
        $result = $stmt->get_result();
        // Dòng cụ thể lấy dữ liệu ra thành mảng $product
        $product = $result->fetch_assoc();
        $stmt->close();
        return $product;
    }
}
?>