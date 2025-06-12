# 🍴 ForkFlash

> A smart recipe companion app with AI voice assistant, offline storage, and grocery management.

**Tech Stack:** Laravel + Filament (Backend) | Flutter (Mobile) | PostgreSQL + Redis

---

## ⚡ Quick Start

```bash
# Clone and setup everything
git clone https://github.com/yourusername/forkflash.git
cd forkflash
npm run setup:full
```

That's it! 🎉 Your development environment will be running at:
- **App**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Database UI**: http://localhost:8080 (CloudBeaver)
- **Mail Testing**: http://localhost:8025 (MailHog)

---

## 🛠️ Manual Setup

<details>
<summary>Click to expand manual setup instructions</summary>

### Prerequisites
```bash
# Required
docker & docker-compose
node.js & npm
git

# Optional (for local development without Docker)
php 8.2+
composer
postgresql
redis
```

### Steps
```bash
# 1. Clone repository
git clone https://github.com/yourusername/forkflash.git
cd forkflash

# 2. Install dependencies & setup hooks
npm run setup:dev

# 3. Setup Laravel backend
cd backend
cp .env.example .env
composer install
php artisan key:generate

# 4. Start Docker environment
cd ..
npm run docker:dev

# 5. Setup database
npm run backend:migrate
npm run backend:seed
```

</details>

---

## 🚀 Available Commands

### Development
```bash
npm run docker:dev          # Start development environment
npm run docker:dev-build    # Rebuild and start
npm run docker:dev-down     # Stop development environment
npm run docker:dev-logs     # View logs

# Legacy aliases
npm run docker:up           # Same as docker:dev
npm run docker:down         # Same as docker:dev-down
```

### Backend
```bash
npm run backend:install     # Install PHP dependencies
npm run backend:migrate     # Run database migrations
npm run backend:seed        # Seed database with test data
npm run backend:serve       # Start Laravel dev server (local)
npm run backend:test        # Run tests with Pest
npm run backend:format      # Format code with Pint
npm run backend:analyse     # Run PHPStan analysis
npm run backend:refactor    # Apply code improvements with Rector
npm run backend:refactor-dry # Preview Rector improvements
npm run backend:check-all   # Run all quality checks (format, analyse, test)
npm run backend:fix-all     # Auto-fix all issues (refactor, format, analyse, test)
```

### Utilities
```bash
npm run setup:dev           # Setup development environment only (git hooks)
npm run setup:full          # Complete setup (Laravel, Docker, database)
npm run test:env            # Test environment setup
npm run test:tools          # Test code quality tools configuration
npm run verify:naming       # Verify script naming convention consistency
```

**📝 Naming Convention**: 
- **Script files**: Use hyphens (e.g., `scripts/setup-dev.sh`, `scripts/setup-full.sh`)
- **npm commands**: Use colons (e.g., `npm run setup:dev`, `npm run setup:full`)
- **1:1 Mapping**: Each script file has a corresponding npm command for intuitive usage

### Production
```bash
npm run docker:prod         # Start production environment
npm run docker:prod-build   # Build and start production
npm run docker:prod-down    # Stop production environment
```

---

## 📁 Project Structure

```
forkflash/
├── backend/                 # Laravel API + Filament Admin
│   ├── app/                # Application code
│   ├── database/           # Migrations, seeders, factories
│   ├── docker/             # Docker configurations
│   ├── routes/             # API and web routes
│   └── ...
├── mobile/                 # Flutter app (coming soon)
├── scripts/                # Setup and utility scripts
└── package.json           # Project scripts and dependencies
```

---

## 🔧 Development Features

- **🔄 Hot Reload**: Code changes reflect instantly
- **🐛 Debugging**: Xdebug enabled for PHP debugging
- **📧 Email Testing**: MailHog catches all emails
- **🗄️ Database UI**: CloudBeaver for PostgreSQL management
- **📊 Object Storage**: MinIO for S3-compatible testing
- **✅ Code Quality**: Pre-commit hooks with Pint, Rector, PHPStan, and Pest
- **🧪 Testing**: Pest for elegant and expressive testing
- **🔍 Static Analysis**: PHPStan level 6 for fast development
- **🔄 Code Refactoring**: Rector for automated code improvements
- **🔒 Security**: Production-ready Docker with security hardening

---

## 🏗️ Architecture

### Backend (Laravel + Filament)
- **API**: RESTful endpoints for mobile app
- **Admin Panel**: Filament-powered admin interface
- **Authentication**: Sanctum for API, Filament Breezy for admin
- **Database**: PostgreSQL with Redis caching
- **Queues**: Redis-backed job processing
- **Storage**: S3-compatible object storage

### Frontend (Flutter) - Coming Soon
- **Mobile App**: iOS and Android
- **Offline Support**: Local storage for recipes
- **Voice Assistant**: AI-powered cooking guidance

---

## 📖 Documentation

- **[Development Setup](DEVELOPMENT.md)** - Detailed development guide
- **[Docker Guide](backend/DOCKER.md)** - Docker environments explained
- **[API Documentation](backend/docs/api.md)** - API endpoints (coming soon)

---

## 🧪 Testing

```bash
# Run all tests with Pest
npm run backend:test

# Run specific test types
npm run backend:test:unit        # Unit tests only
npm run backend:test:feature     # Feature tests only
npm run backend:test:coverage    # Generate code coverage report
```

**Testing Framework**: [Pest](https://pestphp.com/) - The elegant PHP testing framework focused on simplicity.

---

## 🚀 Deployment

### Development
```bash
npm run docker:dev
```

### Production
```bash
# Build and deploy
npm run docker:prod-build

# Environment variables
cp .env.example .env.prod
# Edit .env.prod with production settings
```

---

## 🤝 Contributing

1. **Fork** the repository
2. **Clone** your fork: `git clone <your-fork-url>`
3. **Setup**: Run `npm run setup:full`
4. **Branch**: `git checkout -b feature/amazing-feature`
5. **Code**: Make your changes (hooks ensure quality)
6. **Test**: `npm run backend:test`
7. **Commit**: Use conventional commits (enforced by hooks)
8. **Push**: `git push origin feature/amazing-feature`
9. **PR**: Create a pull request

### Code Quality
- **Formatting**: Automatic with PHP Pint
- **Analysis**: PHPStan level 6
- **Testing**: Pest framework
- **Commits**: Conventional commits enforced
- **Pre-push**: All checks must pass

---

## 📄 License

**Dual License:**
- **MIT License** - Non-commercial use (personal, education, open-source)
- **Commercial License** - Commercial use (contact: neatadmiral@gmail.com)

See [LICENSE.md](backend/LICENSE.md) for details.

---

## 💡 Tech Stack

| Component | Technology | Purpose |
|-----------|------------|---------|
| **Backend** | Laravel 12 + Filament 3 | API + Admin Panel |
| **Database** | PostgreSQL 15 | Primary database |
| **Cache** | Redis 7 | Caching + Queues |
| **Search** | Laravel Scout | Full-text search |
| **Storage** | S3/MinIO | File storage |
| **Mobile** | Flutter | iOS/Android app |
| **DevOps** | Docker + Docker Compose | Development + Deployment |
