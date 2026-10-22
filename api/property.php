<?php
/**
 * Butter Property - Property Details & Catalog REST API
 * 
 * Endpoints:
 * GET /api/property.php?id=dxb-01          -> Get single property details
 * GET /api/property.php?slug=the-sky-...   -> Get property by slug
 * GET /api/property.php?market=dubai       -> List properties by market
 * GET /api/property.php?action=related&id=dxb-01 -> Get related properties
 */

// Enable CORS and JSON Response
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight requests
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/models/Property.php';

try {
    $action = $_GET['action'] ?? 'detail';
    $id = $_GET['id'] ?? null;
    $slug = $_GET['slug'] ?? null;
    $market = $_GET['market'] ?? $_GET['region'] ?? null;
    $status = $_GET['status'] ?? 'active';

    // 1. Single Property Detail by ID
    if (!empty($id)) {
        if ($action === 'related') {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2;
            $related = Property::getRelated($id, $limit);
            echo json_encode([
                'success' => true,
                'total' => count($related),
                'data' => $related
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }

        $property = Property::getById($id);
        if ($property === null) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Property Not Found',
                'message' => "No property matches allocation reference: {$id}"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }

        echo json_encode([
            'success' => true,
            'data' => $property
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    // 2. Single Property Detail by Slug
    if (!empty($slug)) {
        $property = Property::getBySlug($slug);
        if ($property === null) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Property Not Found',
                'message' => "No property matches slug: {$slug}"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }

        echo json_encode([
            'success' => true,
            'data' => $property
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    // 3. Properties Catalog / Filtered List
    $properties = Property::getAll($market, $status);

    echo json_encode([
        'success' => true,
        'count' => count($properties),
        'filters' => [
            'market' => $market ?: 'all',
            'status' => $status ?: 'all'
        ],
        'data' => $properties
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server Error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
