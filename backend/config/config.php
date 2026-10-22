<?php
/**
 * Butter Property - Backend Configuration
 * butterproperty.com
 */

// Enable error reporting for development (disable in production)
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Base application paths
define('BP_ROOT', dirname(__DIR__, 2));
define('BP_BACKEND', dirname(__DIR__));
define('BP_DATA_DIR', BP_BACKEND . '/data');
define('BP_PROPERTIES_FILE', BP_DATA_DIR . '/properties.json');
define('BP_LEADS_FILE', BP_DATA_DIR . '/leads.json');

// Application metadata
define('BP_SITE_NAME', 'Butter Property');
define('BP_SITE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
define('BP_CONTACT_EMAIL', 'advisory@butterproperty.com');
define('BP_WHATSAPP_PHONE', '971500000000');

// Database credentials (MySQL / MariaDB PDO support)
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'butter_property');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

/**
 * Database Connection Manager (PDO)
 * Automatically falls back to flat-file JSON if database connection is unavailable
 */
class Database {
    private static ?PDO $instance = null;
    private static bool $connectionAttempted = false;

    public static function getConnection(): ?PDO {
        if (self::$instance !== null) {
            return self::$instance;
        }

        if (self::$connectionAttempted) {
            return null;
        }

        self::$connectionAttempted = true;

        // Try connecting to MySQL if configured
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 2, // Quick 2-second timeout so flat-file fallback is instant
            ];
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            return self::$instance;
        } catch (Throwable $e) {
            // Silently fall back to JSON flat-file storage
            self::$instance = null;
            return null;
        }
    }
}

