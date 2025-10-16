# CLAUDE.md

This file provides context about the project for AI assistants like Claude Code.

## Project Overview

**Project Name:** Project1
**Type:** Custom PHP Web Application with Role-Based Access Control (RBAC)
**Framework:** None (Custom vanilla PHP)
**Database:** MySQL/MariaDB
**Environment:** Apache on Termux (Android)

## Architecture

### Technology Stack
- **Backend:** PHP 8+ (custom, no framework)
- **Database:** MySQL/MariaDB with PDO
- **Frontend:** Custom modular CSS, Bootstrap Icons
- **Version Control:** Git + GitHub
- **Server:** Apache 2.4

### Design Philosophy
- **No frameworks:** Pure PHP for full control and learning
- **Modular architecture:** Organized by feature modules
- **Role-Based Access Control:** Flexible permission system
- **Security-first:** Prepared statements, password hashing, input sanitization
- **Creme color scheme:** Soft, earthy design aesthetic with desaturated colors

## Project Structure

```
project1/
├── assets/              # Frontend assets
│   ├── css/            # Modular CSS architecture
│   │   ├── reset.css       # Browser reset
│   │   ├── variables.css   # CSS custom properties (creme colors)
│   │   ├── layout.css      # Layout and grid
│   │   ├── components.css  # UI components
│   │   └── utilities.css   # Utility classes
│   └── js/             # JavaScript utilities
│       └── main.js
├── config/             # Configuration
│   ├── config.php          # Main config & constants
│   └── database.php        # Database connection (Singleton PDO)
├── database/           # Database files
│   ├── schema.sql          # RBAC database schema
│   ├── init_db.sh          # Database initialization script
│   └── README.md           # Database documentation
├── helpers/            # Helper functions
│   └── functions.php       # Common utilities (sanitize, redirect, flash, etc.)
├── includes/           # Reusable components
│   ├── header.php          # Site header with dynamic navigation
│   └── footer.php          # Site footer
├── modules/            # Feature modules (MVC-like structure)
│   ├── users/
│   │   └── User.php        # User management & RBAC methods
│   ├── roles/
│   │   └── Role.php        # Role management
│   ├── permissions/
│   │   └── Permission.php  # Permission management
│   └── modules/
│       └── Module.php      # Module management
├── index.php           # Entry point (redirects based on auth)
├── login.php           # Login page
├── register.php        # Registration page
├── logout.php          # Logout handler
├── dashboard.php       # User dashboard
└── README.md           # Project documentation
```

## Database Schema (RBAC)

### Core Tables

**users**
- User accounts with username, email, password (hashed)
- `is_active` flag for account status

**roles**
- Role definitions (admin, manager, user, custom)
- `is_system` flag protects system roles from deletion

**user_roles**
- Many-to-many: Users can have multiple roles

**modules**
- Application features/sections (dashboard, users, roles, settings)
- Each module can be enabled/disabled

**permissions**
- Actions within modules (view, create, edit, delete)
- Unique constraint per module+permission combination

**role_permissions**
- Many-to-many: Defines what each role can do

### Default Setup

**Roles:**
- `admin` (system) - Full access
- `manager` - Most features
- `user` - Basic access (dashboard view only)

**Modules:**
- dashboard (view)
- users (view, create, edit, delete)
- roles (view, create, edit, delete, assign_permissions)
- settings (view, edit)

## Key Features

### Authentication
- Registration with validation
- Login with email + password
- Session-based authentication
- Account active/inactive status
- Secure password hashing (bcrypt)

### Role-Based Access Control (RBAC)
- Users → Multiple Roles → Multiple Permissions
- Granular module-level permissions
- Dynamic permission checking
- Protected system roles
- Create custom roles on the fly

### Security
- PDO prepared statements (SQL injection prevention)
- Input sanitization helpers
- Password hashing with `password_hash()`
- Session management
- XSS protection via `htmlspecialchars()`

### Design System
- Creme color palette (#f5f3e8 backgrounds, #faf8f0 cards)
- Desaturated dark colors for buttons/text
- Bootstrap Icons integration
- Responsive grid system
- Modular CSS architecture

## Common Tasks

### Adding a New Module

1. **Database:**
   ```sql
   INSERT INTO modules (name, display_name, description, icon, sort_order)
   VALUES ('products', 'Product Management', 'Manage products', 'bi-box', 5);
   ```

2. **Permissions:**
   ```sql
   INSERT INTO permissions (module_id, name, description)
   VALUES
   ((SELECT id FROM modules WHERE name = 'products'), 'view', 'Can view products'),
   ((SELECT id FROM modules WHERE name = 'products'), 'create', 'Can create products');
   ```

3. **Create Module Class:**
   ```php
   // modules/products/Product.php
   class Product {
       private $db;
       public function __construct() {
           $this->db = getDB();
       }
       // CRUD methods...
   }
   ```

4. **Assign to Role:**
   ```sql
   INSERT INTO role_permissions (role_id, permission_id)
   SELECT role_id, permission_id FROM ...
   ```

### Checking Permissions

```php
$user = new User();
if ($user->hasPermission($_SESSION['user_id'], 'products', 'create')) {
    // User can create products
}
```

### Creating a New Role

```php
$role = new Role();
$roleId = $role->create('editor', 'Content Editor');
$role->assignPermission($roleId, $permissionId);
```

## Configuration

### Database Connection
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'project1_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Base URL
Edit `config/config.php`:
```php
define('BASE_URL', '/project1');
```

### Error Reporting
Development: `error_reporting(E_ALL); ini_set('display_errors', 1);`
Production: Set to `0` in `config/config.php`

## Development Guidelines

### Code Style
- Use PSR-12 coding standards
- Descriptive variable names
- Comment complex logic
- Keep functions focused and small

### Security Checklist
- ✓ Always use prepared statements
- ✓ Sanitize all user input
- ✓ Escape output with `htmlspecialchars()`
- ✓ Never store plain-text passwords
- ✓ Validate on both client and server
- ✓ Use HTTPS in production

### Adding New Pages

1. Create PHP file in root
2. Check authentication: `if (!isLoggedIn()) redirect(...)`
3. Check permissions: `$user->hasPermission(...)`
4. Include header/footer
5. Use flash messages for feedback

## Helper Functions

Available in `helpers/functions.php`:
- `sanitize($data)` - Clean input
- `redirect($url)` - Redirect to URL
- `isLoggedIn()` - Check if user logged in
- `setFlash($type, $msg)` - Set flash message
- `getFlash()` - Retrieve flash message
- `displayFlash()` - Output flash message HTML
- `formatDate($date)` - Format dates
- `dd($data)` - Debug & die

## Git Workflow

```bash
# Make changes
git add .
git commit -m "Description of changes"
git push origin main
```

## Future Enhancements

Potential features to add:
- [ ] User profile management
- [ ] Password reset functionality
- [ ] Email verification
- [ ] Activity logging/audit trail
- [ ] API endpoints (REST/JSON)
- [ ] Two-factor authentication
- [ ] File upload system
- [ ] Advanced search/filtering
- [ ] Export data (CSV, PDF)
- [ ] Dark mode toggle
- [ ] Multi-language support

## Troubleshooting

### Database Connection Issues
- Check MySQL is running: `mysql -u root -e "SELECT 1;"`
- Verify credentials in `config/database.php`
- Check database exists: `SHOW DATABASES;`

### Permission Denied Errors
- Check file permissions: `chmod -R 755 project1/`
- Verify Apache user has access

### Session Issues
- Ensure `session_start()` is called in `config/config.php`
- Check session directory permissions

## Contact & Credits

**Developer:** gonoff
**Email:** kikcroma@gmail.com
**GitHub:** https://github.com/gonoff/project1

Built with assistance from Claude Code (Anthropic).

---

*Last Updated: 2025-10-16*
