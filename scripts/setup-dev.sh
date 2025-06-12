#!/bin/bash

# ForkFlash Development Setup Script
# This script sets up git hooks for conventional commits and pre-push validation

echo "🍴 Setting up ForkFlash development environment..."

# Check if we're in a git repository
if [ ! -d ".git" ]; then
    echo "⚠️  Not in a git repository. Initializing git..."
    git init
    echo "✅ Git repository initialized"
fi

# Check if husky is installed
if [ ! -f "node_modules/.bin/husky" ]; then
    echo "⚠️  Husky not found. Installing dependencies..."
    npm install
fi

# Set up husky
echo "🔧 Setting up git hooks..."
npx husky init

# Create commit-msg hook for conventional commits
cat > .husky/commit-msg << 'EOF'
#!/bin/sh
npx --no -- commitlint --edit $1
EOF

# Create pre-push hook for validation
cat > .husky/pre-push << 'EOF'
#!/bin/sh
echo "🔍 Running pre-push validation and auto-fixes..."

# Check if backend directory exists
if [ -d "backend" ]; then
    echo "📋 Entering backend directory..."
    cd backend
    
    # Ensure config files exist
    if [ ! -f "rector.php" ]; then
        echo "❌ rector.php config file not found!"
        exit 1
    fi
    
    if [ ! -f "pint.json" ]; then
        echo "❌ pint.json config file not found!"
        exit 1
    fi
    
    if [ ! -f "phpstan.neon" ]; then
        echo "❌ phpstan.neon config file not found!"
        exit 1
    fi
    
    # Step 1: Run Rector to automatically fix code issues
    echo "🔄 Running Rector to fix code issues..."
    if php -d memory_limit=1G vendor/bin/rector process --config=rector.php --no-progress-bar; then
        echo "✅ Rector completed successfully"
        # Stage any changes made by Rector
        git add . || true
    else
        echo "❌ Rector failed to process files"
        exit 1
    fi
    
    # Step 2: Run Pint to format code
    echo "🎨 Running Pint to format code..."
    if php -d memory_limit=1G vendor/bin/pint --config=pint.json; then
        echo "✅ Code formatting completed"
        # Stage any changes made by Pint
        git add . || true
    else
        echo "❌ Code formatting failed"
        exit 1
    fi
    
    # Step 3: Run PHPStan analysis (should pass after fixes)
    echo "🔍 Running PHPStan static analysis..."
    if ! php -d memory_limit=1G vendor/bin/phpstan analyse --configuration=phpstan.neon --memory-limit=1G --no-progress; then
        echo "❌ Static analysis failed even after auto-fixes."
        echo "💡 Some issues require manual intervention. Check the output above."
        echo "💡 Run 'npm run backend:analyse' locally to see detailed errors."
        exit 1
    fi
    
    # Step 4: Run tests with Pest
    echo "🧪 Running tests with Pest..."
    if ! php -d memory_limit=1G vendor/bin/pest --no-interaction; then
        echo "❌ Tests failed. Please fix the failing tests before pushing."
        echo "💡 Run 'npm run backend:test' locally to debug test failures."
        exit 1
    fi
    
    cd ..
fi

echo "✅ All pre-push checks passed! Code auto-fixed and validated."
EOF

# Make hooks executable
chmod +x .husky/commit-msg
chmod +x .husky/pre-push

echo "✅ Git hooks set up successfully!"
echo ""
echo "📋 Development environment ready!"
echo "   - Conventional commits enforced"
echo "   - Pre-push validation with auto-fixes enabled"
echo "   - Code formatting with Pint (auto-fix)"
echo "   - Code improvements with Rector (auto-fix)"
echo "   - Static analysis with PHPStan level 6"
echo "   - Tests run with Pest before push"
echo "   - All config files properly loaded"
echo "   - Multi-stage Docker builds available"
echo ""
echo "🐳 Docker environments:"
echo "   - Development: npm run docker:dev"
echo "   - Production:  npm run docker:prod"
echo ""
echo "💡 Tip: Use 'npm run backend:format' to format your code manually"
echo "💡 Tip: Use 'npm run backend:analyse' to run static analysis manually"
echo "💡 Tip: Use 'npm run backend:refactor' to apply code improvements manually"
echo "💡 Tip: Use 'npm run backend:test' to run tests with Pest manually"
echo "💡 Tip: See backend/DOCKER.md for Docker documentation"
