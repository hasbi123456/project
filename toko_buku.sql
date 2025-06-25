-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 25, 2025 at 04:45 PM
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
-- Database: `toko_buku`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `username`, `message`, `timestamp`) VALUES
(1, 'admin', 'fhfghfgh', '2025-06-25 21:01:29'),
(2, 'user', 'sdfsdfsdfs', '2025-06-25 21:13:25'),
(3, 'user', 'dfssdfsdf', '2025-06-25 21:21:40'),
(4, 'Hasbi Zibran', 'kjshksdjhfks', '2025-06-25 21:35:04');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','processing','shipped','completed','cancelled') DEFAULT 'pending',
  `total_amount` decimal(12,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `status`, `total_amount`, `shipping_address`, `phone`, `created_at`, `updated_at`) VALUES
(26, 2, '2025-05-22 16:31:03', 'completed', 75000.00, 'balai tangah', '089567764832', '2025-05-22 09:31:03', '2025-05-23 05:47:23'),
(27, 2, '2025-05-22 17:25:38', 'completed', 75000.00, 'tanjung durian', '68998089', '2025-05-22 10:25:38', '2025-05-23 05:47:20'),
(31, 2, '2025-06-24 20:52:30', 'pending', 755000.00, 'padang', '345435346564', '2025-06-24 13:52:30', '2025-06-24 13:52:30'),
(32, 9, '2025-06-25 21:34:52', 'pending', 755000.00, 'Batusangkar', '081384045083', '2025-06-25 14:34:52', '2025-06-25 14:34:52');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(36, 26, 1, 1, 75000.00, 75000.00),
(37, 27, 1, 1, 75000.00, 75000.00),
(43, 31, 1, 1, 75000.00, 75000.00),
(44, 31, 3, 1, 400000.00, 400000.00),
(45, 31, 7, 1, 150000.00, 150000.00),
(46, 31, 8, 1, 130000.00, 130000.00),
(47, 32, 1, 1, 75000.00, 75000.00),
(48, 32, 3, 1, 400000.00, 400000.00),
(49, 32, 7, 1, 150000.00, 150000.00),
(50, 32, 8, 1, 130000.00, 130000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock`, `image_url`, `created_at`) VALUES
(1, 'Buku-Dia Kakakku', 'Cerita novel ini menceritakan tentang perjuangan seorang Gadis ', 75000.00, 100000, '1.JPG', '2025-05-19 13:03:20'),
(3, 'Buku-Rentang kisah', 'buku ini menceritakan perjalanan hidupnya dari mulai SMA', 400000.00, 100000, '6724561ca73658b075d4bf05da6074f5.jpg', '2025-05-19 20:07:29'),
(7, 'Buku-Garis Waktu', 'menceritakan curahan hati tentang perjumpaan', 150000.00, 600, 'a1a2e5d1c80ea609f1d63929f64669a1.jpg', '2025-05-22 09:34:10'),
(8, 'Buku-Filosofi Teras', 'Buku ini menceritakan tentang filsafat Yunani-Romawi', 130000.00, 250, '0c56e6d8b73b9754542680d6752cc516.jpg', '2025-05-22 09:38:20'),
(9, 'Buku-Surat Dari Semesta', 'Terimalah surat dari semesta Sebagai suatu cara ', 185000.00, 500, '03285110125a916ecd1f82dfcb05a4f4.jpg', '2025-05-22 09:41:59'),
(10, 'Buku-Kitab Filsafat', 'filsafat sering dipandang sebagai teori ', 140000.00, 900, '31f1aab0fc1ea5c007719aa51ddf650c.jpg', '2025-05-22 09:44:15'),
(11, 'Buku-Anak Muda Bebas ', 'tekhnik pengalaman untuk golongan muda  supaya bebas', 80000.00, 125, '7e3155ed9b6a93519ad0c916a6a07fa7.jpg', '2025-05-22 09:56:04'),
(12, 'Buku-Berani Menjadi Aku', ' antologi kisah yang cuba membawa pembaca ', 155000.00, 235, '86bd22b93a3c27bb93db62621c5b4d10.jpg', '2025-05-22 10:01:14'),
(13, 'Buku-Sampah Di Laut Maira', 'Menjaga Sampah di laut maira', 177000.00, 345, 'f437cf05d732cd33a8aa7abaeb956cfb.jpeg', '2025-05-22 10:04:11'),
(14, 'Buku-Rahasia Ruh ', 'tentang semua hal terkait terminologi ', 160000.00, 645, 'c53df09b35a4b6e6a4cd55d1a57c6196.jpeg', '2025-05-22 10:06:12'),
(15, 'Buku-Terusir', 'berkisah mengenai seorang perempuan bernama Mariah', 145000.00, 780, 'b5855fa2fa6f4b8da6ba3aa54ee379c7.png', '2025-05-22 10:08:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '2025-05-19 13:03:20', 'admin'),
(2, 'user', 'user@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2025-05-19 13:03:20', 'user'),
(8, 'jarot', 'jarot@gmail.com', 'b866086860fd1d2f7bbced23688df96b', '2025-05-20 20:12:16', 'user'),
(9, 'Hasbi Zibran', 'hasbizibran369@gmail.com', '960ae1f42508f0f9c4547071c5efc27f', '2025-06-25 14:33:53', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
