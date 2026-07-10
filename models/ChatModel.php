<?php
class ChatModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tin nhắn giữa admin và 1 user
    public function getMessages($user_id) {
        $query = "SELECT c.*, p.name as product_name, p.image as product_image, p.price as product_price 
                  FROM chat_messages c 
                  LEFT JOIN products p ON c.product_id = p.id 
                  WHERE c.user_id = ? 
                  ORDER BY c.created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $messages = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }
        }
        $stmt->close();
        return $messages;
    }

    // Khách hàng hoặc Admin gửi tin nhắn
    public function sendMessage($user_id, $message, $is_admin = 0, $product_id = null) {
        if (empty($product_id)) $product_id = null;
        
        $query = "INSERT INTO chat_messages (user_id, message, is_admin, product_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isii", $user_id, $message, $is_admin, $product_id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Admin lấy danh sách các user đã nhắn tin
    public function getChatUsers() {
        $query = "SELECT u.id, u.full_name as fullname, u.email, u.avatar, 
                         (SELECT message FROM chat_messages WHERE user_id = u.id ORDER BY created_at DESC LIMIT 1) as last_message,
                         (SELECT created_at FROM chat_messages WHERE user_id = u.id ORDER BY created_at DESC LIMIT 1) as last_time
                  FROM users u 
                  WHERE u.id IN (SELECT DISTINCT user_id FROM chat_messages)
                  ORDER BY last_time DESC";
        $result = $this->conn->query($query);
        
        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }
}
?>
