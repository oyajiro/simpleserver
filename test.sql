CREATE TABLE IF NOT EXISTS `UserData` (
  `user_id` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `coins` int(11) NOT NULL,
  `last_visit` int(11) NOT NULL,
  `visit_days` int(11) NOT NULL,
  `continious_visit_days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `UserObjects` (
  `user_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `UserData`
 ADD PRIMARY KEY (`user_id`);

ALTER TABLE `UserObjects`
 ADD UNIQUE KEY `name` (`name`);

