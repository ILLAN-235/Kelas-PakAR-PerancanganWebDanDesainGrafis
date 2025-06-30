-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 06:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u747399580_toko_veridla`
--

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `name`, `description`, `price`, `stock`, `image_url`) VALUES
(2, 'Nila Bumbu', 'Ikan nila segar dengan bumbu rempah.', 34000.00, 30, 'assets/img/nila.jpg'),
(3, 'Gurame Bumbu', 'Ikan gurame siap masak.', 70000.00, 25, 'assets/img/gurame.jpg'),
(4, 'Jambu Citra', 'Jambu Citra segar dengan tekstur renyah dan rasa manis ringan. Buah ini cocok dikonsumsi langsung atau dijadikan jus. Kaya vitamin dan serat, praktis dinikmati kapan saja.', 32000.00, 40, 'assets/img/jambu.jpg'),
(6, 'Gula Jawa Asli', 'Gula Jawa asli dibuat dari nira kelapa pilihan dengan proses tradisional. Memiliki rasa manis alami dengan aroma khas yang cocok untuk berbagai masakan dan minuman tradisional.', 24000.00, 0, 'assets/img/gula_jawa.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
