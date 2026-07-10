<?php
class ChatController {
    private $chatModel;
    private $db;

    public function __construct($db) {
        require_once 'models/ChatModel.php';
        $this->chatModel = new ChatModel($db);
        $this->db = $db;
    }

    // Lấy tin nhắn (dành cho User)
    public function getMessages() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $messages = $this->chatModel->getMessages($user_id);
        
        echo json_encode(['status' => 'success', 'data' => $messages]);
        exit();
    }

    // Gửi tin nhắn (dành cho User)
    public function sendMessage() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $message = isset($_POST['message']) ? trim($_POST['message']) : '';
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;

        if (!empty($message) || $product_id > 0) {
            $this->chatModel->sendMessage($user_id, $message, 0, $product_id);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Empty message']);
        }
        exit();
    }

    // API Tìm kiếm sản phẩm cho khung chat
    public function searchProduct() {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($keyword) >= 2) {
            require_once 'models/ProductModel.php';
            $productModel = new ProductModel($this->db);
            $query = "SELECT id, name, image, price FROM products WHERE name LIKE ? OR id = ? LIMIT 5";
            
            $stmt = $this->db->prepare($query);
            $searchPattern = "%$keyword%";
            $stmt->bind_param("si", $searchPattern, $keyword);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            echo json_encode(['status' => 'success', 'data' => $products]);
        } else {
            echo json_encode(['status' => 'success', 'data' => []]);
        }
        exit();
    }
}
?>
