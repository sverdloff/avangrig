CREATE DATABASE `waypoint` /*!40100 COLLATE 'utf8_latvian_ci' */;
USE `Waypoint`;
CREATE TABLE `points` (
	`Id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`Lat` FLOAT NOT NULL,
	`Lng` FLOAT NOT NULL,
	`Address` VARCHAR(50) NOT NULL COLLATE 'utf8_latvian_ci',
	`IsDeleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`PlaceId` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8_latvian_ci',
	PRIMARY KEY (`Id`),
	UNIQUE INDEX `Address` (`Address`),
	INDEX `del` (`IsDeleted`),
	INDEX `geodel` (`Lat`, `Lng`, `IsDeleted`) USING BTREE
)
COLLATE='utf8_latvian_ci'
ENGINE=MyISAM
AUTO_INCREMENT=0
;

CREATE TABLE `pointstatistic` (
	`Counter` INT(10) UNSIGNED NULL DEFAULT '0',
	`OptimalDistance` INT(10) UNSIGNED NULL DEFAULT '0',
	`TimeToWay` INT(10) UNSIGNED NULL DEFAULT '0'
)
COLLATE='utf8_latvian_ci'
ENGINE=MyISAM
;
INSERT INTO `waypoint`.`pointstatistic` (`TimeToWay`) VALUES ('1');