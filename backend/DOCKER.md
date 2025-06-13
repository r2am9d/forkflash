# 🐳 Docker Setup Guide

This document explains the multi-stage Docker setup for ForkFlash with separate development and production environments.

## 📁 Docker Structure

```
backend/
├── Dockerfile                    # Multi-stage build (base, development, production)
├── docker-compose.dev.yml        # Development environment
├── docker-compose.prod.yml       # Production environment  
├── docker-compose.yml            # Symlink to dev (for compatibility)
└── docker/
    ├── nginx/
    │   ├── development.conf       # Dev Nginx config (debugging enabled)
    │   └── production.conf        # Prod Nginx config (security hardened)
    ├── php/
    │   ├── development.ini        # Dev PHP config (high limits, debugging)
    │   └── production.ini         # Prod PHP config (OPcache, security)
    ├── postgres/
    │   └── init.sql              # Database initialization for development
    └── supervisor/
        └── supervisord.conf      # Production process management
```

## 🔧 Development Environment

### Features
- **Xdebug** enabled for debugging
- **Hot reloading** with volume mounts
- **Development tools**: MailHog, MinIO, CloudBeaver
- **Relaxed security** for easier development
- **Enhanced logging** and error reporting

### Services
- **App**: Laravel with Xdebug
- **Nginx**: Development-friendly configuration
- **PostgreSQL**: With test database
- **Redis**: For caching and queues
- **CloudBeaver**: Database management UI
- **MailHog**: Email testing
- **MinIO**: S3-compatible storage testing

### Quick Start
```bash
# Development (default)
npm run docker:dev
npm run docker:dev-build    # Force rebuild
npm run docker:dev-logs     # View logs
npm run docker:dev-down     # Stop containers

# Or use the symlinked version
npm run docker:up
npm run docker:logs
npm run docker:down
```

### Access Points
- **Application**: http://localhost:8000
- **CloudBeaver**: http://localhost:8080
- **MailHog**: http://localhost:8025
- **MinIO Console**: http://localhost:9001

## 🚀 Production Environment

### Features
- **Multi-stage build** for minimal image size
- **Security hardened** configurations
- **OPcache optimized** for performance
- **Health checks** for all services
- **Automatic backups** for database
- **Rate limiting** and security headers
- **Process management** with Supervisor

### Security Measures
- Composer removed from production image
- Non-root user execution
- Minimal file permissions
- Security headers in Nginx
- Rate limiting on API endpoints
- Sensitive files blocked
- Development tools excluded

### Quick Start
```bash
# Production
npm run docker:prod
npm run docker:prod-build   # Force rebuild
npm run docker:prod-logs    # View logs
npm run docker:prod-down    # Stop containers
```

## 📊 Multi-Stage Build Benefits

### Stage 1: Base
- Common dependencies
- PHP extensions
- System packages
- User creation

### Stage 2: Development  
- Development tools (Git, Node.js, Xdebug)
- All Composer dependencies
- Hot-reload friendly
- Enhanced debugging

### Stage 3: Production
- Production-only files
- No dev dependencies
- Optimized PHP settings
- Security hardened
- Smaller image size

## 🔍 Image Size Comparison

| Stage | Approximate Size | Use Case |
|-------|-----------------|----------|
| Base | ~200MB | Foundation |
| Development | ~800MB | Local development |
| Production | ~400MB | Deployment |

## 🛡️ Security Features (Production)

### Nginx Security
- Rate limiting (API: 10r/s, Login: 5r/m)
- Security headers (CSP, XSS protection)
- Hidden server tokens
- Sensitive file blocking
- HTTPS ready

### PHP Security
- `expose_php = Off`
- Secure session settings
- OPcache optimizations
- Memory limits
- Error logging only

### Container Security
- Non-root user execution
- Minimal file permissions
- Read-only filesystem where possible
- Health checks for monitoring

## 📈 Performance Optimizations

### Development
- Volume mounts for instant changes
- Xdebug for debugging
- Increased timeouts
- Enhanced logging

### Production
- OPcache enabled
- Gzip compression
- Static file caching
- Process supervision
- Connection pooling

## 🔧 Configuration Management

### Environment Variables
```bash
# Development
APP_ENV=local
APP_DEBUG=true
XDEBUG_MODE=debug

# Production  
APP_ENV=production
APP_DEBUG=false
```

### PHP Configuration
- **Development**: High memory, Xdebug, error display
- **Production**: OPcache, security headers, minimal memory

### Nginx Configuration
- **Development**: Debug logging, relaxed security
- **Production**: Rate limiting, security headers, caching

## 📦 Available Scripts

```bash
# Development
npm run docker:dev           # Start dev environment
npm run docker:dev-build     # Build and start dev
npm run docker:dev-down      # Stop dev environment
npm run docker:dev-logs      # View dev logs

# Production
npm run docker:prod          # Start prod environment
npm run docker:prod-build    # Build and start prod
npm run docker:prod-down     # Stop prod environment  
npm run docker:prod-logs     # View prod logs

# Legacy (points to dev)
npm run docker:up            # Start dev environment
npm run docker:down          # Stop dev environment
npm run docker:logs          # View dev logs
```

## 🚨 Important Notes

### Development
- Use for local development only
- Contains debugging tools and relaxed security
- Volume mounts for hot reloading
- Development databases and tools included

### Production
- Never use development compose in production
- Security hardened and optimized
- Automatic health checks
- Database backups included
- Minimal attack surface

### Best Practices
- Always use specific compose files (`-f` flag)
- Review security settings before deployment
- Monitor health check status
- Regular backup verification
- Keep images updated

## 🔄 Migration Guide

### From Old Setup
1. Stop old containers: `docker-compose down`
2. Use new commands: `npm run docker:dev`
3. Update any custom configurations
4. Test both environments

### Environment Switching
```bash
# Switch to development
npm run docker:prod-down
npm run docker:dev

# Switch to production
npm run docker:dev-down  
npm run docker:prod
```

This setup provides a robust, secure, and efficient Docker environment for both development and production use! 🎉
