<?php
/**
 * Module Management
 * Handles module operations
 */

class Module {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Get all modules
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM modules ORDER BY sort_order ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get active modules
     */
    public function getActive() {
        $stmt = $this->db->query("SELECT * FROM modules WHERE is_active = TRUE ORDER BY sort_order ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get module by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM modules WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get module by name
     */
    public function getByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM modules WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    /**
     * Create new module
     */
    public function create($name, $displayName, $description = null, $icon = null, $sortOrder = 0) {
        $stmt = $this->db->prepare("
            INSERT INTO modules (name, display_name, description, icon, sort_order)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$name, $displayName, $description, $icon, $sortOrder]);
    }

    /**
     * Update module
     */
    public function update($id, $displayName, $description = null, $icon = null, $sortOrder = 0, $isActive = true) {
        $stmt = $this->db->prepare("
            UPDATE modules
            SET display_name = ?, description = ?, icon = ?, sort_order = ?, is_active = ?
            WHERE id = ?
        ");
        return $stmt->execute([$displayName, $description, $icon, $sortOrder, $isActive, $id]);
    }

    /**
     * Delete module
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM modules WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Toggle module active status
     */
    public function toggleActive($id) {
        $stmt = $this->db->prepare("UPDATE modules SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get modules accessible by user
     */
    public function getAccessibleByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT m.*
            FROM modules m
            JOIN permissions p ON m.id = p.module_id
            JOIN role_permissions rp ON p.id = rp.permission_id
            JOIN user_roles ur ON rp.role_id = ur.role_id
            WHERE ur.user_id = ? AND m.is_active = TRUE
            ORDER BY m.sort_order
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
?>
