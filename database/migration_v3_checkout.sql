-- ================================================================
-- MIGRATION V3: Nang cap Quy trinh Checkout & Don hang
-- Du an: Glow Cosmetics MVC
-- Phien ban: 3.0
-- Ngay: 2026-07-08
-- Mo ta: Chuan hoa bang orders, tao bang order_invoices,
--         bo sung payment_method day du (COD, Bank, ZaloPay, MoMo)
--
-- LUU Y QUAN TRONG:
--   - Script nay an toan de chay nhieu lan (idempotent)
--   - Su dung ADD COLUMN IF NOT EXISTS de khong bao loi neu cot da co
--   - Khong xoa bat ky du lieu nao hien co
--   - Chay SAU migration_step1.sql va migration_v2_ecommerce.sql
-- ================================================================

USE cosmetics_db;

SET FOREIGN_KEY_CHECKS = 0;

-- ================================================================
-- PHAN 1: NANG CAP BANG `orders`
-- Dong bo & bo sung cac cot can thiet cho checkout moi
-- ================================================================

ALTER TABLE `orders`
    MODIFY COLUMN `payment_method`
        ENUM('COD', 'bank_transfer', 'zalopay', 'momo')
        DEFAULT 'COD'
        COMMENT 'Phuong thuc thanh toan: COD / Chuyen khoan / ZaloPay / MoMo',

    ADD COLUMN IF NOT EXISTS `delivery_method`
        ENUM('shipping', 'pickup')
        DEFAULT 'shipping'
        COMMENT 'shipping = Giao tan noi | pickup = Lay tai cua hang'
        AFTER `payment_method`,

    ADD COLUMN IF NOT EXISTS `store_id`
        INT DEFAULT NULL
        COMMENT 'FK -> stores.id (chi co khi delivery_method = pickup)'
        AFTER `delivery_method`,

    ADD COLUMN IF NOT EXISTS `note`
        TEXT DEFAULT NULL
        COMMENT 'Ghi chu cua khach hang'
        AFTER `store_id`,

    ADD COLUMN IF NOT EXISTS `customer_email`
        VARCHAR(100) DEFAULT NULL
        COMMENT 'Email nguoi dat hang'
        AFTER `note`,

    ADD COLUMN IF NOT EXISTS `customer_city`
        VARCHAR(100) DEFAULT NULL
        COMMENT 'Tinh/Thanh pho giao hang'
        AFTER `customer_email`,

    ADD COLUMN IF NOT EXISTS `customer_district`
        VARCHAR(100) DEFAULT NULL
        COMMENT 'Quan/Huyen giao hang'
        AFTER `customer_city`,

    ADD COLUMN IF NOT EXISTS `customer_ward`
        VARCHAR(100) DEFAULT NULL
        COMMENT 'Phuong/Xa giao hang'
        AFTER `customer_district`,

    ADD COLUMN IF NOT EXISTS `subtotal`
        DECIMAL(12, 0) DEFAULT 0
        COMMENT 'Tam tinh: Tong gia tri san pham (truoc giam gia)'
        AFTER `customer_ward`,

    ADD COLUMN IF NOT EXISTS `coupon_code`
        VARCHAR(50) DEFAULT NULL
        COMMENT 'Ma coupon da ap dung (luu de tra cuu sau)'
        AFTER `subtotal`,

    ADD COLUMN IF NOT EXISTS `coupon_discount`
        DECIMAL(12, 0) DEFAULT 0
        COMMENT 'So tien duoc giam boi ma coupon (VND)'
        AFTER `coupon_code`,

    ADD COLUMN IF NOT EXISTS `member_discount`
        DECIMAL(12, 0) DEFAULT 0
        COMMENT 'So tien duoc giam do hang thanh vien (VND)'
        AFTER `coupon_discount`,

    ADD COLUMN IF NOT EXISTS `discount_amount`
        DECIMAL(12, 0) DEFAULT 0
        COMMENT 'Tong so tien giam gia (coupon + member)'
        AFTER `member_discount`,

    ADD COLUMN IF NOT EXISTS `shipping_fee`
        DECIMAL(12, 0) DEFAULT 0
        COMMENT 'Phi van chuyen (0 neu mien phi hoac pickup)'
        AFTER `discount_amount`,

    ADD COLUMN IF NOT EXISTS `vat_amount`
        DECIMAL(12, 0) DEFAULT 0
        COMMENT 'Tien thue VAT (tinh % tren tong sau giam gia)'
        AFTER `shipping_fee`,

    ADD COLUMN IF NOT EXISTS `vat_requested`
        TINYINT(1) DEFAULT 0
        COMMENT '1 = Khach yeu cau xuat hoa don VAT, 0 = Khong'
        AFTER `vat_amount`,

    ADD COLUMN IF NOT EXISTS `vat_company_name`
        VARCHAR(255) DEFAULT NULL
        COMMENT 'Ten cong ty tren hoa don VAT'
        AFTER `vat_requested`,

    ADD COLUMN IF NOT EXISTS `vat_tax_code`
        VARCHAR(50) DEFAULT NULL
        COMMENT 'Ma so thue tren hoa don VAT'
        AFTER `vat_company_name`,

    ADD COLUMN IF NOT EXISTS `vat_company_address`
        TEXT DEFAULT NULL
        COMMENT 'Dia chi cong ty tren hoa don VAT'
        AFTER `vat_tax_code`,

    ADD COLUMN IF NOT EXISTS `points_earned`
        INT DEFAULT 0
        COMMENT 'Diem tich luy duoc tu don hang nay'
        AFTER `vat_company_address`,

    ADD COLUMN IF NOT EXISTS `updated_at`
        TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP()
        COMMENT 'Thoi diem cap nhat don hang gan nhat'
        AFTER `points_earned`;

-- ================================================================
-- PHAN 2: THEM INDEX TOI UU CHO BANG `orders`
-- ================================================================

ALTER TABLE `orders`
    ADD INDEX IF NOT EXISTS `idx_orders_status`      (`status`),
    ADD INDEX IF NOT EXISTS `idx_orders_user_id`     (`user_id`),
    ADD INDEX IF NOT EXISTS `idx_orders_order_date`  (`order_date`),
    ADD INDEX IF NOT EXISTS `idx_orders_store_id`    (`store_id`),
    ADD INDEX IF NOT EXISTS `idx_orders_coupon_code` (`coupon_code`);

-- ================================================================
-- PHAN 3: TAO BANG `order_invoices`
-- Luu thong tin xuat hoa don VAT theo yeu cau khach hang
-- ================================================================

CREATE TABLE IF NOT EXISTS `order_invoices` (
    `id`              INT NOT NULL AUTO_INCREMENT,
    `order_id`        INT NOT NULL                  COMMENT 'FK -> orders.id (1-1)',
    `company_name`    VARCHAR(255) NOT NULL          COMMENT 'Ten cong ty / don vi xuat hoa don',
    `tax_code`        VARCHAR(50) NOT NULL           COMMENT 'Ma so thue (MST)',
    `company_address` TEXT NOT NULL                 COMMENT 'Dia chi cong ty tren hoa don',
    `company_email`   VARCHAR(100) DEFAULT NULL      COMMENT 'Email nhan hoa don dien tu (tuy chon)',
    `invoice_status`  ENUM('pending', 'issued', 'cancelled')
                      DEFAULT 'pending'             COMMENT 'pending=Cho xuat | issued=Da xuat | cancelled=Huy',
    `issued_at`       DATETIME DEFAULT NULL          COMMENT 'Thoi diem hoa don duoc xuat chinh thuc',
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(),

    PRIMARY KEY (`id`),
    UNIQUE KEY  `uk_invoice_order` (`order_id`)     COMMENT '1 don hang chi co 1 hoa don VAT',

    CONSTRAINT `fk_invoice_order`
        FOREIGN KEY (`order_id`)
        REFERENCES `orders` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Thong tin hoa don VAT theo yeu cau khach hang';

-- ================================================================
-- PHAN 4: THEM FK LIEN KET orders.store_id -> stores.id
-- ================================================================

SET @store_fk_exists = (
    SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = 'cosmetics_db'
      AND TABLE_NAME         = 'orders'
      AND CONSTRAINT_NAME    = 'fk_order_store'
);
SET @store_table_exists = (
    SELECT COUNT(*) FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = 'cosmetics_db'
      AND TABLE_NAME   = 'stores'
);

SET @add_store_fk = IF(
    @store_fk_exists = 0 AND @store_table_exists > 0,
    'ALTER TABLE `orders` ADD CONSTRAINT `fk_order_store` FOREIGN KEY (`store_id`) REFERENCES `stores`(`id`) ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT "FK fk_order_store already exists or stores table missing - skipped" AS info'
);
PREPARE _stmt FROM @add_store_fk;
EXECUTE _stmt;
DEALLOCATE PREPARE _stmt;

-- ================================================================
-- PHAN 5: CAP NHAT BANG `settings`
-- Them cac cau hinh lien quan den checkout
-- ================================================================

CREATE TABLE IF NOT EXISTS `settings` (
    `id`            INT NOT NULL AUTO_INCREMENT,
    `setting_key`   VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT DEFAULT NULL,
    `setting_name`  VARCHAR(255) DEFAULT NULL,
    `updated_at`    TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`, `setting_name`) VALUES
('vat_enabled',          '0',                           'Bat/Tat tinh thue VAT tren don hang'),
('vat_percent',          '10',                          'Phan tram thue VAT (%)'),
('shipping_fee_default', '30000',                       'Phi van chuyen mac dinh (VND)'),
('free_shipping_min',    '500000',                      'Mien phi van chuyen khi don tu (VND)'),
('points_per_1000',      '1',                           'So diem tich luy cho moi 1.000d chi tieu'),
('bank_name',            'Vietcombank',                 'Ten ngan hang nhan chuyen khoan'),
('bank_account_number',  '1234567890',                  'So tai khoan ngan hang'),
('bank_account_name',    'CONG TY TNHH GLOW COSMETICS', 'Ten chu tai khoan'),
('bank_branch',          'Chi nhanh Ha Noi',            'Chi nhanh ngan hang');

SET FOREIGN_KEY_CHECKS = 1;

-- ================================================================
-- KIEM TRA KET QUA
-- ================================================================
-- SHOW COLUMNS FROM orders;
-- SHOW CREATE TABLE order_invoices;
-- SELECT setting_key, setting_value FROM settings;

SELECT 'Migration V3 (Checkout Upgrade) completed successfully!' AS status;
