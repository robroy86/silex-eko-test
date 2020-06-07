CREATE DATABASE IF NOT EXISTS myDb;
USE myDb;
CREATE TABLE `eko_przystanki` (
	`id` INT(6) unsigned NOT NULL AUTO_INCREMENT,
	`nazwa` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_polish_ci,
	`adres` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_polish_ci,
	`opis` TEXT(255) CHARACTER SET utf8 COLLATE utf8_polish_ci,
	`zdj1` VARCHAR(255) DEFAULT '',
	`zdj2` VARCHAR(255) DEFAULT '',
	`zdj3` VARCHAR(255) DEFAULT '',
	`reviewed` TINYINT(1) NOT NULL DEFAULT 0,
	`ip` VARCHAR(255) DEFAULT '',
	`browser` VARCHAR(255) DEFAULT '',
	`data` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	UNIQUE KEY `index_przystanki_unique_nazwa` (`nazwa`) USING BTREE,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;