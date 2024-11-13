-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 12:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glamour_nail_salon`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `email`) VALUES
(4, 'admin', '$2y$10$m3sxS415dPQXSLt0h4an2eipAtc2q/GyCjEWHwCXJVPYjC9B9aMFG', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `booking_date` date NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `worker_name` varchar(255) DEFAULT NULL,
  `worker_contact` varchar(15) DEFAULT NULL,
  `booking_time` time DEFAULT NULL,
  `booking_hour` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `username`, `service`, `booking_date`, `status`, `created_at`, `amount`, `worker_id`, `worker_name`, `worker_contact`, `booking_time`, `booking_hour`) VALUES
(1, 'jane_doe', 'Manicure', '2024-11-12', 'Done', '2024-11-12 12:26:59', 250.00, 2, 'Emily', '09182318573', '10:00:00', '00:00:01'),
(2, 'sarah_lee', 'Pedicure', '2024-11-12', 'Done', '2024-11-12 12:26:59', 300.00, 1, 'Jane', '09171234567', '11:00:00', '00:00:02'),
(3, 'amy_smith', 'Gel Nails', '2024-11-12', 'Confirmed', '2024-11-12 12:26:59', 400.00, 4, 'Olivia', '09653847263', '12:00:00', '00:00:03'),
(4, 'emily_jones', 'Acrylic Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 500.00, NULL, NULL, NULL, '13:00:00', '00:00:04'),
(5, 'lucy_brown', 'Nail Art', '2024-11-12', 'Confirmed', '2024-11-12 12:26:59', 350.00, 1, 'Jane', '09171234567', '14:00:00', '00:00:05'),
(6, 'olivia_clark', 'Spa Treatments', '2024-11-12', 'Confirmed', '2024-11-12 12:26:59', 700.00, 4, 'Olivia', '09653847263', '15:00:00', '00:00:06'),
(7, 'sophia_morris', 'Manicure', '2024-11-12', 'Confirmed', '2024-11-12 12:26:59', 250.00, 3, 'Sophia', '09753815382', '16:00:00', '00:00:07'),
(8, 'mia_hall', 'Pedicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 300.00, NULL, NULL, NULL, '17:00:00', '00:00:08'),
(9, 'ava_thompson', 'Gel Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 400.00, NULL, NULL, NULL, '18:00:00', '00:00:09'),
(10, 'isabella_turner', 'Acrylic Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 500.00, NULL, NULL, NULL, '19:00:00', '00:00:10'),
(11, 'chloe_white', 'Nail Art', '2024-11-12', 'Confirmed', '2024-11-12 12:26:59', 350.00, 2, 'Emily', '09182318573', '20:00:00', '00:00:11'),
(12, 'ella_black', 'Spa Treatments', '2024-11-12', 'pending', '2024-11-12 12:26:59', 700.00, NULL, NULL, NULL, '21:00:00', '00:00:12'),
(13, 'grace_green', 'Manicure', '2024-11-12', 'Confirmed', '2024-11-12 12:26:59', 250.00, 3, 'Sophia', '09753815382', '22:00:00', '00:00:13'),
(14, 'lily_brown', 'Pedicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 300.00, NULL, NULL, NULL, '23:00:00', '00:00:14'),
(15, 'emma_moore', 'Gel Nails', '2024-11-12', 'Done', '2024-11-12 12:26:59', 400.00, 1, 'Jane', '09171234567', '08:00:00', '00:00:15'),
(16, 'ava_baker', 'Acrylic Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 500.00, NULL, NULL, NULL, '09:00:00', '00:00:16'),
(17, 'zoe_smith', 'Nail Art', '2024-11-12', 'pending', '2024-11-12 12:26:59', 350.00, NULL, NULL, NULL, '10:30:00', '00:00:17'),
(18, 'mia_turner', 'Spa Treatments', '2024-11-12', 'pending', '2024-11-12 12:26:59', 700.00, NULL, NULL, NULL, '11:30:00', '00:00:18'),
(19, 'ella_grant', 'Manicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 250.00, NULL, NULL, NULL, '12:30:00', '00:00:19'),
(20, 'luna_wright', 'Pedicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 300.00, NULL, NULL, NULL, '13:30:00', '00:00:20'),
(21, 'harper_young', 'Gel Nails', '2024-11-12', 'Done', '2024-11-12 12:26:59', 400.00, 4, 'Sophia', '09753815382', '14:30:00', '00:00:21'),
(22, 'zoe_carter', 'Acrylic Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 500.00, NULL, NULL, NULL, '15:30:00', '00:00:22'),
(23, 'aurora_adams', 'Nail Art', '2024-11-12', 'pending', '2024-11-12 12:26:59', 350.00, NULL, NULL, NULL, '16:30:00', '00:00:23'),
(24, 'aria_walker', 'Spa Treatments', '2024-11-12', 'Confirmed', '2024-11-12 12:26:59', 700.00, 2, 'Emily', '09182318573', '17:30:00', '00:00:24'),
(25, 'stella_mitchell', 'Manicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 250.00, NULL, NULL, NULL, '18:30:00', '00:00:25'),
(26, 'nora_ward', 'Pedicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 300.00, NULL, NULL, NULL, '19:30:00', '00:00:26'),
(27, 'lucy_morgan', 'Gel Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 400.00, NULL, NULL, NULL, '20:30:00', '00:00:27'),
(28, 'mia_reed', 'Acrylic Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 500.00, NULL, NULL, NULL, '21:30:00', '00:00:28'),
(29, 'hannah_foster', 'Nail Art', '2024-11-12', 'pending', '2024-11-12 12:26:59', 350.00, NULL, NULL, NULL, '22:30:00', '00:00:29'),
(30, 'ruby_sanders', 'Spa Treatments', '2024-11-12', 'pending', '2024-11-12 12:26:59', 700.00, NULL, NULL, NULL, '23:30:00', '00:00:30'),
(31, 'lila_kelly', 'Manicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 250.00, NULL, NULL, NULL, '08:30:00', '00:00:31'),
(32, 'lyla_smith', 'Pedicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 300.00, NULL, NULL, NULL, '09:30:00', '00:00:32'),
(33, 'hazel_diaz', 'Gel Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 400.00, NULL, NULL, NULL, '10:30:00', '00:00:33'),
(34, 'molly_garcia', 'Acrylic Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 500.00, NULL, NULL, NULL, '11:30:00', '00:00:34'),
(35, 'ivy_woods', 'Nail Art', '2024-11-12', 'pending', '2024-11-12 12:26:59', 350.00, NULL, NULL, NULL, '12:30:00', '00:00:35'),
(36, 'luna_perez', 'Spa Treatments', '2024-11-12', 'pending', '2024-11-12 12:26:59', 700.00, NULL, NULL, NULL, '13:30:00', '00:00:36'),
(37, 'elsa_rivera', 'Manicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 250.00, NULL, NULL, NULL, '14:30:00', '00:00:37'),
(38, 'ivy_ross', 'Pedicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 300.00, NULL, NULL, NULL, '15:30:00', '00:00:38'),
(39, 'zoey_ellis', 'Gel Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 400.00, NULL, NULL, NULL, '16:30:00', '00:00:39'),
(40, 'lily_barnes', 'Acrylic Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 500.00, NULL, NULL, NULL, '17:30:00', '00:00:40'),
(41, 'layla_ortiz', 'Nail Art', '2024-11-12', 'pending', '2024-11-12 12:26:59', 350.00, NULL, NULL, NULL, '18:30:00', '00:00:41'),
(42, 'lucas_scott', 'Spa Treatments', '2024-11-12', 'Confirmed', '2024-11-12 12:26:59', 700.00, NULL, 'Sophia', '09753815382', '19:30:00', '00:00:42'),
(43, 'emma_gray', 'Manicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 250.00, NULL, NULL, NULL, '20:30:00', '00:00:43'),
(44, 'aria_fisher', 'Pedicure', '2024-11-12', 'pending', '2024-11-12 12:26:59', 300.00, NULL, NULL, NULL, '21:30:00', '00:00:44'),
(45, 'bella_smith', 'Gel Nails', '2024-11-12', 'Confirmed', '2024-11-12 12:26:59', 400.00, 4, 'Olivia', '09653847263', '22:30:00', '00:00:45'),
(46, 'mila_evans', 'Acrylic Nails', '2024-11-12', 'pending', '2024-11-12 12:26:59', 500.00, NULL, NULL, NULL, '23:30:00', '00:00:46'),
(47, 'ella_morris', 'Nail Art', '2024-11-12', 'pending', '2024-11-12 12:26:59', 350.00, NULL, NULL, NULL, '08:00:00', '00:00:47'),
(48, 'ava_fowler', 'Spa Treatments', '2024-11-12', 'pending', '2024-11-12 12:26:59', 700.00, NULL, NULL, NULL, '09:00:00', '00:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `username`, `email`, `message`, `created_at`) VALUES
(1, 'jane_doe', 'jane.doe@email.com', 'Absolutely loved the manicure! The staff was friendly and attentive. Will come back soon!', '2024-11-01 06:32:10'),
(2, 'sarah_lee', 'sarah.lee@email.com', 'The pedicure service was relaxing, and my feet feel amazing. Great ambiance too!', '2024-11-02 02:15:45'),
(3, 'amy_smith', 'amy.smith@email.com', 'Tried the Gel Nails, and they look stunning! The color options were impressive.', '2024-11-03 04:50:20'),
(4, 'emily_jones', 'emily.jones@email.com', 'First time trying acrylic nails. They did an amazing job, very professional!', '2024-11-04 08:05:30'),
(5, 'lucy_brown', 'lucy.brown@email.com', 'Nail Art was on point! The design turned out exactly as I imagined.', '2024-11-05 01:27:50'),
(6, 'olivia_clark', 'olivia.clark@email.com', 'Spa treatment was so refreshing. Loved the relaxation and care taken.', '2024-11-06 07:35:15'),
(7, 'sophia_morris', 'sophia.morris@email.com', 'My manicure and pedicure combo was perfect! Great service and friendly staff.', '2024-11-07 03:20:45'),
(8, 'mia_hall', 'mia.hall@email.com', 'Gel Nails looked fantastic and lasted long without chipping. Worth every penny!', '2024-11-08 05:45:20'),
(9, 'ava_thompson', 'ava.thompson@email.com', 'Very pleased with my acrylic nails. The shape and finish were exactly what I wanted!', '2024-11-09 09:30:55'),
(10, 'isabella_turner', 'isabella.turner@email.com', 'The spa treatment was rejuvenating, and the ambiance was calming. Highly recommend!', '2024-11-10 06:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `availability` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `availability`, `created_at`, `image_path`) VALUES
(4, 'Manicure', 'Our luxurious manicures pamper and perfect your nails.', 250.00, 0, '2024-11-12 10:30:56', 'Manicure.jpg'),
(6, 'Pedicure', 'Relax and rejuvenate with our soothing pedicures.', 300.00, 0, '2024-11-12 10:32:29', 'Pedicure.png'),
(7, 'Gel Nails', 'Shine bright like a diamond!\r\nLong-lasting, high-shine gel nails.', 400.00, 0, '2024-11-12 10:39:45', 'Gel Nails.jpg'),
(8, 'Acrylic Nails', 'Strong, stunning, and customized.\r\nCreate your dream nails with acrylics.', 500.00, 0, '2024-11-12 10:40:30', 'Acrylic Nails.png'),
(9, 'Nail Art', 'Dazzle and delight.\r\nUnique nail designs for every style.', 350.00, 0, '2024-11-12 10:41:24', 'Nail Art.png'),
(10, 'Spa Treatments', 'Rejuvenate your senses. Indulge in our luxurious spa treatments.', 700.00, 0, '2024-11-12 10:41:56', 'Spa Treatments.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`) VALUES
(1, 'jane_doe', 'jane.doe@email.com', '5f4dcc3b5aa765d61d8327deb882cf99'),
(2, 'sarah_lee', 'sarah.lee@email.com', 'd8578edf8458ce06fbc5bb76a58c5ca4'),
(3, 'amy_smith', 'amy.smith@email.com', '25d55ad283aa400af464c76d713c07ad'),
(4, 'emily_jones', 'emily.jones@email.com', '098f6bcd4621d373cade4e832627b4f6'),
(5, 'lucy_brown', 'lucy.brown@email.com', '6cb75f652a9b52798eb6cf2201057c73'),
(6, 'olivia_clark', 'olivia.clark@email.com', 'bcbef826f8dba30b7f2c46f769c9777b'),
(7, 'sophia_morris', 'sophia.morris@email.com', '098f6bcd4621d373cade4e832627b4f6'),
(8, 'mia_hall', 'mia.hall@email.com', '5f4dcc3b5aa765d61d8327deb882cf99'),
(9, 'ava_thompson', 'ava.thompson@email.com', '25d55ad283aa400af464c76d713c07ad'),
(10, 'isabella_turner', 'isabella.turner@email.com', 'd8578edf8458ce06fbc5bb76a58c5ca4'),
(11, 'chloe_white', 'chloe.white@email.com', '6cb75f652a9b52798eb6cf2201057c73'),
(12, 'ella_black', 'ella.black@email.com', '25d55ad283aa400af464c76d713c07ad'),
(13, 'grace_green', 'grace.green@email.com', '098f6bcd4621d373cade4e832627b4f6'),
(14, 'lily_brown', 'lily.brown@email.com', '5f4dcc3b5aa765d61d8327deb882cf99'),
(15, 'emma_moore', 'emma.moore@email.com', 'bcbef826f8dba30b7f2c46f769c9777b'),
(16, 'ava_baker', 'ava.baker@email.com', 'd8578edf8458ce06fbc5bb76a58c5ca4'),
(17, 'zoe_smith', 'zoe.smith@email.com', '6cb75f652a9b52798eb6cf2201057c73'),
(18, 'mia_turner', 'mia.turner@email.com', '5f4dcc3b5aa765d61d8327deb882cf99'),
(19, 'ella_grant', 'ella.grant@email.com', '25d55ad283aa400af464c76d713c07ad'),
(20, 'luna_wright', 'luna.wright@email.com', '098f6bcd4621d373cade4e832627b4f6'),
(21, 'harper_young', 'harper.young@email.com', '6cb75f652a9b52798eb6cf2201057c73'),
(22, 'zoe_carter', 'zoe.carter@email.com', '25d55ad283aa400af464c76d713c07ad'),
(23, 'aurora_adams', 'aurora.adams@email.com', 'd8578edf8458ce06fbc5bb76a58c5ca4'),
(24, 'aria_walker', 'aria.walker@email.com', '098f6bcd4621d373cade4e832627b4f6'),
(25, 'stella_mitchell', 'stella.mitchell@email.com', '5f4dcc3b5aa765d61d8327deb882cf99'),
(26, 'nora_ward', 'nora.ward@email.com', 'bcbef826f8dba30b7f2c46f769c9777b'),
(27, 'lucy_morgan', 'lucy.morgan@email.com', '6cb75f652a9b52798eb6cf2201057c73'),
(28, 'mia_reed', 'mia.reed@email.com', 'd8578edf8458ce06fbc5bb76a58c5ca4'),
(29, 'hannah_foster', 'hannah.foster@email.com', '098f6bcd4621d373cade4e832627b4f6'),
(30, 'ruby_sanders', 'ruby.sanders@email.com', '25d55ad283aa400af464c76d713c07ad'),
(31, 'lila_kelly', 'lila.kelly@email.com', '5f4dcc3b5aa765d61d8327deb882cf99'),
(32, 'lyla_smith', 'lyla.smith@email.com', '6cb75f652a9b52798eb6cf2201057c73'),
(33, 'hazel_diaz', 'hazel.diaz@email.com', '098f6bcd4621d373cade4e832627b4f6'),
(34, 'molly_garcia', 'molly.garcia@email.com', 'd8578edf8458ce06fbc5bb76a58c5ca4'),
(35, 'ivy_woods', 'ivy.woods@email.com', 'bcbef826f8dba30b7f2c46f769c9777b'),
(36, 'luna_perez', 'luna.perez@email.com', '25d55ad283aa400af464c76d713c07ad'),
(37, 'elsa_rivera', 'elsa.rivera@email.com', '5f4dcc3b5aa765d61d8327deb882cf99'),
(38, 'ivy_ross', 'ivy.ross@email.com', '098f6bcd4621d373cade4e832627b4f6'),
(39, 'zoey_ellis', 'zoey.ellis@email.com', '6cb75f652a9b52798eb6cf2201057c73'),
(40, 'lily_barnes', 'lily.barnes@email.com', 'd8578edf8458ce06fbc5bb76a58c5ca4'),
(41, 'layla_ortiz', 'layla.ortiz@email.com', 'bcbef826f8dba30b7f2c46f769c9777b'),
(42, 'lucas_scott', 'lucas.scott@email.com', '25d55ad283aa400af464c76d713c07ad'),
(43, 'emma_gray', 'emma.gray@email.com', '098f6bcd4621d373cade4e832627b4f6'),
(44, 'aria_fisher', 'aria.fisher@email.com', '5f4dcc3b5aa765d61d8327deb882cf99'),
(45, 'riley_gonzalez', 'riley.gonzalez@email.com', '6cb75f652a9b52798eb6cf2201057c73'),
(46, 'camila_murphy', 'camila.murphy@email.com', 'd8578edf8458ce06fbc5bb76a58c5ca4'),
(47, 'bella_king', 'bella.king@email.com', '25d55ad283aa400af464c76d713c07ad'),
(48, 'mila_evans', 'mila.evans@email.com', '098f6bcd4621d373cade4e832627b4f6'),
(49, 'ella_morris', 'ella.morris@email.com', 'bcbef826f8dba30b7f2c46f769c9777b'),
(50, 'ava_fowler', 'ava.fowler@email.com', '5f4dcc3b5aa765d61d8327deb882cf99');

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `worker_id` int(11) NOT NULL,
  `worker_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `worker_contact` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`worker_id`, `worker_name`, `last_name`, `username`, `password`, `created_at`, `worker_contact`) VALUES
(1, 'Jane', 'Smith', 'jane_smith', '$2y$10$P4aWqv2VpTsNWrkNkXYfA.os9p4u.5zWRhi0fm32UO.4CatGaETMm', '2024-11-12 14:27:47', '09171234567'),
(2, 'Emily', 'Johnson', 'emily_johnson', '$2y$10$WbUxy.Gcu82NsR1jLaAc9OLGoaCRQXLazrCUsaRrqMCQCxUuLsTzm', '2024-11-12 14:29:24', '09182318573'),
(3, 'Sophia', 'Brown', 'Sophia_Brown', '$2y$10$G2xSuQlMi1BCasCsMPHC2uRhT21h7v2onYGfxFslY2sdkCMusNGha', '2024-11-12 16:04:16', '09753815382'),
(4, 'Olivia', 'Garcia', 'Olivia_Garcia', '$2y$10$wp1VGq2JVDlICJzCklDG/OKyJ7U3IKWU1Uz26AG0x6FAbSo1fSYUO', '2024-11-12 16:07:44', '09653847263');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `worker_id` (`worker_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `username_2` (`username`,`email`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`worker_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `first_name` (`worker_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `worker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`worker_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
