<?php
//Lấy thông tin các thương hiệu đối tác 
//để hiển thị trên trang chủ và trang danh mục hãng.
class BrandModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBrands() {
        $sql = "SELECT * FROM brands ORDER BY name ASC";
        $result = $this->conn->query($sql);
        
        $brands = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $brands[] = $row;
            }
        }
        return $brands;
    }

    // Hàm mới: Lấy thông tin chi tiết 1 hãng theo Tên
    public function getBrandByName($name) {
        $stmt = $this->conn->prepare("SELECT * FROM brands WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $brand = $result->fetch_assoc();
        $stmt->close();
        return $brand;
    }
}
?>