<?php
class ReviewModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm đánh giá
    public function addReview($product_id, $user_id, $rating, $comment) {
        $query = "INSERT INTO product_reviews (product_id, user_id, rating, comment, status) 
                  VALUES (?, ?, ?, ?, 1)"; // Mặc định status = 1 (Hiển thị)
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Lấy đánh giá của một sản phẩm (chỉ hiển thị status = 1)
    public function getReviewsByProduct($product_id) {
        $query = "SELECT r.*, u.full_name as fullname, u.email 
                  FROM product_reviews r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.product_id = ? AND r.status = 1 
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reviews = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
        }
        $stmt->close();
        return $reviews;
    }

    // Tính điểm đánh giá trung bình của sản phẩm
    public function getAverageRating($product_id) {
        $query = "SELECT AVG(rating) as avg_rating, COUNT(id) as total_reviews 
                  FROM product_reviews 
                  WHERE product_id = ? AND status = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stats = ['avg_rating' => 0, 'total_reviews' => 0];
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stats['avg_rating'] = $row['avg_rating'] ? round($row['avg_rating'], 1) : 0;
            $stats['total_reviews'] = $row['total_reviews'];
        }
        $stmt->close();
        return $stats;
    }

    // Lấy tất cả đánh giá cho Admin
    public function getAllReviews() {
        $query = "SELECT r.*, u.full_name as fullname, p.name as product_name 
                  FROM product_reviews r 
                  JOIN users u ON r.user_id = u.id 
                  JOIN products p ON r.product_id = p.id 
                  ORDER BY r.created_at DESC";
        $result = $this->conn->query($query);
        $reviews = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
        }
        return $reviews;
    }

    // Xóa đánh giá (Admin)
    public function deleteReview($id) {
        $query = "DELETE FROM product_reviews WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Ẩn/Hiện đánh giá (Admin)
    public function toggleStatus($id) {
        // Lấy status hiện tại
        $query = "SELECT status FROM product_reviews WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $newStatus = ($row['status'] == 1) ? 0 : 1;
            
            $updateQuery = "UPDATE product_reviews SET status = ? WHERE id = ?";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bind_param("ii", $newStatus, $id);
            $updateStmt->execute();
            $updateStmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
}
?>
