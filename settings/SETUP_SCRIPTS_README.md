# PHP 8.4 Setup Scripts Summary

Complete bash scripts and guides for setting up PHP 8.4 development environment for php-box project.

## 📦 What's Included

### Scripts
| File | Purpose | Scope |
|------|---------|-------|
| `setup.sh` | Full automated setup | Debian/Ubuntu only, complete |
| `quick-setup.sh` | Multi-distro setup | Multiple Linux distros, minimal |
| `Dockerfile` | Container setup | All platforms, isolated |
| `docker-compose.yml` | Container orchestration | All platforms, with services |

### Documentation
| File | Content |
|------|---------|
| `INSTALL.md` | Complete installation guide (4+ methods) |
| `SETUP_GUIDE.md` | Detailed setup walkthrough with troubleshooting |
| `setup.sh` | Full script with inline documentation |
| `quick-setup.sh` | Minimal script with distro detection |

---

## 🚀 Quick Start

### Option 1: Automated Full Setup (Recommended for Debian/Ubuntu)

```bash
cd /path/to/php-box
sudo bash setup.sh
```

**Installs:**
- ✅ PHP 8.4 with all extensions
- ✅ Composer
- ✅ SQLite3
- ✅ Git, dev tools
- ✅ Nginx (optional)
- ✅ PHPUnit
- ✅ Project dependencies

### Option 2: Quick Setup (Works on Multiple Distros)

```bash
sudo bash quick-setup.sh
```

**Supports:**
- ✅ Debian/Ubuntu (apt)
- ✅ Fedora/RHEL/CentOS (dnf)
- ✅ Arch Linux (pacman)
- ✅ Alpine Linux (apk)

### Option 3: Docker Setup

```bash
docker-compose up -d
# Access at http://localhost:6969
```

### Option 4: Manual Installation

Follow [INSTALL.md](./INSTALL.md) section "Manual Installation"

---

## 📋 setup.sh Features

The full setup script (`setup.sh`) automatically:

1. **OS Detection**
   - Detects Debian/Ubuntu version
   - Validates system compatibility

2. **System Preparation**
   - Updates packages
   - Installs required dependencies
   - Adds PHP repository (Sury's PPA)

3. **PHP 8.4 Installation**
   - Installs PHP CLI and FPM
   - Adds required extensions:
     - sqlite3 (database)
     - pdo (database abstraction)
     - curl (HTTP)
     - mbstring (strings)
     - xml (parsing)
     - json (data format)
     - intl (internationalization)
     - gd (images)
     - zip (archives)

4. **Development Tools**
   - Composer (package manager)
   - SQLite3 CLI
   - Git
   - curl, wget, nano, vim, htop
   - build-essential

5. **Configuration**
   - Configures PHP for development
   - Sets memory limits
   - Enables error display

6. **Project Setup**
   - Creates project directories
   - Installs Composer dependencies
   - Prints setup summary

### Script Features

- **Color-coded output** - Easy to read progress
- **Error handling** - Exits on first error
- **Verification** - Checks PHP installation
- **Idempotent** - Safe to run multiple times
- **Interactive** - Prompts for optional tools (Nginx)

---

## 📋 quick-setup.sh Features

Minimal setup script supporting multiple Linux distributions:

1. **Multi-Distro Support**
   ```
   ✓ Debian/Ubuntu (apt)
   ✓ Fedora/RHEL/CentOS (dnf)
   ✓ Arch Linux (pacman)
   ✓ Alpine Linux (apk)
   ```

2. **Installs Essentials Only**
   - PHP 8.4
   - Required extensions
   - Composer
   - Git

3. **Fast & Simple**
   - ~2 minutes on typical system
   - No optional components
   - Automatic distro detection

---

## 🐳 Docker Setup

### docker-compose.yml includes:

1. **php-box service**
   - PHP 8.4 CLI Alpine image
   - Development server on :6969
   - Volume mounts for live editing
   - Development environment variables

2. **db-browser service** (optional)
   - PHPMyAdmin on :8080
   - Database management UI
   - Can be disabled by removing from compose

### Usage

```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f

# Stop services
docker-compose down

# Rebuild image
docker-compose up -d --build
```

---

## 📝 Installation Steps After Running Script

### 1. Run Setup
```bash
sudo bash setup.sh
# or
sudo bash quick-setup.sh
```

### 2. Initialize Database
```bash
sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql
```

### 3. Start Development Server
```bash
composer run dev
# or
php -S localhost:6969 -t auth/public/
```

### 4. Access Application
```
http://localhost:6969
```

### 5. Run Tests
```bash
composer test
```

---

## 🔧 Troubleshooting

### Common Issues

| Issue | Solution |
|-------|----------|
| "PHP not found" | `sudo ln -s /usr/bin/php8.4 /usr/bin/php` |
| "SQLite extension missing" | `sudo apt-get install php8.4-sqlite3` |
| "Composer not found" | Already installed at `/usr/local/bin/composer` |
| "Port 6969 in use" | Use different port: `php -S localhost:7777 -t auth/public/` |
| "Database not found" | Create it: `sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql` |

See [SETUP_GUIDE.md](./SETUP_GUIDE.md) for detailed troubleshooting.

---

## 📚 Documentation Files

### For Installation
- **INSTALL.md** - Complete guide with 4+ methods
- **SETUP_GUIDE.md** - Detailed walkthrough
- **setup.sh** - Full automated script

### For Development
- **README.md** - Project overview
- **auth/TESTING.md** - Testing guide
- **auth/CONTROLLER_TESTS.md** - Test examples
- **auth/TESTING_CHEATSHEET.md** - Quick test reference

---

## 🎯 PHP 8.4 Extensions Installed

| Extension | Package | Purpose |
|-----------|---------|---------|
| sqlite3 | php8.4-sqlite3 | SQLite database |
| pdo | php8.4-pdo | Database abstraction |
| curl | php8.4-curl | HTTP requests |
| mbstring | php8.4-mbstring | Multibyte strings |
| xml | php8.4-xml | XML parsing |
| json | php8.4-json | JSON processing |
| intl | php8.4-intl | Internationalization |
| gd | php8.4-gd | Image processing |
| zip | php8.4-zip | ZIP archives |

---

## 🔐 Security Notes

### Development
- Scripts enable error display (development only)
- Increases memory limits for testing
- Allows large file uploads

### Production
- Review `/etc/php/8.4/fpm/php.ini`
- Disable display_errors
- Reduce memory_limit
- Set appropriate file size limits
- Use Nginx with proper SSL

---

## 📊 Comparison Matrix

| Method | Speed | Setup | OS Support | Docker |
|--------|-------|-------|------------|--------|
| setup.sh | Fast | 1 command | Debian/Ubuntu | ❌ |
| quick-setup.sh | Fast | 1 command | Multi-distro | ❌ |
| docker-compose | Medium | 1 command | All | ✅ |
| Manual | Slow | Many steps | All | ❌ |

---

## 📋 Pre-Setup Checklist

Before running setup scripts:

- [ ] Have sudo access
- [ ] ~500MB disk space available
- [ ] Internet connection (for downloads)
- [ ] 1GB+ RAM available
- [ ] Running Debian/Ubuntu or other supported Linux
- [ ] Not running another PHP/web server on same ports

---

## 📦 After Setup

### Verify Installation
```bash
php -v                          # Check PHP
php -m | grep sqlite3           # Check extensions
composer --version              # Check Composer
```

### Start Development
```bash
composer run dev                # Start server
composer test                   # Run tests
```

### Project Structure
```
php-box/
├── auth/
│   ├── public/
│   │   └── index.php           # Entry point
│   ├── src/
│   │   ├── controller/
│   │   ├── model/
│   │   ├── views/
│   │   ├── database/           # SQLite files
│   │   └── scripts/            # SQL seeds
│   └── tests/
├── setup.sh                     # Full setup
├── quick-setup.sh               # Quick setup
├── docker-compose.yml           # Docker
└── INSTALL.md                   # This guide
```

---

## 🆘 Getting Help

1. **Read Documentation**
   - [INSTALL.md](./INSTALL.md) - Installation guide
   - [SETUP_GUIDE.md](./SETUP_GUIDE.md) - Detailed setup

2. **Check Troubleshooting**
   - Common issues in each guide
   - Script output for clues

3. **Verify Installation**
   - Run verification commands
   - Check PHP extensions: `php -m`
   - Check Composer: `composer diagnose`

4. **Review Logs**
   - Run server in foreground: `php -S localhost:6969 -t auth/public/`
   - Watch for error messages

---

## 🎓 Next Steps After Setup

1. **Explore codebase**
   ```bash
   cat auth/public/index.php           # Entry point
   cat auth/src/controller/UserController.php
   cat auth/src/model/UserModel.php
   ```

2. **Run tests**
   ```bash
   composer test
   php vendor/bin/phpunit auth/tests/ -v
   ```

3. **Start development**
   ```bash
   composer run dev
   # Edit files in auth/src/
   ```

4. **Read documentation**
   - [auth/TESTING.md](./auth/TESTING.md) - Testing guide
   - [README.md](./README.md) - Project overview

---

## 📄 License & Credits

Scripts created for php-box learning project.
Based on official PHP, Composer, and Docker documentation.

---

**Ready to get started? Run:**
```bash
sudo bash setup.sh
```

🚀 Happy coding!
