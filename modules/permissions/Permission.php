<?php
/**
 * Permission Module
 * Handles permission operations
 */

class Permission {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Get all permissions
     */
    public function getAll() {
        $stmt = $this->db->query("
            SELECT p.*, m.name as module_name, m.display_name as module_display_name
            FROM permissions p
            JOIN modules m ON p.module_id = m.id
            ORDER BY m.sort_order, p.name
        ");
        return $stmt->fetchAll();
    }

    /**
     * Get permissions by module
     */
    public function getByModule($moduleId) {
        $stmt = $this->db->prepare("
            SELECT p.*, m.name as module_name, m.display_name as module_display_name
            FROM permissions p
            JOIN modules m ON p.module_id = m.id
            WHERE p.module_id = ?
            ORDER BY p.name
        ");
        $stmt->execute([$moduleId]);
        return $stmt->fetchAll();
    }

    /**
     * Get permission by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, m.name as module_name, m.display_name as module_display_name
            FROM permissions p
            JOIN modules m ON p.module_id = m.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create new permission
     */
    public function create($moduleId, $name, $description = null) {
        $stmt = $this->db->prepare("
            INSERT INTO permissions (module_id, name, description)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$moduleId, $name, $description]);
    }

    /**
     * Update permission
     */
    public function update($id, $name, $description = null) {
        $stmt = $this->db->prepare("
            UPDATE permissions
            SET name = ?, description = ?
            WHERE id = ?
        ");
        return $stmt->execute([$name, $description, $id]);
    }

    /**
     * Delete permission
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM permissions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get permissions grouped by module
     */
    public function getAllGroupedByModule() {
        $stmt = $this->db->query("
            SELECT
                m.id as module_id,
                m.name as module_name,
                m.display_name as module_display_name,
                m.icon as module_icon,
                p.id as permission_id,
                p.name as permission_name,
                p.description as permission_description
            FROM modules m
            LEFT JOIN permissions p ON m.id = p.module_id
            WHERE m.is_active = TRUE
            ORDER BY m.sort_order, p.name
        ");

        $results = $stmt->fetchAll();
        $grouped = [];

        foreach ($results as $row) {
            $moduleId = $row['module_id'];

            if (!isset($grouped[$moduleId])) {
                $grouped[$moduleId] = [
                    'id' => $row['module_id'],
                    'name' => $row['module_name'],
                    'display_name' => $row['module_display_name'],
                    'icon' => $row['module_icon'],
                    'permissions' => []
                ];
            }

            if ($row['permission_id']) {
                $grouped[$moduleId]['permissions'][] = [
                    'id' => $row['permission_id'],
                    'name' => $row['permission_name'],
                    'description' => $row['permission_description']
                ];
            }
        }

        return array_values($grouped);
    }
}
?>
