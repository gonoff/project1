<?php
/**
 * User Module
 * Handles user-related operations
 */

class User {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Get all users
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * Get user by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get user by email
     */
    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Create new user
     */
    public function create($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, created_at)
            VALUES (?, ?, ?, NOW())
        ");

        return $stmt->execute([$username, $email, $hashedPassword]);
    }

    /**
     * Update user
     */
    public function update($id, $username, $email) {
        $stmt = $this->db->prepare("
            UPDATE users
            SET username = ?, email = ?
            WHERE id = ?
        ");

        return $stmt->execute([$username, $email, $id]);
    }

    /**
     * Delete user
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Verify password
     */
    public function verifyPassword($email, $password) {
        $user = $this->getByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Check if email exists
     */
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Check if username exists
     */
    public function usernameExists($username) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Assign role to user
     */
    public function assignRole($userId, $roleId) {
        $stmt = $this->db->prepare("
            INSERT INTO user_roles (user_id, role_id)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE assigned_at = CURRENT_TIMESTAMP
        ");
        return $stmt->execute([$userId, $roleId]);
    }

    /**
     * Remove role from user
     */
    public function removeRole($userId, $roleId) {
        $stmt = $this->db->prepare("DELETE FROM user_roles WHERE user_id = ? AND role_id = ?");
        return $stmt->execute([$userId, $roleId]);
    }

    /**
     * Get user roles
     */
    public function getRoles($userId) {
        $stmt = $this->db->prepare("
            SELECT r.* FROM roles r
            JOIN user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Check if user has role
     */
    public function hasRole($userId, $roleName) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM user_roles ur
            JOIN roles r ON ur.role_id = r.id
            WHERE ur.user_id = ? AND r.name = ?
        ");
        $stmt->execute([$userId, $roleName]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Get user permissions
     */
    public function getPermissions($userId) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.*, m.name as module_name
            FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            JOIN user_roles ur ON rp.role_id = ur.role_id
            JOIN modules m ON p.module_id = m.id
            WHERE ur.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Check if user has permission
     */
    public function hasPermission($userId, $moduleName, $permissionName) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM permissions p
            JOIN modules m ON p.module_id = m.id
            JOIN role_permissions rp ON p.id = rp.permission_id
            JOIN user_roles ur ON rp.role_id = ur.role_id
            WHERE ur.user_id = ? AND m.name = ? AND p.name = ?
        ");
        $stmt->execute([$userId, $moduleName, $permissionName]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
