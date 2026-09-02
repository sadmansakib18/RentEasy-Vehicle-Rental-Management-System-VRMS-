<?php
require_once __DIR__ . "/Database.php";

class ReportModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getSummaryNumbers() {
        $revRes = $this->db->query("SELECT SUM(total_cost) AS revenue FROM rentals WHERE status IN ('approved', 'rented', 'returned')");
        $revRow = $revRes->fetch_assoc();
        $revenue = (float)($revRow["revenue"] ?? 0);

        $daysRes = $this->db->query("SELECT SUM(DATEDIFF(end_date, start_date) + 1) AS total_days FROM rentals WHERE status IN ('approved', 'rented', 'returned')");
        $daysRow = $daysRes->fetch_assoc();
        $days = (int)($daysRow["total_days"] ?? 0);

        $vehRes = $this->db->query("SELECT COUNT(*) AS total FROM vehicles");
        $vehRow = $vehRes->fetch_assoc();
        $totalVehicles = (int)($vehRow["total"] ?? 0);

        $rentedRes = $this->db->query("SELECT COUNT(*) AS total FROM vehicles WHERE status = 'rented'");
        $rentedRow = $rentedRes->fetch_assoc();
        $rentedVehicles = (int)($rentedRow["total"] ?? 0);

        return [
            "revenue" => $revenue,
            "total_days" => $days,
            "total_vehicles" => $totalVehicles,
            "rented_vehicles" => $rentedVehicles
        ];
    }

    public function getOccupancyStats() {
        $sql = "SELECT type,
                       COUNT(*) AS total_count,
                       SUM(CASE WHEN status = 'rented' THEN 1 ELSE 0 END) AS rented_count
                FROM vehicles
                GROUP BY type";
        $res = $this->db->query($sql);
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $total = (int)$row["total_count"];
            $rented = (int)$row["rented_count"];
            $rate = $total > 0 ? round(($rented / $total) * 100) : 0;
            $data[] = [
                "type" => $row["type"],
                "total_count" => $total,
                "rented_count" => $rented,
                "rate" => $rate
            ];
        }
        return $data;
    }

    public function getTopCustomers() {
        $sql = "SELECT u.name, u.email,
                       COUNT(r.id) AS rental_count,
                       SUM(r.total_cost) AS total_spent
                FROM rentals r
                JOIN users u ON r.customer_id = u.id
                WHERE r.status IN ('approved', 'rented', 'returned')
                GROUP BY u.id
                ORDER BY total_spent DESC
                LIMIT 5";
        $res = $this->db->query($sql);
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
?>
