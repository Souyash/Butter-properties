-- =======================================================
-- Butter Property Database Setup for phpMyAdmin
-- Database Name: butter_property
-- Target: MySQL 5.7+ / MariaDB 10.3+ / MySQL 8.x
-- =======================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- -------------------------------------------------------
-- 1. Create & Select Database
-- -------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `butter_property` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `butter_property`;

-- -------------------------------------------------------
-- 2. Table structure for `properties`
-- -------------------------------------------------------
DROP TABLE IF EXISTS `properties`;
CREATE TABLE `properties` (
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
-- 3. Table structure for `leads` (Investor CRM)
-- -------------------------------------------------------
DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
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
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_lead_status` (`status`),
  INDEX `idx_lead_prop` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 4. Seed Data: Active Luxury Portfolio Properties
-- -------------------------------------------------------

INSERT INTO `properties` (
  `id`, `slug`, `title`, `tagline`, `neighborhood`, `city`, `country`, `region`, `region_label`, `flag`,
  `type`, `tier`, `config`, `bedrooms`, `bathrooms`, `size_sqft`, `price_aed`, `price_inr`, `badge`,
  `developer`, `completion`, `ownership`, `visa_eligible`, `rental_yield`, `service_charges`, `main_image`,
  `gallery_json`, `description`, `highlights_json`, `amenities_json`, `financials_json`, `status`, `sort_order`, `is_active`
) VALUES (
  'dxb-01',
  'the-sky-palace-one-canal',
  'The Sky Palace at One Canal',
  'Dual-Aspect Panoramic Sky Duplex Overlooking Dubai Canal & Burj Khalifa',
  'Dubai Water Canal, Downtown District',
  'Dubai',
  'United Arab Emirates',
  'dubai',
  'Dubai, UAE',
  '🇦🇪',
  'Penthouses',
  'tier-3',
  '5 & 6 Bed Sky Duplex',
  6,
  8,
  11400,
  24500000,
  555000000,
  'Trophy Asset',
  'Firas Nehma & AHS Properties',
  'Q4 2025 (Under Construction / 85% Complete)',
  'Freehold Title Deed (100% Foreign Ownership)',
  'UAE 10-Year Golden Visa Included',
  '7.8% Net Projected Yield',
  'AED 22 / sq.ft per annum',
  'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1600&q=80',
  '[{\"url\":\"https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Exterior Architecture & Water Canal Promontory\",\"caption\":\"Cantilevered private infinity swimming pools on floating terraces.\"},{\"url\":\"https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Double-Height Grand Living Salon\",\"caption\":\"11-meter ceiling heights with book-matched Italian Calacatta marble.\"},{\"url\":\"https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Private Sky Terrace & Heated Pool\",\"caption\":\"Heated zero-edge pool overlooking the entire Burj Khalifa skyline.\"},{\"url\":\"https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Master Bedroom Sanctuary\",\"caption\":\"Custom poliform walk-in dressing suites and private massage lounge.\"},{\"url\":\"https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Bespoke Show & Prep Kitchens\",\"caption\":\"Sub-Zero refrigeration and Gaggenau 400 series touch-screen appliances.\"}]',
  'Positioned directly along the Dubai Water Canal with uninterrupted sightlines extending to the Burj Khalifa and Downtown skyline, The Sky Palace at One Canal redefines trophy duplex living. Designed by globally acclaimed architectural ateliers, each curve and terrace has been engineered to maximize private outdoor living in the sky.

Featuring an ultra-exclusive car lift option delivering vehicles directly to a private glass sky gallery, a private 15-meter cantilevered infinity pool, dual chef show kitchens, and dedicated staff quarters for four personnel, this residence serves as a multi-generational legacy asset.',
  '[\"Private 15-meter cantilevered infinity pool on floating terrace\",\"Full unobstructed Burj Khalifa and Dubai Water Canal skyline views\",\"Dedicated private elevator opening into private foyer\",\"Dual chef kitchens: Gaggenau 400 series and Sub-Zero refrigeration\",\"Rolls-Royce and supercar direct vehicle elevator option\",\"Eligible for immediate UAE 10-Year Golden Visa residency\"]',
  '[{\"icon\":\"fa-water-ladder\",\"name\":\"Private Sky Pool\"},{\"icon\":\"fa-shield-halved\",\"name\":\"24/7 Diplomatic Security\"},{\"icon\":\"fa-bell-concierge\",\"name\":\"Bespoke Rolls-Royce Chauffeur\"},{\"icon\":\"fa-spa\",\"name\":\"Private Steam & Sauna\"},{\"icon\":\"fa-utensils\",\"name\":\"Michelin-Trained Resident Chef\"},{\"icon\":\"fa-square-parking\",\"name\":\"4 Allocated Basement Bays\"}]',
  '{\"annualRentalValueAED\":\"AED 1,911,000 / year\",\"projectedCapRate\":\"7.8% Net\",\"capitalAppreciationForecast\":\"9.5% p.a. over 36 months\",\"taxImplications\":\"0% Capital Gains, 0% Rental Income Tax in UAE\"}',
  'Active',
  1,
  1
);

INSERT INTO `properties` (
  `id`, `slug`, `title`, `tagline`, `neighborhood`, `city`, `country`, `region`, `region_label`, `flag`,
  `type`, `tier`, `config`, `bedrooms`, `bathrooms`, `size_sqft`, `price_aed`, `price_inr`, `badge`,
  `developer`, `completion`, `ownership`, `visa_eligible`, `rental_yield`, `service_charges`, `main_image`,
  `gallery_json`, `description`, `highlights_json`, `amenities_json`, `financials_json`, `status`, `sort_order`, `is_active`
) VALUES (
  'dxb-02',
  'signature-frond-royal-villa-palm-jumeirah',
  'Signature Frond Royal Villa',
  'Private White-Sand Shoreline Estate on Palm Jumeirah Frond N',
  'Palm Jumeirah Frond N',
  'Dubai',
  'United Arab Emirates',
  'dubai',
  'Palm Jumeirah, Dubai',
  '🇦🇪',
  'Private Villas',
  'tier-3',
  '6 Bed Beachfront Estate',
  6,
  9,
  14200,
  48000000,
  1085000000,
  'Private Beach Access',
  'Bespoke Private Commission',
  'Ready for Immediate Key Handover',
  'Freehold Title Deed (100% Foreign Ownership)',
  'UAE 10-Year Golden Visa Included',
  '8.2% Capitalized ROI',
  'AED 16 / sq.ft per annum',
  'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1600&q=80',
  '[{\"url\":\"https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Beachfront Facade & Tropical Landscaping\",\"caption\":\"180 feet of private beachfront with custom travertine architectural fins.\"},{\"url\":\"https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Infinity Lap Pool & Private Shoreline\",\"caption\":\"Heated Olympic-length pool cascading down to calm private turquoise waters.\"},{\"url\":\"https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Grand Rotunda & Modern Living Pavilion\",\"caption\":\"Seamless indoor-outdoor motorized glass pocket doors spanning 24 meters.\"},{\"url\":\"https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Bespoke Dining Suite & Wine Cellar\",\"caption\":\"Climate-controlled 600-bottle glass wine gallery adjacent to dining salon.\"},{\"url\":\"https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Master En-Suite Spa Sanctuary\",\"caption\":\"Freestanding monolithic marble bathtub overlooking the tranquil sea.\"}]',
  'A rare architectural tour de force situated along the exclusive Frond N of Palm Jumeirah. This ultra-private royal beachfront residence commands 180 feet of pristine direct shoreline with calm, swimmable private waters and horizon views of the Dubai Marina skyline.

Boasting over 14,200 sq.ft of hyper-curated interior living, the villa incorporates automated glass walls that slide silently into wall pockets, erasing the boundary between the internal entertaining pavilions and the private palm-fringed sun decks.',
  '[\"180-ft private white-sand beach frontage with direct water access\",\"Private yacht mooring capability directly off the frond buffer\",\"Climate-controlled subterranean car gallery for 6 luxury vehicles\",\"State-of-the-art IMAX private screening theatre with Dolby Atmos\",\"Full smart-home integration with Savant control systems\",\"Turnkey furnished with custom Minotti and B&B Italia collections\"]',
  '[{\"icon\":\"fa-umbrella-beach\",\"name\":\"Private Beach Access\"},{\"icon\":\"fa-ship\",\"name\":\"Yacht Mooring Permitted\"},{\"icon\":\"fa-film\",\"name\":\"12-Seat IMAX Cinema\"},{\"icon\":\"fa-wine-bottle\",\"name\":\"Sommelier Wine Vault\"},{\"icon\":\"fa-dumbbell\",\"name\":\"Technogym Health Suite\"},{\"icon\":\"fa-lock\",\"name\":\"Gated Private Frond\"}]',
  '{\"annualRentalValueAED\":\"AED 3,936,000 / year (Luxury Holiday Rental Yield)\",\"projectedCapRate\":\"8.2% Capitalized\",\"capitalAppreciationForecast\":\"12% p.a. due to Palm Jumeirah land scarcity\",\"taxImplications\":\"0% Property Tax, No Recurring Holding Taxes\"}',
  'Active',
  2,
  1
);

INSERT INTO `properties` (
  `id`, `slug`, `title`, `tagline`, `neighborhood`, `city`, `country`, `region`, `region_label`, `flag`,
  `type`, `tier`, `config`, `bedrooms`, `bathrooms`, `size_sqft`, `price_aed`, `price_inr`, `badge`,
  `developer`, `completion`, `ownership`, `visa_eligible`, `rental_yield`, `service_charges`, `main_image`,
  `gallery_json`, `description`, `highlights_json`, `amenities_json`, `financials_json`, `status`, `sort_order`, `is_active`
) VALUES (
  'dxb-03',
  'marina-horizon-residence-bluewaters',
  'Marina Horizon Panoramic Residence',
  'Dual-Aspect High-Yield Waterfront Haven in Dubai Marina',
  'Dubai Marina & Bluewaters Enclave',
  'Dubai',
  'United Arab Emirates',
  'dubai',
  'Dubai Marina, UAE',
  '🇦🇪',
  'Luxury Apartments',
  'tier-1',
  '3 Bed Waterfront Corner Residence',
  3,
  4,
  3450,
  8900000,
  201000000,
  'High Rental Yield',
  'Select Group & Kerzner Partners',
  'Ready for Immediate Occupation',
  'Freehold Title Deed (100% Foreign Ownership)',
  'UAE 10-Year Golden Visa Included',
  '9.1% High-Yield Cash Flow',
  'AED 18 / sq.ft per annum',
  'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1600&q=80',
  '[{\"url\":\"https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Terrace Vantage Point over Dubai Marina\",\"caption\":\"Overlooking luxury superyacht slips and the shimmering marina promenade.\"},{\"url\":\"https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Modern Minimalist Waterfront Living\",\"caption\":\"Warm oak timber flooring paired with brushed brass hardware accents.\"},{\"url\":\"https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Floor-to-Ceiling Corner Fenestration\",\"caption\":\"270-degree panoramic views of Ain Dubai and the Arabian Gulf sunset.\"},{\"url\":\"https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Master En-Suite Dressing & Bath\",\"caption\":\"Honed Pietra Serena sandstone with concealed rain showers.\"}]',
  'Engineered specifically for high-net-worth investors seeking rock-solid rental yield alongside strong capital preservation, Marina Horizon is located on a coveted promontory bridging Dubai Marina and Bluewaters Island.

The residence enjoys immediate walking access to Michelin-starred dining, private yacht charters, and upscale beach clubs, generating exceptional short-to-medium-term corporate yields exceeding 9% net after service expenses.',
  '[\"Dual-aspect corner orientation capturing both Marina and sea sunsets\",\"Turnkey asset managed by ultra-luxury hospitality operator\",\"Consistently ranked top 1% for rental yield across Dubai Marina\",\"Direct yacht berth booking privilege for residents\",\"Zero income or capital gains tax structuring in UAE jurisdiction\"]',
  '[{\"icon\":\"fa-anchor\",\"name\":\"Direct Marina Berthing\"},{\"icon\":\"fa-water-ladder\",\"name\":\"Rooftop Horizon Pool\"},{\"icon\":\"fa-dumbbell\",\"name\":\"Technogym Health Club\"},{\"icon\":\"fa-shield-halved\",\"name\":\"24/7 Concierge & Security\"},{\"icon\":\"fa-charging-station\",\"name\":\"EV Rapid Charging\"},{\"icon\":\"fa-utensils\",\"name\":\"Fine Dining In-Building\"}]',
  '{\"annualRentalValueAED\":\"AED 809,900 / year\",\"projectedCapRate\":\"9.1% Net\",\"capitalAppreciationForecast\":\"7.5% p.a.\",\"taxImplications\":\"Tax-Free Rental Distribution\"}',
  'Active',
  3,
  1
);

INSERT INTO `properties` (
  `id`, `slug`, `title`, `tagline`, `neighborhood`, `city`, `country`, `region`, `region_label`, `flag`,
  `type`, `tier`, `config`, `bedrooms`, `bathrooms`, `size_sqft`, `price_aed`, `price_inr`, `badge`,
  `developer`, `completion`, `ownership`, `visa_eligible`, `rental_yield`, `service_charges`, `main_image`,
  `gallery_json`, `description`, `highlights_json`, `amenities_json`, `financials_json`, `status`, `sort_order`, `is_active`
) VALUES (
  'ind-01',
  'worli-sea-face-signature-duplex-mumbai',
  'Worli Sea Face Signature Duplex',
  'Unobstructed Arabian Sea Frontage in South Mumbai’s Trophy Postcode',
  'Worli Sea Face, South Mumbai',
  'Mumbai',
  'India',
  'india',
  'Mumbai, India',
  '🇮🇳',
  'Penthouses',
  'tier-3',
  '4 & 5 BHK Arab Sea Front Sky Villa',
  5,
  6,
  7850,
  33000000,
  750000000,
  'Unobstructed Sea View',
  'Signature Boutique Private Developer',
  'Ready for Interior Fitout / Clear OC Received',
  'Freehold Clear Title (RERA Approved & Audited)',
  'NRI & OCI Compliant Under FEMA Regulations',
  'Trophy Legacy Capital Appreciation',
  '₹ 25 / sq.ft per month',
  'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80',
  '[{\"url\":\"https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Arabian Sea Front Architectural Living\",\"caption\":\"Expansive wrap-around glass balconies greeting the Worli waves.\"},{\"url\":\"https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Grand Sea-Facing Living Pavilion\",\"caption\":\"14-foot clear slab-to-slab ceiling heights and imported Statuario marble.\"},{\"url\":\"https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Bespoke Private Library & Cigar Lounge\",\"caption\":\"Crafted with acoustic walnut panelling and discrete security vaults.\"},{\"url\":\"https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Master Bedroom with Coastal Sunset Views\",\"caption\":\"Double-glazed acoustic acoustic glass isolating the ambient coastal soundscape.\"}]',
  'Worli Sea Face remains the most revered residential promenade in the subcontinent. This expansive sky duplex occupies the premium highest residential levels, framing an unbroken 180-degree vista of the Arabian Sea and the Bandra-Worli Sea Link.

Crafted with 14-foot slab-to-slab ceiling heights, dual high-speed private biometric elevators, separate service cores, and 5 dedicated covered parking bays, this residence accommodates the highest echelon of Indian industrial and financial leadership.',
  '[\"Unbroken, permanent Arabian Sea panorama with zero obstruction risk\",\"Full Occupancy Certificate (OC) received; zero GST on purchase\",\"Dual high-speed private biometric passenger elevators opening directly in suite\",\"5 designated covered mechanical parking bays in prime podium\",\"Separate service entrance and quarters for 3 live-in domestic staff\",\"Vetted by senior counsel for 100% marketable and unencumbered title\"]',
  '[{\"icon\":\"fa-water\",\"name\":\"Unobstructed Sea View\"},{\"icon\":\"fa-elevator\",\"name\":\"Private Biometric Lifts\"},{\"icon\":\"fa-shield-halved\",\"name\":\"Z+ Level Security Protocol\"},{\"icon\":\"fa-car\",\"name\":\"5 Covered Parking Bays\"},{\"icon\":\"fa-spa\",\"name\":\"Heated Rooftop Infinity Pool\"},{\"icon\":\"fa-users-gear\",\"name\":\"Dedicated Butler Core\"}]',
  '{\"annualRentalValueAED\":\"AED 1,320,000 / year (₹ 3.0 Cr / yr Corporate Lease)\",\"projectedCapRate\":\"4.2% Prime South Mumbai Yield\",\"capitalAppreciationForecast\":\"14% p.a. Driven by Coastal Road Connectivity\",\"taxImplications\":\"Capital Gains Rollover Eligible under Sec 54\"}',
  'Active',
  4,
  1
);

INSERT INTO `properties` (
  `id`, `slug`, `title`, `tagline`, `neighborhood`, `city`, `country`, `region`, `region_label`, `flag`,
  `type`, `tier`, `config`, `bedrooms`, `bathrooms`, `size_sqft`, `price_aed`, `price_inr`, `badge`,
  `developer`, `completion`, `ownership`, `visa_eligible`, `rental_yield`, `service_charges`, `main_image`,
  `gallery_json`, `description`, `highlights_json`, `amenities_json`, `financials_json`, `status`, `sort_order`, `is_active`
) VALUES (
  'ind-02',
  'ub-city-imperial-sky-residence-bengaluru',
  'UB City Imperial Sky Residences',
  'Ultra-Luxury Condominium Overlooking Cubbon Park Canopies',
  'Vittal Mallya Road, Bengaluru',
  'Bengaluru',
  'India',
  'india',
  'Bengaluru, India',
  '🇮🇳',
  'Luxury Apartments',
  'tier-2',
  '4 BHK Ultra-Luxury Condominium',
  4,
  5,
  5600,
  12500000,
  285000000,
  'Prime CBD Address',
  'Prestige Group & Private JV',
  'Ready for Occupation',
  'Freehold RERA Registered Title',
  'NRI / OCI FEMA Compliant',
  '6.4% Prime CBD Rental Yield',
  '₹ 18 / sq.ft per month',
  'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80',
  '[{\"url\":\"https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Architectural Panorama over Vittal Mallya Road\",\"caption\":\"Overlooking lush 300-acre Cubbon Park tree canopies.\"},{\"url\":\"https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Expansive Formal Entertaining Salon\",\"caption\":\"Italian marble cladding with bespoke fluted bronze divider screens.\"},{\"url\":\"https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Private Sky Balcony & Green Buffer\",\"caption\":\"Triple-aspect green view unmatched in central Bengaluru.\"},{\"url\":\"https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Master Retreat & En-Suite Dresser\",\"caption\":\"Integrated smart climate and humidity control systems.\"}]',
  'Occupying Bengaluru\'s most prestigious micro-market on Vittal Mallya Road, adjacent to the UB City luxury shopping arcade and Cubbon Park. This high-floor residence offers quiet grandeur in the center of India\'s Silicon capital.

Favored by founders, tech titans, and venture capitalists, the tower features a private rooftop helipad, temperature-controlled Olympic pool, private boardrooms, and white-glove concierge services.',
  '[\"Direct green views across Cubbon Park canopy in Central Bengaluru\",\"Walking proximity to luxury retail, fine dining, and private bank desks\",\"Exclusive rooftop helipad access for resident principals\",\"LEED Platinum certified sustainable architectural build\",\"Highest rental demand micro-market for multinational C-suite executives\"]',
  '[{\"icon\":\"fa-helicopter\",\"name\":\"Private Rooftop Helipad\"},{\"icon\":\"fa-tree\",\"name\":\"Cubbon Park Facing\"},{\"icon\":\"fa-water-ladder\",\"name\":\"Temperature Controlled Pool\"},{\"icon\":\"fa-handshake\",\"name\":\"Private Executive Boardrooms\"},{\"icon\":\"fa-shield-halved\",\"name\":\"Multi-Tier Security\"},{\"icon\":\"fa-car\",\"name\":\"4 Reserved Parking Bays\"}]',
  '{\"annualRentalValueAED\":\"AED 800,000 / year (₹ 1.82 Cr / yr)\",\"projectedCapRate\":\"6.4% Net Yield\",\"capitalAppreciationForecast\":\"10.5% p.a.\",\"taxImplications\":\"Indexation & Tax Shield Available\"}',
  'Active',
  5,
  1
);

INSERT INTO `properties` (
  `id`, `slug`, `title`, `tagline`, `neighborhood`, `city`, `country`, `region`, `region_label`, `flag`,
  `type`, `tier`, `config`, `bedrooms`, `bathrooms`, `size_sqft`, `price_aed`, `price_inr`, `badge`,
  `developer`, `completion`, `ownership`, `visa_eligible`, `rental_yield`, `service_charges`, `main_image`,
  `gallery_json`, `description`, `highlights_json`, `amenities_json`, `financials_json`, `status`, `sort_order`, `is_active`
) VALUES (
  'ind-03',
  'the-camellias-grand-sovereign-suite-gurugram',
  'The Camellias Grand Sovereign Suite',
  'Championship Golf Front Sky Manor on DLF Golf Course Road',
  'Golf Course Road, Gurugram (NCR)',
  'Gurugram',
  'India',
  'india',
  'Gurugram, India',
  '🇮🇳',
  'Luxury Apartments',
  'tier-2',
  '4 & 5 BHK Golf-Facing Sky Manor',
  5,
  6,
  7400,
  18800000,
  425000000,
  'Championship Golf Front',
  'DLF Luxury Division',
  'Ready for Immediate Occupation',
  'Freehold Title Deed (RERA Approved)',
  'FEMA / NRI Compliant',
  'Highest Tier Appreciation in NCR',
  '₹ 22 / sq.ft per month',
  'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1600&q=80',
  '[{\"url\":\"https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Golf Front Panoramic Promenade\",\"caption\":\"Overlooking Gary Player and Arnold Palmer 18-hole championship greens.\"},{\"url\":\"https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Triple-Aspect Glass Living Pavilion\",\"caption\":\"12-foot column-free glass spans overlooking the fairway water bodies.\"},{\"url\":\"https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Master Bedroom Suite & Balcony\",\"caption\":\"Soundproofed double-laminated glazing for absolute serenity.\"},{\"url\":\"https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80\",\"title\":\"Bespoke Kitchen & Sommelier Space\",\"caption\":\"Custom stone countertops with built-in European wine cooler.\"}]',
  'The Camellias is universally recognized as India\'s premier super-luxury residential community, matching and exceeding luxury residential benchmarks in Singapore and London.

Directly overlooking the Gary Player 18-hole championship golf course, the Grand Sovereign Suite boasts a 130,000 sq.ft private clubhouse featuring a full-service spa, indoor heated pools, boxing ring, simulation golf, and private private dining restaurants curated by award-winning chefs.',
  '[\"Frontline panoramic view over 18-hole Gary Player championship golf course\",\"Exclusive membership to 130,000 sq.ft ultra-luxury clubhouse and health center\",\"7-tier institutional security with centralized command room\",\"Dedicated golf cart tracks and private clubhouse access\",\"Proven capital appreciation with unmatched prestige across North India\"]',
  '[{\"icon\":\"fa-golf-ball-tee\",\"name\":\"18-Hole Championship Golf\"},{\"icon\":\"fa-building-columns\",\"name\":\"130,000 sq.ft Clubhouse\"},{\"icon\":\"fa-spa\",\"name\":\"Six Senses Style Wellness Spa\"},{\"icon\":\"fa-water-ladder\",\"name\":\"Indoor Heated Olympic Pool\"},{\"icon\":\"fa-shield-halved\",\"name\":\"7-Tier Security Architecture\"},{\"icon\":\"fa-car\",\"name\":\"4 Underground Secured Bays\"}]',
  '{\"annualRentalValueAED\":\"AED 1,150,000 / year (₹ 2.6 Cr / yr)\",\"projectedCapRate\":\"6.1% Net Yield\",\"capitalAppreciationForecast\":\"15% p.a. High Scarcity Premium\",\"taxImplications\":\"Capital Gains Tax Shield under Indian IT Act\"}',
  'Active',
  6,
  1
);

-- -------------------------------------------------------
-- 5. Initial Sample Leads / Inquiries
-- -------------------------------------------------------
INSERT INTO `leads` (`lead_id`, `name`, `email`, `phone`, `property_id`, `property_title`, `region`, `timeline`, `estimated_budget`, `notes`, `status`, `source`) VALUES
('BP-DEMO-01', 'Rahul Singhania', 'rahul@singhaniaholdings.com', '+971 50 882 1920', 'dxb-01', 'The Sky Palace at One Canal', 'Dubai, UAE', 'Immediate Allocation (30 Days)', 'AED 24.50M', 'Requesting private inspection for sky duplex during DIFC FinTech summit.', 'Dossier Dispatched', 'Property Dossier Form (PHP)'),
('BP-DEMO-02', 'Vikramaditya Roy', 'v.roy@calcutta-equities.in', '+91 98200 11928', 'ind-01', 'Worli Sea Face Signature Duplex', 'Mumbai, India', 'Current Quarter (Q1-Q2 2026)', '₹75.00 Cr', 'Client mandate for unbroken sea-facing penthouse with clear OC.', 'NDA Sent', 'Property Dossier Form (PHP)');

COMMIT;
