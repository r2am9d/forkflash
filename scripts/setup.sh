#!/bin/bash

# 🍴 ForkFlash Setup Script
# Comprehensive setup for Laravel + Docker development environment

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Emojis
SUCCESS="✅"
ERROR="❌"
INFO="ℹ️"
ROCKET="🚀"
GEAR="⚙️"
DATABASE="🗄️"
DOCKER="🐳"
PHP="🐘"

print_header() {
    echo -e "${CYAN}"
    echo "🍴 =================================="
    echo "   ForkFlash Setup Script"
    echo "   Laravel + Docker + Development Tools"
    echo "=================================== 🍴"
    echo -e "${NC}"
}

print_step() {
    echo -e "${BLUE}${GEAR} $1${NC}"
}

print_success() {
    echo -e "${GREEN}${SUCCESS} $1${NC}"
}

print_error() {
    echo -e "${RED}${ERROR} $1${NC}"
}

print_info() {
    echo -e "${YELLOW}${INFO} $1${NC}"
}

check_requirements() {
    print_step "Checking system requirements..."
    
    # Check for required commands
    local required_commands=("git" "docker" "node" "npm")
    local missing_commands=()
    
    for cmd in "${required_commands[@]}"; do
        if ! command -v "$cmd" &> /dev/null; then
            missing_commands+=("$cmd")
        fi
    done
    
    if [ ${#missing_commands[@]} -ne 0 ]; then
        print_error "Missing required commands: ${missing_commands[*]}"
        echo -e "${YELLOW}Please install the missing requirements:${NC}"
        echo "  - Git: https://git-scm.com/downloads"
        echo "  - Docker: https://docs.docker.com/get-docker/"
        echo "  - Node.js & npm: https://nodejs.org/"
        exit 1
    fi
    
    # Check Docker daemon
    if ! docker info &> /dev/null; then
        print_error "Docker daemon is not running. Please start Docker."
        exit 1
    fi
    
    print_success "All requirements satisfied"
}

setup_node_dependencies() {
    print_step "Installing Node.js dependencies and setting up git hooks..."
    
    if [ ! -f "package.json" ]; then
        print_error "package.json not found. Are you in the correct directory?"
        exit 1
    fi
    
    # Install Node.js dependencies
    npm install
    
    # The postinstall script should have run, but let's ensure git hooks are set up
    if [ ! -d ".husky" ]; then
        print_info "Setting up git hooks manually..."
        ./scripts/setup-dev.sh
    fi
    
    print_success "Node.js dependencies installed and git hooks configured"
}

setup_laravel_backend() {
    print_step "Setting up Laravel backend..."
    
    cd backend
    
    # Create .env file if it doesn't exist
    if [ ! -f ".env" ]; then
        print_info "Creating .env file from .env.example"
        cp .env.example .env
        
        # Update .env with Docker database settings
        sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env
        sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env
        sed -i 's/DB_PORT=3306/DB_PORT=5432/' .env
        sed -i 's/DB_DATABASE=laravel/DB_DATABASE=forkflash_dev/' .env
        sed -i 's/DB_USERNAME=root/DB_USERNAME=postgres/' .env
        sed -i 's/DB_PASSWORD=/DB_PASSWORD=password/' .env
        
        # Redis configuration
        echo "" >> .env
        echo "# Redis Configuration" >> .env
        echo "REDIS_HOST=redis" >> .env
        echo "REDIS_PASSWORD=null" >> .env
        echo "REDIS_PORT=6379" >> .env
        
        # Mail configuration for MailHog
        echo "" >> .env
        echo "# Mail Configuration (MailHog)" >> .env
        echo "MAIL_MAILER=smtp" >> .env
        echo "MAIL_HOST=mailhog" >> .env
        echo "MAIL_PORT=1025" >> .env
        echo "MAIL_USERNAME=null" >> .env
        echo "MAIL_PASSWORD=null" >> .env
        echo "MAIL_ENCRYPTION=null" >> .env
        
        print_success ".env file created and configured for Docker"
    else
        print_info ".env file already exists"
    fi
    
    # Install Composer dependencies
    if [ ! -d "vendor" ]; then
        print_info "Installing Composer dependencies..."
        if command -v composer &> /dev/null; then
            composer install --no-interaction
        else
            print_info "Composer not found locally, will install dependencies in Docker"
        fi
    else
        print_info "Composer dependencies already installed"
    fi
    
    cd ..
    print_success "Laravel backend setup complete"
}

start_docker_environment() {
    print_step "Starting Docker development environment..."
    
    cd backend
    
    # Stop any existing containers
    docker compose -f docker-compose.dev.yml down &> /dev/null || true
    
    # Build and start containers
    print_info "Building and starting Docker containers (this may take a few minutes)..."
    docker compose -f docker-compose.dev.yml up -d --build
    
    # Wait for services to be ready
    print_info "Waiting for services to be ready..."
    sleep 10
    
    # Check if services are running
    if docker compose -f docker-compose.dev.yml ps --services --filter "status=running" | grep -q "app"; then
        print_success "Docker containers are running"
    else
        print_error "Some Docker containers failed to start"
        docker compose -f docker-compose.dev.yml logs
        exit 1
    fi
    
    cd ..
}

setup_laravel_application() {
    print_step "Configuring Laravel application..."
    
    cd backend
    
    # Generate application key if not exists
    if ! grep -q "APP_KEY=base64:" .env; then
        print_info "Generating Laravel application key..."
        docker compose -f docker-compose.dev.yml exec app php artisan key:generate --no-interaction
    fi
    
    # Install Composer dependencies in container if needed
    if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
        print_info "Installing Composer dependencies in Docker container..."
        docker compose -f docker-compose.dev.yml exec app composer install --no-interaction
    fi
    
    # Wait for database to be ready
    print_info "Waiting for database to be ready..."
    for i in {1..30}; do
        if docker compose -f docker-compose.dev.yml exec app php artisan db:monitor --no-interaction &> /dev/null; then
            break
        fi
        if [ $i -eq 30 ]; then
            print_error "Database did not become ready in time"
            exit 1
        fi
        sleep 2
    done
    
    # Run database migrations
    print_info "Running database migrations..."
    docker compose -f docker-compose.dev.yml exec app php artisan migrate --no-interaction --force
    
    # Create Filament admin user if not exists
    print_info "Setting up Filament admin panel..."
    docker compose -f docker-compose.dev.yml exec app php artisan make:filament-user --name="Admin" --email="admin@forkflash.local" --password="password" --no-interaction &> /dev/null || true
    
    # Install Filament Shield
    docker compose -f docker-compose.dev.yml exec app php artisan shield:install --fresh --no-interaction &> /dev/null || true
    
    # Seed database with sample data
    print_info "Seeding database with sample data..."
    docker compose -f docker-compose.dev.yml exec app php artisan db:seed --no-interaction &> /dev/null || true
    
    # Clear and cache configuration
    print_info "Optimizing Laravel configuration..."
    docker compose -f docker-compose.dev.yml exec app php artisan config:clear
    docker compose -f docker-compose.dev.yml exec app php artisan cache:clear
    docker compose -f docker-compose.dev.yml exec app php artisan view:clear
    
    cd ..
    print_success "Laravel application configured successfully"
}

verify_installation() {
    print_step "Verifying installation..."
    
    # Check if containers are running
    cd backend
    local running_services=$(docker compose -f docker-compose.dev.yml ps --services --filter "status=running" | wc -l)
    local total_services=$(docker compose -f docker-compose.dev.yml config --services | wc -l)
    
    if [ "$running_services" -eq "$total_services" ]; then
        print_success "All Docker services are running ($running_services/$total_services)"
    else
        print_error "Some services are not running ($running_services/$total_services)"
    fi
    
    # Test Laravel application
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200"; then
        print_success "Laravel application is responding"
    else
        print_error "Laravel application is not responding on http://localhost:8000"
    fi
    
    cd ..
    
    # Check git hooks setup
    if [ -f ".husky/commit-msg" ] && [ -f ".husky/pre-push" ]; then
        print_success "Git hooks (conventional commits & pre-push) are configured"
    else
        print_error "Git hooks are not properly configured"
    fi
    
    # Check PHPStan level
    if grep -q "level: 6" backend/phpstan.neon; then
        print_success "PHPStan configured at level 6 for fast development"
    else
        print_error "PHPStan level not properly configured"
    fi
}

print_access_info() {
    echo -e "${CYAN}"
    echo "🎉 =================================="
    echo "   Setup Complete!"
    echo "===================================="
    echo -e "${NC}"
    
    echo -e "${GREEN}${ROCKET} Your ForkFlash development environment is ready!${NC}"
    echo ""
    echo -e "${YELLOW}📍 Access Points:${NC}"
    echo "  🌐 Application:       http://localhost:8000"
    echo "  👨‍💼 Admin Panel:       http://localhost:8000/admin"
    echo "  🗄️  Database UI:       http://localhost:8080 (CloudBeaver)"
    echo "  📧 Mail Testing:      http://localhost:8025 (MailHog)"
    echo "  📦 Object Storage:    http://localhost:9001 (MinIO Console)"
    echo ""
    echo -e "${YELLOW}🔐 Default Credentials:${NC}"
    echo "  Admin Panel:   admin@forkflash.local / password"
    echo "  Database:      postgres / password"
    echo "  MinIO:         minio / minio123"
    echo ""
    echo -e "${YELLOW}💡 Useful Commands:${NC}"
    echo "  View logs:     npm run docker:dev-logs"
    echo "  Stop env:      npm run docker:dev-down"
    echo "  Restart:       npm run docker:dev"
    echo "  Run tests:     npm run backend:test"
    echo "  Format code:   npm run backend:format"
    echo "  Test tools:    npm run test:tools"
    echo "  Test env:      npm run test:env"
    echo "  Test hooks:    git commit --dry-run (test conventional commits)"
    echo ""
    echo -e "${YELLOW}🔧 Development Features:${NC}"
    echo "  ✅ Conventional commits enforced"
    echo "  ✅ Pre-push validation (format, refactor, analysis, tests)"
    echo "  ✅ PHPStan level 6 for fast development"
    echo "  ✅ Rector for code improvements"
    echo "  ✅ Pest for elegant testing"
    echo "  ✅ Xdebug enabled for debugging"
    echo "  ✅ Hot reload with volume mounts"
    echo ""
    echo -e "${BLUE}📖 Documentation:${NC}"
    echo "  Development:   DEVELOPMENT.md"
    echo "  Docker Guide:  backend/DOCKER.md"
    echo ""
    echo -e "${GREEN}Happy coding! 🚀${NC}"
}

cleanup_on_error() {
    print_error "Setup failed. Cleaning up..."
    cd backend &> /dev/null && docker compose -f docker-compose.dev.yml down &> /dev/null || true
    exit 1
}

# Main execution
main() {
    # Trap errors and cleanup
    trap cleanup_on_error ERR
    
    print_header
    
    # Check if we're in the right directory
    if [ ! -f "package.json" ] || [ ! -d "backend" ]; then
        print_error "This script must be run from the ForkFlash project root directory"
        exit 1
    fi
    
    check_requirements
    setup_node_dependencies
    setup_laravel_backend
    start_docker_environment
    setup_laravel_application
    verify_installation
    print_access_info
}

# Run main function
main "$@"
