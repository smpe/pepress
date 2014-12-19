CREATE TABLE `help` (
  `help_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(64) NOT NULL,
  PRIMARY KEY (`help_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='帮助';

CREATE TABLE `help_revision` (
  `help_revision_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `help_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL COMMENT '-1未通过审核 0:草稿 1待审 2通过审核',
  `status_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '审核时间',
  `status_message` varchar(32) NOT NULL,
  `body` varchar(21755) NOT NULL,
  PRIMARY KEY (`help_revision_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COMMENT='帮助-正文';

