#!/bin/bash

# Database initialization script for Project1
# This script will create the database and tables

echo "=========================================="
echo "Project1 Database Setup"
echo "=========================================="
echo ""

# Database credentials
DB_NAME="project1_db"
DB_USER="root"

# Check if MySQL is running
echo "Checking MySQL connection..."
if ! mysql -u $DB_USER -e "SELECT 1;" > /dev/null 2>&1; then
    echo "Error: Cannot connect to MySQL. Please ensure MySQL is running."
    echo "Start MySQL with: mysqld_safe --datadir='/data/data/com.termux/files/usr/var/lib/mysql' &"
    exit 1
fi

echo "MySQL connection successful!"
echo ""

# Import schema
echo "Creating database and tables from schema.sql..."
mysql -u $DB_USER < schema.sql

if [ $? -eq 0 ]; then
    echo ""
    echo "=========================================="
    echo "Database setup completed successfully!"
    echo "=========================================="
    echo ""
    echo "Database Name: $DB_NAME"
    echo "Tables created:"
    echo "  - users"
    echo "  - posts"
    echo "  - categories"
    echo "  - post_categories"
    echo "  - sessions"
    echo ""
    echo "You can now register your first user at:"
    echo "http://localhost/project1/register.php"
else
    echo ""
    echo "Error: Database setup failed!"
    exit 1
fi
