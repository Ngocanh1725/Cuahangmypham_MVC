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

    // Nâng cấp: Lấy sản phẩm theo BỘ LỌC và TỪ KHÓA TÌM KIẾM an toàn
    public function getProductsByFilter($filter = 'all', $keyword = '') {
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];
        $types = "";

        // Ưu tiên xử lý từ khóa tìm kiếm trước bằng Prepared Statement
        if (!empty($keyword)) {
            $sql .= " AND (name LIKE ? OR category LIKE ?)";
            $types .= "ss";
            $searchParam = "%{$keyword}%";
            $params[] = &$searchParam;
            $params[] = &$searchParam;
        } else {
            // Xử lý các Tab lọc (An toàn vì $filter được gán cứng từ code, không nhập trực tiếp)
            if ($filter == 'promotion') {
                $sql .= " AND old_price > price ORDER BY id DESC";
            } elseif ($filter == 'new') {
                $sql .= " ORDER BY id DESC LIMIT 20"; 
            } elseif ($filter == 'skincare') {
                $sql .= " AND category LIKE '%Chăm sóc da%'";
            } elseif ($filter == 'makeup') {
                $sql .= " AND category LIKE '%Trang điểm%'";
            } elseif ($filter == 'perfume') {
                $sql .= " AND category LIKE '%Nước hoa%'";
            } elseif ($filter == 'hairbody') {
                $sql .= " AND (category LIKE '%Tóc%' OR category LIKE '%Cơ thể%')";
            } else {
                $sql .= " ORDER BY id DESC"; 
            }
        }

        $stmt = $this->conn->prepare($sql);
        
        // Nếu có tham số keyword thì bind vào
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
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