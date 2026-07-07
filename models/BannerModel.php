<?php
class BannerModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBanners() {
        $banners = [];
        try {
            $sql = "SELECT * FROM banners ORDER BY id DESC";
            $result = $this->conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $banners[] = $row;
                }
            }
        } catch (Exception $e) {}
        return $banners;
    }

    public function addBanner($title, $image, $link, $description, $position, $status) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO banners (title, image, link, description, position, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $title, $image, $link, $description, $position, $status);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) { return false; }
    }

    public function getBannerById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM banners WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $banner = $result->fetch_assoc();
            $stmt->close();
            return $banner;
        } catch (Exception $e) { return null; }
    }

    public function updateBanner($id, $title, $image, $link, $description, $position, $status) {
        try {
            $stmt = $this->conn->prepare("UPDATE banners SET title=?, image=?, link=?, description=?, position=?, status=? WHERE id=?");
            $stmt->bind_param("sssssii", $title, $image, $link, $description, $position, $status, $id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) { return false; }
    }

    public function deleteBanner($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM banners WHERE id=?");
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) { return false; }
    }
}
?>
