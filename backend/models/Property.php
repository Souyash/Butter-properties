<?php
/**
 * Butter Property - Property Model & Repository
 * Handles property retrieval, filtering, and detail resolution
 */

require_once __DIR__ . '/../config/config.php';

class Property {

    /**
     * Fetch all properties, with optional market and status filtering
     * 
     * @param string|null $market Filter by 'dubai' or 'india'
     * @param string|null $status Filter by 'active' or 'off-market'
     * @return array
     */
    public static function getAll(?string $market = null, ?string $status = null): array {
        $properties = self::loadProperties();

        if ($market !== null && $market !== '' && strtolower($market) !== 'all') {
            $m = strtolower(trim($market));
            $properties = array_filter($properties, function($item) use ($m) {
                return isset($item['region']) && strtolower($item['region']) === $m;
            });
        }

        if ($status !== null && $status !== '' && strtolower($status) !== 'all') {
            $s = strtolower(trim($status));
            $properties = array_filter($properties, function($item) use ($s) {
                $itemStatus = strtolower($item['status'] ?? 'active');
                return $itemStatus === $s;
            });
        }

        return array_map([self::class, 'enrichProperty'], array_values($properties));
    }

    /**
     * Fetch a single property by unique identifier (e.g., 'dxb-01', 'ind-02')
     * 
     * @param string $id
     * @return array|null
     */
    public static function getById(string $id): ?array {
        $cleanId = trim($id);
        if ($cleanId === '') {
            return null;
        }

        $properties = self::loadProperties();
        foreach ($properties as $prop) {
            if (isset($prop['id']) && strcasecmp($prop['id'], $cleanId) === 0) {
                return self::enrichProperty($prop);
            }
        }

        // Try matching by numeric or ref format (e.g., 'BP-DXB-01')
        $cleanRef = preg_replace('/^BP-/i', '', $cleanId);
        foreach ($properties as $prop) {
            if (isset($prop['id']) && strcasecmp($prop['id'], $cleanRef) === 0) {
                return self::enrichProperty($prop);
            }
        }

        return null;
    }

    /**
     * Fetch property by SEO slug (e.g. 'the-sky-palace-one-canal')
     * 
     * @param string $slug
     * @return array|null
     */
    public static function getBySlug(string $slug): ?array {
        $cleanSlug = trim($slug);
        if ($cleanSlug === '') {
            return null;
        }

        $properties = self::loadProperties();
        foreach ($properties as $prop) {
            if (isset($prop['slug']) && strcasecmp($prop['slug'], $cleanSlug) === 0) {
                return self::enrichProperty($prop);
            }
        }

        return null;
    }

    /**
     * Retrieve related curated properties
     * 
     * @param string $currentId
     * @param int $limit
     * @return array
     */
    public static function getRelated(string $currentId, int $limit = 2): array {
        $properties = self::loadProperties();
        $related = [];
        $current = self::getById($currentId);
        $currentRegion = $current['region'] ?? '';

        // Prioritize same region first
        foreach ($properties as $prop) {
            if (isset($prop['id']) && strcasecmp($prop['id'], $currentId) !== 0) {
                if (isset($prop['region']) && $prop['region'] === $currentRegion) {
                    $related[] = self::enrichProperty($prop);
                    if (count($related) >= $limit) {
                        return $related;
                    }
                }
            }
        }

        // Fill remaining slots from any region
        if (count($related) < $limit) {
            foreach ($properties as $prop) {
                if (isset($prop['id']) && strcasecmp($prop['id'], $currentId) !== 0) {
                    $alreadyIncluded = false;
                    foreach ($related as $rel) {
                        if ($rel['id'] === $prop['id']) {
                            $alreadyIncluded = true;
                            break;
                        }
                    }
                    if (!$alreadyIncluded) {
                        $related[] = self::enrichProperty($prop);
                        if (count($related) >= $limit) {
                            break;
                        }
                    }
                }
            }
        }

        return $related;
    }

    /**
     * Save / Add / Update a property
     * 
     * @param array $data
     * @return array The saved property
     */
    public static function save(array $data): array {
        $properties = self::loadProperties();
        $id = $data['id'] ?? ('bp-' . bin2hex(random_bytes(4)));
        $data['id'] = $id;

        // Generate slug if missing
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['title']), '-'));
        }

        $existingIndex = -1;
        foreach ($properties as $index => $prop) {
            if (isset($prop['id']) && strcasecmp($prop['id'], $id) === 0) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex >= 0) {
            $properties[$existingIndex] = array_merge($properties[$existingIndex], $data);
            $saved = $properties[$existingIndex];
        } else {
            $properties[] = $data;
            $saved = $data;
        }

        self::persistProperties($properties);
        return self::enrichProperty($saved);
    }

    /**
     * Remove / Delete a property
     * 
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool {
        $properties = self::loadProperties();
        $initialCount = count($properties);
        $filtered = array_filter($properties, function($item) use ($id) {
            return !isset($item['id']) || strcasecmp($item['id'], $id) !== 0;
        });

        if (count($filtered) !== $initialCount) {
            self::persistProperties(array_values($filtered));
            return true;
        }

        return false;
    }

    /**
     * Format currency amount for display (AED / INR)
     */
    public static function formatPrice(float|int|string $val, string $currency = 'AED'): string {
        $num = (float)$val;
        if ($currency === 'INR') {
            if ($num >= 10000000) {
                return '₹' . number_format($num / 10000000, 2) . ' Cr';
            }
            if ($num >= 100000) {
                return '₹' . number_format($num / 100000, 2) . ' Lac';
            }
            return '₹' . number_format($num);
        } else {
            if ($num >= 1000000) {
                return 'AED ' . number_format($num / 1000000, 2) . 'M';
            }
            return 'AED ' . number_format($num);
        }
    }

    /**
     * Internal: Load properties from DB or JSON flat file
     */
    private static function loadProperties(): array {
        // Check if MySQL PDO is connected
        $pdo = Database::getConnection();
        if ($pdo !== null) {
            try {
                $stmt = $pdo->query("SELECT * FROM properties WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
                $rows = $stmt->fetchAll();
                if (!empty($rows)) {
                    return array_map([self::class, 'hydrateFromDbRow'], $rows);
                }
            } catch (Throwable $e) {
                // Table might not exist yet, continue to JSON file
            }
        }

        // Flat-file JSON repository
        if (file_exists(BP_PROPERTIES_FILE)) {
            $json = file_get_contents(BP_PROPERTIES_FILE);
            $decoded = json_decode($json, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    /**
     * Internal: Persist properties to JSON
     */
    private static function persistProperties(array $properties): bool {
        if (!is_dir(BP_DATA_DIR)) {
            mkdir(BP_DATA_DIR, 0755, true);
        }
        return (bool)file_put_contents(
            BP_PROPERTIES_FILE, 
            json_encode($properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * Enrich property with computed attributes (formatted strings, ref code, etc.)
     */
    private static function enrichProperty(array $prop): array {
        $prop['refCode'] = 'BP-' . strtoupper($prop['id'] ?? 'PROPO');
        $prop['formattedPriceAED'] = self::formatPrice($prop['priceAED'] ?? 0, 'AED');
        $prop['formattedPriceINR'] = self::formatPrice($prop['priceINR'] ?? 0, 'INR');
        $prop['status'] = $prop['status'] ?? 'Active';
        
        // Ensure gallery is an array
        if (empty($prop['gallery']) && !empty($prop['mainImage'])) {
            $prop['gallery'] = [
                [
                    'url' => $prop['mainImage'],
                    'title' => $prop['title'] ?? 'Asset Showcase',
                    'caption' => $prop['tagline'] ?? ''
                ]
            ];
        }

        return $prop;
    }

    /**
     * Convert DB row with JSON columns into property array
     */
    private static function hydrateFromDbRow(array $row): array {
        return [
            'id' => $row['id'],
            'slug' => $row['slug'] ?? '',
            'title' => $row['title'] ?? '',
            'tagline' => $row['tagline'] ?? '',
            'neighborhood' => $row['neighborhood'] ?? '',
            'city' => $row['city'] ?? '',
            'country' => $row['country'] ?? '',
            'region' => $row['region'] ?? 'dubai',
            'regionLabel' => $row['region_label'] ?? '',
            'flag' => $row['flag'] ?? '',
            'type' => $row['type'] ?? '',
            'tier' => $row['tier'] ?? 'tier-3',
            'config' => $row['config'] ?? '',
            'bedrooms' => (int)($row['bedrooms'] ?? 0),
            'bathrooms' => (int)($row['bathrooms'] ?? 0),
            'sizeSqFt' => (int)($row['size_sqft'] ?? 0),
            'priceAED' => (float)($row['price_aed'] ?? 0),
            'priceINR' => (float)($row['price_inr'] ?? 0),
            'badge' => $row['badge'] ?? '',
            'developer' => $row['developer'] ?? '',
            'completion' => $row['completion'] ?? '',
            'ownership' => $row['ownership'] ?? '',
            'visaEligible' => $row['visa_eligible'] ?? '',
            'rentalYield' => $row['rental_yield'] ?? '',
            'serviceCharges' => $row['service_charges'] ?? '',
            'mainImage' => $row['main_image'] ?? '',
            'gallery' => json_decode($row['gallery_json'] ?? '[]', true) ?: [],
            'description' => $row['description'] ?? '',
            'highlights' => json_decode($row['highlights_json'] ?? '[]', true) ?: [],
            'amenities' => json_decode($row['amenities_json'] ?? '[]', true) ?: [],
            'financials' => json_decode($row['financials_json'] ?? '{}', true) ?: [],
            'status' => $row['status'] ?? 'Active'
        ];
    }
}
