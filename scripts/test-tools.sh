#!/bin/bash

# 🛠️ ForkFlash Code Quality Tools Test
# Test that all code quality tools load their config files properly

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_header() {
    echo -e "${BLUE}"
    echo "🛠️ =================================="
    echo "   Code Quality Tools Test"
    echo "===================================="
    echo -e "${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ️ $1${NC}"
}

test_config_files() {
    print_info "Testing configuration files existence..."
    
    cd backend
    
    # Check if config files exist
    if [ ! -f "rector.php" ]; then
        print_error "rector.php config file not found!"
        exit 1
    fi
    print_success "rector.php found"
    
    if [ ! -f "pint.json" ]; then
        print_error "pint.json config file not found!"
        exit 1
    fi
    print_success "pint.json found"
    
    if [ ! -f "phpstan.neon" ]; then
        print_error "phpstan.neon config file not found!"
        exit 1
    fi
    print_success "phpstan.neon found"
    
    if [ ! -f "pest.php" ]; then
        print_error "pest.php config file not found!"
        exit 1
    fi
    print_success "pest.php found"
    
    cd ..
}

test_tools() {
    print_info "Testing tools with their config files..."
    
    cd backend
    
    # Test Rector config loading
    print_info "Testing Rector config loading..."
    if docker compose -f docker-compose.dev.yml exec app php -d memory_limit=1G vendor/bin/rector process --config=rector.php --dry-run --no-progress-bar > /dev/null 2>&1; then
        print_success "Rector loads rector.php correctly"
    else
        print_error "Rector failed to load rector.php"
        exit 1
    fi
    
    # Test Pint config loading
    print_info "Testing Pint config loading..."
    if docker compose -f docker-compose.dev.yml exec app php -d memory_limit=1G vendor/bin/pint --config=pint.json --test > /dev/null 2>&1; then
        print_success "Pint loads pint.json correctly"
    else
        print_error "Pint failed to load pint.json"
        exit 1
    fi
    
    # Test PHPStan config loading
    print_info "Testing PHPStan config loading..."
    if docker compose -f docker-compose.dev.yml exec app php -d memory_limit=1G vendor/bin/phpstan analyse --configuration=phpstan.neon --no-progress > /dev/null 2>&1; then
        print_success "PHPStan loads phpstan.neon correctly"
    else
        print_error "PHPStan failed to load phpstan.neon"
        exit 1
    fi
    
    # Test Pest config loading
    print_info "Testing Pest config loading..."
    if docker compose -f docker-compose.dev.yml exec app php -d memory_limit=1G vendor/bin/pest --parallel --list-tests > /dev/null 2>&1; then
        print_success "Pest loads pest.php correctly"
    else
        print_error "Pest failed to load pest.php"
        exit 1
    fi
    
    cd ..
}

print_summary() {
    echo -e "${GREEN}"
    echo "🎉 =================================="
    echo "   All Tools Working!"
    echo "===================================="
    echo -e "${NC}"
    
    echo -e "${YELLOW}📋 Configuration Summary:${NC}"
    echo "  🔄 Rector:   rector.php loaded ✅"
    echo "  🎨 Pint:     pint.json loaded ✅"
    echo "  🔍 PHPStan:  phpstan.neon loaded ✅"
    echo "  🧪 Pest:     pest.php loaded ✅"
    echo ""
    echo -e "${YELLOW}🚀 Ready for:${NC}"
    echo "  - Pre-push auto-fixes"
    echo "  - Manual code quality checks"
    echo "  - Continuous integration"
    echo ""
    echo -e "${GREEN}All systems go! 🚀${NC}"
}

# Main execution
main() {
    print_header
    
    # Check if we're in the right directory
    if [ ! -f "package.json" ] || [ ! -d "backend" ]; then
        print_error "This script must be run from the ForkFlash project root directory"
        exit 1
    fi
    
    # Check if Docker is running
    if ! docker info &> /dev/null; then
        print_error "Docker is not running. Please start Docker first."
        exit 1
    fi
    
    # Check if containers are running
    cd backend
    if ! docker compose -f docker-compose.dev.yml ps --services --filter "status=running" | grep -q "app"; then
        print_error "Development containers are not running. Run 'npm run docker:dev' first."
        exit 1
    fi
    cd ..
    
    test_config_files
    test_tools
    print_summary
}

# Run main function
main "$@"
