#!/bin/bash

################################################################################
# PHP-Box Setup Script for PHP 8.4 on Debian/Ubuntu
#
# This script configures repositories and installs PHP 8.4 with all required
# extensions and tools for the php-box learning projects.
#
# Usage: bash setup.sh
# Requires: sudo privileges, Debian/Ubuntu-based system
################################################################################

set -e  # Exit on error

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions for formatted output
print_header() {
    echo -e "\n${BLUE}=== $1 ===${NC}\n"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

# Check if running as root or with sudo
check_sudo() {
    if [[ $EUID -ne 0 ]]; then
        print_error "This script must be run with sudo"
        echo "Usage: sudo bash setup.sh"
        exit 1
    fi
}

# Detect OS and version
detect_os() {
    print_header "Detecting OS"
    
    if [[ -f /etc/os-release ]]; then
        . /etc/os-release
        OS=$ID
        VERSION=$VERSION_CODENAME
        print_success "Detected: $PRETTY_NAME"
    else
        print_error "Could not detect OS"
        exit 1
    fi
    
    # Check if Debian/Ubuntu
    if [[ "$OS" != "debian" && "$OS" != "ubuntu" ]]; then
        print_error "This script supports Debian/Ubuntu only"
        exit 1
    fi
}

# Update system packages
update_system() {
    print_header "Updating system packages"
    apt-get update
    apt-get upgrade -y
    print_success "System updated"
}

# Add PHP repository (Sury)
add_php_repo() {
    print_header "Adding PHP 8.4 repository"
    
    # Install dependencies for adding repository
    apt-get install -y lsb-release ca-certificates apt-transport-https software-properties-common gnupg2
    
    # Add Ondrej Sury's PHP repository
    add-apt-repository -y ppa:ondrej/php
    
    apt-get update
    print_success "PHP repository added"
}

# Install PHP 8.4 and extensions
install_php() {
    print_header "Installing PHP 8.4"
    
    # Core PHP
    apt-get install -y php8.4-cli php8.4-fpm php8.4-dev
    
    # Essential extensions for php-box
    apt-get install -y \
        php8.4-common \
        php8.4-mysql \
        php8.4-sqlite3 \
        php8.4-pgsql \
        php8.4-curl \
        php8.4-gd \
        php8.4-json \
        php8.4-mbstring \
        php8.4-xml \
        php8.4-zip \
        php8.4-intl \
        php8.4-pdo \
        php8.4-fileinfo
    
    print_success "PHP 8.4 installed"
}

# Verify PHP installation
verify_php() {
    print_header "Verifying PHP installation"
    
    PHP_VERSION=$(php -v | head -n 1)
    print_success "PHP version: $PHP_VERSION"
    
    # Check key extensions
    php -m | grep -q sqlite3 && print_success "SQLite3 extension loaded" || print_warning "SQLite3 not found"
    php -m | grep -q curl && print_success "cURL extension loaded" || print_warning "cURL not found"
    php -m | grep -q pdo && print_success "PDO extension loaded" || print_warning "PDO not found"
}

# Install Composer
install_composer() {
    print_header "Installing Composer"
    
    # Download Composer installer
    curl -sS https://getcomposer.org/installer -o composer-setup.php
    
    # Verify the signature (optional but recommended)
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    
    rm composer-setup.php
    
    COMPOSER_VERSION=$(composer --version)
    print_success "Composer installed: $COMPOSER_VERSION"
}

# Install Nginx (optional)
install_nginx() {
    print_header "Installing Nginx (optional)"
    
    read -p "Install Nginx for local development? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        apt-get install -y nginx
        systemctl enable nginx
        systemctl start nginx
        print_success "Nginx installed and started"
    else
        print_warning "Nginx skipped"
    fi
}

# Install SQLite3 CLI tools
install_sqlite() {
    print_header "Installing SQLite3 tools"
    
    apt-get install -y sqlite3
    print_success "SQLite3 CLI installed"
}

# Install Git (if not present)
install_git() {
    print_header "Installing Git"
    
    if ! command -v git &> /dev/null; then
        apt-get install -y git
        print_success "Git installed"
    else
        GIT_VERSION=$(git --version)
        print_success "Git already installed: $GIT_VERSION"
    fi
}

# Install additional development tools
install_dev_tools() {
    print_header "Installing development tools"
    
    apt-get install -y \
        curl \
        wget \
        nano \
        vim \
        htop \
        build-essential \
        libssl-dev
    
    print_success "Development tools installed"
}

# Create php-box project directories
setup_project_dirs() {
    print_header "Setting up php-box project structure"
    
    # This assumes php-box directory exists
    if [[ ! -d "./php-box" ]] && [[ ! -f "./composer.json" ]]; then
        print_warning "php-box directory not found. Skipping directory setup."
        return
    fi
    
    # Create necessary directories if they don't exist
    mkdir -p auth/public
    mkdir -p auth/src/{controller,model,data,views,database,scripts}
    mkdir -p auth/tests
    
    print_success "Project directories verified"
}

# Install PHP-Box dependencies via Composer
install_dependencies() {
    print_header "Installing php-box dependencies"
    
    if [[ -f "./composer.json" ]]; then
        composer install
        print_success "Composer dependencies installed"
    else
        print_warning "composer.json not found, skipping composer install"
    fi
}

# Configure PHP for development
configure_php_dev() {
    print_header "Configuring PHP for development"
    
    PHP_INI="/etc/php/8.4/cli/php.ini"
    
    if [[ -f "$PHP_INI" ]]; then
        # Enable error display for development
        sed -i 's/display_errors = Off/display_errors = On/' "$PHP_INI"
        sed -i 's/error_reporting = E_ALL/error_reporting = E_ALL/' "$PHP_INI"
        
        # Set reasonable limits for development
        sed -i 's/memory_limit = .*/memory_limit = 512M/' "$PHP_INI"
        sed -i 's/post_max_size = .*/post_max_size = 100M/' "$PHP_INI"
        sed -i 's/upload_max_filesize = .*/upload_max_filesize = 100M/' "$PHP_INI"
        
        print_success "PHP configured for development"
    else
        print_warning "PHP configuration file not found at $PHP_INI"
    fi
}

# Create setup summary
print_summary() {
    print_header "Setup Complete!"
    
    cat << EOF
${GREEN}✓ PHP 8.4 environment configured successfully!${NC}

${BLUE}Next steps:${NC}

1. Navigate to php-box directory:
   cd /path/to/php-box

2. Install Composer dependencies:
   composer install

3. Start development server:
   composer run dev
   (or: php -S localhost:6969 -t auth/public/)

4. Access the application:
   http://localhost:6969

${BLUE}Useful commands:${NC}
   - Run tests: composer test
   - Check PHP version: php -v
   - Check extensions: php -m
   - SQLite database: sqlite3 auth/src/database/auth.sqlite3

${BLUE}Database initialization:${NC}
   sqlite3 auth/src/database/auth.sqlite3 < auth/src/scripts/seed_users.sql

${BLUE}Documentation:${NC}
   - Project guide: README.md
   - Testing guide: auth/TESTING.md
   - Controller tests: auth/CONTROLLER_TESTS.md

EOF
}

# Cleanup function
cleanup() {
    print_warning "Setup interrupted or failed"
    exit 1
}

# Trap errors
trap cleanup ERR

# Main execution
main() {
    echo -e "\n${BLUE}╔════════════════════════════════════════╗${NC}"
    echo -e "${BLUE}║   PHP-Box Setup Script for PHP 8.4    ║${NC}"
    echo -e "${BLUE}╚════════════════════════════════════════╝${NC}"
    
    check_sudo
    detect_os
    update_system
    add_php_repo
    install_php
    install_composer
    install_sqlite
    install_git
    install_dev_tools
    install_nginx
    verify_php
    configure_php_dev
    setup_project_dirs
    install_dependencies
    print_summary
}

# Run main function
main
