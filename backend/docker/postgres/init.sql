-- Development database initialization script
-- Creates additional databases for testing

-- Create test database
CREATE DATABASE forkflash_test;

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE forkflash_test TO postgres;

-- Set timezone
SET timezone = 'UTC';
