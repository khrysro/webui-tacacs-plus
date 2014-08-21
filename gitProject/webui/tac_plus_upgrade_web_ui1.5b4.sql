-- This file created for WebUI and tac_plus from www.networkforums.net
-- Author:      Andrew Young
-- Last Update: 04/03/2010

USE tacacs;
 
--
-- Table structure for table `command`
--
-- id           : Identification
-- name         : Command name
-- descr        : Description
-- disp_len     : Display length
-- auth         : Type (0 - both, 1 - tacacs, 2 - radius)
-- vid          : Vendor ID

CREATE TABLE `command` (
  `id` int NOT NULL,
  `name` varchar(30) default NULL,
  `descr` varchar(255) default NULL,
  `auth` int NOT NULL,
  `disp_len` int default '0',
  `vid` int NOT NULL,
  PRIMARY KEY  (`id`,`vid`,`auth`),
  KEY `name_index` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `command` WRITE;
/*!40000 ALTER TABLE `command` DISABLE KEYS */;
INSERT INTO `command` VALUES (1,'show','Show',0,NULL,0),(2,'interface','interface',0,NULL,0),(3,'ip','Cisco IP command',0,NULL,9),(4,'route','route',0,NULL,2636);
/*!40000 ALTER TABLE `command` ENABLE KEYS */;
UNLOCK TABLES;

