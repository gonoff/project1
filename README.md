# Project1 - Custom PHP Application

A modular PHP application with clean architecture, no framework dependencies.

## Features

- Custom PHP (no frameworks)
- Modular CSS architecture
- Bootstrap Icons integration
- MySQL/MariaDB database
- Clean folder structure
- PDO database connections
- Helper functions
- Responsive design

## Project Structure

```
project1/
├── assets/              # Static assets
│   ├── css/            # Modular CSS files
│   │   ├── reset.css
│   │   ├── variables.css
│   │   ├── layout.css
│   │   ├── components.css
│   │   └── utilities.css
│   ├── js/             # JavaScript files
│   └── icons/          # Custom icons (Bootstrap Icons via CDN)
├── config/             # Configuration files
│   ├── config.php      # Main configuration
│   └── database.php    # Database connection
├── database/           # Database files
│   ├── schema.sql      # Database schema
│   └── README.md       # Database documentation
├── helpers/            # Helper functions
│   └── functions.php   # Common utility functions
├── includes/           # Reusable components
│   ├── header.php      # Site header
│   └── footer.php      # Site footer
├── modules/            # Feature modules
│   └── users/          # User module
│       └── User.php    # User class
├── index.php           # Main entry point
├── .htaccess          # Apache configuration
└── README.md          # This file
```

## Installation

1. **Clone or copy** the project to your web server directory

2. **Configure Apache** to point to the project root

3. **Create the database:**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

4. **Update database credentials** in `config/database.php`

5. **Set permissions** (if needed):
   ```bash
   chmod -R 755 project1/
   ```

6. **Access the application** at `http://localhost/project1/`

## Configuration

Edit `config/config.php` to customize:
- Base URL
- Error reporting
- File paths

Edit `config/database.php` for database settings:
- DB_HOST
- DB_NAME
- DB_USER
- DB_PASS

## Database

Default credentials (change in production):
- Username: `admin` / Password: `password`
- Username: `demo` / Password: `password`

See `database/README.md` for more details.

## CSS Architecture

The CSS is organized in a modular way:
- `reset.css` - Browser reset
- `variables.css` - CSS custom properties
- `layout.css` - Layout and grid system
- `components.css` - UI components (buttons, cards, forms)
- `utilities.css` - Utility classes

## Creating Modules

1. Create a new folder in `modules/`
2. Add your PHP class files
3. Follow the User module as an example

Example:
```php
require_once MODULES_PATH . '/users/User.php';
$user = new User();
$users = $user->getAll();
```

## Helper Functions

Available in `helpers/functions.php`:
- `sanitize($data)` - Sanitize input
- `redirect($url)` - Redirect to URL
- `isLoggedIn()` - Check authentication
- `setFlash($type, $message)` - Set flash message
- `getFlash()` - Get flash message
- `displayFlash()` - Display flash message
- `formatDate($date)` - Format dates
- `dd($data)` - Debug helper

## Security

- PDO prepared statements for SQL injection prevention
- Password hashing with `password_hash()`
- Input sanitization helpers
- Session management
- HTTPS recommended for production

## Development

This is a foundation for building custom PHP applications. Extend it with:
- Authentication system
- Admin panel
- API endpoints
- More modules
- File uploads
- Email integration

## License

Open source - feel free to use and modify.
