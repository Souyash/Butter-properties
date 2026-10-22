<?php
/**
 * Butter Property - Confidential Lead & Dossier Inquiry API
 * 
 * Endpoint:
 * POST /api/inquiry.php
 * Accepts JSON body or multipart/x-www-form-urlencoded
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method Not Allowed',
        'message' => 'Inquiry submissions require a POST request.'
    ]);
    exit;
}

require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/models/Inquiry.php';

try {
    // Read JSON payload or standard POST variables
    $rawInput = file_get_contents('php://input');
    $payload = [];
    if (!empty($rawInput)) {
        $json = json_decode($rawInput, true);
        if (is_array($json)) {
            $payload = $json;
        }
    }
    
    // Merge standard $_POST if sent via standard form
    if (!empty($_POST)) {
        $payload = array_merge($payload, $_POST);
    }

    if (empty($payload)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Bad Request',
            'message' => 'No submission data provided.'
        ]);
        exit;
    }

    $lead = Inquiry::create($payload);

    echo json_encode([
        'success' => true,
        'message' => "Confidential inquiry received for reference {$lead['id']}. Our Senior Principal will reach out within 4 business hours.",
        'data' => [
            'id' => $lead['id'],
            'property' => $lead['property'],
            'createdAt' => $lead['createdAt']
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

} catch (InvalidArgumentException $e) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'error' => 'Validation Error',
        'message' => $e->getMessage()
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server Error',
        'message' => 'Unable to process inquiry at this moment: ' . $e->getMessage()
    ]);
}

