-- =======================================================
-- Butter Property - Database Schema & Initial Seed
-- butterproperty.com
-- =======================================================

CREATE DATABASE IF NOT EXISTS `butter_property` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `butter_property`;

-- -------------------------------------------------------
-- Table structure for `properties`
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `properties` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `slug` VARCHAR(150) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `tagline` VARCHAR(255) DEFAULT '',
  `neighborhood` VARCHAR(150) DEFAULT '',
  `city` VARCHAR(100) DEFAULT '',
  `country` VARCHAR(100) DEFAULT '',
  `region` ENUM('dubai', 'india') NOT NULL DEFAULT 'dubai',
  `region_label` VARCHAR(100) DEFAULT '',
  `flag` VARCHAR(10) DEFAULT '🇦🇪',
  `type` VARCHAR(100) DEFAULT 'Penthouses',
  `tier` VARCHAR(50) DEFAULT 'tier-3',
  `config` VARCHAR(150) DEFAULT '',
  `bedrooms` INT UNSIGNED DEFAULT 0,
  `bathrooms` INT UNSIGNED DEFAULT 0,
  `size_sqft` INT UNSIGNED DEFAULT 0,
  `price_aed` DECIMAL(15,2) DEFAULT 0.00,
  `price_inr` DECIMAL(15,2) DEFAULT 0.00,
  `badge` VARCHAR(100) DEFAULT 'Trophy Asset',
  `developer` VARCHAR(150) DEFAULT '',
  `completion` VARCHAR(150) DEFAULT '',
  `ownership` VARCHAR(150) DEFAULT '',
  `visa_eligible` VARCHAR(150) DEFAULT '',
  `rental_yield` VARCHAR(100) DEFAULT '',
  `service_charges` VARCHAR(100) DEFAULT '',
  `main_image` TEXT,
  `gallery_json` LONGTEXT,
  `description` LONGTEXT,
  `highlights_json` LONGTEXT,
  `amenities_json` LONGTEXT,
  `financials_json` LONGTEXT,
  `status` ENUM('Active', 'Off-Market', 'Reserved') DEFAULT 'Active',
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_region` (`region`),
  INDEX `idx_status` (`status`),
  INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Table structure for `leads` (Investor Inquiries & CRM)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leads` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `lead_id` VARCHAR(60) NOT NULL UNIQUE,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(60) DEFAULT '',
  `property_id` VARCHAR(50) DEFAULT '',
  `property_title` VARCHAR(255) DEFAULT '',
  `region` VARCHAR(100) DEFAULT '',
  `timeline` VARCHAR(100) DEFAULT '',
  `estimated_budget` VARCHAR(100) DEFAULT '',
  `notes` TEXT,
  `status` ENUM('New Inquiry', 'NDA Sent', 'Dossier Dispatched', 'Private Inspection', 'Closed Won', 'Archived') DEFAULT 'New Inquiry',
  `source` VARCHAR(100) DEFAULT 'Property Dossier Form (PHP)',
  `ip_address` VARCHAR(45) DEFAULT '',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
