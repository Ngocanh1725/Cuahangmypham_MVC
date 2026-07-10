-- MIGRATION V8: Them tinh nang dinh kem san pham vao chat
-- Du an: Glow Cosmetics MVC
-- ================================================================

USE cosmetics_db;

-- Them cot product_id vao bang chat_messages
ALTER TABLE `chat_messages` ADD COLUMN `product_id` INT NULL DEFAULT NULL;

-- Them khoa ngoai
ALTER TABLE `chat_messages` ADD CONSTRAINT `fk_chat_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

SELECT 'Migration V8 (Chat Product) completed successfully!' AS status;
