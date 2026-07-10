<?php
class PageController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Trang Hệ thống cửa hàng
    public function stores() {
        require_once 'views/pages/stores.php';
    }

    // Trang Tạp chí làm đẹp
    public function blog() {
        require_once 'models/PostModel.php';
        $postModel = new PostModel($this->conn);
        $posts = $postModel->getAllPosts();
        
        require_once 'views/pages/blog.php';
    }

    // Trang Chi tiết bài viết
    public function post() {
        require_once 'models/PostModel.php';
        $postModel = new PostModel($this->conn);
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $post = $postModel->getPostById($id);
        
        if (!$post) {
            header("Location: index.php?controller=page&action=blog");
            exit();
        }
        
        // Lấy các bài viết liên quan (loại trừ bài hiện tại)
        $posts = $postModel->getAllPosts();
        $relatedPosts = array_filter($posts, function($p) use ($id) {
            return $p['id'] != $id;
        });
        $relatedPosts = array_slice($relatedPosts, 0, 3); // Lấy 3 bài
        
        require_once 'views/pages/post_detail.php';
    }

    // Trang Trung tâm hỗ trợ
    public function support() {
        require_once 'views/pages/support.php';
    }

    // Trang Sự kiện tại store
    public function events() {
        require_once 'views/pages/events.php';
    }
}
?>