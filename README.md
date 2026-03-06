# PHP CRUD API - Using TXT File Storage

A simple PHP backend API that performs CRUD operations using a `.txt` file as data storage instead of a database.

## Features

- Full CRUD operations (Create, Read, Update, Delete)
- JSON-based data storage in text file
- CORS support enabled
- Input validation
- Email validation
- Duplicate email detection
- Timestamp tracking (created_at, updated_at)
- Search by name/email
- Proper error handling
- RESTful API design

## Folder Structure

```
project/
│
├── data.txt          # Data storage file (JSON per line)
├── create.php        # POST - Create user
├── read.php          # GET - Read all users
├── update.php        # PUT - Update user
├── delete.php        # DELETE - Delete user
├── utils.php         # Helper functions
└── .htaccess         # URL rewriting (optional)
```

## Data Structure

Each record is stored as JSON per line in `data.txt`:

```json
{"id":1,"name":"Rahul","email":"rahul@test.com"}
{"id":2,"name":"Aman","email":"aman@test.com"}
```

## Getting Started

### 1. Start PHP Server

```bash
php -S localhost:8000
```

The API will be available at `http://localhost:8000`

### 2. Test the API

You can use curl, Postman, or any HTTP client to test the endpoints.

## API Endpoints

### 1. Create User

**Endpoint:** `POST /create.php`

**Request Body:**

```json
{
  "name": "John Doe",
  "email": "john@test.com"
}
```

**Success Response (201):**

```json
{
  "message": "User created successfully",
  "user": {
    "id": 3,
    "name": "John Doe",
    "email": "john@test.com",
    "created_at": "2026-03-06 10:30:00"
  }
}
```

**Example:**

```bash
curl -X POST http://localhost:8000/create.php \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@test.com"}'
```

---

### 2. Read All Users

**Endpoint:** `GET /read.php`

**Success Response (200):**

```json
[
  { "id": 1, "name": "Rahul", "email": "rahul@test.com" },
  { "id": 2, "name": "Aman", "email": "aman@test.com" }
]
```

**Example:**

```bash
curl http://localhost:8000/read.php
```

**Search by Name:**

```bash
curl "http://localhost:8000/read.php?name=Rahul"
```

**Search by Email:**

```bash
curl "http://localhost:8000/read.php?email=aman@test.com"
```

---

### 3. Update User

**Endpoint:** `PUT /update.php?id=1`

**Request Body:**

```json
{
  "name": "Rahul Sharma",
  "email": "rahulsharma@test.com"
}
```

**Success Response (200):**

```json
{
  "message": "User updated successfully",
  "user": {
    "id": 1,
    "name": "Rahul Sharma",
    "email": "rahulsharma@test.com",
    "updated_at": "2026-03-06 11:00:00"
  }
}
```

**Example:**

```bash
curl -X PUT "http://localhost:8000/update.php?id=1" \
  -H "Content-Type: application/json" \
  -d '{"name":"Rahul Sharma","email":"rahulsharma@test.com"}'
```

---

### 4. Delete User

**Endpoint:** `DELETE /delete.php?id=1`

**Success Response (200):**

```json
{
  "message": "User deleted successfully"
}
```

**Example:**

```bash
curl -X DELETE "http://localhost:8000/delete.php?id=1"
```

## Error Handling

The API returns appropriate HTTP status codes and error messages:

### Common Error Responses

**400 Bad Request** - Invalid input

```json
{
  "error": "Name is required, Invalid email format"
}
```

**404 Not Found** - User not found

```json
{
  "error": "User not found"
}
```

**405 Method Not Allowed** - Wrong HTTP method

```json
{
  "error": "Method not allowed. Use POST."
}
```

**409 Conflict** - Duplicate email

```json
{
  "error": "Email already exists"
}
```

**500 Internal Server Error** - Server error

```json
{
  "error": "Failed to create user"
}
```

## Technical Implementation

### File Handling Functions Used

- `fopen()` - Open file for reading/writing
- `fwrite()` - Write data to file
- `fgets()` - Read line from file
- `fclose()` - Close file handle
- `file_get_contents()` - Read entire file or HTTP request body
- `file_put_contents()` - Write data to file (alternative method)

### JSON Functions Used

- `json_encode()` - Convert PHP array to JSON string
- `json_decode()` - Convert JSON string to PHP array

### Features Implemented

**Input Validation**

- Name and email required
- Email format validation
- Duplicate email detection

**CORS Support**

- Cross-Origin Resource Sharing enabled
- Handles preflight OPTIONS requests

**Timestamps**

- `created_at` - Set on user creation
- `updated_at` - Set on user update

**Search Functionality**

- Search by name (partial match)
- Search by email (partial match)
- Case-insensitive search

**Error Handling**

- Missing ID validation
- Empty file handling
- Invalid JSON detection
- File operation error handling

## Testing the API

### Complete Test Sequence

```bash
# 1. Read all users
curl http://localhost:8000/read.php

# 2. Create a new user
curl -X POST http://localhost:8000/create.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Alice Smith","email":"alice@test.com"}'

# 3. Read all users again
curl http://localhost:8000/read.php

# 4. Update user with ID 1
curl -X PUT "http://localhost:8000/update.php?id=1" \
  -H "Content-Type: application/json" \
  -d '{"name":"Rahul Kumar","email":"rahul.kumar@test.com"}'

# 5. Search by name
curl "http://localhost:8000/read.php?name=Alice"

# 6. Delete user with ID 2
curl -X DELETE "http://localhost:8000/delete.php?id=2"

# 7. Verify deletion
curl http://localhost:8000/read.php
```

## Requirements

- PHP 7.4 or higher
- No database required
- No external dependencies

## Code Structure

### utils.php

Contains helper functions:

- `setCorsHeaders()` - Enable CORS
- `sendResponse()` - Send JSON response
- `sendError()` - Send error response
- `readAllRecords()` - Read from data.txt
- `writeAllRecords()` - Write to data.txt
- `generateId()` - Generate unique ID
- `findRecordById()` - Find record by ID
- `validateEmail()` - Validate email format
- `validateUserInput()` - Validate user data

### create.php

- Validates input
- Checks for duplicate email
- Generates unique ID
- Appends to data.txt

### read.php

- Reads all records
- Supports search by name/email
- Returns JSON array

### update.php

- Validates ID and input
- Updates specific record
- Rewrites entire file

### delete.php

- Validates ID
- Removes record
- Rewrites file without deleted record

## Notes

- Data is stored in plain text file (not suitable for production)
- File locking not implemented (could cause issues with concurrent requests)
- IDs are auto-incremented integers
- All responses are in JSON format
- CORS is enabled for all origins (adjust for production)
