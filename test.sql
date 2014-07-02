     CREATE TABLE `users` (
         `id` int(11) NOT NULL AUTO_INCREMENT,
         `first_name` varchar(32) DEFAULT NULL,
         `last_name` varchar(32) DEFAULT NULL,
         `modified` datetime DEFAULT NULL,
         PRIMARY KEY (`id`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

     CREATE TABLE `points` (
         `id` int(11) DEFAULT NULL,
         `points` int(11) DEFAULT NULL
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

     CREATE TABLE `friend` (
  `user` int(11) DEFAULT NULL,
  `friend` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `live` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `live` (`live`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `message` (
  `user` int(11) DEFAULT NULL,
  `message` int(11) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `live` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `live` (`live`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

     CREATE TABLE `file` (
       `id` int(11) NOT NULL AUTO_INCREMENT,
       `name` varchar(255) DEFAULT NULL,
       `mime` varchar(32) DEFAULT NULL,
       `type` int(11) DEFAULT NULL,
       `size` int(11) DEFAULT NULL,
       `width` int(11) DEFAULT NULL,
       `height` int(11) DEFAULT NULL,
       `user` int(11) DEFAULT NULL,
       `live` tinyint(4) DEFAULT NULL,
       `deleted` tinyint(4) DEFAULT NULL,
       `created` datetime DEFAULT NULL,
       `modified` datetime DEFAULT NULL,
       PRIMARY KEY (`id`),
       KEY `live` (`live`),
       KEY `deleted` (`deleted`)

     ) ENGINE = InnoDB DEFAULT CHARSET = utf8 ;
