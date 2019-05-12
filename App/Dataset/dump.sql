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

INSERT INTO `points` (`Id`, `Lat`, `Lng`, `Address`, `IsDeleted`, `PlaceId`) VALUES (125, 56.9691, 24.101, 'Rīgas pilsēta, Rīga, Latvia', 0, 'ChIJz39q47DP7kYRCi-4TDT1Px0');
INSERT INTO `points` (`Id`, `Lat`, `Lng`, `Address`, `IsDeleted`, `PlaceId`) VALUES (122, 56.9268, 24.1026, 'Mūkusalas iela 71, Zemgales priekšpilsēta, Rīga, L', 0, 'ChIJP_iBgsvR7kYRnBkfjvptOew');
INSERT INTO `points` (`Id`, `Lat`, `Lng`, `Address`, `IsDeleted`, `PlaceId`) VALUES (121, 56.9496, 24.1052, 'Riga, Latvia', 0, 'ChIJ7T0H5bDP7kYRMP7yaM3PAAQ');
INSERT INTO `points` (`Id`, `Lat`, `Lng`, `Address`, `IsDeleted`, `PlaceId`) VALUES (123, 56.9436, 24.1149, 'Nēģu iela 7, Latgales priekšpilsēta, Rīga, LV-1050', 0, 'ChIJ4zbC0dTP7kYR1Atmanhp1zg');
