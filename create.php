<?php

require_once 'utils.php';

// Set CORS headers
setCorsHeaders();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed. Use POST.', 405);
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!$data) {
    sendError('Invalid JSON data');
}

$name = isset($data['name']) ? trim($data['name']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';

// Validate user input
$errors = validateUserInput($name, $email);
if (!empty($errors)) {
    sendError(implode(', ', $errors));
}

// Read existing records
$records = readAllRecords();

// Check if email already exists
foreach ($records as $record) {
    if ($record['email'] === $email) {
        sendError('Email already exists', 409);
    }
}

// Generate unique ID
$id = generateId($records);

// Create new user record
$newUser = [
    'id' => $id,
    'name' => $name,
    'email' => $email,
    'created_at' => date('Y-m-d H:i:s')
];

// Append to records
$records[] = $newUser;

// Write to file
if (writeAllRecords($records)) {
    sendResponse([
        'message' => 'User created successfully',
        'user' => $newUser
    ], 201);
} else {
    sendError('Failed to create user', 500);
}
