CREATE TABLE `freeservice_usage` (
    `id` int UNSIGNED NOT NULL,
    `inmate_id` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `service_id` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `date` date DEFAULT NULL,
    `usage` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `freeservice_usage`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `freeservice_usage`
    MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;