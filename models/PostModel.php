<?php
class PostModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllPosts() {
        $sql = "SELECT * FROM posts ORDER BY id DESC";
        $posts = [];
        try {
            $result = $this->conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $posts[] = $row;
                }
            }
        } catch (Exception $e) { }
        return $posts;
    }

    public function getPostById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();
        $stmt->close();
        return $post;
    }

    public function addPost($title, $content, $image, $status) {
        $stmt = $this->conn->prepare("INSERT INTO posts (title, content, image, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $content, $image, $status);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updatePost($id, $title, $content, $image, $status) {
        $stmt = $this->conn->prepare("UPDATE posts SET title=?, content=?, image=?, status=? WHERE id=?");
        $stmt->bind_param("sssii", $title, $content, $image, $status, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deletePost($id) {
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id=?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}