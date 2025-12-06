#!/bin/bash

################################################################################
# PHP-Box Quick Setup - Minimal Version
#
# This is a minimal setup script for users who want just the essentials.
# For full setup with optional tools, use setup.sh
#
# Usage: bash quick-setup.sh
################################################################################

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m'

print_header() {
    echo -e "\n${BLUE}=== $1 ===${NC}\n"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

# Check sudo
if [[ $EUID -ne 0 ]]; then
    print_error "This script requires sudo"
    exit 1
fi

print_header "PHP 8.4 Quick Setup for php-box"

# Detect OS
if [[ -f /etc/os-release ]]; then
    . /etc/os-release
    OS=$ID
else
    print_error "Could not detect OS"
    exit 1
fi

# Setup based on OS
case "$OS" in
    debian|ubuntu)
        print_header "Setting up Debian/Ubuntu"
        
        # Update
        print_header "Updating packages"
        apt-get update
        apt-get upgrade -y
        
        # Add PHP repo
        print_header "Adding PHP repository"
        apt-get install -y lsb-release ca-certificates apt-transport-https software-properties-common gnupg2
        add-apt-repository -y ppa:ondrej/php
        apt-get update
        
        # Install PHP 8.4
        print_header "Installing PHP 8.4"
        apt-get install -y \
            php8.4-cli \
            php8.4-fpm \
            php8.4-sqlite3 \
            php8.4-pdo \
            php8.4-curl \
            php8.4-mbstring \
            php8.4-xml \
            php8.4-json
        
        print_success "PHP 8.4 installed"
        ;;
    
    fedora|rhel|centos)
        print_header "Setting up Fedora/RHEL/CentOS"
        
        # Update
        print_header "Updating packages"
        dnf upgrade -y
        
        # Add remi repository
        print_header "Adding PHP repository"
        dnf install -y https://rpms.remirepo.net/fedora/remi-release-$(rpm -E %fedora).rpm
        dnf module reset php -y
        dnf module enable php:remi-8.4 -y
        
        # Install PHP 8.4
        print_header "Installing PHP 8.4"
        dnf install -y \
            php-cli \
            php-fpm \
            php-sqlite3 \
            php-pdo \
            php-curl \
            php-mbstring \
            php-xml \
            php-json \
            php-devel
        
        print_success "PHP 8.4 installed"
        ;;
    
    arch)
        print_header "Setting up Arch Linux"
        
        # Update
        pacman -Syu --noconfirm
        
        # Install PHP 8.4
        print_header "Installing PHP 8.4"
        pacman -S --noconfirm \
            php \
            sqlite \
            curl
        
        print_success "PHP 8.4 installed"
        ;;
    
    alpine)
        print_header "Setting up Alpine Linux"
        
        # Update
        apk update
        apk upgrade
        
        # Install PHP 8.4
        print_header "Installing PHP 8.4"
        apk add --no-cache \
            php8 \
            php8-cli \
            php8-sqlite3 \
            php8-pdo \
            php8-curl \
            php8-mbstring \
            php8-xml \
            php8-json
        
        print_success "PHP 8.4 installed"
        ;;
    
    *)
        print_error "Unsupported OS: $OS"
        print_error "Supported: debian, ubuntu, fedora, rhel, centos, arch, alpine"
        exit 1
        ;;
esac

# Install Composer
print_header "Installing Composer"
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
print_success "Composer installed"

# Verify installations
print_header "Verifying installations"
php -v
composer --version
php -m | grep sqlite3 && print_success "SQLite3 extension found" || print_error "SQLite3 not found"

# Install project dependencies
print_header "Installing project dependencies"
composer install
print_success "Dependencies installed"

print_header "Setup Complete!"
echo -e "\n${GREEN}Next steps:${NC}"
echo "1. composer run dev"
echo "2. Visit http://localhost:6969"
echo ""
echo -e "${GREEN}Run tests:${NC}"
echo "   composer test"
