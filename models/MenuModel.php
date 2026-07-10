<?php
class MenuModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllMenus() {
        $query = "SELECT * FROM menus ORDER BY position ASC, sort_order ASC";
        $result = $this->conn->query($query);
        $menus = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $menus[] = $row;
            }
        }
        return $menus;
    }

    // Lấy menu gốc (parent_id IS NULL)
    public function getRootMenusByPosition($position, $activeOnly = false) {
        $query = "SELECT * FROM menus WHERE position = ? AND parent_id IS NULL";
        if ($activeOnly) {
            $query .= " AND status = 1";
        }
        $query .= " ORDER BY sort_order ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $position);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $menus = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $menus[] = $row;
            }
        }
        $stmt->close();
        return $menus;
    }

    // Xây dựng cây menu (Recursive)
    public function getMenuTree($position, $parentId = null, $activeOnly = true) {
        $query = "SELECT * FROM menus WHERE position = ? AND ";
        if ($parentId === null) {
            $query .= "parent_id IS NULL";
        } else {
            $query .= "parent_id = ?";
        }
        
        if ($activeOnly) {
            $query .= " AND status = 1";
        }
        $query .= " ORDER BY sort_order ASC";
        
        $stmt = $this->conn->prepare($query);
        if ($parentId === null) {
            $stmt->bind_param("s", $position);
        } else {
            $stmt->bind_param("si", $position, $parentId);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $menus = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Đệ quy lấy con
                $row['children'] = $this->getMenuTree($position, $row['id'], $activeOnly);
                $menus[] = $row;
            }
        }
        $stmt->close();
        return $menus;
    }

    // Để giữ tương thích ngược nếu có chỗ nào gọi
    public function getMenusByPosition($position, $activeOnly = true) {
        return $this->getMenuTree($position, null, $activeOnly);
    }

    public function getMenuById($id) {
        $query = "SELECT * FROM menus WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $menu = false;
        if ($result && $result->num_rows > 0) {
            $menu = $result->fetch_assoc();
        }
        $stmt->close();
        return $menu;
    }

    public function addMenu($title, $url, $position, $sort_order, $status, $parent_id = null, $target = '_self') {
        $query = "INSERT INTO menus (parent_id, title, url, target, position, sort_order, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        // mysqli bind_param doesn't support named params. 
        // We have: parent_id(i), title(s), url(s), target(s), position(s), sort_order(i), status(i) -> isssbii? NO.
        // i for integer, s for string. So: issssii. But parent_id can be null.
        // If parent_id is empty string or 0, we should set it to NULL.
        
        $pId = (!empty($parent_id) && $parent_id > 0) ? (int)$parent_id : null;
        
        $stmt->bind_param("issssii", $pId, $title, $url, $target, $position, $sort_order, $status);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function updateMenu($id, $title, $url, $position, $sort_order, $status, $parent_id = null, $target = '_self') {
        $query = "UPDATE menus 
                  SET parent_id = ?, title = ?, url = ?, target = ?, position = ?, sort_order = ?, status = ? 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $pId = (!empty($parent_id) && $parent_id > 0) ? (int)$parent_id : null;
        
        $stmt->bind_param("issssiii", $pId, $title, $url, $target, $position, $sort_order, $status, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function deleteMenu($id) {
        $query = "DELETE FROM menus WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>
