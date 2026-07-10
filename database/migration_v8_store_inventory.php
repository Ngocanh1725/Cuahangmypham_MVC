<?php
$conn = new mysqli('localhost', 'root', '', 'cosmetics_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Tạo bảng store_inventory
$sql = "CREATE TABLE IF NOT EXISTS store_inventory (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    store_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    stock INT(11) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_store_product (store_id, product_id),
    FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table store_inventory created successfully.\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// Seed dữ liệu mẫu: Lấy tất cả products và stores để seed.
// Store ID = 1 (Ví dụ cơ sở 1) sẽ có hàng.
// Store ID = 2 (Ví dụ cơ sở 2) sẽ thiếu hàng một số sản phẩm (stock = 0 hoặc 1).
$storesRes = $conn->query("SELECT id FROM stores");
$productsRes = $conn->query("SELECT id FROM products LIMIT 50");

$stores = [];
while($r = $storesRes->fetch_assoc()) $stores[] = $r['id'];

$products = [];
while($r = $productsRes->fetch_assoc()) $products[] = $r['id'];

if (!empty($stores) && !empty($products)) {
    $stmt = $conn->prepare("INSERT IGNORE INTO store_inventory (store_id, product_id, stock) VALUES (?, ?, ?)");
    
    foreach ($stores as $index => $storeId) {
        foreach ($products as $productId) {
            // Cơ sở đầu tiên có nhiều hàng (ví dụ 100)
            // Các cơ sở khác có ít hoặc hết hàng để test (random 0 - 5)
            $stock = ($index == 0) ? 100 : rand(0, 5); 
            $stmt->bind_param("iii", $storeId, $productId, $stock);
            $stmt->execute();
        }
    }
    echo "Seeded store inventory data successfully.\n";
}

$conn->close();
?>
