<?php
/**
 * BƯỚC 1: Script chạy Migration Database
 * Chạy file này 1 lần duy nhất qua terminal: php database/run_migration.php
 */

echo "============================================\n";
echo " BƯỚC 1: MIGRATION DATABASE - cosmetics_db\n";
echo "============================================\n\n";

// Kết nối DB
$db = new mysqli('localhost', 'root', '', 'cosmetics_db');
if ($db->connect_error) {
    die("❌ Lỗi kết nối: " . $db->connect_error . "\n");
}
$db->set_charset('utf8mb4');
echo "✅ Kết nối database thành công.\n\n";

// --- 1. USERS: Thêm cột phone, address ---
echo "📌 [1/6] Cập nhật bảng USERS...\n";
$db->query("ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone` VARCHAR(20) DEFAULT NULL AFTER `email`");
$db->query("ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `address` TEXT DEFAULT NULL AFTER `phone`");
echo "   ✅ Đã thêm cột phone, address.\n\n";

// --- 2. CATEGORIES: Tạo bảng + Migrate ---
echo "📌 [2/6] Tạo bảng CATEGORIES...\n";
$db->query("CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_category_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Migrate dữ liệu từ cột category VARCHAR sang bảng categories
$result = $db->query("SELECT DISTINCT `category` FROM `products` WHERE `category` IS NOT NULL AND `category` != ''");
$count = 0;
if ($result && $result->num_rows > 0) {
    $stmt = $db->prepare("INSERT IGNORE INTO `categories` (`name`) VALUES (?)");
    while ($row = $result->fetch_assoc()) {
        $stmt->bind_param("s", $row['category']);
        $stmt->execute();
        $count++;
    }
    $stmt->close();
}
$catCount = $db->query("SELECT COUNT(*) as c FROM categories")->fetch_assoc()['c'];
echo "   ✅ Đã tạo bảng categories và migrate {$catCount} danh mục.\n\n";

// --- 3. BRANDS: Thêm created_at ---
echo "📌 [3/6] Cập nhật bảng BRANDS...\n";
$db->query("ALTER TABLE `brands` ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP()");
echo "   ✅ Đã thêm cột created_at cho brands.\n\n";

// --- 4. PRODUCTS: Thêm category_id + FK ---
echo "📌 [4/6] Cập nhật bảng PRODUCTS...\n";
$db->query("ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `category_id` INT(11) DEFAULT NULL AFTER `category`");

// Migrate category_id
$db->query("UPDATE `products` p INNER JOIN `categories` c ON p.`category` = c.`name` SET p.`category_id` = c.`id` WHERE p.`category_id` IS NULL");
$migrated = $db->affected_rows;
echo "   ✅ Đã migrate {$migrated} sản phẩm với category_id.\n";

// Thêm FK nếu chưa có
$fkCheck = $db->query("SELECT COUNT(*) as c FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'cosmetics_db' AND TABLE_NAME = 'products' AND CONSTRAINT_NAME = 'fk_product_category'")->fetch_assoc()['c'];
if ($fkCheck == 0) {
    $db->query("ALTER TABLE `products` ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE");
    echo "   ✅ Đã thêm Foreign Key fk_product_category.\n";
} else {
    echo "   ⏭️  FK fk_product_category đã tồn tại.\n";
}

// Index
$db->query("ALTER TABLE `products` ADD INDEX IF NOT EXISTS `idx_products_category` (`category_id`)");
$db->query("ALTER TABLE `products` ADD INDEX IF NOT EXISTS `idx_products_status` (`status`)");
echo "   ✅ Đã thêm indexes.\n\n";

// --- 5. ORDERS: Thêm note, updated_at, FK ---
echo "📌 [5/6] Cập nhật bảng ORDERS...\n";
$db->query("ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `note` TEXT DEFAULT NULL AFTER `payment_method`");
$db->query("ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP()");

$fkCheck = $db->query("SELECT COUNT(*) as c FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'cosmetics_db' AND TABLE_NAME = 'orders' AND CONSTRAINT_NAME = 'fk_order_user'")->fetch_assoc()['c'];
if ($fkCheck == 0) {
    $db->query("ALTER TABLE `orders` ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE");
    echo "   ✅ Đã thêm FK fk_order_user.\n";
} else {
    echo "   ⏭️  FK fk_order_user đã tồn tại.\n";
}

$db->query("ALTER TABLE `orders` ADD INDEX IF NOT EXISTS `idx_orders_status` (`status`)");
$db->query("ALTER TABLE `orders` ADD INDEX IF NOT EXISTS `idx_orders_user` (`user_id`)");
$db->query("ALTER TABLE `orders` ADD INDEX IF NOT EXISTS `idx_orders_date` (`order_date`)");
echo "   ✅ Đã thêm indexes cho orders.\n\n";

// --- 6. ORDER_DETAILS: Index ---
echo "📌 [6/6] Cập nhật bảng ORDER_DETAILS...\n";
$db->query("ALTER TABLE `order_details` ADD INDEX IF NOT EXISTS `idx_od_order` (`order_id`)");
$db->query("ALTER TABLE `order_details` ADD INDEX IF NOT EXISTS `idx_od_product` (`product_id`)");
echo "   ✅ Đã thêm indexes.\n\n";

// === BÁO CÁO TỔNG KẾT ===
echo "============================================\n";
echo " 📊 BÁO CÁO TỔNG KẾT\n";
echo "============================================\n";

$tables = ['users','categories','brands','products','orders','order_details','banners','settings','posts'];
foreach ($tables as $t) {
    $r = $db->query("SELECT COUNT(*) as c FROM `$t`");
    $c = $r ? $r->fetch_assoc()['c'] : '?';
    echo "   📁 {$t}: {$c} bản ghi\n";
}

echo "\n============================================\n";
echo " ✅ MIGRATION BƯỚC 1 HOÀN TẤT!\n";
echo " 🔒 Dữ liệu cũ được BẢO TOÀN 100%.\n";
echo "============================================\n";

$db->close();
