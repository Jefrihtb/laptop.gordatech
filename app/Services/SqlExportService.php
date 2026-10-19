<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SqlExportService
{
    /**
     * Generate a complete MySQL / MariaDB compatible SQL dump of all tables and data
     * for easy import into phpMyAdmin (XAMPP or cPanel Shared Hosting).
     */
    public function generateMysqlDump(): string
    {
        $sql = "-- =============================================================================\n";
        $sql .= "-- CYBER GAMING STORE - MYSQL / MARIADB DATABASE DUMP (SHARED HOSTING READY)\n";
        $sql .= '-- Generated at: '.now()->toDateTimeString()."\n";
        $sql .= "-- WhatsApp Store: 0818880497\n";
        $sql .= "-- =============================================================================\n\n";
        $sql .= "SET NAMES utf8mb4;\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        $schemaDefinitions = $this->mysqlTableSchemas();

        foreach ($schemaDefinitions as $tableName => $createStatement) {
            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "-- Struktur dari tabel `{$tableName}`\n";
            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createStatement."\n\n";

            $rows = DB::table($tableName)->get();
            if ($rows->isNotEmpty()) {
                $sql .= "-- Dumping data untuk tabel `{$tableName}`\n";
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $columns = array_map(fn (string $col): string => "`{$col}`", array_keys($rowArray));
                    $values = array_map(function (mixed $val): string {
                        if ($val === null) {
                            return 'NULL';
                        }
                        if (is_bool($val)) {
                            return $val ? '1' : '0';
                        }
                        if (is_int($val) || is_float($val)) {
                            return (string) $val;
                        }

                        $escaped = str_replace(
                            ['\\', "\0", "\n", "\r", "'", '"', "\x1a"],
                            ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'],
                            (string) $val
                        );

                        return "'{$escaped}'";
                    }, array_values($rowArray));

                    $sql .= "INSERT INTO `{$tableName}` (".implode(', ', $columns).') VALUES ('.implode(', ', $values).");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

        return $sql;
    }

    public function saveDumpToFile(?string $path = null): string
    {
        $targetPath = $path ?: database_path('cyber_gaming_store_mysql.sql');
        $content = $this->generateMysqlDump();
        file_put_contents($targetPath, $content);

        return $targetPath;
    }

    /**
     * @return array<string, string>
     */
    private function mysqlTableSchemas(): array
    {
        return [
            'users' => "CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            'sessions' => 'CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',

            'cache' => 'CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',

            'categories' => "CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'laptop',
  `icon` varchar(255) DEFAULT NULL,
  `description` text,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            'products' => "CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'laptop',
  `condition` varchar(255) NOT NULL DEFAULT 'second_like_new',
  `condition_label` varchar(255) NOT NULL DEFAULT 'Second Like New 98%',
  `brand` varchar(255) NOT NULL,
  `price` bigint unsigned NOT NULL,
  `original_price` bigint unsigned DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '1',
  `sold_count` int NOT NULL DEFAULT '0',
  `views_count` int NOT NULL DEFAULT '0',
  `short_description` text,
  `description` longtext NOT NULL,
  `processor` varchar(255) DEFAULT NULL,
  `gpu` varchar(255) DEFAULT NULL,
  `ram` varchar(255) DEFAULT NULL,
  `storage` varchar(255) DEFAULT NULL,
  `display` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `battery_health` varchar(255) DEFAULT NULL,
  `warranty` varchar(255) DEFAULT NULL,
  `completeness` varchar(255) DEFAULT NULL,
  `extra_specs` json DEFAULT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `images` json NOT NULL,
  `video_review_url` varchar(255) DEFAULT NULL,
  `video_review_type` varchar(255) NOT NULL DEFAULT 'youtube',
  `video_review_title` varchar(255) DEFAULT NULL,
  `is_best_seller_week` tinyint(1) NOT NULL DEFAULT '0',
  `is_popular_type` tinyint(1) NOT NULL DEFAULT '0',
  `popular_badge` varchar(255) DEFAULT NULL,
  `is_flash_deal` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `rating` decimal(3,2) NOT NULL DEFAULT '4.95',
  `review_count` int NOT NULL DEFAULT '15',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_product_type_index` (`product_type`),
  KEY `products_is_best_seller_week_index` (`is_best_seller_week`),
  KEY `products_is_popular_type_index` (`is_popular_type`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            'rental_packages' => "CREATE TABLE `rental_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `target_audience` varchar(255) NOT NULL,
  `brand_model` varchar(255) NOT NULL,
  `processor` varchar(255) NOT NULL,
  `ram` varchar(255) NOT NULL,
  `storage` varchar(255) NOT NULL,
  `gpu` varchar(255) NOT NULL,
  `display` varchar(255) NOT NULL,
  `price_daily` bigint unsigned NOT NULL,
  `price_weekly` bigint unsigned NOT NULL,
  `price_monthly` bigint unsigned NOT NULL,
  `min_rental_days` int NOT NULL DEFAULT '1',
  `available_units` int NOT NULL DEFAULT '25',
  `thumbnail` varchar(255) NOT NULL,
  `images` json DEFAULT NULL,
  `video_review_url` varchar(255) DEFAULT NULL,
  `benefits` json NOT NULL,
  `description` text,
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rental_packages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            'service_catalogs' => "CREATE TABLE `service_catalogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'hardware',
  `icon` varchar(255) DEFAULT NULL,
  `estimated_price_start` bigint unsigned NOT NULL,
  `estimated_price_end` bigint unsigned DEFAULT NULL,
  `estimated_duration` varchar(255) NOT NULL,
  `warranty_info` varchar(255) NOT NULL,
  `symptoms` json DEFAULT NULL,
  `description` text NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_catalogs_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            'service_tickets' => "CREATE TABLE `service_tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_code` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `device_type` varchar(255) NOT NULL DEFAULT 'laptop',
  `device_brand_model` varchar(255) NOT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `problem_description` text NOT NULL,
  `diagnosis_notes` text,
  `status` varchar(255) NOT NULL DEFAULT 'received',
  `estimated_cost` bigint unsigned NOT NULL DEFAULT '0',
  `final_cost` bigint unsigned DEFAULT NULL,
  `technician_name` varchar(255) NOT NULL DEFAULT 'Tim Teknisi Cyber Gaming',
  `warranty_until` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_tickets_ticket_code_unique` (`ticket_code`),
  KEY `service_tickets_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            'banners' => "CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` text NOT NULL,
  `badge_text` varchar(255) DEFAULT NULL,
  `highlight_text` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `cta_primary_text` varchar(255) NOT NULL DEFAULT 'Lihat Katalog Promo',
  `cta_primary_url` varchar(255) NOT NULL DEFAULT '#katalog',
  `cta_secondary_text` varchar(255) NOT NULL DEFAULT 'Konsultasi Gratis via WA',
  `cta_secondary_url` varchar(255) DEFAULT NULL,
  `accent_color` varchar(255) NOT NULL DEFAULT 'blue',
  `position` varchar(255) NOT NULL DEFAULT 'hero',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            'testimonials' => "CREATE TABLE `testimonials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `customer_role` varchar(255) NOT NULL,
  `customer_city` varchar(255) NOT NULL,
  `service_type` varchar(255) NOT NULL DEFAULT 'beli_laptop',
  `item_purchased` varchar(255) NOT NULL,
  `rating` tinyint unsigned NOT NULL DEFAULT '5',
  `comment` text NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            'articles' => "CREATE TABLE `articles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'Panduan & Tips',
  `excerpt` text NOT NULL,
  `content` longtext NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `author_name` varchar(255) NOT NULL DEFAULT 'Tim Pakar Cyber Gaming Store',
  `read_time_minutes` int NOT NULL DEFAULT '5',
  `views_count` int NOT NULL DEFAULT '120',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            'leads' => "CREATE TABLE `leads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lead_code` varchar(255) NOT NULL,
  `lead_type` varchar(255) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `customer_city` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `items_summary` json DEFAULT NULL,
  `total_amount` bigint unsigned NOT NULL DEFAULT '0',
  `whatsapp_message` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leads_lead_code_unique` (`lead_code`),
  KEY `leads_lead_type_index` (`lead_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            'settings' => "CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` longtext,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        ];
    }
}
