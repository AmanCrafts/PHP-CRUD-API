<?php

require_once 'utils.php';

// Set CORS headers
setCorsHeaders();

// Only accept DELETE requests
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    sendError('Method not allowed. Use DELETE.', 405);
}

// Get ID from query parameter
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    sendError('Valid ID is required');
}

// Read existing records
$records = readAllRecords();

// Find record by ID
$found = findRecordById($records, $id);

if (!$found) {
    sendError('User not found', 404);
}

// Remove record
array_splice($records, $found['index'], 1);

// Write to file
if (writeAllRecords($records)) {
    sendResponse([
        'message' => 'User deleted successfully'
    ]);
} else {
    sendError('Failed to delete user', 500);
}
