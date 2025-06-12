# 🛠️ Development Setup

This document explains how the development environment is automatically configured for ForkFlash.

## 🚀 Quick Start

When you clone this repository and run `npm run setup:dev`, the development environment will be automatically set up with:

- **Conventional Commits**: Enforced commit message format
- **Pre-push Hooks**: Code quality checks before pushing
- **Code Formatting**: Automatic PHP formatting validation
- **Static Analysis**: PHPStan analysis
- **Test Validation**: Tests must pass before push

## 📋 What Gets Set Up Automatically

### 1. Git Hooks (Not Tracked)
The `.husky/` directory is automatically created but **not tracked in git**. This means:
- ✅ Every developer gets the same hooks
- ✅ Hooks are not part of the repository
- ✅ No conflicts or unwanted hook modifications

### 2. Commit Message Validation
All commits must follow [Conventional Commits](https://www.conventionalcommits.org/) format:
```
feat: add new recipe search functionality
fix: resolve database connection issue
docs: update installation instructions
```

### 3. Pre-push Validation
Before every push, the following checks run automatically:
- **Code Formatting**: PHP Pint formatting check
- **Static Analysis**: PHPStan level 8 analysis
- **Tests**: All tests must pass

## 🔧 Manual Setup

If automatic setup doesn't work, run:
```bash
npm run setup
```

## 🚫 Bypassing Hooks (Emergency Only)

If you need to bypass hooks in an emergency:
```bash
# Skip commit message validation
git commit --no-verify -m "emergency fix"

# Skip pre-push validation  
git push --no-verify
```

**⚠️ Note**: Only use `--no-verify` in true emergencies. The hooks ensure code quality and prevent issues.

## 📝 Available Scripts

```bash
# Backend commands
npm run backend:install      # Install PHP dependencies
npm run backend:format       # Format PHP code
npm run backend:format-check # Check PHP formatting
npm run backend:analyse      # Run static analysis
npm run backend:test         # Run tests
npm run backend:serve        # Start Laravel server

# Docker commands
npm run docker:up           # Start Docker containers
npm run docker:down         # Stop Docker containers
npm run docker:logs         # View Docker logs

# Development setup
npm run setup              # Manually run development setup
npm run lint:commit        # Check last commit message
```

## 🎯 Benefits

This setup ensures:
- **Consistent Code Quality**: All code meets the same standards
- **Reliable History**: Clean, readable commit messages
- **Reduced Bugs**: Static analysis catches issues early
- **Team Consistency**: Everyone follows the same practices
- **CI/CD Ready**: Code is pre-validated before reaching CI

## 🤝 Contributing

The development environment setup is designed to make contributing easy:
1. Clone the repository
2. Run `npm run setup:dev` (or `npm install` which auto-runs it)
3. Start coding! The hooks will guide you

## 📋 Script Naming Convention

ForkFlash uses a consistent naming convention for scripts:
- **Script files**: Use hyphens (e.g., `scripts/setup-dev.sh`)
- **npm commands**: Use colons (e.g., `npm run setup:dev`)

This creates an intuitive 1:1 mapping:
- `setup-dev.sh` → `npm run setup:dev`
- `setup-full.sh` → `npm run setup:full`
- `test-env.sh` → `npm run test:env`
- `test-tools.sh` → `npm run test:tools`

The setup script handles everything automatically, so you can focus on building great features! 🚀
