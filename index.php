<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'message' => 'Welcome to PHP CRUD API',
    'version' => '1.0',
    'endpoints' => [
        [
            'method' => 'POST',
            'path' => '/create.php',
            'description' => 'Create a new user',
            'body' => ['name' => 'string', 'email' => 'string']
        ],
        [
            'method' => 'GET',
            'path' => '/read.php',
            'description' => 'Get all users',
            'query' => ['name' => 'optional', 'email' => 'optional']
        ],
        [
            'method' => 'PUT',
            'path' => '/update.php?id={id}',
            'description' => 'Update user by ID',
            'body' => ['name' => 'string', 'email' => 'string']
        ],
        [
            'method' => 'DELETE',
            'path' => '/delete.php?id={id}',
            'description' => 'Delete user by ID'
        ]
    ],
    'documentation' => [
        'readme' => '/README.md',
        'api_reference' => '/API_REFERENCE.md'
    ]
], JSON_PRETTY_PRINT);
