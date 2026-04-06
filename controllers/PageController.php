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
        require_once 'views/pages/blog.php';
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