-- vouchers_import.sql
-- SQL script to create vouchers tables and insert sample data for phpMyAdmin import
-- Adjust database name if needed, then import via phpMyAdmin or mysql CLI

-- 1) Add is_admin column to users (if users table exists)
ALTER TABLE `users` 
  ADD COLUMN IF NOT EXISTS `is_admin` TINYINT(1) NOT NULL DEFAULT 0;

-- 2) Create vouchers table
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` VARCHAR(50) NOT NULL,
  `date` DATE NOT NULL,
  `account` VARCHAR(255) DEFAULT NULL,
  `counterparty` VARCHAR(255) DEFAULT NULL,
  `amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `payment_method` VARCHAR(100) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_by` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  INDEX (`type`),
  INDEX (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3) Create voucher_lines table
CREATE TABLE IF NOT EXISTS `voucher_lines` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `voucher_id` BIGINT UNSIGNED NOT NULL,
  `account` VARCHAR(255) DEFAULT NULL,
  `debit` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `credit` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  INDEX (`voucher_id`),
  CONSTRAINT `fk_voucher_lines_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4) Create simple customers and invoices sample tables if they don't exist (lightweight, safe)
CREATE TABLE IF NOT EXISTS `customers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `balance` DECIMAL(16,2) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `no` VARCHAR(100) DEFAULT NULL,
  `amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `paid_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `customer_id` BIGINT UNSIGNED DEFAULT NULL,
  `date` DATE DEFAULT NULL,
  `due_date` DATE DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`customer_id`),
  CONSTRAINT IF NOT EXISTS `fk_invoices_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5) Insert example customers and invoices (idempotent behavior: use INSERT IGNORE or check existence)
INSERT INTO `customers` (`id`,`name`,`balance`,`created_at`,`updated_at`)
VALUES
  (1,'Acme A.Ş.',1000.00,NOW(),NOW()),
  (2,'Beta Ticaret',500.00,NOW(),NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO `invoices` (`id`,`no`,`amount`,`paid_amount`,`customer_id`,`date`,`due_date`,`created_at`,`updated_at`)
VALUES
  (1,'INV-1001',1200.00,200.00,1,CURDATE(),DATE_ADD(CURDATE(), INTERVAL 30 DAY),NOW(),NOW()),
  (2,'INV-1002',800.00,0.00,1,CURDATE(),DATE_ADD(CURDATE(), INTERVAL 10 DAY),NOW(),NOW())
ON DUPLICATE KEY UPDATE no=VALUES(no);

-- 6) Insert sample vouchers and lines
INSERT INTO `vouchers` (`id`,`type`,`date`,`account`,`counterparty`,`amount`,`payment_method`,`notes`,`created_by`,`created_at`,`updated_at`)
VALUES
  (1,'collection',CURDATE(),'Kasa 1','Acme A.Ş.',150.50,'cash','Örnek tahsilat',NULL,NOW(),NOW()),
  (2,'payment',CURDATE(),'Banka Hesabı','Beta Ticaret',300.00,'bank_transfer','Örnek ödeme',NULL,NOW(),NOW()),
  (3,'journal',CURDATE(),NULL,NULL,0.00,NULL,'Mahsup örneği',NULL,NOW(),NOW())
ON DUPLICATE KEY UPDATE notes=VALUES(notes);

INSERT INTO `voucher_lines` (`voucher_id`,`account`,`debit`,`credit`,`description`,`created_at`,`updated_at`)
VALUES
  (1,'100-KASA',150.50,0.00,'Tahsilat - nakit',NOW(),NOW()),
  (1,'120-ALACAK',0.00,150.50,'Tahsilat eşleme',NOW(),NOW()),
  (2,'100-KASA',0.00,300.00,'Ödeme - banka',NOW(),NOW()),
  (2,'200- BORC',300.00,0.00,'Ödeme eşleme',NOW(),NOW()),
  (3,'300-ISLE',100.00,0.00,'Mahsup satırı 1',NOW(),NOW()),
  (3,'400-ISLE',0.00,100.00,'Mahsup satırı 2',NOW(),NOW());

-- Optional: show final counts
SELECT 'vouchers_count' AS `type`, COUNT(*) AS `cnt` FROM `vouchers`;
SELECT 'voucher_lines_count' AS `type`, COUNT(*) AS `cnt` FROM `voucher_lines`;

-- End of file
