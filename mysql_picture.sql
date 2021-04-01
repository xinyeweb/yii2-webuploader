DROP TABLE IF EXISTS `picture`;
CREATE TABLE `hv_picture` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `path` varchar(80) NOT NULL,
  `md5` varchar(32) NOT NULL,
  `created_at` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8;
