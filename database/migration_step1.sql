-- ============================================================
-- BƯỚC 1: THIẾT KẾ CƠ SỞ DỮ LIỆU CHUẨN E-COMMERCE
-- Database: cosmetics_db (MariaDB / XAMPP)
-- Chiến lược: ALTER bảng hiện có + Tạo bảng thiếu
-- => BẢO TOÀN toàn bộ 79 sản phẩm và dữ liệu hiện tại
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. BẢNG USERS (Tài khoản người dùng)
-- Hiện tại: thiếu cột address, phone
-- Thêm để phục vụ checkout (auto-fill thông tin giao hàng)
-- ============================================================
ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `phone` VARCHAR(20) DEFAULT NULL AFTER `email`,
    ADD COLUMN IF NOT EXISTS `address` TEXT DEFAULT NULL AFTER `phone`;

-- ============================================================
-- 2. BẢNG CATEGORIES (Danh mục mỹ phẩm)
-- Hiện tại: KHÔNG CÓ bảng này. products.category là VARCHAR.
-- => Tạo bảng categories chuẩn hóa, sau đó migrate dữ liệu.
-- ============================================================
CREATE TABLE IF NOT EXISTS `categories` (
    `id`          INT(11) NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100) NOT NULL COMMENT 'Tên danh mục: Chăm sóc da, Trang điểm...',
    `slug`        VARCHAR(100) DEFAULT NULL COMMENT 'URL-friendly: cham-soc-da',
    `description` TEXT DEFAULT NULL,
    `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_category_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrate dữ liệu: Chuyển các giá trị category VARCHAR hiện có thành bản ghi
INSERT IGNORE INTO `categories` (`name`)
SELECT DISTINCT `category` FROM `products`
WHERE `category` IS NOT NULL AND `category` != '';

-- ============================================================
-- 3. BẢNG BRANDS (Thương hiệu mỹ phẩm)
-- Hiện tại: đã có, thêm cột created_at nếu thiếu
-- ============================================================
ALTER TABLE `brands`
    ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP();

-- ============================================================
-- 4. BẢNG PRODUCTS (Sản phẩm mỹ phẩm) - BẢNG CỐT LÕI
-- Hiện tại: category là VARCHAR, cần thêm category_id FK
-- Chiến lược: Thêm category_id, migrate dữ liệu, giữ cột cũ
-- ============================================================
ALTER TABLE `products`
    ADD COLUMN IF NOT EXISTS `category_id` INT(11) DEFAULT NULL AFTER `category`;

-- Migrate: Cập nhật category_id dựa trên tên category hiện có
UPDATE `products` p
    INNER JOIN `categories` c ON p.`category` = c.`name`
SET p.`category_id` = c.`id`
WHERE p.`category_id` IS NULL;

-- Thêm Foreign Key cho category_id (nếu chưa có)
-- Kiểm tra trước khi thêm constraint
SET @fk_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = 'cosmetics_db'
    AND TABLE_NAME = 'products'
    AND CONSTRAINT_NAME = 'fk_product_category');

SET @sql = IF(@fk_exists = 0,
    'ALTER TABLE `products` ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Thêm INDEX cho các cột thường query
ALTER TABLE `products`
    ADD INDEX IF NOT EXISTS `idx_products_category` (`category_id`),
    ADD INDEX IF NOT EXISTS `idx_products_status` (`status`),
    ADD INDEX IF NOT EXISTS `idx_products_name` (`name`);

-- ============================================================
-- 5. BẢNG ORDERS (Đơn hàng)
-- Hiện tại: đã có, thêm FK user_id & cột note
-- ============================================================
ALTER TABLE `orders`
    ADD COLUMN IF NOT EXISTS `note` TEXT DEFAULT NULL COMMENT 'Ghi chú đơn hàng' AFTER `payment_method`,
    ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP() AFTER `order_date`;

-- Thêm Foreign Key cho user_id (nếu chưa có)
SET @fk_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = 'cosmetics_db'
    AND TABLE_NAME = 'orders'
    AND CONSTRAINT_NAME = 'fk_order_user');

SET @sql = IF(@fk_exists = 0,
    'ALTER TABLE `orders` ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

ALTER TABLE `orders`
    ADD INDEX IF NOT EXISTS `idx_orders_status` (`status`),
    ADD INDEX IF NOT EXISTS `idx_orders_user` (`user_id`),
    ADD INDEX IF NOT EXISTS `idx_orders_date` (`order_date`);

-- ============================================================
-- 6. BẢNG ORDER_DETAILS (Chi tiết đơn hàng)
-- Hiện tại: đã có, đã có FK. Chuẩn rồi, chỉ thêm index.
-- ============================================================
ALTER TABLE `order_details`
    ADD INDEX IF NOT EXISTS `idx_od_order` (`order_id`),
    ADD INDEX IF NOT EXISTS `idx_od_product` (`product_id`);

-- ============================================================
-- 7. BẢNG BANNERS, SETTINGS, POSTS, EVENTS
-- Giữ nguyên - đã chuẩn, không cần thay đổi
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- KIỂM TRA KẾT QUẢ
-- ============================================================
-- Chạy lệnh sau để xác nhận:
-- SELECT * FROM categories;
-- SHOW COLUMNS FROM products;
-- SHOW COLUMNS FROM users;
-- SHOW COLUMNS FROM orders;
