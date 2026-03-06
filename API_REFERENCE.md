# Quick API Reference

## Base URL

```
http://localhost:8000
```

## Endpoints

### CREATE USER

```bash
POST /create.php
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@test.com"
}
```

### READ ALL USERS

```bash
GET /read.php
```

### SEARCH USERS

```bash
GET /read.php?name=John
GET /read.php?email=test.com
GET /read.php?name=John&email=test.com
```

### UPDATE USER

```bash
PUT /update.php?id=1
Content-Type: application/json

{
  "name": "Updated Name",
  "email": "updated@test.com"
}
```

### DELETE USER

```bash
DELETE /delete.php?id=1
```

## Quick Test Commands

```bash
# Read all users
curl http://localhost:8000/read.php

# Create user
curl -X POST http://localhost:8000/create.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Alice","email":"alice@test.com"}'

# Update user (ID 1)
curl -X PUT "http://localhost:8000/update.php?id=1" \
  -H "Content-Type: application/json" \
  -d '{"name":"Alice Smith","email":"alice.smith@test.com"}'

# Delete user (ID 1)
curl -X DELETE "http://localhost:8000/delete.php?id=1"

# Search by name
curl "http://localhost:8000/read.php?name=Alice"
```

## Response Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request (validation error)
- `404` - Not Found
- `405` - Method Not Allowed
- `409` - Conflict (duplicate email)
- `500` - Server Error
