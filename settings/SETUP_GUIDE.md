# PHP 8.4 Setup Guide for php-box

## Quick Start

```bash
# Clone or navigate to php-box
cd /path/to/php-box

# Run setup script with sudo
sudo bash setup.sh
```

The script will:
- ✅ Add PHP 8.4 repository (Sury's PPA)
- ✅ Install PHP 8.4 CLI and FPM
- ✅ Install all required PHP extensions
- ✅ Install Composer
- ✅ Install SQLite3 tools
- ✅ Install Git and development tools
- ✅ Configure PHP for development
- ✅ Install project dependencies

---

## What Gets Installed

### Core PHP 8.4
- `php8.4-cli` - PHP command line interface
- `php8.4-fpm` - PHP FastCGI Process Manager (for Nginx)
- `php8.4-dev` - PHP development headers

### PHP Extensions (Required for php-box)
| Extension | Purpose |
|-----------|---------|
| `sqlite3` | SQLite database support |
| `pdo` | PHP Data Objects (database abstraction) |
| `curl` | HTTP requests |
| `mbstring` | Multibyte string support |
| `xml` | XML parsing |
| `json` | JSON encoding/decoding |
| `intl` | Internationalization |
| `gd` | Image processing |
| `zip` | ZIP file support |

### Development Tools
- **Composer** - PHP package manager
- **SQLite3** - Database CLI tool
- **Git** - Version control
- **Nginx** (optional) - Web server
- **curl, wget, nano, vim, htop, build-essential**

---

## Manual Installation (If Script Fails)

### 1. Add PHP Repository

```bash
# For Debian/Ubuntu
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
```

### 2. Install PHP 8.4

```bash
# Core PHP
sudo apt-get install -y php8.4-cli php8.4-fpm php8.4-dev

# Essential extensions
sudo apt-get install -y \
    php8.4-sqlite3 \
    php8.4-pdo \
    php8.4-curl \
    php8.4-mbstring \
    php8.4-xml \
    php8.4-json \
    php8.4-intl
```

### 3. Install Composer

```bash
# Download installer
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Install to system
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Clean up
rm composer-setup.php

# Verify
composer --version
```

### 4. Install Other Tools

```bash
sudo apt-get install -y sqlite3 git curl wget
```

---

## Verify Installation

```bash
# Check PHP version
php -v

# Check extensions
php -m | grep -E "(sqlite3|pdo|curl|json)"

# Check Composer
composer --version

# Check SQLite
sqlite3 --version

# Check Git
git --version
```

---

## Setup php-box Project

### 1. Navigate to Project

```bash
cd /path/to/php-box
```

### 2. Install Dependencies

```bash
composer install
```

This installs PHPUnit and other dependencies defined in `composer.json`.

### 3. Initialize Database

```bash
# Create database directory if needed
mkdir -p auth/src/database

# Initialize SQLite database with seed data
sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql
```

### 4. Run Development Server

```bash
# Using composer script (recommended)
composer run dev

# Or directly
php -S localhost:6969 -t auth/public/
```

Access at: **http://localhost:6969**

---

## Run Tests

```bash
# Run all tests
composer test

# Run specific test file
php vendor/bin/phpunit auth/tests/UserModelTest.php

# Run with coverage report
php vendor/bin/phpunit auth/tests/ --coverage-html coverage/
```

---

## Troubleshooting

### "PHP command not found"
```bash
# Verify PHP is installed
which php8.4

# Create symlink if needed
sudo ln -s /usr/bin/php8.4 /usr/bin/php
```

### "SQLite extension not found"
```bash
# Check if loaded
php -m | grep sqlite3

# If not, install
sudo apt-get install php8.4-sqlite3

# Restart PHP
sudo systemctl restart php8.4-fpm
```

### "Composer command not found"
```bash
# Check if installed
which composer

# If not, install manually
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### "Database file not found"
```bash
# Create and seed database
mkdir -p auth/src/database
sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql

# Verify
sqlite3 auth/src/database/auth.sqlite3 ".tables"
```

### "Port 6969 already in use"
```bash
# Use different port
php -S localhost:7777 -t auth/public/

# Or find what's using the port
lsof -i :6969
```

---

## Nginx Configuration (Optional)

If you installed Nginx and want to use it instead of the PHP dev server:

### 1. Create Nginx site config

```bash
sudo nano /etc/nginx/sites-available/php-box
```

Add:
```nginx
server {
    listen 80;
    server_name localhost;
    root /path/to/php-box/auth/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 2. Enable site

```bash
sudo ln -s /etc/nginx/sites-available/php-box /etc/nginx/sites-enabled/
sudo systemctl reload nginx
```

### 3. Access
- http://localhost (instead of localhost:6969)

---

## PHP Configuration for Development

The setup script configures PHP for development with:

```ini
display_errors = On              # Show errors
error_reporting = E_ALL          # Report all errors
memory_limit = 512M              # Memory limit
post_max_size = 100M             # POST data size
upload_max_filesize = 100M       # File upload size
```

Location: `/etc/php/8.4/cli/php.ini`

---

## Next Steps

1. **Read Documentation**
   - `README.md` - Project overview
   - `auth/doc/README.md` - Auth module guide
   - `auth/TESTING.md` - Testing guide

2. **Run Tests**
   ```bash
   composer test
   ```

3. **Start Development**
   ```bash
   composer run dev
   ```

4. **Explore Code**
   - `auth/public/index.php` - Entry point
   - `auth/src/controller/UserController.php` - Controller example
   - `auth/src/model/UserModel.php` - Model example
   - `auth/tests/` - Test examples

---

## System Requirements

- **OS**: Debian or Ubuntu (20.04 LTS or newer)
- **PHP**: 8.4 or newer
- **Composer**: Latest version
- **Database**: SQLite3
- **RAM**: 1GB minimum (2GB+ recommended)
- **Disk**: 500MB free space

---

## Support

For issues or questions:
1. Check troubleshooting section above
2. Review project documentation
3. Check PHP and Composer documentation:
   - PHP: https://www.php.net/docs.php
   - Composer: https://getcomposer.org/doc/
   - PHPUnit: https://phpunit.de/documentation.html

---

## Script Details

The `setup.sh` script performs these steps in order:

1. Detects OS and version
2. Updates system packages
3. Adds PHP 8.4 repository
4. Installs PHP 8.4 with extensions
5. Installs Composer
6. Installs SQLite3 CLI
7. Installs Git
8. Installs development tools
9. Optionally installs Nginx
10. Verifies PHP installation
11. Configures PHP for development
12. Sets up project directories
13. Installs Composer dependencies

Each step includes error checking and colored output for clarity.

---

**Happy coding! 🚀**
