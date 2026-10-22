<?php
/**
 * Butter Property - Lead & Inquiry Model
 * Handles confidential investor dossier requests and consultation inquiries
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Property.php';

class Inquiry {

    /**
     * Save an investor inquiry / dossier request
     * 
     * @param array $input
     * @return array
     */
    public static function create(array $input): array {
        // Validate required fields
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');

        if ($email === '' && $phone === '') {
            throw new InvalidArgumentException('Please provide either an email or direct telephone number for dispatch.');
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Please provide a valid email address.');
        }

        $propertyId = trim($input['propertyId'] ?? $input['property_id'] ?? '');
        $propertyTitle = trim($input['property'] ?? $input['property_title'] ?? '');
        $region = trim($input['region'] ?? '');

        // If propertyId provided, enrich title and region
        if ($propertyId !== '') {
            $prop = Property::getById($propertyId);
            if ($prop !== null) {
                $propertyTitle = $prop['title'];
                $region = $prop['regionLabel'];
            }
        }

        $lead = [
            'id' => 'BP-' . strtoupper(dechex(time())) . '-' . strtoupper(bin2hex(random_bytes(2))),
            'createdAt' => date('c'),
            'status' => 'New Inquiry',
            'source' => $input['source'] ?? 'Property Dossier Form (PHP)',
            'name' => $name !== '' ? htmlspecialchars($name, ENT_QUOTES, 'UTF-8') : 'Anonymous Principal',
            'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
            'phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
            'property' => htmlspecialchars($propertyTitle ?: 'General Portfolio Consultation', ENT_QUOTES, 'UTF-8'),
            'propertyId' => htmlspecialchars($propertyId, ENT_QUOTES, 'UTF-8'),
            'region' => htmlspecialchars($region ?: 'Both (India & Dubai)', ENT_QUOTES, 'UTF-8'),
            'timeline' => htmlspecialchars($input['timeline'] ?? 'Immediate Allocation', ENT_QUOTES, 'UTF-8'),
            'estimatedBudget' => htmlspecialchars($input['estimatedBudget'] ?? $input['budget'] ?? '', ENT_QUOTES, 'UTF-8'),
            'notes' => htmlspecialchars($input['notes'] ?? '', ENT_QUOTES, 'UTF-8'),
            'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'userAgent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255)
        ];

        // 1. Try MySQL Database insert if connected
        $pdo = Database::getConnection();
        if ($pdo !== null) {
            try {
                $sql = "INSERT INTO leads (lead_id, name, email, phone, property_id, property_title, region, timeline, notes, created_at) 
                        VALUES (:lead_id, :name, :email, :phone, :property_id, :property_title, :region, :timeline, :notes, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':lead_id' => $lead['id'],
                    ':name' => $lead['name'],
                    ':email' => $lead['email'],
                    ':phone' => $lead['phone'],
                    ':property_id' => $lead['propertyId'],
                    ':property_title' => $lead['property'],
                    ':region' => $lead['region'],
                    ':timeline' => $lead['timeline'],
                    ':notes' => $lead['notes']
                ]);
            } catch (Throwable $e) {
                // Table might not exist yet, fallback to flat file
            }
        }

        // 2. Persist to JSON leads file
        self::saveToJsonFile($lead);

        return $lead;
    }

    /**
     * Get all recorded leads
     */
    public static function getAll(): array {
        if (file_exists(BP_LEADS_FILE)) {
            $data = json_decode(file_get_contents(BP_LEADS_FILE), true);
            if (is_array($data)) {
                return $data;
            }
        }
        return [];
    }

    private static function saveToJsonFile(array $lead): void {
        if (!is_dir(BP_DATA_DIR)) {
            mkdir(BP_DATA_DIR, 0755, true);
        }

        $leads = self::getAll();
        array_unshift($leads, $lead);

        file_put_contents(
            BP_LEADS_FILE, 
            json_encode($leads, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }
}
