-- ================================================================
-- MIGRATION STEP 3: HỆ THỐNG QUẢN LÝ TỒN KHO & NHÀ CUNG CẤP
-- Dự án: Glow Cosmetics MVC
-- ================================================================

USE cosmetics_db;

SET FOREIGN_KEY_CHECKS = 0;

-- ================================================================
-- 1. BẢNG SUPPLIERS (Nhà cung cấp)
-- ================================================================
CREATE TABLE IF NOT EXISTS `suppliers` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL COMMENT 'Tên nhà cung cấp',
    `phone` VARCHAR(20) DEFAULT NULL COMMENT 'Số điện thoại',
    `email` VARCHAR(100) DEFAULT NULL COMMENT 'Email liên hệ',
    `address` TEXT DEFAULT NULL COMMENT 'Địa chỉ',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu Nhà cung cấp
INSERT IGNORE INTO `suppliers` (`id`, `name`, `phone`, `email`, `address`) VALUES
(1, 'L\'Oréal Việt Nam', '028 3936 9333', 'contact@loreal.vn', 'Tầng 10, Tòa nhà Vincom Center, 72 Lê Thánh Tôn, Quận 1, TP.HCM'),
(2, 'Rohto-Mentholatum VN', '028 3822 9322', 'info@rohto.com.vn', 'Tầng 18, Saigon Trade Center, 37 Tôn Đức Thắng, Quận 1, TP.HCM'),
(3, 'AmorePacific VN', '028 3823 3315', 'support@amorepacific.vn', 'Tầng 4, Tòa nhà Kumho Asiana Plaza, 39 Lê Duẩn, Quận 1, TP.HCM');

-- ================================================================
-- 2. CẬP NHẬT BẢNG PRODUCTS
-- Thêm cột supplier_id
-- ================================================================
ALTER TABLE `products`
    ADD COLUMN IF NOT EXISTS `supplier_id` INT(11) DEFAULT NULL COMMENT 'Nhà cung cấp' AFTER `brand_id`;

-- Thêm Foreign Key cho supplier_id (nếu chưa có)
SET @fk_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = 'cosmetics_db'
    AND TABLE_NAME = 'products'
    AND CONSTRAINT_NAME = 'fk_product_supplier');

SET @sql = IF(@fk_exists = 0,
    'ALTER TABLE `products` ADD CONSTRAINT `fk_product_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ================================================================
-- 3. BẢNG INVENTORY_LOGS (Lịch sử Nhập/Xuất kho)
-- ================================================================
CREATE TABLE IF NOT EXISTS `inventory_logs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `product_id` INT(11) NOT NULL,
    `supplier_id` INT(11) DEFAULT NULL COMMENT 'NULL nếu là xuất kho cho đơn hàng',
    `change_amount` INT(11) NOT NULL COMMENT 'Số lượng thay đổi: + hoặc -',
    `reason` VARCHAR(255) NOT NULL COMMENT 'Lý do: Nhập hàng mới, Đặt hàng #123, Hoàn kho #123...',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_inv_log_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_inv_log_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Migration Step 3 (Inventory & Suppliers) completed successfully!' AS status;
