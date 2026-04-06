<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "cosmetics_db";
    public $conn;

    // Hàm tự động kết nối khi gọi class
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            $this->conn->set_charset("utf8");
        } catch(Exception $e) {
            echo "Lỗi kết nối CSDL: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>