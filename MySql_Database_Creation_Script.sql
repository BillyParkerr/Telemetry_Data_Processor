DROP DATABASE IF EXISTS telemetry_data_processor_db;

CREATE DATABASE IF NOT EXISTS telemetry_data_processor_db;

GRANT SELECT, INSERT ON telemetry_data_processor_db.* TO telemetrydataprocessor_user@localhost IDENTIFIED BY 'telemetrydataprocessor_user_pass';

use telemetry_data_processor_db;

DROP TABLE IF EXISTS `message_content`;
CREATE TABLE `message_content` (
	`id` int(4) NOT NULL AUTO_INCREMENT,
	`teamName` varchar(30) NOT NULL,
	`heaterTemperature` int(10) NOT NULL,
	`fanState` varchar(30) NOT NULL,
	`keypadValue` int(10) NOT NULL,
	`switchOneState` varchar(5) NOT NULL,
	`switchTwoState` varchar(5) NOT NULL,
	`switchThreeState` varchar(5) NOT NULL,
	`switchFourState` varchar(5) NOT NULL,
	PRIMARY KEY (id)
);

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
	`id` int(4) NOT NULL AUTO_INCREMENT,
	`sourcemsisdn` varchar(30) NOT NULL,
	`destinationmsisdn` varchar(30) NOT NULL,
	`receivedTime` varchar(30) NOT NULL,
	`bearer` varchar(30) NOT NULL,
	`messageref` int(4) NOT NULL,
	`messageContentId` int(4),
	PRIMARY KEY (id),
	FOREIGN KEY (messageContentId) REFERENCES `message_content`(`id`)
);

