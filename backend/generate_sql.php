<?php
/**
 * Butter Property - Generate Full phpMyAdmin SQL Script
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Property.php';

$jsonFile = __DIR__ . '/data/properties.json';
if (!file_exists($jsonFile)) {
    die("Error: properties.json not found\n");
}

$properties = json_decode(file_get_contents($jsonFile), true);
if (!is_array($properties)) {
    die("Error: invalid properties.json\n");
}

function sqlQuote($val) {
    if ($val === null) return "NULL";
    return "'" . addslashes((string)$val) . "'";
}

$sql = "-- =======================================================\n";
$sql .= "-- Butter Property Database Setup for phpMyAdmin\n";
$sql .= "-- Database Name: butter_property\n";
$sql .= "-- Target: MySQL 5.7+ / MariaDB 10.3+ / MySQL 8.x\n";
$sql .= "-- =======================================================\n\n";
$sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
$sql .= "START TRANSACTION;\n";
$sql .= "SET time_zone = \"+00:00\";\n\n";

$sql .= "-- -------------------------------------------------------\n";
$sql .= "-- 1. Create & Select Database\n";
$sql .= "-- -------------------------------------------------------\n";
$sql .= "CREATE DATABASE IF NOT EXISTS `butter_property` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
$sql .= "USE `butter_property`;\n\n";

$sql .= "-- -------------------------------------------------------\n";
$sql .= "-- 2. Table structure for `properties`\n";
$sql .= "-- -------------------------------------------------------\n";
$sql .= "DROP TABLE IF EXISTS `properties`;\n";
$sql .= "CREATE TABLE `properties` (\n";
$sql .= "  `id` VARCHAR(50) NOT NULL PRIMARY KEY,\n";
$sql .= "  `slug` VARCHAR(150) NOT NULL,\n";
$sql .= "  `title` VARCHAR(255) NOT NULL,\n";
$sql .= "  `tagline` VARCHAR(255) DEFAULT '',\n";
$sql .= "  `neighborhood` VARCHAR(150) DEFAULT '',\n";
$sql .= "  `city` VARCHAR(100) DEFAULT '',\n";
$sql .= "  `country` VARCHAR(100) DEFAULT '',\n";
$sql .= "  `region` ENUM('dubai', 'india') NOT NULL DEFAULT 'dubai',\n";
$sql .= "  `region_label` VARCHAR(100) DEFAULT '',\n";
$sql .= "  `flag` VARCHAR(10) DEFAULT '🇦🇪',\n";
$sql .= "  `type` VARCHAR(100) DEFAULT 'Penthouses',\n";
$sql .= "  `tier` VARCHAR(50) DEFAULT 'tier-3',\n";
$sql .= "  `config` VARCHAR(150) DEFAULT '',\n";
$sql .= "  `bedrooms` INT UNSIGNED DEFAULT 0,\n";
$sql .= "  `bathrooms` INT UNSIGNED DEFAULT 0,\n";
$sql .= "  `size_sqft` INT UNSIGNED DEFAULT 0,\n";
$sql .= "  `price_aed` DECIMAL(15,2) DEFAULT 0.00,\n";
$sql .= "  `price_inr` DECIMAL(15,2) DEFAULT 0.00,\n";
$sql .= "  `badge` VARCHAR(100) DEFAULT 'Trophy Asset',\n";
$sql .= "  `developer` VARCHAR(150) DEFAULT '',\n";
$sql .= "  `completion` VARCHAR(150) DEFAULT '',\n";
$sql .= "  `ownership` VARCHAR(150) DEFAULT '',\n";
$sql .= "  `visa_eligible` VARCHAR(150) DEFAULT '',\n";
$sql .= "  `rental_yield` VARCHAR(100) DEFAULT '',\n";
$sql .= "  `service_charges` VARCHAR(100) DEFAULT '',\n";
$sql .= "  `main_image` TEXT,\n";
$sql .= "  `gallery_json` LONGTEXT,\n";
$sql .= "  `description` LONGTEXT,\n";
$sql .= "  `highlights_json` LONGTEXT,\n";
$sql .= "  `amenities_json` LONGTEXT,\n";
$sql .= "  `financials_json` LONGTEXT,\n";
$sql .= "  `status` ENUM('Active', 'Off-Market', 'Reserved') DEFAULT 'Active',\n";
$sql .= "  `sort_order` INT DEFAULT 0,\n";
$sql .= "  `is_active` TINYINT(1) DEFAULT 1,\n";
$sql .= "  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
$sql .= "  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n";
$sql .= "  INDEX `idx_region` (`region`),\n";
$sql .= "  INDEX `idx_status` (`status`),\n";
$sql .= "  INDEX `idx_slug` (`slug`)\n";
$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

$sql .= "-- -------------------------------------------------------\n";
$sql .= "-- 3. Table structure for `leads` (Investor CRM)\n";
$sql .= "-- -------------------------------------------------------\n";
$sql .= "DROP TABLE IF EXISTS `leads`;\n";
$sql .= "CREATE TABLE `leads` (\n";
$sql .= "  `id` INT AUTO_INCREMENT PRIMARY KEY,\n";
$sql .= "  `lead_id` VARCHAR(60) NOT NULL UNIQUE,\n";
$sql .= "  `name` VARCHAR(150) NOT NULL,\n";
$sql .= "  `email` VARCHAR(150) NOT NULL,\n";
$sql .= "  `phone` VARCHAR(60) DEFAULT '',\n";
$sql .= "  `property_id` VARCHAR(50) DEFAULT '',\n";
$sql .= "  `property_title` VARCHAR(255) DEFAULT '',\n";
$sql .= "  `region` VARCHAR(100) DEFAULT '',\n";
$sql .= "  `timeline` VARCHAR(100) DEFAULT '',\n";
$sql .= "  `estimated_budget` VARCHAR(100) DEFAULT '',\n";
$sql .= "  `notes` TEXT,\n";
$sql .= "  `status` ENUM('New Inquiry', 'NDA Sent', 'Dossier Dispatched', 'Private Inspection', 'Closed Won', 'Archived') DEFAULT 'New Inquiry',\n";
$sql .= "  `source` VARCHAR(100) DEFAULT 'Property Dossier Form (PHP)',\n";
$sql .= "  `ip_address` VARCHAR(45) DEFAULT '',\n";
$sql .= "  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
$sql .= "  INDEX `idx_lead_status` (`status`),\n";
$sql .= "  INDEX `idx_lead_prop` (`property_id`)\n";
$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

$sql .= "-- -------------------------------------------------------\n";
$sql .= "-- 4. Seed Data: Active Luxury Portfolio Properties\n";
$sql .= "-- -------------------------------------------------------\n\n";

foreach ($properties as $idx => $p) {
    $galleryJson = json_encode($p['gallery'] ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $highlightsJson = json_encode($p['highlights'] ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $amenitiesJson = json_encode($p['amenities'] ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $financialsJson = json_encode($p['financials'] ?? (object)[], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $sql .= "INSERT INTO `properties` (\n";
    $sql .= "  `id`, `slug`, `title`, `tagline`, `neighborhood`, `city`, `country`, `region`, `region_label`, `flag`,\n";
    $sql .= "  `type`, `tier`, `config`, `bedrooms`, `bathrooms`, `size_sqft`, `price_aed`, `price_inr`, `badge`,\n";
    $sql .= "  `developer`, `completion`, `ownership`, `visa_eligible`, `rental_yield`, `service_charges`, `main_image`,\n";
    $sql .= "  `gallery_json`, `description`, `highlights_json`, `amenities_json`, `financials_json`, `status`, `sort_order`, `is_active`\n";
    $sql .= ") VALUES (\n";
    $sql .= "  " . sqlQuote($p['id']) . ",\n";
    $sql .= "  " . sqlQuote($p['slug']) . ",\n";
    $sql .= "  " . sqlQuote($p['title']) . ",\n";
    $sql .= "  " . sqlQuote($p['tagline']) . ",\n";
    $sql .= "  " . sqlQuote($p['neighborhood']) . ",\n";
    $sql .= "  " . sqlQuote($p['city']) . ",\n";
    $sql .= "  " . sqlQuote($p['country']) . ",\n";
    $sql .= "  " . sqlQuote($p['region']) . ",\n";
    $sql .= "  " . sqlQuote($p['regionLabel']) . ",\n";
    $sql .= "  " . sqlQuote($p['flag']) . ",\n";
    $sql .= "  " . sqlQuote($p['type']) . ",\n";
    $sql .= "  " . sqlQuote($p['tier']) . ",\n";
    $sql .= "  " . sqlQuote($p['config']) . ",\n";
    $sql .= "  " . (int)($p['bedrooms'] ?? 0) . ",\n";
    $sql .= "  " . (int)($p['bathrooms'] ?? 0) . ",\n";
    $sql .= "  " . (int)($p['sizeSqFt'] ?? 0) . ",\n";
    $sql .= "  " . (float)($p['priceAED'] ?? 0) . ",\n";
    $sql .= "  " . (float)($p['priceINR'] ?? 0) . ",\n";
    $sql .= "  " . sqlQuote($p['badge']) . ",\n";
    $sql .= "  " . sqlQuote($p['developer']) . ",\n";
    $sql .= "  " . sqlQuote($p['completion']) . ",\n";
    $sql .= "  " . sqlQuote($p['ownership']) . ",\n";
    $sql .= "  " . sqlQuote($p['visaEligible']) . ",\n";
    $sql .= "  " . sqlQuote($p['rentalYield']) . ",\n";
    $sql .= "  " . sqlQuote($p['serviceCharges']) . ",\n";
    $sql .= "  " . sqlQuote($p['mainImage']) . ",\n";
    $sql .= "  " . sqlQuote($galleryJson) . ",\n";
    $sql .= "  " . sqlQuote($p['description']) . ",\n";
    $sql .= "  " . sqlQuote($highlightsJson) . ",\n";
    $sql .= "  " . sqlQuote($amenitiesJson) . ",\n";
    $sql .= "  " . sqlQuote($financialsJson) . ",\n";
    $sql .= "  " . sqlQuote($p['status'] ?? 'Active') . ",\n";
    $sql .= "  " . ($idx + 1) . ",\n";
    $sql .= "  1\n";
    $sql .= ");\n\n";
}

$sql .= "-- -------------------------------------------------------\n";
$sql .= "-- 5. Initial Sample Leads / Inquiries\n";
$sql .= "-- -------------------------------------------------------\n";
$sql .= "INSERT INTO `leads` (`lead_id`, `name`, `email`, `phone`, `property_id`, `property_title`, `region`, `timeline`, `estimated_budget`, `notes`, `status`, `source`) VALUES\n";
$sql .= "('BP-DEMO-01', 'Rahul Singhania', 'rahul@singhaniaholdings.com', '+971 50 882 1920', 'dxb-01', 'The Sky Palace at One Canal', 'Dubai, UAE', 'Immediate Allocation (30 Days)', 'AED 24.50M', 'Requesting private inspection for sky duplex during DIFC FinTech summit.', 'Dossier Dispatched', 'Property Dossier Form (PHP)'),\n";
$sql .= "('BP-DEMO-02', 'Vikramaditya Roy', 'v.roy@calcutta-equities.in', '+91 98200 11928', 'ind-01', 'Worli Sea Face Signature Duplex', 'Mumbai, India', 'Current Quarter (Q1-Q2 2026)', '₹75.00 Cr', 'Client mandate for unbroken sea-facing penthouse with clear OC.', 'NDA Sent', 'Property Dossier Form (PHP)');\n\n";

$sql .= "COMMIT;\n";

file_put_contents(__DIR__ . '/../butter_property.sql', $sql);
file_put_contents(__DIR__ . '/schema.sql', $sql);

echo "SUCCESS: Generated butter_property.sql (" . strlen($sql) . " bytes)\n";
