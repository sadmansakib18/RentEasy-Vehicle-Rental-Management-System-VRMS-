<?php
require_once __DIR__ . "/Database.php";

class VehicleModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll() {
        $res = $this->db->query("SELECT * FROM vehicles ORDER BY id DESC");
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getAvailable() {
        $res = $this->db->query("SELECT * FROM vehicles WHERE status = 'available' ORDER BY id DESC");
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM vehicles WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function getByPlate($plate) {
        $stmt = $this->db->prepare("SELECT * FROM vehicles WHERE plate_number = ? LIMIT 1");
        $stmt->bind_param("s", $plate);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function create($plate_number, $brand, $model, $type, $year, $daily_rate, $status = "available", $image_path = "assets/images/r15.webp") {
        $stmt = $this->db->prepare("INSERT INTO vehicles (plate_number, brand, model, type, year, daily_rate, status, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssdss", $plate_number, $brand, $model, $type, $year, $daily_rate, $status, $image_path);
        return $stmt->execute();
    }

    public function update($id, $plate_number, $brand, $model, $type, $year, $daily_rate, $status, $image_path) {
        $stmt = $this->db->prepare("UPDATE vehicles SET plate_number = ?, brand = ?, model = ?, type = ?, year = ?, daily_rate = ?, status = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("sssssdssi", $plate_number, $brand, $model, $type, $year, $daily_rate, $status, $image_path, $id);
        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE vehicles SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM vehicles WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function search($type = "", $keyword = "") {
        $sql = "SELECT * FROM vehicles WHERE 1=1";
        $params = [];
        $types = "";

        if (!empty($type) && $type !== "All") {
            $sql .= " AND type = ?";
            $params[] = $type;
            $types .= "s";
        }

        if (!empty($keyword)) {
            $sql .= " AND (brand LIKE ? OR model LIKE ? OR plate_number LIKE ?)";
            $term = "%" . $keyword . "%";
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
            $types .= "sss";
        }

        $sql .= " ORDER BY id DESC";

        if (!empty($params)) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $res = $stmt->get_result();
        } else {
            $res = $this->db->query($sql);
        }

        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
?>
