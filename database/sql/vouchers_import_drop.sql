-- vouchers_import_drop.sql
-- WARNING: This file will DROP existing tables named vouchers, voucher_lines, invoices, customers
-- Use only on a test or disposable database. Do NOT run on production unless you intend to erase those tables.

-- 0) Use your database
-- You can uncomment and set your DB name, or select the DB in phpMyAdmin before running
-- USE `your_database_name`;

-- 1) Drop foreign keys and tables if they exist (order matters)
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `voucher_lines`;
DROP TABLE IF EXISTS `vouchers`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `customers`;
SET FOREIGN_KEY_CHECKS=1;

-- 2) Create vouchers table
CREATE TABLE `vouchers` (
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
CREATE TABLE `voucher_lines` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `voucher_id` BIGINT UNSIGNED NOT NULL,
  `account` VARCHAR(255) DEFAULT NULL,
  `debit` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `credit` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  INDEX (`voucher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `voucher_lines` ADD CONSTRAINT `fk_voucher_lines_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers`(`id`) ON DELETE CASCADE;

-- 4) Create simple customers and invoices sample tables
CREATE TABLE `customers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `balance` DECIMAL(16,2) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `invoices` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `no` VARCHAR(100) DEFAULT NULL,
  `amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `paid_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `customer_id` BIGINT UNSIGNED DEFAULT NULL,
  `date` DATE DEFAULT NULL,
  `due_date` DATE DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `invoices` ADD CONSTRAINT `fk_invoices_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL;

-- 5) Insert sample customers and invoices
INSERT INTO `customers` (`name`,`balance`) VALUES
('Acme A.Ş.',1000.00),
('Beta Ticaret',500.00);

INSERT INTO `invoices` (`no`,`amount`,`paid_amount`,`customer_id`,`date`,`due_date`) VALUES
('INV-1001',1200.00,200.00,1,CURDATE(),DATE_ADD(CURDATE(), INTERVAL 30 DAY)),
('INV-1002',800.00,0.00,1,CURDATE(),DATE_ADD(CURDATE(), INTERVAL 10 DAY));

-- 6) Insert sample vouchers and lines
INSERT INTO `vouchers` (`type`,`date`,`account`,`counterparty`,`amount`,`payment_method`,`notes`) VALUES
('collection',CURDATE(),'Kasa 1','Acme A.Ş.',150.50,'cash','Örnek tahsilat'),
('payment',CURDATE(),'Banka Hesabı','Beta Ticaret',300.00,'bank_transfer','Örnek ödeme'),
('journal',CURDATE(),NULL,NULL,0.00,NULL,'Mahsup örneği');

INSERT INTO `voucher_lines` (`voucher_id`,`account`,`debit`,`credit`,`description`) VALUES
(1,'100-KASA',150.50,0.00,'Tahsilat - nakit'),
(1,'120-ALACAK',0.00,150.50,'Tahsilat eşleme'),
(2,'100-KASA',0.00,300.00,'Ödeme - banka'),
(2,'200-BORC',300.00,0.00,'Ödeme eşleme'),
(3,'300-ISLE',100.00,0.00,'Mahsup satırı 1'),
(3,'400-ISLE',0.00,100.00,'Mahsup satırı 2');

-- 7) Optional: add is_admin to users if table exists
-- Uncomment if you want to ensure users have the is_admin column (non-destructive if column not exists)
-- ALTER TABLE `users` ADD COLUMN `is_admin` TINYINT(1) NOT NULL DEFAULT 0;

-- 8) Final counts
SELECT 'vouchers_count' AS `type`, COUNT(*) AS `cnt` FROM `vouchers`;
SELECT 'voucher_lines_count' AS `type`, COUNT(*) AS `cnt` FROM `voucher_lines`;

-- End
