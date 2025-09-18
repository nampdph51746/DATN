/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `actors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `biography` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `birth_date` date DEFAULT NULL,
  `nationality` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `age_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `age_limits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID giới hạn độ tuổi',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên giới hạn độ tuổi (duy nhất)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả giới hạn độ tuổi',
  `min_age` int DEFAULT NULL COMMENT 'Độ tuổi tối thiểu',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `age_limits_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attribute_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attribute_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID giá trị thuộc tính',
  `attribute_id` bigint unsigned NOT NULL,
  `value` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Giá trị thuộc tính',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `attribute_values_attribute_id_value_unique` (`attribute_id`,`value`),
  CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attributes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID thuộc tính',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên thuộc tính (duy nhất)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `attributes_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID banner',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề banner',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL ảnh banner',
  `link_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL liên kết khi nhấp vào banner',
  `display_order` int DEFAULT NULL COMMENT 'Thứ tự hiển thị',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `start_date` timestamp NULL DEFAULT NULL COMMENT 'Ngày bắt đầu hiển thị',
  `end_date` timestamp NULL DEFAULT NULL COMMENT 'Ngày kết thúc hiển thị',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `booking_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_attempts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID của lần thử đặt vé',
  `user_id` bigint unsigned NOT NULL,
  `showtime_id` bigint unsigned NOT NULL,
  `seat_ids` json NOT NULL COMMENT 'Danh sách ID ghế đã đặt',
  `status` enum('reserved','timeout','cancelled','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reserved' COMMENT 'Trạng thái: reserved(đang giữ), timeout(hết hạn), cancelled(hủy), completed(hoàn thành)',
  `reserved_at` timestamp NOT NULL COMMENT 'Thời gian bắt đầu giữ ghế',
  `expired_at` timestamp NOT NULL COMMENT 'Thời gian hết hạn giữ ghế',
  `completed_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian hoàn thành thanh toán',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_attempts_showtime_id_foreign` (`showtime_id`),
  KEY `booking_attempts_user_id_status_index` (`user_id`,`status`),
  KEY `booking_attempts_user_id_reserved_at_index` (`user_id`,`reserved_at`),
  CONSTRAINT `booking_attempts_showtime_id_foreign` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `booking_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID mục trong đơn đặt vé',
  `booking_id` bigint unsigned NOT NULL,
  `product_variant_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL COMMENT 'Số lượng sản phẩm',
  `price_at_purchase` decimal(10,2) NOT NULL COMMENT 'Giá tại thời điểm mua',
  `product_status` enum('valid','used','cancelled','checked') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valid',
  `used_at` timestamp NULL DEFAULT NULL,
  `scanned_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `combo_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_items_booking_id_foreign` (`booking_id`),
  KEY `booking_items_product_variant_id_foreign` (`product_variant_id`),
  KEY `booking_items_combo_id_index` (`combo_id`),
  CONSTRAINT `booking_items_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  CONSTRAINT `booking_items_combo_id_foreign` FOREIGN KEY (`combo_id`) REFERENCES `combos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID đơn đặt vé',
  `user_id` bigint unsigned NOT NULL,
  `booking_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã đặt vé (duy nhất)',
  `total_amount_before_discount` decimal(12,2) NOT NULL COMMENT 'Tổng tiền trước giảm giá',
  `discount_amount` decimal(12,2) DEFAULT NULL COMMENT 'Số tiền giảm giá',
  `final_amount` decimal(12,2) NOT NULL COMMENT 'Tổng tiền cuối cùng',
  `promotion_id` bigint unsigned DEFAULT NULL,
  `payment_method_id` bigint unsigned DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Trạng thái đơn đặt vé',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú đơn hàng',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_promotion_id_foreign` (`promotion_id`),
  KEY `bookings_payment_method_id_foreign` (`payment_method_id`),
  CONSTRAINT `bookings_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  CONSTRAINT `bookings_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`),
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cinemas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cinemas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID rạp chiếu phim',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên rạp',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Địa chỉ rạp',
  `city_id` bigint unsigned DEFAULT NULL,
  `hotline` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số hotline',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email liên hệ',
  `map_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL bản đồ',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL ảnh rạp',
  `opening_hours` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giờ mở cửa',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả rạp',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái rạp',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cinemas_city_id_foreign` (`city_id`),
  CONSTRAINT `cinemas_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID thành phố',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên thành phố (duy nhất)',
  `country_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cities_name_unique` (`name`),
  KEY `cities_country_id_foreign` (`country_id`),
  CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `combo_package_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `combo_package_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID mục trong gói combo',
  `combo_id` bigint unsigned DEFAULT NULL,
  `combo_product_variant_id` bigint unsigned NOT NULL,
  `item_product_variant_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1' COMMENT 'Số lượng sản phẩm con trong combo',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  KEY `combo_package_items_combo_product_variant_id_foreign` (`combo_product_variant_id`),
  KEY `combo_package_items_item_product_variant_id_foreign` (`item_product_variant_id`),
  KEY `combo_package_items_combo_id_foreign` (`combo_id`),
  CONSTRAINT `combo_package_items_combo_id_foreign` FOREIGN KEY (`combo_id`) REFERENCES `combos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `combo_package_items_combo_product_variant_id_foreign` FOREIGN KEY (`combo_product_variant_id`) REFERENCES `product_variants` (`id`),
  CONSTRAINT `combo_package_items_item_product_variant_id_foreign` FOREIGN KEY (`item_product_variant_id`) REFERENCES `product_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `combos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `combos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Combo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `combo_product_variant_id` bigint unsigned NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stock_quantity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `combos_combo_product_variant_id_foreign` (`combo_product_variant_id`),
  CONSTRAINT `combos_combo_product_variant_id_foreign` FOREIGN KEY (`combo_product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID cấu hình',
  `config_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa cấu hình (duy nhất)',
  `config_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Giá trị cấu hình',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả cấu hình',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `configs_config_key_unique` (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID quốc gia',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên quốc gia (duy nhất)',
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã quốc gia (duy nhất)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `countries_name_unique` (`name`),
  UNIQUE KEY `countries_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_rank_promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_rank_promotions` (
  `customer_rank_id` bigint unsigned NOT NULL,
  `promotion_id` bigint unsigned NOT NULL,
  `discount_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giảm giá riêng cho từng liên kết',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả khuyến mãi cho xếp hạng',
  PRIMARY KEY (`customer_rank_id`,`promotion_id`),
  KEY `customer_rank_promotions_promotion_id_foreign` (`promotion_id`),
  CONSTRAINT `customer_rank_promotions_customer_rank_id_foreign` FOREIGN KEY (`customer_rank_id`) REFERENCES `customer_ranks` (`id`),
  CONSTRAINT `customer_rank_promotions_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_ranks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_ranks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID xếp hạng khách hàng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên xếp hạng (duy nhất)',
  `min_points_required` int DEFAULT NULL COMMENT 'Số điểm tối thiểu để đạt xếp hạng',
  `discount_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phần trăm giảm giá cho xếp hạng',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả xếp hạng',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_ranks_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `directors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `directors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `biography` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `birth_date` date DEFAULT NULL,
  `nationality` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genres` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID thể loại phim',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên thể loại (duy nhất)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả thể loại',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `genres_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `movie_actors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movie_actors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `movie_id` bigint unsigned NOT NULL,
  `actor_id` bigint unsigned NOT NULL,
  `character_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_main_character` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `movie_actor_unique` (`movie_id`,`actor_id`),
  KEY `movie_actors_actor_id_foreign` (`actor_id`),
  CONSTRAINT `movie_actors_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `actors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movie_actors_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `movie_directors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movie_directors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `movie_id` bigint unsigned NOT NULL,
  `director_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `movie_directors_movie_id_director_id_unique` (`movie_id`,`director_id`),
  KEY `movie_directors_director_id_foreign` (`director_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `movie_genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movie_genres` (
  `movie_id` bigint unsigned NOT NULL,
  `genre_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`movie_id`,`genre_id`),
  KEY `movie_genres_genre_id_foreign` (`genre_id`),
  CONSTRAINT `movie_genres_genre_id_foreign` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`),
  CONSTRAINT `movie_genres_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `movies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID phim',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên phim',
  `director_id` bigint unsigned DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL COMMENT 'Thời lượng phim (phút)',
  `release_date` date DEFAULT NULL COMMENT 'Ngày phát hành',
  `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc chiếu',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả phim',
  `poster_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL ảnh poster',
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trailer_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL trailer',
  `language` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ngôn ngữ phim',
  `country_id` bigint unsigned DEFAULT NULL,
  `age_limit_id` bigint unsigned DEFAULT NULL,
  `status` enum('showing','upcoming','ended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái phim',
  `average_rating` decimal(3,1) DEFAULT NULL COMMENT 'Điểm đánh giá trung bình (0-10)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  KEY `movies_country_id_foreign` (`country_id`),
  KEY `movies_age_limit_id_foreign` (`age_limit_id`),
  KEY `movies_director_id_foreign` (`director_id`),
  CONSTRAINT `movies_age_limit_id_foreign` FOREIGN KEY (`age_limit_id`) REFERENCES `age_limits` (`id`),
  CONSTRAINT `movies_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `movies_director_id_foreign` FOREIGN KEY (`director_id`) REFERENCES `directors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL COMMENT 'ID thông báo',
  `user_id` bigint unsigned NOT NULL,
  `entity_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên model entity liên quan (booking, movie, ...)',
  `entity_id` bigint unsigned DEFAULT NULL COMMENT 'ID của entity liên quan',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề thông báo',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung thông báo',
  `type` enum('booking','promotion','system') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại thông báo',
  `priority` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mức độ ưu tiên của thông báo (low, medium, high)',
  `old_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái cũ của entity (nếu có)',
  `new_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái mới của entity (nếu có)',
  `event_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Chi tiết sự kiện bổ sung (nếu có)',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Trạng thái đã đọc',
  `read_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian đọc thông báo',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email cần đặt lại mật khẩu',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token đặt lại mật khẩu',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID phương thức thanh toán',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên phương thức (duy nhất)',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã phương thức (duy nhất)',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `logo_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL logo phương thức',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_methods_name_unique` (`name`),
  UNIQUE KEY `payment_methods_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID thanh toán',
  `booking_id` bigint unsigned NOT NULL,
  `payment_method_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL COMMENT 'Số tiền thanh toán',
  `transaction_id_gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID giao dịch từ cổng thanh toán',
  `status` enum('pending','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Trạng thái thanh toán',
  `payment_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Chi tiết thanh toán (JSON)',
  `paid_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian thanh toán',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_id_gateway_unique` (`transaction_id_gateway`),
  KEY `payments_booking_id_foreign` (`booking_id`),
  KEY `payments_payment_method_id_foreign` (`payment_method_id`),
  CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  CONSTRAINT `payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `point_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `point_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `booking_id` bigint unsigned DEFAULT NULL,
  `points_change` int NOT NULL,
  `reason_type` enum('earned','spent','expired','adjusted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  KEY `point_histories_user_id_foreign` (`user_id`),
  KEY `point_histories_booking_id_foreign` (`booking_id`),
  CONSTRAINT `point_histories_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `point_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `point_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `point_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID lịch sử điểm',
  `user_id` bigint unsigned NOT NULL,
  `booking_id` bigint unsigned DEFAULT NULL,
  `points_change` int NOT NULL COMMENT 'Số điểm thay đổi (dương: kiếm được, âm: sử dụng)',
  `reason_type` enum('earned','spent','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Lý do thay đổi điểm',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả chi tiết',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  KEY `point_history_user_id_foreign` (`user_id`),
  KEY `point_history_booking_id_foreign` (`booking_id`),
  CONSTRAINT `point_history_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  CONSTRAINT `point_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `points` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID điểm tích lũy',
  `user_id` bigint unsigned NOT NULL,
  `total_points` int DEFAULT NULL COMMENT 'Tổng điểm hiện tại',
  `points_expiry_date` date DEFAULT NULL COMMENT 'Ngày hết hạn điểm',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  KEY `points_user_id_foreign` (`user_id`),
  CONSTRAINT `points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID danh mục sản phẩm',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên danh mục (duy nhất)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả danh mục',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_categories_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_variant_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variant_options` (
  `product_variant_id` bigint unsigned NOT NULL,
  `attribute_value_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_variant_id`,`attribute_value_id`),
  KEY `product_variant_options_attribute_value_id_foreign` (`attribute_value_id`),
  CONSTRAINT `product_variant_options_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`),
  CONSTRAINT `product_variant_options_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID biến thể sản phẩm',
  `product_id` bigint unsigned NOT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã SKU (duy nhất)',
  `price` decimal(10,2) NOT NULL COMMENT 'Giá bán của biến thể',
  `stock_quantity` int NOT NULL DEFAULT '0' COMMENT 'Số lượng tồn kho',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL ảnh riêng (ghi đè image_url của sản phẩm)',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái biến thể',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_foreign` (`product_id`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID sản phẩm',
  `category_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên sản phẩm',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả sản phẩm',
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã SKU (duy nhất)',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL ảnh chung (có thể bị ghi đè bởi biến thể)',
  `product_type` enum('food','drink','combo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại sản phẩm',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái sản phẩm',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promotions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID khuyến mãi',
  `rank_id` bigint unsigned DEFAULT NULL COMMENT 'ID hạng khách hàng',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên khuyến mãi',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã khuyến mãi (duy nhất)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả khuyến mãi',
  `discount_type` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại giảm giá',
  `discount_value` decimal(10,2) NOT NULL COMMENT 'Giá trị giảm giá',
  `max_discount_amount` decimal(10,2) DEFAULT NULL COMMENT 'Giá trị giảm tối đa',
  `min_booking_value` decimal(10,2) DEFAULT NULL COMMENT 'Giá trị đơn hàng tối thiểu',
  `start_date` timestamp NOT NULL COMMENT 'Ngày bắt đầu',
  `end_date` timestamp NOT NULL COMMENT 'Ngày kết thúc',
  `quantity` int DEFAULT NULL COMMENT 'Số lượng mã có thể sử dụng',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái khuyến mãi (active, inactive)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promotions_code_unique` (`code`),
  KEY `promotions_rank_id_foreign` (`rank_id`),
  CONSTRAINT `promotions_rank_id_foreign` FOREIGN KEY (`rank_id`) REFERENCES `customer_ranks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID đánh giá',
  `user_id` bigint unsigned NOT NULL,
  `movie_id` bigint unsigned NOT NULL,
  `rating_star` int NOT NULL COMMENT 'Số sao đánh giá (1-5)',
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Nội dung đánh giá',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Trạng thái đánh giá (pending, approved, rejected)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `admin_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú từ admin',
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian duyệt',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_seat_configurations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_seat_configurations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `seat_type_id` bigint unsigned NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_seat_configurations_room_id_foreign` (`room_id`),
  KEY `room_seat_configurations_seat_type_id_foreign` (`seat_type_id`),
  CONSTRAINT `room_seat_configurations_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_seat_configurations_seat_type_id_foreign` FOREIGN KEY (`seat_type_id`) REFERENCES `seat_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_seat_type_constraints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_seat_type_constraints` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_type_id` bigint unsigned NOT NULL,
  `seat_type_id` bigint unsigned NOT NULL,
  `min_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `max_percentage` decimal(5,2) NOT NULL DEFAULT '100.00',
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_seat_type_constraints_room_type_id_seat_type_id_unique` (`room_type_id`,`seat_type_id`),
  KEY `room_seat_type_constraints_seat_type_id_foreign` (`seat_type_id`),
  CONSTRAINT `room_seat_type_constraints_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_seat_type_constraints_seat_type_id_foreign` FOREIGN KEY (`seat_type_id`) REFERENCES `seat_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID loại phòng chiếu',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại phòng (duy nhất)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả loại phòng',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `base_price` decimal(10,2) NOT NULL DEFAULT '100000.00' COMMENT 'Giá vé cơ bản cho loại phòng này',
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID phòng chiếu',
  `cinema_id` bigint unsigned NOT NULL,
  `room_type_id` bigint unsigned DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên phòng',
  `capacity` int DEFAULT '0' COMMENT 'Sức chứa',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái phòng (active, maintenance)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_cinema_id_name_unique` (`cinema_id`,`name`),
  KEY `rooms_room_type_id_foreign` (`room_type_id`),
  CONSTRAINT `rooms_cinema_id_foreign` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`),
  CONSTRAINT `rooms_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `seat_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seat_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID loại ghế',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại ghế (duy nhất)',
  `price_modifier` decimal(10,2) DEFAULT NULL COMMENT 'Hệ số giá (thêm vào giá cơ bản)',
  `color_code` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã màu hiển thị ghế (hex)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả loại ghế',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seat_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `seats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID ghế',
  `room_id` bigint unsigned NOT NULL,
  `seat_type_id` bigint unsigned NOT NULL,
  `row_char` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hàng ghế (A, B, C,...)',
  `seat_number` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số ghế trong hàng',
  `status` enum('available','maintenance','booked','reserved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái ghế',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `seats_room_id_row_char_seat_number_unique` (`room_id`,`row_char`,`seat_number`),
  KEY `seats_seat_type_id_foreign` (`seat_type_id`),
  CONSTRAINT `seats_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  CONSTRAINT `seats_seat_type_id_foreign` FOREIGN KEY (`seat_type_id`) REFERENCES `seat_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sensitive_words`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sensitive_words` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `showtime_seat_states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `showtime_seat_states` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID trạng thái ghế trong suất chiếu',
  `showtime_id` bigint unsigned NOT NULL,
  `seat_id` bigint unsigned NOT NULL,
  `status` enum('available','reserved','booked') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Trạng thái ghế trong suất chiếu',
  `locked_until` timestamp NULL DEFAULT NULL COMMENT 'Thời gian khóa ghế (nếu có)',
  `locked_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `showtime_seat_states_showtime_id_seat_id_unique` (`showtime_id`,`seat_id`),
  KEY `showtime_seat_states_seat_id_foreign` (`seat_id`),
  KEY `showtime_seat_states_booking_id_foreign` (`booking_id`),
  CONSTRAINT `showtime_seat_states_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  CONSTRAINT `showtime_seat_states_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`),
  CONSTRAINT `showtime_seat_states_showtime_id_foreign` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `showtimes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `showtimes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID suất chiếu',
  `movie_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned NOT NULL,
  `start_time` timestamp NOT NULL COMMENT 'Thời gian bắt đầu',
  `end_time` timestamp NOT NULL COMMENT 'Thời gian kết thúc',
  `base_price` decimal(10,2) NOT NULL COMMENT 'Giá vé cơ bản',
  `status` enum('scheduled','ongoing','completed','cancelled','postponed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái suất chiếu',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  KEY `showtimes_movie_id_foreign` (`movie_id`),
  KEY `showtimes_room_id_foreign` (`room_id`),
  CONSTRAINT `showtimes_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`),
  CONSTRAINT `showtimes_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sliders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID slider',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề slider',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả slider',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL ảnh slider',
  `link_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL liên kết khi nhấp vào slider',
  `display_order` int DEFAULT NULL COMMENT 'Thứ tự hiển thị',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID vé',
  `booking_id` bigint unsigned NOT NULL,
  `showtime_id` bigint unsigned NOT NULL,
  `seat_id` bigint unsigned NOT NULL,
  `ticket_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã vé (duy nhất)',
  `price_at_purchase` decimal(10,2) NOT NULL COMMENT 'Giá vé tại thời điểm mua',
  `status` enum('valid','used','cancelled','checked') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `scanned_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tickets_showtime_id_seat_id_unique` (`showtime_id`,`seat_id`),
  UNIQUE KEY `tickets_ticket_code_unique` (`ticket_code`),
  KEY `tickets_booking_id_foreign` (`booking_id`),
  KEY `tickets_seat_id_foreign` (`seat_id`),
  CONSTRAINT `tickets_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  CONSTRAINT `tickets_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`),
  CONSTRAINT `tickets_showtime_id_foreign` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_booking_bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_booking_bans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID của lệnh cấm',
  `user_id` bigint unsigned NOT NULL,
  `failed_attempts` int NOT NULL COMMENT 'Số lần đặt vé thất bại liên tiếp',
  `banned_at` timestamp NOT NULL COMMENT 'Thời gian bắt đầu cấm',
  `banned_until` timestamp NOT NULL COMMENT 'Thời gian kết thúc cấm',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái cấm có đang hiệu lực',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Lý do cấm',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_booking_bans_user_id_is_active_index` (`user_id`,`is_active`),
  KEY `user_booking_bans_banned_until_index` (`banned_until`),
  CONSTRAINT `user_booking_bans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID người dùng',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ và tên',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email người dùng (duy nhất)',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mật khẩu đã mã hóa',
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại (duy nhất)',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Địa chỉ',
  `avatar_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL ảnh đại diện',
  `date_of_birth` date DEFAULT NULL COMMENT 'Ngày sinh',
  `status` enum('active','inactive','suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'Trạng thái tài khoản',
  `email_verified_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian xác thực email',
  `last_login_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian đăng nhập cuối',
  `customer_rank_id` bigint unsigned DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_number_unique` (`phone_number`),
  KEY `users_customer_rank_id_foreign` (`customer_rank_id`),
  CONSTRAINT `users_customer_rank_id_foreign` FOREIGN KEY (`customer_rank_id`) REFERENCES `customer_ranks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2025_05_29_124207_create_permission_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_05_29_124346_create_countries_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_05_29_124409_create_age_limits_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_05_29_124438_create_genres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_05_29_124509_create_customer_ranks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_05_29_124549_create_payment_methods_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_05_29_124632_create_product_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_05_29_124701_create_attributes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_05_29_124742_create_room_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_05_29_124813_create_seat_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_05_29_124858_create_configs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_05_29_124926_create_banners_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_05_29_124954_create_sliders_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_05_29_125022_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_05_29_125052_create_cities_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_05_29_125122_create_movies_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_05_29_125147_create_attribute_values_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_05_29_125222_create_promotions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_05_29_125253_create_cinemas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_05_29_125319_create_rooms_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_05_29_125345_create_seats_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_05_29_125412_create_products_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_05_29_125413_create_product_variants_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_05_29_125447_create_points_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_05_29_125513_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_05_29_125932_create_movie_genres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_05_29_130002_create_reviews_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_05_29_130033_create_showtimes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_05_29_130105_create_product_variant_options_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_05_29_130143_create_bookings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_05_29_130205_create_showtime_seat_states_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_05_29_130236_create_tickets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_05_29_130341_create_combo_package_items_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_05_29_130410_create_booking_items_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_05_29_130504_create_customer_rank_promotions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_05_29_130542_create_payments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_05_29_130616_create_notifications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_05_31_151814_create_point_histories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_05_31_170237_create_sessions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_06_03_042857_add_deleted_at_to_seat_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_06_04_110242_add_status_to_room_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2025_06_05_083343_add_discount_code_to_customer_rank_promotions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2025_06_05_150548_add_rank_id_to_promotions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2025_06_05_153450_add_deleted_at_to_cinemas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2025_06_05_164303_add_deleted_at_to_cities_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2025_06_06_042320_add_deleted_at_to_countries_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2025_06_10_163709_add_deleted_at_to_product_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2025_06_22_032424_add_deleted_at_to_promotions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2025_06_25_124412_add_image_url_to_movies_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2025_06_25_143157_rename_image_url_to_image_path_in_movies_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2025_06_26_135627_create_room_seat_configurations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2025_06_28_140512_add_locked_by_to_showtime_seat_states_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2025_07_17_093340_add_deleted_at_to_roles_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2025_05_29_130434_create_point_history_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2025_07_19_000001_add_base_price_to_room_types_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2025_07_19_000001_create_room_seat_type_constraints_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2025_07_19_000002_add_new_seat_types',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2025_07_19_000003_seed_room_seat_type_constraints',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2025_07_20_000001_create_combos_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2025_07_20_000002_add_combo_id_to_combo_package_items_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2025_08_01_232153_add_ticket_scan_fields_to_booking_items_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2025_08_01_233149_add_scan_fields_to_tickets_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2025_08_20_000001_create_booking_attempts_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2025_08_20_000002_create_user_booking_bans_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2025_08_25_133635_add_product_status_and_combo_id_to_booking_items_table',11);
