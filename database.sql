CREATE DATABASE IF NOT EXISTS `renteasy_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `renteasy_db`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `role` ENUM('super_admin', 'admin', 'staff', 'customer') NOT NULL DEFAULT 'customer',
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `vehicles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `plate_number` VARCHAR(20) NOT NULL UNIQUE,
    `brand` VARCHAR(50) NOT NULL,
    `model` VARCHAR(50) NOT NULL,
    `type` VARCHAR(30) NOT NULL,
    `year` INT NOT NULL,
    `daily_rate` DECIMAL(10,2) NOT NULL,
    `status` ENUM('available', 'rented', 'maintenance') NOT NULL DEFAULT 'available',
    `image_path` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `rentals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `vehicle_id` INT NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `total_cost` DECIMAL(10,2) NOT NULL,
    `status` ENUM('pending', 'approved', 'rented', 'returned', 'cancelled') NOT NULL DEFAULT 'pending',
    `processed_by` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`processed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `system_settings` (
    `setting_key` VARCHAR(50) PRIMARY KEY,
    `setting_value` TEXT NOT NULL
) ENGINE=InnoDB;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `status`) VALUES
(1, 'Sadman Sakib Chowdhury', 'superadmin@renteasy.com', '$2y$10$f/C5o8J7g4D9xQc5o5I/yeG07Y9vOa1sU4K5f4D9xQc5o5I/yeG07', '+8801711111111', 'AIUB, Kuratoli, Dhaka', 'super_admin', 'active'),
(2, 'Md. Sajjatul Alam', 'admin@renteasy.com', '$2y$10$f/C5o8J7g4D9xQc5o5I/yeG07Y9vOa1sU4K5f4D9xQc5o5I/yeG07', '+8801722222222', 'Dhaka, Bangladesh', 'admin', 'active'),
(3, 'Rahim Ahmed', 'staff@renteasy.com', '$2y$10$f/C5o8J7g4D9xQc5o5I/yeG07Y9vOa1sU4K5f4D9xQc5o5I/yeG07', '+8801733333333', 'Banani, Dhaka', 'staff', 'active'),
(4, 'Karim Hasan', 'customer@renteasy.com', '$2y$10$f/C5o8J7g4D9xQc5o5I/yeG07Y9vOa1sU4K5f4D9xQc5o5I/yeG07', '+8801744444444', 'Gulshan, Dhaka', 'customer', 'active')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `vehicles` (`id`, `plate_number`, `brand`, `model`, `type`, `year`, `daily_rate`, `status`, `image_path`) VALUES
(1, 'DHAKA-METRO-KA-1122', 'Toyota', 'Premio', 'Sedan', 2018, 3500.00, 'available', 'assets/images/r15.webp'),
(2, 'DHAKA-METRO-GA-4455', 'Honda', 'Civic', 'Sedan', 2020, 4500.00, 'available', 'assets/images/r15.webp'),
(3, 'DHAKA-METRO-HA-7788', 'Mitsubishi', 'Outlander', 'SUV', 2019, 6000.00, 'available', 'assets/images/r15.webp'),
(4, 'DHAKA-METRO-LA-9900', 'Nissan', 'X-Trail', 'SUV', 2017, 5500.00, 'rented', 'assets/images/r15.webp'),
(5, 'DHAKA-METRO-MA-3344', 'Yamaha', 'R15 V3', 'Motorcycle', 2021, 1500.00, 'available', 'assets/images/r15.webp')
ON DUPLICATE KEY UPDATE `plate_number` = VALUES(`plate_number`);

INSERT INTO `system_settings` (`setting_key`, `setting_value`) VALUES
('system_name', 'RentEasy VRMS'),
('currency', 'BDT'),
('contact_email', 'support@renteasy.com'),
('contact_phone', '+88029999999'),
('allow_registration', '1')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);
