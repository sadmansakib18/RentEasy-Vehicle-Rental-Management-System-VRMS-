<?php
require_once __DIR__ . "/Database.php";

class RentalModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllWithDetails() {
        $sql = "SELECT r.*, u.name AS customer_name, u.email AS customer_email, u.phone AS customer_phone,
                       v.brand, v.model, v.plate_number, v.type AS vehicle_type, v.daily_rate,
                       p.name AS processor_name
                FROM rentals r
                JOIN users u ON r.customer_id = u.id
                JOIN vehicles v ON r.vehicle_id = v.id
                LEFT JOIN users p ON r.processed_by = p.id
                ORDER BY r.id DESC";
        $res = $this->db->query($sql);
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getByCustomer($customerId) {
        $sql = "SELECT r.*, v.brand, v.model, v.plate_number, v.type AS vehicle_type, v.daily_rate, v.image_path
                FROM rentals r
                JOIN vehicles v ON r.vehicle_id = v.id
                WHERE r.customer_id = ?
                ORDER BY r.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getById($id) {
        $sql = "SELECT r.*, u.name AS customer_name, v.brand, v.model, v.plate_number
                FROM rentals r
                JOIN users u ON r.customer_id = u.id
                JOIN vehicles v ON r.vehicle_id = v.id
                WHERE r.id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function create($customerId, $vehicleId, $startDate, $endDate, $totalCost, $status = "pending", $processedBy = null) {
        $stmt = $this->db->prepare("INSERT INTO rentals (customer_id, vehicle_id, start_date, end_date, total_cost, status, processed_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissdsi", $customerId, $vehicleId, $startDate, $endDate, $totalCost, $status, $processedBy);
        return $stmt->execute();
    }

    public function updateStatus($id, $status, $processedBy = null) {
        if ($processedBy !== null) {
            $stmt = $this->db->prepare("UPDATE rentals SET status = ?, processed_by = ? WHERE id = ?");
            $stmt->bind_param("sii", $status, $processedBy, $id);
        } else {
            $stmt = $this->db->prepare("UPDATE rentals SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM rentals WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getPendingCount() {
        $res = $this->db->query("SELECT COUNT(*) AS total FROM rentals WHERE status = 'pending'");
        $row = $res->fetch_assoc();
        return (int)$row["total"];
    }

    public function getActiveCount() {
        $res = $this->db->query("SELECT COUNT(*) AS total FROM rentals WHERE status = 'rented'");
        $row = $res->fetch_assoc();
        return (int)$row["total"];
    }

    public function getTotalRevenue() {
        $res = $this->db->query("SELECT SUM(total_cost) AS total FROM rentals WHERE status IN ('rented', 'returned', 'approved')");
        $row = $res->fetch_assoc();
        return (float)($row["total"] ?? 0);
    }
}
?>
