<?php
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
                $parts = explode('-', $range); // Tách chuỗi '0-500000'
                if (count($parts) == 2) {
                    $min = (int)$parts[0];
                    $max = (int)$parts[1];
                    if ($max == 0) { // Trường hợp "Trên 2.000.000đ" (2000000-)
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
                // Ánh xạ value của form HTML sang từ khóa trong Database
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

        // 5. LỌC THƯƠNG HIỆU (Tìm tương đối trong Tên sản phẩm để tránh sót)
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

        // 7. SẮP XẾP SẢN PHẨM (Sorting)
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
            $sql .= " ORDER BY id DESC"; // Mặc định
            if (!empty($params['filter']) && $params['filter'] == 'new') {
                $sql .= " LIMIT 20";
            }
        }

        // Thực thi Prepare Statement an toàn (Chống SQL Injection)
        $stmt = $this->conn->prepare($sql);
        
        if (!empty($bindParams)) {
            $bindNames[] = $types;
            for ($i = 0; $i < count($bindParams); $i++) {
                $bindName = 'bind' . $i;
                $$bindName = $bindParams[$i];
                $bindNames[] = &$$bindName;
            }
            // Bind mảng động vào stmt
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

    // HÀM MỚI: Đếm số lượng sản phẩm theo từng từ khóa (Dành cho Bộ Lọc Sidebar)
    public function getFilterCounts($column, $keywords) {
        $counts = [];
        // Bảo mật: Chỉ cho phép quét ở cột 'category' hoặc 'name'
        $allowedColumns = ['category', 'name'];
        if (!in_array($column, $allowedColumns)) {
            return $counts;
        }

        // Chuẩn bị câu lệnh SQL Đếm số lượng
        $sql = "SELECT COUNT(*) as total FROM products WHERE $column LIKE ?";
        $stmt = $this->conn->prepare($sql);

        foreach ($keywords as $kw) {
            $term = "%" . $kw . "%";
            $stmt->bind_param("s", $term);
            $stmt->execute();
            // Gán số lượng đếm được vào Mảng với key là tên từ khóa
            $counts[$kw] = $stmt->get_result()->fetch_assoc()['total'];
        }
        
        $stmt->close();
        return $counts;
    }
}
?>