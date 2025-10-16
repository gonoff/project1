# Database Setup

## Installation

Run the following command to create the database and tables:

```bash
mysql -u root -p < schema.sql
```

Or import via phpMyAdmin or any MySQL client.

## Configuration

Update database credentials in `/config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'project1_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

## Default Credentials

**Sample Users:**
- Username: `admin` / Email: `admin@project1.local` / Password: `password`
- Username: `demo` / Email: `demo@project1.local` / Password: `password`

**Important:** Change these default passwords in production!

## Tables

- `users` - User accounts
- `posts` - Blog posts or content
- `categories` - Content categories
- `post_categories` - Many-to-many relationship between posts and categories
- `sessions` - Session management (optional)

## Migrations

For future database changes, create timestamped SQL files:
- `2025_01_15_create_users_table.sql`
- `2025_01_16_add_column_to_posts.sql`

## Backup

Regular backups are recommended:

```bash
mysqldump -u root -p project1_db > backup_$(date +%Y%m%d).sql
```
