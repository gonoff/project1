-- Database Schema for Project1
-- Role-Based Access Control (RBAC) System

-- Create database
CREATE DATABASE IF NOT EXISTS project1_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE project1_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Roles table
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    is_system BOOLEAN DEFAULT FALSE COMMENT 'System roles cannot be deleted',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Roles junction table (users can have multiple roles)
CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_role_id (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modules table (represents different parts/features of the application)
CREATE TABLE IF NOT EXISTS modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50) COMMENT 'Bootstrap icon class',
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permissions table (defines what actions can be performed)
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    name VARCHAR(50) NOT NULL COMMENT 'e.g., view, create, edit, delete',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    UNIQUE KEY unique_module_permission (module_id, name),
    INDEX idx_module_id (module_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Role Permissions junction table (defines what permissions each role has)
CREATE TABLE IF NOT EXISTS role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    granted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    INDEX idx_role_id (role_id),
    INDEX idx_permission_id (permission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default system roles
INSERT INTO roles (name, description, is_system) VALUES
('admin', 'System Administrator - Full access to everything', TRUE),
('manager', 'Manager - Can manage most features', FALSE),
('user', 'Regular User - Basic access', FALSE);

-- Insert default modules
INSERT INTO modules (name, display_name, description, icon, sort_order) VALUES
('dashboard', 'Dashboard', 'Main dashboard view', 'bi-speedometer2', 1),
('users', 'User Management', 'Manage users and their roles', 'bi-people', 2),
('roles', 'Role Management', 'Manage roles and permissions', 'bi-shield-lock', 3),
('settings', 'Settings', 'System settings and configuration', 'bi-gear', 4);

-- Insert default permissions for each module
-- Dashboard permissions
INSERT INTO permissions (module_id, name, description) VALUES
((SELECT id FROM modules WHERE name = 'dashboard'), 'view', 'Can view dashboard');

-- Users module permissions
INSERT INTO permissions (module_id, name, description) VALUES
((SELECT id FROM modules WHERE name = 'users'), 'view', 'Can view users list'),
((SELECT id FROM modules WHERE name = 'users'), 'create', 'Can create new users'),
((SELECT id FROM modules WHERE name = 'users'), 'edit', 'Can edit existing users'),
((SELECT id FROM modules WHERE name = 'users'), 'delete', 'Can delete users');

-- Roles module permissions
INSERT INTO permissions (module_id, name, description) VALUES
((SELECT id FROM modules WHERE name = 'roles'), 'view', 'Can view roles list'),
((SELECT id FROM modules WHERE name = 'roles'), 'create', 'Can create new roles'),
((SELECT id FROM modules WHERE name = 'roles'), 'edit', 'Can edit existing roles'),
((SELECT id FROM modules WHERE name = 'roles'), 'delete', 'Can delete roles'),
((SELECT id FROM modules WHERE name = 'roles'), 'assign_permissions', 'Can assign permissions to roles');

-- Settings module permissions
INSERT INTO permissions (module_id, name, description) VALUES
((SELECT id FROM modules WHERE name = 'settings'), 'view', 'Can view settings'),
((SELECT id FROM modules WHERE name = 'settings'), 'edit', 'Can edit settings');

-- Assign ALL permissions to admin role
INSERT INTO role_permissions (role_id, permission_id)
SELECT
    (SELECT id FROM roles WHERE name = 'admin'),
    id
FROM permissions;

-- Assign basic permissions to regular user role
INSERT INTO role_permissions (role_id, permission_id)
SELECT
    (SELECT id FROM roles WHERE name = 'user'),
    id
FROM permissions
WHERE name = 'view' AND module_id IN (
    SELECT id FROM modules WHERE name IN ('dashboard')
);
