# PHP-Box Installation Guide

This guide provides multiple ways to set up your environment for php-box development.

## Table of Contents
1. [Quick Start (Automated)](#quick-start-automated)
2. [Manual Installation](#manual-installation)
3. [Docker Setup](#docker-setup)
4. [Troubleshooting](#troubleshooting)

---

## Quick Start (Automated)

### Option 1: Full Setup Script (Recommended)

```bash
# Clone or navigate to project
cd /path/to/php-box

# Run setup script
sudo bash setup.sh
```

**What it installs:**
- PHP 8.4 with all extensions
- Composer
- SQLite3
- Git and development tools
- Nginx (optional)
- PHPUnit for testing

### Option 2: Quick Setup (Minimal)

For a minimal installation without optional tools:

```bash
sudo bash quick-setup.sh
```

**Supports:**
- Debian/Ubuntu (apt)
- Fedora/RHEL/CentOS (dnf)
- Arch Linux (pacman)
- Alpine Linux (apk)

---

## Manual Installation

### 1. Add PHP 8.4 Repository

**Debian/Ubuntu:**
```bash
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
```

**Fedora/RHEL:**
```bash
sudo dnf install https://rpms.remirepo.net/fedora/remi-release-$(rpm -E %fedora).rpm
sudo dnf module reset php -y
sudo dnf module enable php:remi-8.4 -y
```

**Arch Linux:**
```bash
# PHP 8.4 is in the official repository
sudo pacman -Syu
```

### 2. Install PHP 8.4

**Debian/Ubuntu:**
```bash
sudo apt-get install -y php8.4-cli php8.4-fpm php8.4-dev
```

**Fedora/RHEL:**
```bash
sudo dnf install -y php-cli php-fpm php-devel
```

**Arch Linux:**
```bash
sudo pacman -S php
```

### 3. Install Required Extensions

**Debian/Ubuntu:**
```bash
sudo apt-get install -y \
    php8.4-sqlite3 \
    php8.4-pdo \
    php8.4-curl \
    php8.4-mbstring \
    php8.4-xml \
    php8.4-json \
    php8.4-intl
```

**Fedora/RHEL:**
```bash
sudo dnf install -y \
    php-sqlite3 \
    php-pdo \
    php-curl \
    php-mbstring \
    php-xml \
    php-json \
    php-intl
```

### 4. Install Composer

```bash
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
```

### 5. Install Project Dependencies

```bash
cd /path/to/php-box
composer install
```

---

## Docker Setup

### Option 1: Using Docker Compose (Easiest)

```bash
# Build and start containers
docker-compose up -d

# Access application at http://localhost:6969
```

**Available services:**
- `php-box` - Development server on port 6969
- `db-browser` - Database browser on port 8080

### Option 2: Using Docker Directly

```bash
# Build image
docker build -t php-box .

# Run container
docker run -it -p 6969:6969 -v $(pwd):/app php-box
```

### Option 3: Using Dev Container (VS Code)

```bash
# Open in VS Code with Dev Container extension
code /path/to/php-box
```

Press `Ctrl+Shift+P` and select "Dev Containers: Reopen in Container"

---

## Verify Installation

```bash
# Check PHP version
php -v

# Check extensions
php -m | grep -E "(sqlite3|pdo|curl)"

# Check Composer
composer --version

# Check installed dependencies
composer show
```

---

## Initialize Database

```bash
# Create database directory
mkdir -p auth/src/database

# Initialize with seed data
sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql

# Verify
sqlite3 auth/src/database/auth.sqlite3 ".tables"
```

---

## Start Development

### Using Composer Script

```bash
composer run dev
```

Then visit: **http://localhost:6969**

### Using PHP Built-in Server

```bash
php -S localhost:6969 -t auth/public/
```

### Using Docker

```bash
docker-compose up
```

---

## Run Tests

```bash
# All tests
composer test

# Specific test file
php vendor/bin/phpunit auth/tests/UserModelTest.php

# With coverage report
php vendor/bin/phpunit auth/tests/ --coverage-html coverage/
```

---

## Troubleshooting

### Issue: "sudo: command not found" or Permission Denied

**Solution:** Use your system's package manager directly:
```bash
# Without sudo on some systems
apt-get install php8.4-cli
```

### Issue: "PHP command not found" after installation

**Solution:** Create symlink:
```bash
sudo ln -s /usr/bin/php8.4 /usr/bin/php
```

### Issue: "SQLite extension not found"

**Solution:** Install PHP SQLite extension:
```bash
# Debian/Ubuntu
sudo apt-get install php8.4-sqlite3

# Fedora/RHEL
sudo dnf install php-sqlite3
```

### Issue: "Composer command not found"

**Solution:** Add Composer to PATH:
```bash
export PATH=$PATH:~/.composer/vendor/bin:/usr/local/bin
```

Or reinstall:
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Issue: "Port 6969 already in use"

**Solution:** Use different port:
```bash
php -S localhost:7777 -t auth/public/
```

Or find process using port:
```bash
lsof -i :6969
kill -9 <PID>
```

### Issue: Database file not found

**Solution:** Create and seed database:
```bash
mkdir -p auth/src/database
sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql
```

### Issue: "Class not found" or Autoload error

**Solution:** Regenerate Composer autoloader:
```bash
composer dumpautoload
```

### Issue: Docker permission denied

**Solution:** Add user to docker group:
```bash
sudo usermod -aG docker $USER
newgrp docker
```

---

## PHP Configuration

### Development php.ini settings

The setup script configures these values:

```ini
display_errors = On
error_reporting = E_ALL
memory_limit = 512M
post_max_size = 100M
upload_max_filesize = 100M
```

Location: `/etc/php/8.4/cli/php.ini`

### Edit PHP Configuration

```bash
# View configuration
php -i | grep php.ini

# Edit main php.ini
sudo nano /etc/php/8.4/cli/php.ini

# Or for FPM
sudo nano /etc/php/8.4/fpm/php.ini
```

---

## Environment-Specific Setup

### Development
```bash
composer install
php -S localhost:6969 -t auth/public/
```

### Testing
```bash
composer install --dev
composer test
```

### Production (Nginx)
```bash
# See SETUP_GUIDE.md for Nginx configuration
sudo nano /etc/nginx/sites-available/php-box
sudo systemctl restart nginx
```

---

## Next Steps

1. **Read Documentation**
   - [SETUP_GUIDE.md](./SETUP_GUIDE.md) - Detailed setup guide
   - [README.md](./README.md) - Project overview

2. **Learn Testing**
   - [auth/TESTING.md](./auth/TESTING.md) - Testing guide
   - [auth/CONTROLLER_TESTS.md](./auth/CONTROLLER_TESTS.md) - Controller tests

3. **Start Coding**
   - Run tests: `composer test`
   - Start server: `composer run dev`
   - Edit code: `auth/src/` directory

4. **Explore Examples**
   - Route: `auth/public/index.php`
   - Controller: `auth/src/controller/UserController.php`
   - Model: `auth/src/model/UserModel.php`
   - Tests: `auth/tests/`

---

## System Requirements

- **OS:** Linux (Debian/Ubuntu/Fedora/Arch/Alpine)
- **PHP:** 8.4+
- **RAM:** 1GB minimum
- **Disk:** 500MB free
- **Docker:** (Optional) for containerized setup

---

## Getting Help

1. Check [Troubleshooting](#troubleshooting) section
2. Review [SETUP_GUIDE.md](./SETUP_GUIDE.md)
3. Check logs: `php -S localhost:6969 -t auth/public/ -d display_errors=1`
4. Read documentation in each subdirectory

---

**Ready to start? Run: `sudo bash setup.sh`** 🚀
