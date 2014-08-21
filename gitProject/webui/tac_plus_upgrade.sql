# This file created by Andrew Young <baram01@hotmail.com>
# For updating tac_plus database and tables

USE tacacs1;

ALTER TABLE admin ADD vrows INT(2);

CREATE TABLE `component` (
  `id` int(5) NOT NULL PRIMARY KEY,
  `description` VARCHAR(50)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `component` WRITE;
/*!40000 ALTER TABLE `component` DISABLE KEYS */;
INSERT INTO `component` VALUES (1,'desktop switch'),(2,'chassis'),(3,'supervisor/mgmt'),(4,'board/card/module'),(5,'power supply'),(6,'flash card'),(7,'fan tray'),(8,'memory'),(9,'firmware'),(10,'gbic'),(11,'mini-gbic'),(12,'xenpak'),(13,'sfp'),(14,'xfp');
/*!40000 ALTER TABLE `component` ENABLE KEYS */;
UNLOCK TABLES;

CREATE TABLE `config` (
  `version` double NOT NULL,
  `release` int(4) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE contact_info DROP city, DROP state, DROP zip;
ALTER TABLE contact_info MODIFY address1 VARCHAR(50), MODIFY address2 VARCHAR(50);
ALTER TABLE contact_info ADD address3 VARCHAR(50);

CREATE TABLE `failure` (
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `nas` varchar(20) NOT NULL,
  `date` date default NULL,
  `impact` int NOT NULL default '0',
  `cid` int NOT NULL,
  `ticket_no` VARCHAR(30),
  `vticket_no` VARCHAR(30),
  `rma_no` VARCHAR(30),
  `description` text,
  `resolution` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE host ADD name VARCHAR(20);

CREATE TABLE `profile` (
  `id` double NOT NULL auto_increment,
  `uid` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

CREATE TABLE `vcomponent` (
  `id` INT(5) NOT NULL auto_increment,
  `vid` INT(5) NOT NULL,
  `component` INT(5) NOT NULL,
  `description` varchar(50),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


ALTER TABLE vendor ADD url VARCHAR(200);
ALTER TABLE vendor ADD scname VARCHAR(100), ADD scphone VARCHAR(15), ADD scemail VARCHAR(200);
ALTER TABLE vendor ADD tsphone VARCHAR(15), ADD tsemail VARCHAR(200), ADD contract VARCHAR(200);
ALTER TABLE vendor ADD start DATE, ADD end DATE;

CREATE TABLE `user2` (
  `id` double NOT NULL,
  `uid` varchar(20) NOT NULL,
  `gid` varchar(20) default NULL,
  `groupid` double NOT NULL default '65534',
  `comment` text,
  `auth` int(1) default '1',
  `password` varchar(35) default NULL,
  `enable` varchar(35) default NULL,
  `arap` varchar(35) default NULL,
  `pap` varchar(35) default NULL,
  `chap` varchar(35) default NULL,
  `mschap` varchar(35) default NULL,
  `expires` datetime default NULL,
  `disable` int(1) NOT NULL default '0',
  `b_author` varchar(20) default NULL,
  `a_author` varchar(20) default NULL,
  `svc_dflt` int(1) NOT NULL default '0',
  `cmd_dflt` int(1) NOT NULL default '0',
  `maxsess` int(4) default NULL,
  `user` int(1) NOT NULL default '1',
  `acl_id` int(4) default NULL,
  `sess` int(4) default NULL,
  `shell` varchar(255) default NULL,
  `homedir` varchar(255) default NULL,
  PRIMARY KEY  (`uid`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO user2 SELECT * FROM user;

DROP TABLE user;
CREATE TABLE `user` (
  `id` double NOT NULL,
  `uid` varchar(20) NOT NULL,
  `gid` varchar(20) default NULL,
  `groupid` double NOT NULL default '65534',
  `comment` text,
  `auth` int(1) default '1',
  `password` varchar(35) default NULL,
  `enable` varchar(35) default NULL,
  `arap` varchar(35) default NULL,
  `pap` varchar(35) default NULL,
  `chap` varchar(35) default NULL,
  `mschap` varchar(35) default NULL,
  `expires` datetime default NULL,
  `disable` int(1) NOT NULL default '0',
  `b_author` varchar(20) default NULL,
  `a_author` varchar(20) default NULL,
  `svc_dflt` int(1) NOT NULL default '0',
  `cmd_dflt` int(1) NOT NULL default '0',
  `maxsess` int(4) default NULL,
  `user` int(1) NOT NULL default '1',
  `acl_id` int(4) default NULL,
  `sess` int(4) default NULL,
  `shell` varchar(255) default NULL,
  `homedir` varchar(255) default NULL,
  PRIMARY KEY  (`uid`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO user SELECT * FROM user2;
DROP TABLE user2;
