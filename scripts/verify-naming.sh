#!/bin/bash

# 🔍 Script Naming Convention Verification
# Verifies that npm scripts correctly map to script files

echo "🔍 Verifying script naming convention..."
echo ""

# Check if we're in the right directory
if [ ! -f "package.json" ] || [ ! -d "scripts" ]; then
    echo "❌ Must be run from project root directory"
    exit 1
fi

# Function to check script mapping
check_script_mapping() {
    local npm_name="$1"
    local file_name="$2"
    local script_path="./scripts/$file_name"
    
    if [ -f "$script_path" ]; then
        if grep -q "\"$npm_name\":" package.json; then
            echo "✅ $npm_name → $file_name"
        else
            echo "❌ Missing npm script: $npm_name"
        fi
    else
        echo "❌ Missing script file: $file_name"
    fi
}

echo "📋 Current Script Mappings:"
echo ""

# Check all expected mappings
check_script_mapping "setup:dev" "setup-dev.sh"
check_script_mapping "setup:full" "setup-full.sh"
check_script_mapping "test:env" "test-env.sh"
check_script_mapping "test:tools" "test-tools.sh"

echo ""
echo "📝 Naming Convention:"
echo "   Script files: use hyphens (setup-full.sh)"
echo "   npm commands: use colons (setup:full)"
echo ""

# Verify all script files exist
echo "📁 Script Files in ./scripts/:"
for script in scripts/*.sh; do
    if [ -f "$script" ]; then
        basename "$script"
    fi
done

echo ""
echo "✅ Naming convention verification complete!"
