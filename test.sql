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
