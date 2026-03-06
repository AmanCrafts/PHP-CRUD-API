#!/bin/bash

# PHP CRUD API Test Script
# This script tests all CRUD endpoints

BASE_URL="http://localhost:8000"

echo "========================================="
echo "  PHP CRUD API - Test Script"
echo "========================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}1. Testing READ - Get all users${NC}"
curl -s $BASE_URL/read.php | json_pp
echo -e "\n"

echo -e "${BLUE}2. Testing CREATE - Create new user${NC}"
curl -s -X POST $BASE_URL/create.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Alice Smith","email":"alice@test.com"}' | json_pp
echo -e "\n"

echo -e "${BLUE}3. Testing READ - Get all users (after create)${NC}"
curl -s $BASE_URL/read.php | json_pp
echo -e "\n"

echo -e "${BLUE}4. Testing UPDATE - Update user ID 1${NC}"
curl -s -X PUT "$BASE_URL/update.php?id=1" \
  -H "Content-Type: application/json" \
  -d '{"name":"Rahul Kumar","email":"rahul.kumar@test.com"}' | json_pp
echo -e "\n"

echo -e "${BLUE}5. Testing READ - Search by name${NC}"
curl -s "$BASE_URL/read.php?name=Alice" | json_pp
echo -e "\n"

echo -e "${BLUE}6. Testing DELETE - Delete user ID 2${NC}"
curl -s -X DELETE "$BASE_URL/delete.php?id=2" | json_pp
echo -e "\n"

echo -e "${BLUE}7. Testing READ - Get all users (after delete)${NC}"
curl -s $BASE_URL/read.php | json_pp
echo -e "\n"

echo -e "${YELLOW}8. Testing Error Handling - Invalid email${NC}"
curl -s -X POST $BASE_URL/create.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"invalid-email"}' | json_pp
echo -e "\n"

echo -e "${YELLOW}9. Testing Error Handling - Missing name${NC}"
curl -s -X POST $BASE_URL/create.php \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com"}' | json_pp
echo -e "\n"

echo -e "${YELLOW}10. Testing Error Handling - User not found${NC}"
curl -s -X DELETE "$BASE_URL/delete.php?id=9999" | json_pp
echo -e "\n"

echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}  All tests completed!${NC}"
echo -e "${GREEN}=========================================${NC}"
