CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(40),
    last_name VARCHAR(40),
    email VARCHAR(190) NOT NULL,
    password VARCHAR(90) NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
	UNIQUE KEY (email)
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;