CREATE TABLE categories (
    `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(155) NOT NULL UNIQUE,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_name` (`name`)
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;