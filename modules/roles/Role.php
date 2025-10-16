<?php
/**
 * Role Module
 * Handles role management operations
 */

class Role {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Get all roles
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY is_system DESC, name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get role by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get role by name
     */
    public function getByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    /**
     * Create new role
     */
    public function create($name, $description = null) {
        $stmt = $this->db->prepare("
            INSERT INTO roles (name, description, is_system)
            VALUES (?, ?, FALSE)
        ");
        return $stmt->execute([$name, $description]);
    }

    /**
     * Update role
     */
    public function update($id, $name, $description = null) {
        // Don't allow updating system roles
        $role = $this->getById($id);
        if ($role && $role['is_system']) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE roles
            SET name = ?, description = ?
            WHERE id = ? AND is_system = FALSE
        ");
        return $stmt->execute([$name, $description, $id]);
    }

    /**
     * Delete role
     */
    public function delete($id) {
        // Don't allow deleting system roles
        $role = $this->getById($id);
        if ($role && $role['is_system']) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM roles WHERE id = ? AND is_system = FALSE");
        return $stmt->execute([$id]);
    }

    /**
     * Get role permissions
     */
    public function getPermissions($roleId) {
        $stmt = $this->db->prepare("
            SELECT p.*, m.name as module_name, m.display_name as module_display_name
            FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            JOIN modules m ON p.module_id = m.id
            WHERE rp.role_id = ?
            ORDER BY m.sort_order, p.name
        ");
        $stmt->execute([$roleId]);
        return $stmt->fetchAll();
    }

    /**
     * Assign permission to role
     */
    public function assignPermission($roleId, $permissionId) {
        $stmt = $this->db->prepare("
            INSERT INTO role_permissions (role_id, permission_id)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE granted_at = CURRENT_TIMESTAMP
        ");
        return $stmt->execute([$roleId, $permissionId]);
    }

    /**
     * Remove permission from role
     */
    public function removePermission($roleId, $permissionId) {
        $stmt = $this->db->prepare("DELETE FROM role_permissions WHERE role_id = ? AND permission_id = ?");
        return $stmt->execute([$roleId, $permissionId]);
    }

    /**
     * Set all permissions for a role (replaces existing)
     */
    public function setPermissions($roleId, $permissionIds) {
        try {
            $this->db->beginTransaction();

            // Remove all existing permissions
            $stmt = $this->db->prepare("DELETE FROM role_permissions WHERE role_id = ?");
            $stmt->execute([$roleId]);

            // Add new permissions
            if (!empty($permissionIds)) {
                $stmt = $this->db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
                foreach ($permissionIds as $permissionId) {
                    $stmt->execute([$roleId, $permissionId]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Get users with this role
     */
    public function getUsers($roleId) {
        $stmt = $this->db->prepare("
            SELECT u.* FROM users u
            JOIN user_roles ur ON u.id = ur.user_id
            WHERE ur.role_id = ?
        ");
        $stmt->execute([$roleId]);
        return $stmt->fetchAll();
    }
}
?>
