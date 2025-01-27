-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 20, 2025 at 10:25 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cis`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `deleted_at`) VALUES
(1, 'Perangkat Rumah Tangga', NULL),
(6, 'Elektronik', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(25) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(45) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `cogs_method` enum('FIFO','STANDARD','AVERAGE') DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `status_active` tinyint NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `address`, `phone_number`, `email`, `status_active`, `deleted_at`) VALUES
(1, 'Valerin', 'jalan tenggilis mejoyo blok am 12', '082253749916', 'valerin@gmail.com', 1, NULL),
(2, 'Ievana', 'Jl. RUngkut Mejoyo Utara Blok L1', '081254896327', 'ievana@gmail.com', 1, NULL),
(3, 'asdasd', 'asdasdasd', '1293019230', 'asdasd@gmail.com', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detailkonfigurasi`
--

CREATE TABLE `detailkonfigurasi` (
  `id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `types` enum('mandatory','optional') NOT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `statusActive` tinyint NOT NULL,
  `konfigurasi_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `detailkonfigurasi`
--

INSERT INTO `detailkonfigurasi` (`id`, `name`, `value`, `types`, `desc`, `statusActive`, `konfigurasi_id`) VALUES
(1, 'S-Cash', NULL, 'mandatory', NULL, 1, 3),
(2, 'S-Transfer Bank', NULL, 'optional', NULL, 1, 3),
(3, 'S-Credit Card', NULL, 'optional', NULL, 1, 3),
(4, 'Produk diambil pelanggan langsung', '0', 'mandatory', NULL, 1, 2),
(5, 'Produk dikirim dengan layanan pengiriman toko', '80000', 'optional', NULL, 1, 2),
(6, 'Diskon per produk', '10', 'optional', '', 1, 1),
(7, 'Diskon minimal pembelian', '15', 'optional', '', 1, 1),
(8, 'Diskon jumlah pembelian produk', '20', 'optional', '', 1, 1),
(9, 'Barang dikirim oleh pemasok', '50000', 'mandatory', NULL, 1, 4),
(10, 'Barang diambil ke pemasok', '0', 'optional', NULL, 1, 4),
(11, 'P-Cash', NULL, 'mandatory', NULL, 1, 5),
(12, 'P-Transfer Bank', NULL, 'optional', NULL, 1, 5),
(13, 'P-Credit Card', NULL, 'optional', NULL, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `employes`
--

CREATE TABLE `employes` (
  `id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `phone_number` varchar(45) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status_active` tinyint NOT NULL DEFAULT '1',
  `users_id` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `employes`
--

INSERT INTO `employes` (`id`, `name`, `phone_number`, `address`, `status_active`, `users_id`, `deleted_at`) VALUES
(2, 'Monica', '082253749916', 'Jl. Ketapang-Sukadana', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi`
--

CREATE TABLE `konfigurasi` (
  `id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `types` enum('mandatory','optional') NOT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `statusActive` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `konfigurasi`
--

INSERT INTO `konfigurasi` (`id`, `name`, `types`, `desc`, `statusActive`) VALUES
(1, 'Discount', 'optional', '', 1),
(2, 'Sales Shipping', 'mandatory', NULL, 1),
(3, 'S-Payment Methods', 'mandatory', NULL, 1),
(4, 'Purchase Shipping', 'mandatory', NULL, 1),
(5, 'P-Payment Methods', 'mandatory', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `types` enum('mandatory','optional') NOT NULL,
  `statusActive` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `deleted_at`) VALUES
(1, 'Cash', NULL),
(2, 'Transfer Bank', NULL),
(3, 'Credit Card', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pos_session`
--

CREATE TABLE `pos_session` (
  `id` int NOT NULL,
  `Date` date DEFAULT NULL,
  `cash_in` double DEFAULT NULL,
  `cash_out` double DEFAULT NULL,
  `session_status` enum('close','open') DEFAULT NULL,
  `total_income` double DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `users_id` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `pos_session`
--

INSERT INTO `pos_session` (`id`, `Date`, `cash_in`, `cash_out`, `session_status`, `total_income`, `desc`, `users_id`, `deleted_at`, `updated_at`, `created_at`) VALUES
(1, '2025-01-18', 0, 0, 'close', 0, NULL, 1, NULL, '2025-01-20 00:02:33', '2025-01-17 19:22:50'),
(2, '2025-01-20', 18060980, 0, 'open', 10560000, NULL, 1, NULL, '2025-01-20 00:03:10', '2025-01-19 19:28:07');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` longtext,
  `price` double NOT NULL,
  `cost` double NOT NULL,
  `stock` int NOT NULL,
  `cogs_methods` enum('average','fifo') NOT NULL,
  `minimum_stock` int NOT NULL,
  `maksimum_retur` int NOT NULL,
  `status_active` tinyint NOT NULL DEFAULT '1',
  `categories_id` int NOT NULL,
  `product_image_id` int DEFAULT NULL,
  `suppliers_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `desc`, `price`, `cost`, `stock`, `cogs_methods`, `minimum_stock`, `maksimum_retur`, `status_active`, `categories_id`, `product_image_id`, `suppliers_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'kompor listrik', 'kompor yang menggunakan listrik', 560000, 500000, -1, 'fifo', 5, 5, 1, 1, 20, 1, NULL, '2024-12-17 01:52:10', NULL),
(5, 'Springbed abc', 'Kasur yang empuk enak', 2500000, 2000000, 15, 'fifo', 11, 1, 1, 1, 21, 1, '2024-12-02 09:11:55', '2024-12-17 01:52:23', NULL),
(16, 'Mesin Cuci', 'Mesin ini memiliki daya watt yang rendah sehingga bagus digunakan untuk menghemat listrik dirumah anda!', 2250000, 1900000, 20, 'fifo', 5, 1, 1, 6, 22, 2, '2024-12-17 03:21:52', '2024-12-17 03:22:01', NULL),
(18, 'test', 'test', 20000, 20, 1000, 'fifo', 10, 5, 1, 1, 20, 1, '2025-01-17 06:33:08', '2025-01-17 06:33:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_fifo`
--

CREATE TABLE `product_fifo` (
  `id` int NOT NULL,
  `purcahse_date` date NOT NULL,
  `price` int NOT NULL,
  `stock` int NOT NULL,
  `product_id` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `product_has_warehouse`
--

CREATE TABLE `product_has_warehouse` (
  `product_id` int NOT NULL,
  `warehouse_id` int NOT NULL,
  `stock` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `product_has_warehouse`
--

INSERT INTO `product_has_warehouse` (`product_id`, `warehouse_id`, `stock`, `deleted_at`) VALUES
(16, 2, 20, NULL),
(18, 2, 1000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

CREATE TABLE `product_image` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `product_image`
--

INSERT INTO `product_image` (`id`, `name`, `deleted_at`) VALUES
(20, '1734425529_springbed.jpg', NULL),
(21, '1734425543_komporlistrik2.jpg', NULL),
(22, '1734430921_mesincuci.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_movings`
--

CREATE TABLE `product_movings` (
  `id` int NOT NULL,
  `move_stock` int NOT NULL,
  `product_id` int NOT NULL,
  `warehouse_id_in` int NOT NULL,
  `warehouse_id_out` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` int NOT NULL,
  `noNota` varchar(45) NOT NULL,
  `total_price` double NOT NULL,
  `purchase_date` datetime NOT NULL,
  `receive_date` datetime DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `payment_methods_id` int NOT NULL,
  `suppliers_id` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`id`, `noNota`, `total_price`, `purchase_date`, `receive_date`, `shipping_cost`, `payment_methods_id`, `suppliers_id`, `deleted_at`) VALUES
(2, 'PUR0001', 6420000, '2024-12-17 00:00:00', '2024-12-18 00:00:00', 10000, 2, 1, NULL),
(3, 'PUR0003', 2850000, '2024-12-18 00:00:00', '2024-12-21 00:00:00', 50000, 1, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_detail`
--

CREATE TABLE `purchase_detail` (
  `product_id` int NOT NULL,
  `purchase_id` int NOT NULL,
  `subtotal_price` double NOT NULL,
  `quantity` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `purchase_detail`
--

INSERT INTO `purchase_detail` (`product_id`, `purchase_id`, `subtotal_price`, `quantity`, `deleted_at`) VALUES
(1, 2, 3920000, 7, NULL),
(1, 3, 2800000, 5, NULL),
(5, 2, 2500000, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `retur`
--

CREATE TABLE `retur` (
  `id` int NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `type` enum('penjualan','pembelian') NOT NULL,
  `quantity` varchar(45) NOT NULL,
  `refund_amount` double NOT NULL,
  `retur_desc` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `product_id` int NOT NULL,
  `customers_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `retur`
--

INSERT INTO `retur` (`id`, `invoice_number`, `type`, `quantity`, `refund_amount`, `retur_desc`, `status`, `product_id`, `customers_id`, `created_at`, `updated_at`) VALUES
(5, 'INV0068', 'penjualan', '1', 560000, 'barangnya rusak 1', 'Return initiated', 1, 1, '2024-12-28 09:59:52', '2025-01-07 07:43:22'),
(6, 'INV0001', 'penjualan', '2', 1120000, 'barangnya rusak 2', 'Return initiated', 1, 1, '2024-12-28 10:00:17', '2024-12-28 10:11:12'),
(14, 'PUR0001', 'pembelian', '5', 19600000, 'tidak nyala kompornya 5 unit', 'Return completed', 1, NULL, '2024-12-29 12:35:07', '2024-12-29 12:36:23');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `deleted_at`) VALUES
(1, 'admin', NULL),
(2, 'Owner', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles_has_menu`
--

CREATE TABLE `roles_has_menu` (
  `roles_id` int NOT NULL,
  `menu_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `noNota` varchar(45) NOT NULL,
  `total_price` double NOT NULL,
  `date` datetime NOT NULL,
  `shipped_date` datetime DEFAULT NULL,
  `employes_id` int NOT NULL,
  `payment_methods_id` int NOT NULL,
  `customers_id` int NOT NULL,
  `shipping_cost` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `noNota`, `total_price`, `date`, `shipped_date`, `employes_id`, `payment_methods_id`, `customers_id`, `shipping_cost`, `discount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(61, 'INV0001', 2773000, '2024-12-17 00:00:00', NULL, 2, 1, 1, 2000, 25000, '2024-12-16 08:24:10', '2024-12-16 08:24:10', NULL),
(68, 'INV0068', 6498000, '2024-12-18 00:00:00', NULL, 2, 3, 1, 50000, 1612000, '2024-12-17 03:16:47', '2024-12-17 03:16:47', NULL),
(69, 'INV0069', 560000, '2025-01-07 00:00:00', NULL, 2, 2, 2, 0, 0, '2025-01-06 06:34:24', '2025-01-06 06:34:24', NULL),
(70, 'INV0070', 2500000, '2025-01-20 04:36:36', NULL, 2, 1, 1, 0, 0, '2025-01-19 21:36:36', '2025-01-19 21:36:36', NULL),
(71, 'INV0071', 2500000, '2025-01-20 04:39:37', NULL, 2, 1, 1, 0, 0, '2025-01-19 21:39:37', '2025-01-19 21:39:37', NULL),
(72, 'INV0072', 2500980, '2025-01-20 06:59:24', NULL, 2, 1, 1, 1000, 20, '2025-01-19 23:59:24', '2025-01-19 23:59:24', NULL),
(73, 'INV0073', 10560000, '2025-01-20 07:03:10', NULL, 2, 1, 1, 1000, 1000, '2025-01-20 00:03:10', '2025-01-20 00:03:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_detail`
--

CREATE TABLE `sales_detail` (
  `product_id` int NOT NULL,
  `sales_id` int NOT NULL,
  `total_quantity` int DEFAULT NULL,
  `total_price` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `sales_detail`
--

INSERT INTO `sales_detail` (`product_id`, `sales_id`, `total_quantity`, `total_price`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 61, 5, 2800000, '2024-12-16 08:24:10', '2024-12-16 08:24:10', NULL),
(1, 68, 1, 560000, '2024-12-17 03:16:47', '2024-12-17 03:16:47', NULL),
(1, 69, 1, 560000, '2025-01-06 06:34:24', '2025-01-06 06:34:24', NULL),
(1, 73, 1, 560000, '2025-01-20 00:03:10', '2025-01-20 00:03:10', NULL),
(5, 68, 3, 7500000, '2024-12-17 03:16:47', '2024-12-17 03:16:47', NULL),
(5, 70, 1, 2500000, '2025-01-19 21:36:36', '2025-01-19 21:36:36', NULL),
(5, 71, 1, 2500000, '2025-01-19 21:39:37', '2025-01-19 21:39:37', NULL),
(5, 72, 1, 2500000, '2025-01-19 23:59:24', '2025-01-19 23:59:24', NULL),
(5, 73, 4, 10000000, '2025-01-20 00:03:10', '2025-01-20 00:03:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `submenu`
--

CREATE TABLE `submenu` (
  `id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `types` enum('mandatory','optional') NOT NULL,
  `statusActive` tinyint NOT NULL,
  `menu_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `company_name` varchar(45) NOT NULL,
  `phone_number` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `address` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `company_name`, `phone_number`, `email`, `address`, `deleted_at`) VALUES
(1, 'PT. Bersahaja', '082253749916', 'andreas@gmail.com', 'Jl ketapang', NULL),
(2, 'PT. Maju Mundu', '082253749916', 'maju@gmail.com', 'jalan tenggilis mejoyo blok am 15', NULL),
(7, 'PT. Maju Mundu', '082253749916', 'maju@gmail.com', 'jalan tenggilis mejoyo blok am 15', '2024-12-03 09:24:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `status_active` tinyint NOT NULL,
  `roles_id` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `status_active`, `roles_id`, `deleted_at`, `remember_token`) VALUES
(1, 'andreas', 'admin@gmail.com', '$2y$12$mviGdKzF27OnvK59LoMkbOGgDiKM3eXbWtmTOAnnkI7x3Vs0jIDN6', 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `address` longtext NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`id`, `name`, `address`, `deleted_at`) VALUES
(1, 'Gudang Y - Surabaya Timur', 'Jl Tenggilis Mejoyo Blok AM-12', NULL),
(2, 'Gudang X - Surabaya Barat', 'Jl Mayjend Blok 11A', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detailkonfigurasi`
--
ALTER TABLE `detailkonfigurasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detailKonfigurasi_konfigurasi1_idx` (`konfigurasi_id`);

--
-- Indexes for table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_employes_users1_idx` (`users_id`);

--
-- Indexes for table `konfigurasi`
--
ALTER TABLE `konfigurasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_session`
--
ALTER TABLE `pos_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_PoS session_users1_idx` (`users_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_categories1_idx` (`categories_id`),
  ADD KEY `fk_product_product_image1_idx` (`product_image_id`),
  ADD KEY `fk_product_suppliers1_idx` (`suppliers_id`);

--
-- Indexes for table `product_fifo`
--
ALTER TABLE `product_fifo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_fifo_product1_idx` (`product_id`);

--
-- Indexes for table `product_has_warehouse`
--
ALTER TABLE `product_has_warehouse`
  ADD PRIMARY KEY (`product_id`,`warehouse_id`),
  ADD KEY `fk_product_has_warehouse_warehouse1_idx` (`warehouse_id`),
  ADD KEY `fk_product_has_warehouse_product_idx` (`product_id`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_movings`
--
ALTER TABLE `product_movings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_movings_product1_idx` (`product_id`),
  ADD KEY `fk_product_movings_warehouse1_idx` (`warehouse_id_in`),
  ADD KEY `fk_product_movings_warehouse2_idx` (`warehouse_id_out`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_purchase_payment_methods1_idx` (`payment_methods_id`),
  ADD KEY `fk_purchase_suppliers1_idx` (`suppliers_id`);

--
-- Indexes for table `purchase_detail`
--
ALTER TABLE `purchase_detail`
  ADD PRIMARY KEY (`product_id`,`purchase_id`),
  ADD KEY `fk_product_has_purchase_purchase1_idx` (`purchase_id`),
  ADD KEY `fk_product_has_purchase_product1_idx` (`product_id`);

--
-- Indexes for table `retur`
--
ALTER TABLE `retur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Retur_product1_idx` (`product_id`),
  ADD KEY `fk_Retur_customers1_idx` (`customers_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_has_menu`
--
ALTER TABLE `roles_has_menu`
  ADD PRIMARY KEY (`roles_id`,`menu_id`),
  ADD KEY `fk_roles_has_menu_menu1_idx` (`menu_id`),
  ADD KEY `fk_roles_has_menu_roles1_idx` (`roles_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sales_employes1_idx` (`employes_id`),
  ADD KEY `fk_sales_payment_methods1_idx` (`payment_methods_id`),
  ADD KEY `fk_sales_customers1_idx` (`customers_id`);

--
-- Indexes for table `sales_detail`
--
ALTER TABLE `sales_detail`
  ADD PRIMARY KEY (`product_id`,`sales_id`),
  ADD KEY `fk_product_has_sales_sales1_idx` (`sales_id`),
  ADD KEY `fk_product_has_sales_product1_idx` (`product_id`);

--
-- Indexes for table `submenu`
--
ALTER TABLE `submenu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subMenu_menu1_idx` (`menu_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_roles1_idx` (`roles_id`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detailkonfigurasi`
--
ALTER TABLE `detailkonfigurasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `employes`
--
ALTER TABLE `employes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `konfigurasi`
--
ALTER TABLE `konfigurasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pos_session`
--
ALTER TABLE `pos_session`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `product_fifo`
--
ALTER TABLE `product_fifo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_movings`
--
ALTER TABLE `product_movings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `retur`
--
ALTER TABLE `retur`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `submenu`
--
ALTER TABLE `submenu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detailkonfigurasi`
--
ALTER TABLE `detailkonfigurasi`
  ADD CONSTRAINT `fk_detailKonfigurasi_konfigurasi1` FOREIGN KEY (`konfigurasi_id`) REFERENCES `konfigurasi` (`id`);

--
-- Constraints for table `employes`
--
ALTER TABLE `employes`
  ADD CONSTRAINT `fk_employes_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pos_session`
--
ALTER TABLE `pos_session`
  ADD CONSTRAINT `fk_PoS session_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_categories1` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_product_product_image1` FOREIGN KEY (`product_image_id`) REFERENCES `product_image` (`id`),
  ADD CONSTRAINT `fk_product_suppliers1` FOREIGN KEY (`suppliers_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `product_fifo`
--
ALTER TABLE `product_fifo`
  ADD CONSTRAINT `fk_product_fifo_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `product_has_warehouse`
--
ALTER TABLE `product_has_warehouse`
  ADD CONSTRAINT `fk_product_has_warehouse_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `fk_product_has_warehouse_warehouse1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`);

--
-- Constraints for table `product_movings`
--
ALTER TABLE `product_movings`
  ADD CONSTRAINT `fk_product_movings_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `fk_product_movings_warehouse1` FOREIGN KEY (`warehouse_id_in`) REFERENCES `warehouse` (`id`),
  ADD CONSTRAINT `fk_product_movings_warehouse2` FOREIGN KEY (`warehouse_id_out`) REFERENCES `warehouse` (`id`);

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `fk_purchase_payment_methods1` FOREIGN KEY (`payment_methods_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `fk_purchase_suppliers1` FOREIGN KEY (`suppliers_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `purchase_detail`
--
ALTER TABLE `purchase_detail`
  ADD CONSTRAINT `fk_product_has_purchase_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `fk_product_has_purchase_purchase1` FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`id`);

--
-- Constraints for table `retur`
--
ALTER TABLE `retur`
  ADD CONSTRAINT `fk_Retur_customers1_idx` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Retur_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `roles_has_menu`
--
ALTER TABLE `roles_has_menu`
  ADD CONSTRAINT `fk_roles_has_menu_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`),
  ADD CONSTRAINT `fk_roles_has_menu_roles1` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_customers1` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `fk_sales_employes1` FOREIGN KEY (`employes_id`) REFERENCES `employes` (`id`),
  ADD CONSTRAINT `fk_sales_payment_methods1` FOREIGN KEY (`payment_methods_id`) REFERENCES `payment_methods` (`id`);

--
-- Constraints for table `sales_detail`
--
ALTER TABLE `sales_detail`
  ADD CONSTRAINT `fk_product_has_sales_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `fk_product_has_sales_sales1` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`);

--
-- Constraints for table `submenu`
--
ALTER TABLE `submenu`
  ADD CONSTRAINT `fk_subMenu_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_roles1` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
