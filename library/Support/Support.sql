CREATE TABLE `help` (
  `HelpID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(10) unsigned NOT NULL,
  `CreationTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Title` varchar(64) NOT NULL COMMENT 'Help title',
  PRIMARY KEY (`HelpID`)
) ENGINE=InnoDB AUTO_INCREMENT=1000000 DEFAULT CHARSET=utf8 COMMENT='Help';

CREATE TABLE `help_revision` (
  `HelpRevisionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `HelpID` int(10) unsigned NOT NULL,
  `UserID` int(10) unsigned NOT NULL,
  `CreationTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` tinyint(3) NOT NULL COMMENT '-1:Not approved; 0:New 1:Pending 2:Approved',
  `StatusTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Check time',
  `StatusMessage` varchar(32) NOT NULL COMMENT 'Error message',
  `Body` varchar(21755) NOT NULL COMMENT 'Help content',
  PRIMARY KEY (`HelpRevisionID`)
) ENGINE=InnoDB AUTO_INCREMENT=1000000 DEFAULT CHARSET=utf8 COMMENT='Help-Revision';

