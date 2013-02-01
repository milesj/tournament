
DROP TABLE IF EXISTS `{prefix}team_players`;

CREATE TABLE `{prefix}team_players` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`team_id` int(11) NOT NULL,
	`player_id` int(11) NOT NULL,
	`role` smallint(6) NOT NULL DEFAULT '0',
	`status` smallint(6) NOT NULL DEFAULT '0',
	`created` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `team_id` (`team_id`),
	KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;