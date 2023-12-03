
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- Verdant implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here

-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.

-- Example 2: add a custom field to the standard "player" table
-- ALTER TABLE `player` ADD `player_my_custom_field` INT UNSIGNED NOT NULL DEFAULT '0';


CREATE TABLE IF NOT EXISTS `plant` (
  `plant_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plant_type` varchar(16) NOT NULL,
  `plant_type_arg` int(11) NOT NULL,
  `plant_location` varchar(16) NOT NULL,
  `plant_location_arg` int(11) NOT NULL,
  PRIMARY KEY (`plant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `room` (
  `room_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_type` varchar(16) NOT NULL,
  `room_type_arg` int(11) NOT NULL,
  `room_location` varchar(16) NOT NULL,
  `room_location_arg` int(11) NOT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `item` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_type` varchar(16) NOT NULL,
  `item_type_arg` int(11) NOT NULL,
  `item_location` varchar(16) NOT NULL,
  `item_location_arg` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `robot` (
  `robot_number` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(10) NOT NULL,
  `player_no` int(10) NOT NULL,
  `player_name` varchar(32) NOT NULL,
  `player_color` varchar(6) NOT NULL,
  `player_score` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`robot_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
