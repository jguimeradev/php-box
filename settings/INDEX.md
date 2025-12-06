# 🎯 PHP 8.4 Setup for php-box - Complete Package

## ⚡ Quick Start (Choose One)

### 🐧 Linux (Debian/Ubuntu)
```bash
sudo bash setup.sh
```

### 🐧 Linux (Any Distribution)
```bash
sudo bash quick-setup.sh
```

### 🐳 Docker (All Platforms)
```bash
docker-compose up -d
```

Then visit: **http://localhost:6969**

---

## 📦 What You Get

✅ **PHP 8.4** with all required extensions  
✅ **Composer** package manager  
✅ **SQLite3** database  
✅ **PHPUnit** for testing (27 tests passing)  
✅ **Git** version control  
✅ Development tools and utilities  

---

## 📚 Documentation Files

| File | Best For |
|------|----------|
| 📖 **[INSTALL.md](./INSTALL.md)** | Complete guide with 4 methods |
| 🚀 **[SETUP_QUICK_REFERENCE.md](./SETUP_QUICK_REFERENCE.md)** | One-pagers & cheat sheets |
| 📝 **[SETUP_GUIDE.md](./SETUP_GUIDE.md)** | Detailed walkthrough |
| 📋 **[SETUP_SCRIPTS_README.md](./SETUP_SCRIPTS_README.md)** | Scripts documentation |
| 📊 **[SETUP_FILES_SUMMARY.md](./SETUP_FILES_SUMMARY.md)** | File overview |

---

## 🛠️ Setup Scripts

| Script | Purpose | Time | OS Support |
|--------|---------|------|-----------|
| **setup.sh** | Full setup | 3-5 min | Ubuntu/Debian |
| **quick-setup.sh** | Quick setup | 1-2 min | Multi-distro |
| **Dockerfile** | Container image | 2 min | All |
| **docker-compose.yml** | Orchestration | 2 min | All |

---

## ✅ Installation Checklist

- [ ] Choose installation method
- [ ] Run setup script or Docker
- [ ] Initialize database
- [ ] Start development server
- [ ] Access http://localhost:6969
- [ ] Run tests (`composer test`)
- [ ] Start coding!

---

## 🎓 After Installation

### Run Tests
```bash
composer test
```

### Start Development
```bash
composer run dev
```

### Daily Commands
```bash
php -v                    # Check PHP version
php -m | grep sqlite3     # Check SQLite
composer test             # Run tests
sqlite3 auth/src/database/auth.sqlite3  # Access database
```

---

## 🆘 Need Help?

| Issue | Resource |
|-------|----------|
| How to install? | [INSTALL.md](./INSTALL.md) |
| Something not working? | [SETUP_GUIDE.md](./SETUP_GUIDE.md) → Troubleshooting |
| Quick answer needed? | [SETUP_QUICK_REFERENCE.md](./SETUP_QUICK_REFERENCE.md) |
| About the scripts? | [SETUP_SCRIPTS_README.md](./SETUP_SCRIPTS_README.md) |
| PHP not found? | [SETUP_QUICK_REFERENCE.md](./SETUP_QUICK_REFERENCE.md) → Troubleshooting |

---

## 📖 Reading Guide

1. **First Time?** → Start here
   - Read this file
   - Read [SETUP_QUICK_REFERENCE.md](./SETUP_QUICK_REFERENCE.md)

2. **Ready to Install?**
   - Run: `sudo bash setup.sh` or `sudo bash quick-setup.sh`
   - If issues: Check [INSTALL.md](./INSTALL.md)

3. **Want Details?**
   - Read [SETUP_GUIDE.md](./SETUP_GUIDE.md)

4. **Need Specific Info?**
   - Use [SETUP_QUICK_REFERENCE.md](./SETUP_QUICK_REFERENCE.md)

5. **Script Questions?**
   - See [SETUP_SCRIPTS_README.md](./SETUP_SCRIPTS_README.md)

---

## 🔧 PHP 8.4 Extensions Installed

```
✓ sqlite3       Database
✓ pdo           Database abstraction
✓ curl          HTTP requests
✓ mbstring      String handling
✓ xml           XML parsing
✓ json          JSON processing
✓ intl          Internationalization
✓ gd            Image processing
✓ zip           ZIP archives
```

---

## 📊 System Requirements

- **OS**: Linux (Debian, Ubuntu, Fedora, Arch, Alpine, or Docker)
- **RAM**: 1GB minimum (2GB recommended)
- **Disk**: 500MB free
- **Internet**: For initial setup

---

## 🚀 Get Started Now!

### Option 1: Debian/Ubuntu
```bash
sudo bash setup.sh
```

### Option 2: Other Linux
```bash
sudo bash quick-setup.sh
```

### Option 3: Docker
```bash
docker-compose up -d
```

Then:
```bash
composer run dev
# Visit http://localhost:6969
```

---

## 📁 Files in This Package

```
Executable Scripts:
- setup.sh              (Full automated setup)
- quick-setup.sh        (Quick multi-distro setup)

Docker Files:
- Dockerfile            (Container image)
- docker-compose.yml    (Container orchestration)

Documentation:
- INSTALL.md                    (Complete guide)
- SETUP_QUICK_REFERENCE.md      (Cheat sheet)
- SETUP_GUIDE.md               (Detailed walkthrough)
- SETUP_SCRIPTS_README.md       (Scripts info)
- SETUP_FILES_SUMMARY.md        (File overview)
- INDEX.md                      (This file)
```

---

## ✨ Key Features

✅ **Automated** - One command setup  
✅ **Multi-distro** - Works on any Linux  
✅ **Docker** - Isolated environment  
✅ **Well documented** - 5+ guide files  
✅ **Error handling** - Friendly messages  
✅ **Verified** - All tests passing  
✅ **Fast** - 1-5 minutes  

---

## 📞 Troubleshooting Quick Links

- **PHP issues**: [SETUP_GUIDE.md](./SETUP_GUIDE.md#troubleshooting)
- **Installation issues**: [INSTALL.md](./INSTALL.md#troubleshooting)
- **Quick fixes**: [SETUP_QUICK_REFERENCE.md](./SETUP_QUICK_REFERENCE.md#troubleshooting-checklist)

---

## 🎉 What's Next?

1. Choose your setup method
2. Run the script
3. Follow on-screen instructions
4. Run `composer run dev`
5. Visit http://localhost:6969
6. Start exploring the code!

---

## 💡 Pro Tips

- **Stuck?** Run `php -v` to verify PHP works
- **Database issues?** Check [SETUP_QUICK_REFERENCE.md](./SETUP_QUICK_REFERENCE.md)
- **Want details?** Read [SETUP_GUIDE.md](./SETUP_GUIDE.md)
- **Container?** Use `docker-compose logs -f`
- **Port conflict?** Use different port: `php -S localhost:7777 -t auth/public/`

---

## 🌟 Success Indicators

After setup, you should see:
- ✅ PHP 8.4.x version
- ✅ SQLite3 extension loaded
- ✅ Development server running
- ✅ Tests passing (27/27)
- ✅ Database created

---

**Ready? Pick your method and run:**

```bash
# Ubuntu/Debian - Full setup
sudo bash setup.sh

# Any Linux - Quick setup  
sudo bash quick-setup.sh

# Docker - Container
docker-compose up -d
```

**Questions?** Check the documentation files above.

**Let's code! 🚀**

---

Last Updated: December 2025  
PHP Version: 8.4  
Project: php-box  
Status: ✅ Complete
