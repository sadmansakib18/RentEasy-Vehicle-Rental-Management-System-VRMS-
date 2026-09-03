<?php
require_once __DIR__ . "/Database.php";

class SettingModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll() {
        $res = $this->db->query("SELECT setting_key, setting_value FROM system_settings");
        $data = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $data[$row["setting_key"]] = $row["setting_value"];
            }
        }
        return $data;
    }

    public function get($key, $default = "") {
        $stmt = $this->db->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ? LIMIT 1");
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            return $row["setting_value"];
        }
        return $default;
    }

    public function update($key, $value) {
        $stmt = $this->db->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->bind_param("sss", $key, $value, $value);
        return $stmt->execute();
    }

    public function updateBatch($settings) {
        foreach ($settings as $key => $value) {
            $this->update($key, $value);
        }
        return true;
    }
}

function get_system_setting($key, $default = "") {
    static $cachedSettings = null;
    if ($cachedSettings === null) {
        $model = new SettingModel();
        $cachedSettings = $model->getAll();
    }
    return $cachedSettings[$key] ?? $default;
}
?>
