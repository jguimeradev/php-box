# 🚀 PHP 8.4 Setup - Quick Reference

## Installation Methods at a Glance

```
┌─────────────────────────────────────────────────────────────────┐
│                    Choose Your Installation Path                 │
└─────────────────────────────────────────────────────────────────┘

1️⃣  AUTOMATED (Debian/Ubuntu)
   └─ sudo bash setup.sh
   └─ Installs EVERYTHING
   └─ ~5 minutes
   └─ RECOMMENDED ⭐

2️⃣  QUICK SETUP (Multi-Distro)
   └─ sudo bash quick-setup.sh
   └─ Essential only
   └─ Supports: Ubuntu, Fedora, Arch, Alpine
   └─ ~2 minutes

3️⃣  DOCKER (All Platforms)
   └─ docker-compose up -d
   └─ No local installation
   └─ Isolated environment
   └─ ~2 minutes

4️⃣  MANUAL
   └─ Follow INSTALL.md
   └─ Full control
   └─ 30+ minutes
   └─ For advanced users
```

---

## What Gets Installed

```
✅ PHP 8.4 CLI & FPM
✅ SQLite3 (database)
✅ PDO (database layer)
✅ cURL (HTTP client)
✅ Composer (package manager)
✅ Git (version control)
✅ PHPUnit (testing)
✅ Development tools (curl, wget, nano, vim, htop)

Optional:
✅ Nginx (web server)
```

---

## 3-Step Quick Start

### Step 1: Run Setup
```bash
cd /path/to/php-box
sudo bash setup.sh
```

### Step 2: Initialize Database
```bash
sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql
```

### Step 3: Start Development
```bash
composer run dev
# Visit: http://localhost:6969
```

---

## Daily Commands

```bash
# Start development server
composer run dev

# Run all tests
composer test

# Run specific test
php vendor/bin/phpunit auth/tests/UserModelTest.php

# Check PHP version
php -v

# Check extensions
php -m | grep sqlite3

# Access database
sqlite3 auth/src/database/auth.sqlite3
```

---

## Troubleshooting Checklist

```
❓ PHP not found?
   ➜ sudo ln -s /usr/bin/php8.4 /usr/bin/php

❓ SQLite not working?
   ➜ sudo apt-get install php8.4-sqlite3

❓ Composer issues?
   ➜ composer self-update

❓ Database missing?
   ➜ sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql

❓ Port 6969 in use?
   ➜ php -S localhost:7777 -t auth/public/

❓ Tests failing?
   ➜ composer dumpautoload
   ➜ composer test
```

---

## File Locations

```
📦 php-box/
├── setup.sh                  ← Main setup script
├── quick-setup.sh            ← Multi-distro setup
├── docker-compose.yml        ← Docker orchestration
├── Dockerfile                ← Docker image
├── INSTALL.md               ← Complete guide
├── SETUP_GUIDE.md           ← Detailed walkthrough
├── SETUP_SCRIPTS_README.md  ← Scripts documentation
└── auth/
    ├── public/index.php      ← Entry point
    ├── src/
    │   ├── controller/       ← Request handlers
    │   ├── model/            ← Database layer
    │   ├── views/            ← Templates
    │   └── database/         ← SQLite files
    └── tests/                ← Unit tests
```

---

## System Requirements

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| OS | Debian/Ubuntu | Ubuntu 20.04 LTS |
| PHP | 8.4 | 8.4.x latest |
| RAM | 1 GB | 2+ GB |
| Disk | 500 MB | 1 GB |
| CPU | Any | Multi-core |

---

## After Installation

### Verify Setup
```bash
php -v                    # Check PHP
php -m | grep sqlite3     # Check SQLite
composer --version        # Check Composer
php vendor/bin/phpunit --version  # Check PHPUnit
```

### Run Tests
```bash
composer test
# Output should show: "OK (27 tests, ...)"
```

### Start Development
```bash
composer run dev
# Server running at: http://localhost:6969
```

---

## Documentation Map

| Need | Read |
|------|------|
| 🔧 Installation | **INSTALL.md** or **setup.sh** |
| 📝 Detailed setup | **SETUP_GUIDE.md** |
| 🧪 Testing | **auth/TESTING.md** |
| 💡 Code examples | **auth/CONTROLLER_TESTS.md** |
| ⚡ Test cheatsheet | **auth/TESTING_CHEATSHEET.md** |
| 📖 Project overview | **README.md** |

---

## One-Liners

### Full Setup + Start
```bash
sudo bash setup.sh && composer run dev
```

### Setup + Database + Test
```bash
sudo bash setup.sh && sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql && composer test
```

### Docker Start
```bash
docker-compose up -d && open http://localhost:6969
```

---

## Supported Linux Distros

| Distribution | Script | Method |
|-------------|--------|--------|
| Ubuntu 20.04+ | ✅ Both | apt/PPA |
| Debian 11+ | ✅ setup.sh | apt/PPA |
| Fedora 35+ | ✅ quick-setup.sh | dnf |
| RHEL/CentOS | ✅ quick-setup.sh | dnf |
| Arch Linux | ✅ quick-setup.sh | pacman |
| Alpine Linux | ✅ quick-setup.sh | apk |
| Docker | ✅ Both | docker-compose |

---

## Script Execution Time

```
setup.sh              : 3-5 minutes
quick-setup.sh        : 1-2 minutes
docker-compose up     : 1-2 minutes
Manual installation   : 20-30 minutes
```

---

## Script Safety Features

✅ **Checks sudo privileges** - Won't run without
✅ **Detects OS** - Validates compatibility
✅ **Error handling** - Stops on first error
✅ **Color-coded output** - Easy to follow
✅ **Verification steps** - Confirms installation
✅ **Idempotent** - Safe to run multiple times

---

## Environment Variables

```bash
# Set in development
export PHP_ENV=development
export PHP_MEMORY_LIMIT=512M
export PHP_DISPLAY_ERRORS=1

# Or in .env file (if using)
PHP_ENV=development
COMPOSER_MEMORY_LIMIT=-1
```

---

## Upgrading PHP

If you need to upgrade PHP later:

```bash
# Debian/Ubuntu
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get install php8.5-cli  # Newer version

# Fedora/RHEL
sudo dnf install php85-php-cli
```

---

## Getting Help

```
📖 Documentation    : See INSTALL.md or SETUP_GUIDE.md
🔧 Troubleshoot     : Check troubleshooting sections
⚙️  Run diagnostics : php -i
🐛 Debug            : php -S localhost:6969 -t auth/public/ -d display_errors=1
💬 Check logs       : Look at terminal output while running
```

---

## Next Steps

1. **Run setup**: `sudo bash setup.sh` ⭐
2. **Initialize DB**: `sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql`
3. **Start server**: `composer run dev`
4. **Open browser**: `http://localhost:6969`
5. **Run tests**: `composer test`
6. **Start coding**: Edit files in `auth/src/`

---

## Success Indicators

✅ `php -v` shows PHP 8.4.x  
✅ `php -m | grep sqlite3` shows SQLite  
✅ `composer --version` shows Composer  
✅ Server starts on port 6969  
✅ Tests pass with "OK"  
✅ Database file created at `auth/src/database/auth.sqlite3`

---

**Ready? Run:**
```bash
sudo bash setup.sh
```

**Questions?** Check [INSTALL.md](./INSTALL.md) or [SETUP_GUIDE.md](./SETUP_GUIDE.md)

🎉 Welcome to php-box development!
