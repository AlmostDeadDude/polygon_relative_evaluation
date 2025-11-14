<?php
header('Content-Type: application/json');

// Even though we no longer persist anything, we still parse the payload so it can be validated if needed.
$data = file_get_contents('php://input');
$decoded = json_decode($data, true);

if ($data !== '' && $decoded === null && json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Malformed JSON payload: ' . json_last_error_msg(),
    ]);
    exit;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Demo mode active - selections are not stored on the server.',
]);
