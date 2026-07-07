-- ================================================================
-- MIGRATION V2: Nâng cấp E-commerce Cao cấp
-- Dự án: Glow Cosmetics MVC
-- Ngày: 2026-07-07
-- Mô tả: Thêm Hạng thành viên, Mã giảm giá, VAT, Store Pickup
-- ================================================================

USE cosmetics_db;

-- ================================================================
-- 1. BẢNG MEMBERSHIP_TIERS (Cấp bậc thành viên)
-- Định nghĩa các hạng: Bronze, Silver, Gold, Platinum...
-- Admin có thể CRUD tự do, set % giảm giá cho mỗi hạng
-- ================================================================
CREATE TABLE IF NOT EXISTS membership_tiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tier_name VARCHAR(50) NOT NULL COMMENT 'Tên hạng: Bronze, Silver, Gold...',
    min_points INT DEFAULT 0 COMMENT 'Số điểm tối thiểu để đạt hạng',
    discount_percent DECIMAL(5,2) DEFAULT 0.00 COMMENT '% giảm giá cho hạng này',
    color_code VARCHAR(20) DEFAULT '#cd7f32' COMMENT 'Mã màu thẻ thành viên',
    icon VARCHAR(50) DEFAULT 'fas fa-medal' COMMENT 'Font Awesome icon class',
    description TEXT COMMENT 'Mô tả quyền lợi hạng',
    sort_order INT DEFAULT 0 COMMENT 'Thứ tự sắp xếp',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mặc định cho 4 hạng thành viên
INSERT INTO membership_tiers (tier_name, min_points, discount_percent, color_code, icon, description, sort_order) VALUES
('Bronze', 0, 0.00, '#cd7f32', 'fas fa-medal', 'Hạng khởi đầu - Tích điểm mỗi đơn hàng', 1),
('Silver', 500, 3.00, '#c0c0c0', 'fas fa-award', 'Giảm 3% mọi đơn hàng, ưu tiên deal hot', 2),
('Gold', 2000, 5.00, '#ffd700', 'fas fa-crown', 'Giảm 5% mọi đơn hàng, miễn phí ship đơn từ 300K', 3),
('Platinum', 5000, 10.00, '#e5e4e2', 'fas fa-gem', 'Giảm 10% mọi đơn hàng, miễn phí ship toàn bộ, quà sinh nhật', 4);

-- ================================================================
-- 2. CẬP NHẬT BẢNG USERS
-- Thêm cột: tier_id, points, avatar
-- (phone đã có sẵn)
-- ================================================================
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS tier_id INT DEFAULT 1 COMMENT 'FK -> membership_tiers.id',
    ADD COLUMN IF NOT EXISTS points INT DEFAULT 0 COMMENT 'Điểm tích lũy',
    ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) DEFAULT NULL COMMENT 'Ảnh đại diện',
    ADD COLUMN IF NOT EXISTS date_of_birth DATE DEFAULT NULL COMMENT 'Ngày sinh';

-- ================================================================
-- 3. BẢNG COUPONS (Mã giảm giá)
-- Hỗ trợ cả % và số tiền cố định
-- ================================================================
CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã coupon: VD GLOW50, SUMMER30...',
    type ENUM('percent', 'fixed') DEFAULT 'percent' COMMENT 'Loại giảm: % hoặc số tiền',
    discount_value DECIMAL(10,2) NOT NULL COMMENT 'Giá trị giảm (% hoặc VND)',
    min_order_value DECIMAL(10,0) DEFAULT 0 COMMENT 'Đơn hàng tối thiểu để áp dụng',
    max_discount DECIMAL(10,0) DEFAULT NULL COMMENT 'Giảm tối đa (cho loại %)',
    usage_limit INT DEFAULT NULL COMMENT 'Số lần sử dụng tối đa (NULL = không giới hạn)',
    used_count INT DEFAULT 0 COMMENT 'Số lần đã sử dụng',
    start_date DATETIME DEFAULT NULL COMMENT 'Ngày bắt đầu hiệu lực',
    end_date DATETIME DEFAULT NULL COMMENT 'Ngày hết hạn',
    is_active TINYINT(1) DEFAULT 1 COMMENT '1 = Hoạt động, 0 = Tắt',
    description VARCHAR(255) DEFAULT NULL COMMENT 'Mô tả cho khách hàng',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu
INSERT INTO coupons (code, type, discount_value, min_order_value, max_discount, usage_limit, start_date, end_date, description) VALUES
('WELCOME10', 'percent', 10.00, 200000, 50000, 100, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 'Giảm 10% cho khách hàng mới (tối đa 50K)'),
('GLOW50K', 'fixed', 50000.00, 500000, NULL, 50, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 'Giảm 50.000đ cho đơn từ 500K'),
('SUMMER2026', 'percent', 15.00, 300000, 100000, NULL, '2026-06-01 00:00:00', '2026-08-31 23:59:59', 'Giảm 15% mùa hè 2026 (tối đa 100K)');

-- ================================================================
-- 4. BẢNG STORES (Chi nhánh cửa hàng - cho Lấy tại store)
-- ================================================================
CREATE TABLE IF NOT EXISTS stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COMMENT 'Tên chi nhánh',
    address TEXT NOT NULL COMMENT 'Địa chỉ đầy đủ',
    phone VARCHAR(20) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL COMMENT 'Tỉnh/Thành phố',
    open_hours VARCHAR(100) DEFAULT '08:00 - 21:30' COMMENT 'Giờ mở cửa',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu chi nhánh
INSERT INTO stores (name, address, phone, city, open_hours) VALUES
('Glow Bờ Triệu', '191 Bà Triệu, Hai Bà Trưng, Hà Nội', '024 3943 1234', 'Hà Nội', '08:00 - 22:00'),
('Glow Cầu Giấy', '168 Xuân Thủy, Cầu Giấy, Hà Nội', '024 3795 5678', 'Hà Nội', '08:30 - 21:30'),
('Glow Nguyễn Huệ', '99 Nguyễn Huệ, Quận 1, TP.HCM', '028 3822 9999', 'TP. Hồ Chí Minh', '09:00 - 22:00'),
('Glow Đà Nẵng', '55 Lê Duẩn, Hải Châu, Đà Nẵng', '0236 382 1111', 'Đà Nẵng', '08:00 - 21:00');

-- ================================================================
-- 5. CẬP NHẬT BẢNG ORDERS
-- Thêm các cột mới cho quy trình checkout phức tạp
-- ================================================================
ALTER TABLE orders
    ADD COLUMN IF NOT EXISTS delivery_method ENUM('shipping', 'pickup') DEFAULT 'shipping' COMMENT 'Giao hàng / Lấy tại store',
    ADD COLUMN IF NOT EXISTS store_id INT DEFAULT NULL COMMENT 'FK -> stores.id (nếu pickup)',
    ADD COLUMN IF NOT EXISTS customer_email VARCHAR(100) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS customer_city VARCHAR(100) DEFAULT NULL COMMENT 'Tỉnh/Thành phố',
    ADD COLUMN IF NOT EXISTS coupon_code VARCHAR(50) DEFAULT NULL COMMENT 'Mã giảm giá đã áp dụng',
    ADD COLUMN IF NOT EXISTS coupon_discount DECIMAL(10,0) DEFAULT 0 COMMENT 'Số tiền giảm từ coupon',
    ADD COLUMN IF NOT EXISTS member_discount DECIMAL(10,0) DEFAULT 0 COMMENT 'Số tiền giảm từ hạng thành viên',
    ADD COLUMN IF NOT EXISTS shipping_fee DECIMAL(10,0) DEFAULT 0 COMMENT 'Phí vận chuyển',
    ADD COLUMN IF NOT EXISTS subtotal DECIMAL(10,0) DEFAULT 0 COMMENT 'Tạm tính (trước giảm giá)',
    ADD COLUMN IF NOT EXISTS vat_amount DECIMAL(10,0) DEFAULT 0 COMMENT 'Tiền thuế VAT',
    ADD COLUMN IF NOT EXISTS vat_requested TINYINT(1) DEFAULT 0 COMMENT 'Khách yêu cầu xuất hóa đơn VAT',
    ADD COLUMN IF NOT EXISTS vat_company_name VARCHAR(255) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS vat_tax_code VARCHAR(50) DEFAULT NULL COMMENT 'Mã số thuế',
    ADD COLUMN IF NOT EXISTS vat_company_address TEXT DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS points_earned INT DEFAULT 0 COMMENT 'Điểm tích lũy từ đơn hàng này';

-- ================================================================
-- 6. THÊM CẤU HÌNH VAT VÀO BẢNG SETTINGS
-- ================================================================
INSERT IGNORE INTO settings (setting_key, setting_value, setting_name) VALUES
('vat_enabled', '0', 'Bật/Tắt thuế VAT'),
('vat_percent', '10', 'Phần trăm thuế VAT'),
('shipping_fee_default', '30000', 'Phí vận chuyển mặc định (VND)'),
('free_shipping_min', '500000', 'Miễn phí ship cho đơn từ (VND)'),
('points_per_1000', '1', 'Số điểm tích lũy cho mỗi 1.000đ chi tiêu');

-- ================================================================
-- HOÀN TẤT MIGRATION V2
-- ================================================================
SELECT 'Migration V2 completed successfully!' AS status;
