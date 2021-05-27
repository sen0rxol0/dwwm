-- DROP TABLE IF EXISTS expertises;
CREATE TABLE expertises (
    `id` INT NOT NULL AUTO_INCREMENT,
    `category_id` SMALLINT UNSIGNED NOT NULL,
    `name` VARCHAR(155),
    `description` TEXT,
    `image` VARCHAR(155) NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_name` (`name`)
    CONSTRAINT `fk_expertises_categories` FOREIGN KEY (`category_id`) REFERENCES categories(`id`)
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;