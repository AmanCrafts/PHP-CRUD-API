<?php

require_once 'utils.php';

// Set CORS headers
setCorsHeaders();

// Only accept PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendError('Method not allowed. Use PUT.', 405);
}

// Get ID from query parameter
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    sendError('Valid ID is required');
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!$data) {
    sendError('Invalid JSON data');
}

// Read existing records
$records = readAllRecords();

// Find record by ID
$found = findRecordById($records, $id);

if (!$found) {
    sendError('User not found', 404);
}

$index = $found['index'];
$record = $found['record'];

// Update fields
if (isset($data['name']) && trim($data['name']) !== '') {
    $name = trim($data['name']);
} else {
    $name = $record['name'];
}

if (isset($data['email']) && trim($data['email']) !== '') {
    $email = trim($data['email']);
    
    // Validate email
    if (!validateEmail($email)) {
        sendError('Invalid email format');
    }
    
    // Check if email already exists (excluding current record)
    foreach ($records as $idx => $rec) {
        if ($idx !== $index && $rec['email'] === $email) {
            sendError('Email already exists', 409);
        }
    }
} else {
    $email = $record['email'];
}

// Update record
$records[$index]['name'] = $name;
$records[$index]['email'] = $email;
$records[$index]['updated_at'] = date('Y-m-d H:i:s');

// Write to file
if (writeAllRecords($records)) {
    sendResponse([
        'message' => 'User updated successfully',
        'user' => $records[$index]
    ]);
} else {
    sendError('Failed to update user', 500);
}
