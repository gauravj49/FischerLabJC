/* Create user */
CREATE USER 'UserName1'@'localhost' IDENTIFIED BY 'password_for_UserName1';
GRANT ALL PRIVILEGES ON *.* TO 'UserName1'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

/*!  1) Create database */
CREATE DATABASE journalClub;

/* Create user */
CREATE USER 'JCdatabse'@'localhost' IDENTIFIED BY 'password_for_JCdatabse';
GRANT ALL PRIVILEGES ON journalClub.* TO 'JCdatabse'@'localhost';
FLUSH PRIVILEGES;

/*!  2) Create tables */
USE journalClub;
CREATE TABLE `current_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paper_title` varchar(1024) DEFAULT NULL,
  `link` varchar(3000) DEFAULT NULL,
  `Summary` varchar(5000) DEFAULT NULL,
  `current` int(2) DEFAULT '0',
  `paper_name` varchar(1024) DEFAULT NULL,
  `submitted_by` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `previous_presentations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `presentation_title` varchar(1024) DEFAULT NULL,
  `presented_by` varchar(1024) DEFAULT NULL,
  `presented_on` varchar(255) DEFAULT NULL,
  `presentation_type` varchar(75) DEFAULT NULL,
  `presentation_name` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`));


