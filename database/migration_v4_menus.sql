-- MIGRATION V4: Them bang menus de quan ly menu dong
-- Du an: Glow Cosmetics MVC
-- ================================================================

USE cosmetics_db;

CREATE TABLE IF NOT EXISTS `menus` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL COMMENT 'Ten menu (VD: Trang chu, Cua hang)',
    `url` VARCHAR(255) NOT NULL COMMENT 'Duong dan URL cua menu',
    `position` ENUM('header', 'footer') DEFAULT 'header' COMMENT 'Vi tri hien thi',
    `sort_order` INT DEFAULT 0 COMMENT 'Thu tu sap xep (nho hien thi truoc)',
    `status` TINYINT(1) DEFAULT 1 COMMENT '1 = Hien thi, 0 = An',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chen du lieu mau (cac menu hien tai dang duoc hardcode tren Header)
INSERT IGNORE INTO `menus` (`title`, `url`, `position`, `sort_order`, `status`) VALUES
('Trang chủ', 'index.php', 'header', 1, 1),
('Cửa hàng', 'index.php?controller=product&action=index', 'header', 2, 1),
('Thương hiệu', 'index.php?controller=brand&action=index', 'header', 3, 1),
('Hệ thống', 'index.php?controller=page&action=stores', 'header', 4, 1),
('Tạp chí', 'index.php?controller=page&action=blog', 'header', 5, 1);

-- Chen du lieu mau cho Footer
INSERT IGNORE INTO `menus` (`title`, `url`, `position`, `sort_order`, `status`) VALUES
('Về Glow Cosmetics', 'index.php?controller=page&action=about', 'footer', 1, 1),
('Câu chuyện thương hiệu', 'index.php?controller=page&action=story', 'footer', 2, 1),
('Tuyển dụng', 'index.php?controller=page&action=careers', 'footer', 3, 1),
('Chính sách giao hàng', 'index.php?controller=page&action=shipping', 'footer', 4, 1),
('Chính sách đổi trả', 'index.php?controller=page&action=returns', 'footer', 5, 1),
('Chính sách bảo mật', 'index.php?controller=page&action=privacy', 'footer', 6, 1),
('Điều khoản sử dụng', 'index.php?controller=page&action=terms', 'footer', 7, 1);

SELECT 'Migration V4 (Menus) completed successfully!' AS status;
