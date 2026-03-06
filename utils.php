<?php

// Enable CORS
function setCorsHeaders() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

// Send JSON response
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

// Send error response
function sendError($message, $statusCode = 400) {
    http_response_code($statusCode);
    echo json_encode(['error' => $message]);
    exit();
}

// Read all records from data.txt
function readAllRecords($filename = 'data.txt') {
    if (!file_exists($filename)) {
        file_put_contents($filename, '');
        return [];
    }
    
    $records = [];
    $file = fopen($filename, 'r');
    
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            if (!empty($line)) {
                $record = json_decode($line, true);
                if ($record) {
                    $records[] = $record;
                }
            }
        }
        fclose($file);
    }
    
    return $records;
}

// Write all records to data.txt
function writeAllRecords($records, $filename = 'data.txt') {
    $file = fopen($filename, 'w');
    
    if ($file) {
        foreach ($records as $record) {
            fwrite($file, json_encode($record) . "\n");
        }
        fclose($file);
        return true;
    }
    
    return false;
}

// Get last used ID from counter file
function getLastId($filename = 'last_id.txt') {
    if (!file_exists($filename)) {
        return 0;
    }
    
    $lastId = file_get_contents($filename);
    return intval(trim($lastId));
}

// Save last used ID to counter file
function saveLastId($id, $filename = 'last_id.txt') {
    file_put_contents($filename, $id);
}

// Generate unique ID (never reuses deleted IDs)
function generateId($records) {
    // Get the last used ID from counter file
    $lastId = getLastId();
    
    // If this is first run, check if there are existing records
    // and set the counter to the highest existing ID
    if ($lastId === 0 && !empty($records)) {
        foreach ($records as $record) {
            if (isset($record['id']) && $record['id'] > $lastId) {
                $lastId = $record['id'];
            }
        }
    }
    
    // Increment and save the new ID
    $newId = $lastId + 1;
    saveLastId($newId);
    
    return $newId;
}

// Find record by ID
function findRecordById($records, $id) {
    foreach ($records as $index => $record) {
        if ($record['id'] == $id) {
            return ['index' => $index, 'record' => $record];
        }
    }
    return null;
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate user input
function validateUserInput($name, $email) {
    $errors = [];
    
    if (empty($name) || trim($name) === '') {
        $errors[] = 'Name is required';
    }
    
    if (empty($email) || trim($email) === '') {
        $errors[] = 'Email is required';
    } elseif (!validateEmail($email)) {
        $errors[] = 'Invalid email format';
    }
    
    return $errors;
}
