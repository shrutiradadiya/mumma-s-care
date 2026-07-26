-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2025 at 11:50 AM
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
-- Database: `mumma's_care`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `profile_picture`) VALUES
(1, 'mumma\'s care', 'admin@123', 'OIP (1).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contact_form`
--

CREATE TABLE `contact_form` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_form`
--

INSERT INTO `contact_form` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'radadiya shruti', 'krish@gmail.com', 'best product', '2025-02-19 12:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `password`, `created_at`, `reset_token`, `token_expiry`) VALUES
(1, 'radadiya shruti', 'krish@gmail.com', '$2y$10$JmrEDOr8r0i70zknNIc1quKWJifOy0llJFjIi3jKjUeezN6SNhzi6', '2025-02-13 12:46:22', NULL, NULL),
(3, 'parmar priyansi', 'piu23@gmail.com', '$2y$10$rFIn945oQdgjjynhb/nz0ugU.Zg3T2KxFw/nLcEgvhY94PlCEPZ12', '2025-02-14 09:19:50', NULL, NULL),
(4, 'riddhi', 'riddhi@gamil.com', '$2y$10$4tiSF9gPd.UMQkCgBo8I7eds4rd4yE8nUu80DsasJLSCP0URVkcKy', '2025-02-14 09:20:53', NULL, NULL),
(6, 'shruti', 'shrutiradadiya1035@gmail.com', '$2y$10$uFJvlAq2xCk2IAEFGsWyEeV8K2tWhz4yJHuf9rI7n4bl3UHtR6Nui', '2025-02-19 04:08:26', NULL, NULL),
(7, 'shruti', 'shrutiradadiya10@gmail.com', '$2y$10$Om.zTwjMDPSv4Y6vJGvYv.Us6FmB86taGAF6H4yW3Nk61pIxckwwC', '2025-02-19 05:44:05', NULL, NULL),
(8, 'abc', 'abc@gamil.com', '$2y$10$J5SqiO45mm.S1n/bkSKcMuPVqAFnOBEmT09p/Ogzc.J93vnsXrgJa', '2025-02-19 05:47:36', NULL, NULL),
(9, 'palak', 'palak@gamil.com', '$2y$10$Wp9HUZd2RDPKi8pY8iK19OfpAxpNiGOhlbZ.vV4nK1koiJ54ffb0K', '2025-02-20 04:22:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `razorpay_payment_id` varchar(255) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `name`, `email`, `address`, `contact_number`, `total_price`, `payment_method`, `order_status`, `created_at`, `razorpay_payment_id`, `order_date`, `product_id`) VALUES
(1, 8, 'radadiya shruti', 'abc@gamil.com', 'c block,ranpar', '123456789', 6000.00, 'COD', 'cancelled', '2025-02-26 11:30:21', NULL, '2025-02-26 17:00:21', 0),
(2, 8, 'manisha radadiya', 'abc@gamil.com', 'babara,ranpar', '01234567890', 1200.00, 'COD', 'cancelled', '2025-02-26 11:39:15', NULL, '2025-02-26 17:09:15', 0),
(3, 8, 'radadiya shruti', 'abc@gamil.com', 'c block,ranpar', '123456789', 6000.00, 'COD', 'cancelled', '2025-02-26 11:40:08', NULL, '2025-02-26 17:10:08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `total`) VALUES
(1, 1, 1, 10, 600.00, 6000.00),
(2, 2, 1, 2, 600.00, 1200.00),
(3, 3, 1, 10, 600.00, 6000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `razorpay_order_id` varchar(255) NOT NULL,
  `razorpay_payment_id` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Success','Failed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category`, `created_at`, `stock`) VALUES
(1, 'Wowper', 'Super soft diapers with excellent absorbency. Perfect for your baby.', 600.00, '/dipers/13.jpg', 'diapers', '2025-01-04 12:53:31', 1000),
(2, 'Huggies', 'High-quality diapers designed for overnight comfort.', 700.00, '/dipers/2.jpg', 'diapers', '2025-01-04 12:53:31', 1000),
(3, 'Cotten dipers', 'Reusable, eco-friendly diapers.\n', 250.00, '/dipers/3.jpg', 'diapers', '2025-01-04 12:53:31', 989),
(4, 'Little Smile', '. Soft, absorbent diapers for dryness.\n', 700.00, '/dipers/4.jpg', 'diapers', '2025-01-04 12:53:31', 1000),
(5, 'Mamaearth', 'Comfortable fit diapers.\n', 950.00, '/dipers/5.jpg', 'diapers', '2025-01-04 14:50:40', 1000),
(6, 'Jr.Sr', 'Leak-proof diapers for active babies.\r\n', 700.00, '/dipers/6.jpg', 'diapers', '2025-01-06 17:46:19', 1000),
(7, 'Toddels', 'best beby diapers ', 700.00, '/dipers/7.jpg', 'diapers', '2025-01-06 17:54:33', 1000),
(8, 'Bum Tum', 'Extra absorbent diapers for nighttime. ', 550.00, '/dipers/8.jpg', 'diapers', '2025-01-06 18:07:13', 1000),
(9, 'Honey Bunny', 'Long-lasting protection diapers.', 511.00, '/dipers/9.jpg', 'diapers', '2025-01-06 18:07:13', 1000),
(10, 'Kangaroo', 'Sleep-friendly diapers.', 200.00, '/dipers/10.jpg', 'diapers', '2025-01-06 18:07:13', 1000),
(11, 'Honey Bunny Dipers', 'Easy-to-use potty training diapers.', 530.00, '/dipers/11.jpg', 'diapers', '2025-01-06 18:07:13', 1000),
(12, 'Pro-ease Dipers', 'Soft, flexible training pants.', 800.00, '/dipers/12.jpg', 'diapers', '2025-01-06 18:13:09', 1000),
(13, 'Kiddle care', 'Soft and stretchy.', 312.00, '/dipers/16.jpg', 'diapers', '2025-01-07 09:44:34', 1000),
(14, 'Mother sparsh', 'Softens and moisturizes skin', 1000.00, '/lotion/1.jpg', 'Skin Care', '2025-01-07 11:34:10', 1000),
(15, 'lotion', ' Hydrates and nourishes', 1500.00, '/lotion/2.jpg', 'Skin Care', '2025-01-07 11:44:02', 1000),
(16, 'Ecomama', '  Soothes dry skin.', 1200.00, '/lotion/3.jpg', 'Skin Care', '2025-01-07 11:47:31', 1000),
(17, 'Softsens lotion', 'Locks in moisture', 1200.00, '/lotion/4.jpg', 'Skin Care', '2025-01-07 11:47:31', 1000),
(18, 'Chicco lotion', ' Long-lasting hydration.', 1200.00, '/lotion/5.jpg', 'Skin Care', '2025-01-07 11:54:07', 1000),
(19, 'Himalaya lotion', 'Calms irritated skin.', 1200.00, '/lotion/6.jpg', 'Skin Care', '2025-01-07 11:54:08', 1000),
(20, 'lotion', ' Nourishing lotion for soft and healthy skin.', 1200.00, '/lotion/7.jpg', 'Skin Care', '2025-01-07 11:54:08', 1000),
(21, 'lotion', 'Hydrates dry skin for comfort.', 1200.00, '/lotion/8.jpg', 'Skin Care', '2025-01-07 11:54:08', 1000),
(22, 'lotion', ' Softens and moisturizes deeply.', 1300.00, '/lotion/9.jpg', 'Skin Care', '2025-01-07 11:54:08', 1000),
(23, 'lotion', 'Gentle care for sensitive skin.', 1400.00, '/lotion/10.jpg', 'Skin Care', '2025-01-07 11:54:08', 1000),
(24, 'lotion', 'Softens and smooths skin texture.', 1400.00, '/lotion/11.jpg', 'Skin Care', '2025-01-07 11:58:04', 1000),
(25, 'beby shampoo', ' Natural ingredients for healthy skin.', 1200.00, '/lotion/12.jpg', 'Skin Care', '2025-01-07 11:58:04', 1000),
(26, 'short frock', 'Adorable sundress.\r\n', 250.00, '/cloth/1.jpg', 'Girl\'s Fashion', '2025-01-08 17:43:09', 1000),
(27, 'short skirt', 'Beautiful and flowing floral dress.\n', 300.00, '/cloth/2.jpg', 'Girl\'s Fashion', '2025-01-08 17:45:12', 1000),
(28, 'jeans tshirt', 'Comfy and casual sundress.\n', 300.00, '/cloth/4.jpg', 'Girl\'s Fashion', '2025-01-11 13:08:15', 1000),
(29, 'short skirt', 'Classic denim look that\'s perfect for school or play.\n', 350.00, '/cloth/3.jpg', 'Girl\'s Fashion', '2025-01-11 13:08:16', 1000),
(30, 'party dress', 'Pretty and pink princess dress.\n', 1000.00, '/cloth/5.jpg', 'Girl\'s Fashion', '2025-01-11 13:08:16', 1000),
(31, 'short party wear', 'Absolutely adorable little dress perfect for special occasions.\n', 530.00, '/cloth/6.jpg', 'Girl\'s Fashion', '2025-01-11 13:14:15', 1000),
(32, 'shorts', 'Chic short dangrey and mini skirt.\n', 360.00, '/cloth/8.jpg', 'Girl\'s Fashion', '2025-01-11 13:14:15', 1000),
(33, 'short party skirt', 'Cute dangrey and skirt set.', 700.00, '/cloth/9.jpg', 'Girl\'s Fashion', '2025-01-11 13:14:15', 1000),
(34, 'long skirt', 'Stylish short skirt combo', 350.00, '/cloth/10.jpg', 'Girl\'s Fashion', '2025-01-11 13:14:15', 1000),
(35, 'short skirt', 'Adorable dangrey and short skirt combo', 550.00, '/cloth/11.jpg', 'Girl\'s Fashion', '2025-01-11 13:14:15', 1000),
(36, 'short skirt', 'Cute short skirt set', 600.00, '/cloth/12.jpg', 'Girl\'s Fashion', '2025-01-11 13:14:15', 1000),
(37, 'beby boy', 'Adorable little hero onesie', 500.00, '/cloth/boy/1.jpg', 'Boy\'s Fashion', '2025-01-13 11:15:42', 1000),
(38, 'cloth (boys)', 'Comfy and stylish onesie for everyday wear.\n', 600.00, '/cloth/boy/2.jpg', 'Boy\'s Fashion', '2025-01-13 14:17:14', 1000),
(39, 'cloth (boys)', 'Little champ romper with cool design.', 600.00, '/cloth/boy/3.jpg', 'Boy\'s Fashion', '2025-01-13 14:20:25', 1000),
(40, 'cloth (boys)', 'soft cloth for beby', 600.00, '/cloth/boy/4.jpg', 'Boy\'s Fashion', '2025-01-13 14:20:25', 1000),
(41, 'cloth (boys)', 'Comfy and cute baby boy pants.\n', 600.00, '/cloth/boy/5.jpg', 'Boy\'s Fashion', '2025-01-13 14:20:25', 1000),
(43, 'cloth (boys)', 'soft cloth for beby', 600.00, '/cloth/boy/8.jpg', 'Boy\'s Fashion', '2025-01-13 14:20:25', 1000),
(44, 'cloth (boys)', 'Good pants', 600.00, '/cloth/boy/9.jpg', 'Boy\'s Fashion', '2025-01-13 14:20:25', 1000),
(45, 'cloth (boys)', 'soft cloth for beby', 600.00, '/cloth/boy/10.jpg', 'Boy\'s Fashion', '2025-01-13 14:25:56', 1000),
(46, 'cloth (boys)', 'Cool and nice shorts.', 600.00, '/cloth/boy/12.jpg', 'Boy\'s Fashion', '2025-01-13 14:25:56', 1000),
(47, 'Wintter cloth ', 'Warm hoodie for cold weather', 600.00, '/cloth/boy/15.jpg', 'Boy\'s Fashion', '2025-01-13 14:25:56', 1000),
(48, 'Boy\'s cloth', ' Cool graphic tee for casual wear.\r\n', 1000.00, '/cloth/boy/18.jpg', 'Boy\'s Fashion', '2025-01-14 19:51:25', 1000),
(49, 'Beby gear', 'best for newbon beby gear', 1000.00, '/Baby Gear/1.jpg', 'Baby Gear', '2025-01-14 20:50:34', 1000),
(50, 'Beby gear', NULL, 2000.00, '/Baby Gear/2.jpg', 'Baby Gear', '2025-01-26 13:55:25', 1000),
(51, 'cloth (boys)', 'Cute t-shirt and jacket for casual wear.', 600.00, '/cloth/boy/6.jpg', 'Boy\'s Fashion', '2025-01-13 14:20:25', 1000),
(52, 'Baby gear', 'Comfortable baby gear with soft padding', 2200.00, '/Baby Gear/4.jpg', 'Baby Gear', '2025-01-26 14:15:16', 1000),
(53, 'Baby gear', 'Premium quality baby gear with safety features', 2300.00, '/Baby Gear/5.jpg', 'Baby Gear', '2025-01-26 14:15:16', 1000),
(54, 'Baby gear', 'Stylish and durable baby gear', 2400.00, '/Baby Gear/6.jpg', 'Baby Gear', '2025-01-26 14:15:16', 1000),
(55, 'Baby gear', 'Lightweight baby gear for easy travel', 2500.00, '/Baby Gear/7.jpg', 'Baby Gear', '2025-01-26 14:15:16', 1000),
(56, 'Baby gear', 'Compact and easy-to-store baby gear', 2700.00, '/Baby Gear/9.jpg', 'Baby Gear', '2025-01-26 14:15:16', 1000),
(57, 'Baby gear', 'Adjustable baby gear with multiple features', 2800.00, '/Baby Gear/10.jpg', 'Baby Gear', '2025-01-26 14:15:16', 1000),
(58, 'Baby gear', 'Affordable baby gear for everyday use', 2900.00, '/Baby Gear/11.jpg', 'Baby Gear', '2025-01-26 14:15:16', 1000),
(59, 'Baby gear', 'Baby gear with smart storage options', 3400.00, '/Baby Gear/8.jpg', 'Baby Gear', '2025-01-26 14:15:16', 1000),
(60, 'Toys', 'Best toys for kids', 500.00, '/Toys/1.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(61, 'Toys', 'Colorful toys with safe materials', 600.00, '/Toys/2.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(62, 'Toys', 'Durable and high-quality toys', 800.00, '/Toys/4.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(63, 'Toys', 'Affordable toys for all ages', 900.00, '/Toys/5.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(64, 'Toys', 'Eco-friendly toys with unique designs', 200.00, '/Toys/6.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(65, 'Toys', 'Soft and safe toys for toddlers', 2100.00, '/Toys/7.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(66, 'Toys', 'Creative toys to enhance imagination', 220.00, '/Toys/8.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(67, 'Toys', 'Portable toys for easy travel', 400.00, '/Toys/9.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(68, 'Toys', 'Fun toys with multiple features', 290.00, '/Toys/10.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(69, 'Toys', 'Toys for educational and creative play', 250.00, '/Toys/11.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(70, 'Toys', 'Luxurious toys with premium quality', 600.00, '/Toys/12.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(71, 'Celebration Kit', 'Premium quality celebration items', 3000.00, '/Celebration Kit/2.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(73, 'Celebration Kit', 'Eco-friendly items for parties', 2200.00, '/Celebration Kit/3.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(74, 'Celebration Kit', 'Customizable celebration packages', 800.00, '/Celebration Kit/4.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(75, 'Celebration Kit', 'Affordable celebration kits', 1100.00, '/Celebration Kit/5.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(76, 'Celebration Kit', 'Creative kits for special events', 2100.00, '/Celebration Kit/6.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(77, 'Celebration Kit', 'Luxurious kits for grand celebrations', 1300.00, '/Celebration Kit/7.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(78, 'Celebration Kit', 'Portable kits for outdoor celebrations', 550.00, '/Celebration Kit/8.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(79, 'Celebration Kit', 'Complete celebration set for all occasions', 600.00, '/Celebration Kit/9.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(80, 'Celebration Kit', 'Modern and stylish celebration kits', 1800.00, '/Celebration Kit/10.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(81, 'Celebration Kit', 'Durable and reusable celebration kits', 4500.00, '/Celebration Kit/11.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(82, 'Celebration Kit', 'Custom-made kits for unique events', 4600.00, '/Celebration Kit/12.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(83, 'Feeding Accessories', 'High-quality baby feeding bottles', 250.00, '/FeedingAccessories/1.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:04:13', 1000),
(84, 'Feeding Accessories', 'Eco-friendly feeding bowls and spoons', 230.00, '/FeedingAccessories/2.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:04:13', 1000),
(85, 'Feeding Accessories', 'Anti-spill sippy cups', 1700.00, '/FeedingAccessories/3.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:04:13', 1000),
(86, 'Feeding Accessories', 'Portable feeding kits for travel', 180.00, '/FeedingAccessories/4.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:04:13', 1000),
(87, 'Feeding Accessories', 'BPA-free feeding accessories', 400.00, '/FeedingAccessories/5.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:04:13', 1000),
(88, 'Feeding Accessories', 'Microwave-safe feeding plates', 200.00, '/FeedingAccessories/6.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:04:13', 1000),
(89, 'Feeding Accessories', 'Ergonomic baby feeding bottles', 100.00, '/FeedingAccessories/7.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:04:13', 1000),
(90, 'Bedding', 'Soft and comfortable baby bedding', 500.00, '/FeedingAccessories/Bedding/1.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:32:36', 1000),
(91, 'Bedding', 'Breathable and lightweight bedding sets', 600.00, '/FeedingAccessories/Bedding/2.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:32:36', 1000),
(92, 'Bedding', 'Hypoallergenic baby mattresses', 700.00, '/FeedingAccessories/Bedding/3.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:32:36', 1000),
(93, 'Bedding', 'Waterproof mattress protectors', 800.00, '/FeedingAccessories/Bedding/4.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:32:36', 1000),
(94, 'Bedding', 'Portable bedding sets for travel', 900.00, '/FeedingAccessories/Bedding/5.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:32:36', 1000),
(95, 'Bedding', 'Anti-bacterial bedding materials', 700.00, '/FeedingAccessories/Bedding/6.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:32:36', 1000),
(96, 'Bedding', 'Organic cotton bedding for babies', 500.00, '/FeedingAccessories/Bedding/7.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:32:36', 1000),
(97, 'Grooming', 'Essential grooming kit for babies', 250.00, '/FeedingAccessories/Grooming/1.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:38:55', 1000),
(99, 'Grooming', 'Soft brushes for sensitive skin', 300.00, '/FeedingAccessories/Grooming/3.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:38:55', 1000),
(100, 'Grooming', 'Complete grooming set with storage case', 400.00, '/FeedingAccessories/Grooming/4.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:38:55', 1000),
(101, 'Grooming', 'Hypoallergenic grooming products', 200.00, '/FeedingAccessories/Grooming/5.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:38:55', 1000),
(102, 'Grooming', 'Ergonomic nail clippers for safety', 300.00, '/FeedingAccessories/Grooming/6.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:38:55', 1000),
(103, 'Grooming', 'Portable grooming kits for travel', 500.00, '/FeedingAccessories/Grooming/7.jpg', 'Feeding Accessories/Bedding/Grooming', '2025-01-26 15:38:55', 1000),
(104, 'Baby gear', 'Baby gear with sleek design and quality materials', 3200.00, '/Baby Gear/12.jpg', 'Baby Gear', '2025-01-26 14:15:16', 1000),
(105, 'Toys', 'Interactive toys for learning and fun', 1700.00, '/Toys/3.jpg', 'Toys', '2025-01-26 14:26:03', 1000),
(106, 'Celebration Kit', 'Perfect kit for all celebrations', 3500.00, '/Celebration Kit/1.jpg', 'Celebration Kit', '2025-01-26 14:31:35', 1000),
(125, 'toys', 'fceefe', 50000.00, 'uploads/19.jpg', 'toys', '2025-02-22 17:43:31', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `customer_id`, `product_id`, `added_at`) VALUES
(1, 4, 0, '2025-02-08 14:36:37'),
(2, 5, 0, '2025-02-08 14:37:43'),
(3, 6, 0, '2025-02-09 05:10:59'),
(4, 7, 0, '2025-02-11 11:31:33'),
(5, 8, 0, '2025-02-11 13:11:28'),
(6, 9, 0, '2025-02-11 13:19:15'),
(7, 10, 0, '2025-02-12 02:20:24'),
(8, 11, 0, '2025-02-12 04:02:36'),
(9, 12, 0, '2025-02-12 08:20:49'),
(10, 16, 0, '2025-02-13 09:00:38'),
(11, 1, 0, '2025-02-13 12:46:23'),
(12, 2, 0, '2025-02-14 09:13:37'),
(13, 3, 0, '2025-02-14 09:19:50'),
(14, 4, 0, '2025-02-14 09:20:53'),
(15, 8, 0, '2025-02-19 05:47:36'),
(16, 9, 0, '2025-02-20 04:22:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_form`
--
ALTER TABLE `contact_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_form`
--
ALTER TABLE `contact_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
