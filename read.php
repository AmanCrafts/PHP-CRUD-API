<?php

require_once 'utils.php';

// Set CORS headers
setCorsHeaders();

// Only accept GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method not allowed. Use GET.', 405);
}

// Read all records
$records = readAllRecords();

// Check for search parameters
$searchName = isset($_GET['name']) ? trim($_GET['name']) : '';
$searchEmail = isset($_GET['email']) ? trim($_GET['email']) : '';

// Filter records if search parameters provided
if (!empty($searchName) || !empty($searchEmail)) {
    $filteredRecords = [];
    
    foreach ($records as $record) {
        $matchName = empty($searchName) || stripos($record['name'], $searchName) !== false;
        $matchEmail = empty($searchEmail) || stripos($record['email'], $searchEmail) !== false;
        
        if ($matchName && $matchEmail) {
            $filteredRecords[] = $record;
        }
    }
    
    sendResponse($filteredRecords);
}

// Return all records
sendResponse($records);
