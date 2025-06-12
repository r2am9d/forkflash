#!/bin/bash

# 🧪 ForkFlash Test Script
# Quick verification that everything is working

set -e

GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

SUCCESS="✅"
ERROR="❌"
INFO="ℹ️"

print_test() {
    echo -e "${YELLOW}${INFO} Testing: $1${NC}"
}

print_success() {
    echo -e "${GREEN}${SUCCESS} $1${NC}"
}

print_error() {
    echo -e "${RED}${ERROR} $1${NC}"
}

# Test Docker containers
print_test "Docker containers status"
cd backend
if docker compose -f docker-compose.dev.yml ps --format "table {{.Service}}\t{{.Status}}" | grep -q "Up"; then
    print_success "Docker containers are running"
else
    print_error "Docker containers are not running"
fi

# Test Laravel application
print_test "Laravel application health"
if curl -s http://localhost:8000/health | grep -q "healthy"; then
    print_success "Laravel application is healthy"
else
    print_error "Laravel application health check failed"
fi

# Test database connection
print_test "Database connection"
if docker compose -f docker-compose.dev.yml exec app php artisan tinker --execute="DB::connection()->getPdo();" &> /dev/null; then
    print_success "Database connection works"
else
    print_error "Database connection failed"
fi

# Test Redis connection
print_test "Redis connection"
if docker compose -f docker-compose.dev.yml exec app php artisan tinker --execute="Redis::ping();" &> /dev/null; then
    print_success "Redis connection works"
else
    print_error "Redis connection failed"
fi

# Test Filament admin
print_test "Filament admin panel"
if curl -s http://localhost:8000/admin | grep -q "Filament"; then
    print_success "Filament admin panel is accessible"
else
    print_error "Filament admin panel is not accessible"
fi

# Test CloudBeaver
print_test "CloudBeaver database UI"
if curl -s http://localhost:8080 &> /dev/null; then
    print_success "CloudBeaver is accessible"
else
    print_error "CloudBeaver is not accessible"
fi

# Test MailHog
print_test "MailHog email testing"
if curl -s http://localhost:8025 &> /dev/null; then
    print_success "MailHog is accessible"
else
    print_error "MailHog is not accessible"
fi

# Test git hooks
print_test "Git hooks setup"
if [ -f ".husky/commit-msg" ] && [ -f ".husky/pre-push" ]; then
    print_success "Git hooks are configured"
else
    print_error "Git hooks are not configured"
fi

# Test PHPStan configuration
print_test "PHPStan configuration"
if grep -q "level: 6" backend/phpstan.neon; then
    print_success "PHPStan level 6 configured for fast development"
else
    print_error "PHPStan level not properly configured"
fi

cd ..

echo ""
echo -e "${GREEN}🎉 All tests completed!${NC}"
echo ""
echo -e "${YELLOW}If any tests failed, try:${NC}"
echo "  npm run docker:dev-down"
echo "  npm run docker:dev"
echo "  npm run backend:migrate"
