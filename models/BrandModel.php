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

    // Hàm mới: Lấy danh sách thương hiệu hiển thị trên trang chủ
    public function getHomeBrands($limit = 12) {
        $sql = "SELECT * FROM brands WHERE is_home = 1 ORDER BY name ASC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $brands = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $brands[] = $row;
            }
        }
        $stmt->close();
        return $brands;
    }

    public function getBrandById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM brands WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $brand = $result->fetch_assoc();
        $stmt->close();
        return $brand;
    }

    public function addBrand($name, $logo, $banner, $description, $is_home = 1) {
        $stmt = $this->conn->prepare("INSERT INTO brands (name, logo, banner, description, is_home) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $logo, $banner, $description, $is_home);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateBrand($id, $name, $logo, $banner, $description, $is_home = 1) {
        $stmt = $this->conn->prepare("UPDATE brands SET name=?, logo=?, banner=?, description=?, is_home=? WHERE id=?");
        $stmt->bind_param("ssssii", $name, $logo, $banner, $description, $is_home, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteBrand($id) {
        $stmt = $this->conn->prepare("DELETE FROM brands WHERE id=?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>