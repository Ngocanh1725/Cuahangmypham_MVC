-- MIGRATION V5: Nang cap bang menus chuyen nghiep
-- Du an: Glow Cosmetics MVC
-- ================================================================

USE cosmetics_db;

-- 1. Them cot parent_id
ALTER TABLE `menus` 
ADD COLUMN IF NOT EXISTS `parent_id` INT DEFAULT NULL COMMENT 'ID cua menu cha' AFTER `id`;

-- 2. Them khoa ngoai tham chieu den chinh no (ON DELETE CASCADE) de xoa menu cha thi xoa luon menu con
ALTER TABLE `menus` 
ADD CONSTRAINT `fk_menu_parent` FOREIGN KEY (`parent_id`) REFERENCES `menus`(`id`) ON DELETE CASCADE;

-- 3. Them cot target
ALTER TABLE `menus` 
ADD COLUMN IF NOT EXISTS `target` VARCHAR(20) DEFAULT '_self' COMMENT '_self hoac _blank' AFTER `url`;

SELECT 'Migration V5 (Menus Advanced) completed successfully!' AS status;
