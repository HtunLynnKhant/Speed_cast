-- Create database
CREATE DATABASE IF NOT EXISTS `speedcast_main`;

--
-- Table structure for `categories`
--
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
    `name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Category name',
    `description` VARCHAR(125) NOT NULL DEFAULT '' COMMENT 'Describe the category',
    `active_icon_path` TEXT NOT NULL COMMENT 'Active icon path for the category',
    `default_icon_path` TEXT NOT NULL COMMENT 'Default icon path for the category',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 0-inactive, 1-active',
    `deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestmap',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for `banners`
--
CREATE TABLE IF NOT EXISTS `banners` (
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
    `title` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Banner title',
    `description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Banner description',
    `category_id` INT NOT NULL DEFAULT 0 COMMENT 'Category ID. Ref - `categories` table',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
    `deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NoT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Recorded modified timestamp',
    PRIMARY KEY(`id`),
    KEY `idx_title` (`title`),
    KEY `idx_category_id` (`category_id`)
);

--
-- Table structure for `banner_contents`
--
CREATE TABLE IF NOT EXISTS `banner_contents` (
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
    `banner_id` INT NOT NULL DEFAULT 0 COMMENT 'Banner ID. Ref `banners` table',
    `type` TINYINT NOT NULL DEFAULT 0 COMMENT 'Define content type(s). 1-image, 2-video, etc...',
    `path` TEXT NOT NULL COMMENT 'Content path without actual domain',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
    `deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
    PRIMARY KEY (`id`),
    KEY `idx_type` (`type`),
    KEY `idx_status` (`status`),
    KEY `idx_banner_id` (`banner_id`)
);

--
-- Table structure for `companies`
--
CREATE TABLE IF NOT EXISTS `companies` (
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
    `name` VARCHAR (100) ,
    `description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Description about the company',
    `logo` TEXT NOT NULL COMMENT 'Company logo path',
    `category_id` INT NOT NULL DEFAULT 0 COMMENT 'Category ID. Ref `categories` table',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
    `deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`),
    KEY `idx_category_id` (`category_id`)
);

--
-- Table structure for `ads_types`
--
CREATE TABLE IF NOT EXISTS `ads_types` (
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
    `name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'Ads type name',
    `description` VARCHAR(225) NOT NULL DEFAULT '' COMMENT 'Ads type description',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
    `deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
-- Queries for `ads_types`
INSERT INTO `ads_types` (`name`) VALUES ('Main Banner'), ('Side Banner'), ('Pop Up Ad'), ('Lead Generation');

--
-- Table structure for `pages`
--
CREATE TABLE IF NOT EXISTS `pages` (
	`id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
	`page_no` INT NOT NULL DEFAULT 0 COMMENT 'Page number for category',
	`category_id` INT NOT NULL DEFAULT 0 COMMENT 'Category ID. Ref `categories` table',
	`status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
	`deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`),
    KEY `idx_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for `projects`
--
CREATE TABLE IF NOT EXISTS `projects` (
	`id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
	`name` VARCHAR(125) NOT NULL DEFAULT '' COMMENT 'Project name',
	`description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Project description',
	`client_id` INT NOT NULL DEFAULT 0 COMMENT 'Clinet ID. Ref `clients` table',
	`status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
	`deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`),
    KEY `idx_client_id` (`client_id`)
);

--
-- Table structure for `ads`
--
CREATE TABLE IF NOT EXISTS `ads` (
	`id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
    `title` varchar(155) NOT NULL COMMENT 'title ads',
	`project_id` INT NOT NULL DEFAULT 0 COMMENT 'Project ID. Ref `projects` table',
	`total_price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'Total price for ads',
	`total_final_price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'Total final price after discount and tax',
	`discount` DECIMAL(5, 2) NOT NULL DEFAULT 0.00 COMMENT 'Discount percentage',
	`content_type_id` INT NOT NULL DEFAULT 0 COMMENT 'Content type ID. Ref `content_types` table',
	`content_path` TEXT NOT NULL COMMENT 'Content path',
	`ads_type_id` INT NOT NULL DEFAULT 0 COMMENT 'Advertisement type. Ref `ads_types` table',
	`approval_status` INT NOT NULL DEFAULT 0 COMMENT 'Approval status. 0-pending, 1-approved, 2-rejected',
	`subscription_id` INT NOT NULL DEFAULT 0 COMMENT 'Subscription ID',
	`payment_status` INT NOT NULL DEFAULT 0 COMMENT 'Payment status',
	`remark` VARCHAR(125) NOT NULL DEFAULT '' COMMENT 'Remark for rejected.',
	`status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
    `page_id` int(11) NOT NULL COMMENT 'Page ID. Ref `pages` table',
	`deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`),
    KEY `idx_project_id` (`project_id`),
    KEY `idx_deleted_at` (`deleted_at`),
    KEY `idx_ads_type_id` (`ads_type_id`),
    KEY `idx_payment_status` (`payment_status`),
    KEY `idx_content_type_id` (`content_type_id`),
    KEY `idx_subscription_id` (`subscription_id`),
    KEY `idx_page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for `subscriptions`
--
CREATE TABLE IF NOT EXISTS `subscriptions` (
	`id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
	`active_from` DATETIME NOT NULL COMMENT 'Subscription active date',
	`end_on` DATETIME NOT NULL COMMENT 'Subscription end date',
	`status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
	`deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
    PRIMARY KEY (`id`),
    KEY `idx_end_on` (`end_on`),
    KEY `idx_status` (`status`),
    KEY `idx_active_from` (`active_from`),
    KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for `payments`
--
CREATE TABLE IF NOT EXISTS `payments` (
	`id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
	`ads_id` INT NOT NULL DEFAULT 0 COMMENT 'Ads ID. Ref `ads` table',
	`date` DATETIME NOT NULL COMMENT 'Payment date',
	`amount` DECIMAL(10,2) NOT NULL COMMENT 'Payment amount',
	`payment_type` TINYINT NOT NULL DEFAULT 0 COMMENT 'Payment type',
	`is_fully_paid` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Status for fully paid',
	`currency_id` INT NOT NULL DEFAULT 0 COMMENT 'Currency ID',
	`status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
	`deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
	PRIMARY KEY (`id`),
	KEY `idx_status` (`status`),
	KEY `idx_ads_id` (`ads_id`),
	KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for `currencies`
--
CREATE TABLE IF NOT EXISTS `currencies` (
	`id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
	`code` VARCHAR(5) NOT NULL DEFAULT '' COMMENT 'Currency code',
	`name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'Currency name',
	`status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
	`deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
	PRIMARY KEY (`id`),
	KEY `idx_code` (`code`),
	KEY `idx_status` (`status`),
	KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE IF NOT EXISTS `tablets` (
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
    `name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'tablets type name',
    `description` VARCHAR(225) NOT NULL DEFAULT '' COMMENT 'tablets type description',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
    `deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE `banners`
MODIFY `status` TINYINT NOT NULL DEFAULT 0 COMMENT 'Record status. 1-active, 0-inactive';

CREATE TABLE IF NOT EXISTS `drivers` (
	`id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique table ID',
    `Driver Name` varchar(155) NOT NULL COMMENT 'name of driver',
	`Driver IC Number` varchar(155) NOT NULL COMMENT 'Driver IC Number',
	`Driver Car Plate` varchar(155) NOT NULL COMMENT 'Driver car plate',
    `Drivers license` TEXT NOT NULL COMMENT 'Drivers license',
    `Drives car lisence` TEXT NOT NULL COMMENT 'Drives car lisence',
	`status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Record status. 1-active, 0-inactive',
	`deleted_at` DATETIME DEFAULT NULL COMMENT 'Record deleted timestamp',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created timestamp',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated timestamp',
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`),
    KEY `idx_deleted_at` (`deleted_at`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

