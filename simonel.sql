-- Database: `simonel`
--

CREATE DATABASE IF NOT EXISTS `simonel`;
USE `simonel`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
-- Default Password is 'password123'
--
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Administrator', 'admin@simonel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_code` varchar(50) NOT NULL,
  `device_name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'OFFLINE',
  `api_key` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_code` (`device_code`),
  UNIQUE KEY `api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sensor_logs`
--

CREATE TABLE `sensor_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` varchar(50) NOT NULL,
  `voltage` float DEFAULT 0,
  `current` float DEFAULT 0,
  `daya_semu` float DEFAULT 0,
  `daya_nyata` float DEFAULT 0,
  `daya_reaktif` float DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
