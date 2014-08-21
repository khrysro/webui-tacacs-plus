-- This file created for WebUI and tac_plus from www.networkforums.net
-- Author:	Andrew Young
-- Last Update:	08/30/2009

DROP DATABASE tacacs;
CREATE DATABASE tacacs;
USE tacacs;

--
-- Table structure for table `access`
--
--
-- date		: Time stamp of occurance
-- nas		: IP address of NAS
-- terminal	: Source of access
-- uid		: User ID
-- client_ip	: IP of where the client accces from
-- service	: service type (login, enable, etc)
-- status	: accept/reject

CREATE TABLE `access` (
  `date` datetime NOT NULL,
  `nas` varchar(16) NOT NULL,
  `terminal` varchar(20) default NULL,
  `uid` varchar(20) NOT NULL,
  `client_ip` varchar(16) NOT NULL,
  `service` varchar(10) default NULL,
  `status` varchar(10) default NULL,
  KEY `date_index` (`date`),
  KEY `nas_index` (`nas`),
  KEY `uid_index` (`uid`),
  KEY `client_index` (`client_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `accounting`
--
-- date		: Time stamp of occurance
-- nas		: IP of the NAS
-- uid		: User ID
-- terminal	: Source of access
-- client_ip	: IP of where the client accces from
-- type		: Service type (start, stop, etc)
-- service	: Service (exec, shell, etc)
-- priv_lvl	: Privilege level
-- cmd		: Command used
-- elasped_time	: How much time client spent connected
-- bytes_in	: Amount of bytes received on terminal
-- bytes_out	: Amount of bytes transmitted on terminal

CREATE TABLE `accounting` (
  `date` datetime NOT NULL,
  `nas` varchar(16) NOT NULL,
  `uid` varchar(20) NOT NULL,
  `terminal` varchar(20) default NULL,
  `client_ip` varchar(16) NOT NULL,
  `type` varchar(20) default NULL,
  `service` varchar(20) default NULL,
  `priv_lvl` int default NULL,
  `cmd` varchar(255) default NULL,
  `elapsed_time` int default NULL,
  `bytes_in` int default NULL,
  `bytes_out` int default NULL,
  KEY `date_index` (`date`),
  KEY `acct_index` (`uid`),
  KEY `nas_index` (`nas`),
  KEY `client_index` (`client_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `acl`
--
-- id		: Identification
-- type		: User/NAS ACL
-- seq		: Sequence/Rule number
-- permission	: Permit/Deny this seq/rule
-- value	: User/Group ID or client network/netmask bits
-- value1	: if client ACL, client network
--		: if nas ACL, profile
-- submask	: Subnet mask

CREATE TABLE `acl` (
  `id` int NOT NULL,
  `type` int NOT NULL,
  `seq` int NOT NULL,
  `permission` int NOT NULL,
  `value` varchar(20) NOT NULL,
  `value1` double default NULL,
  `submask` double default NULL,
  PRIMARY KEY  (`id`,`type`,`seq`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `admin`
--
-- uid		: User identification
-- password	: User password
-- priv_lvl	: Privilege level
-- link		: Link to profile user

CREATE TABLE `admin` (
  `uid` varchar(20) NOT NULL,
  `password` varchar(35) NOT NULL,
  `priv_lvl` int default NULL,
  `link` int default NULL,
  `vrows` int default 25,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES ('admin',ENCRYPT('system'),15,0,25);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute`
--
-- id		: Identification
-- name		: Attribute name
-- descr	: Description
-- type		: Type of data (0 - string, 1 - integer, 2 - ipaddr, 3 - date)
-- has_tag	: Attribute tagging
-- encrypt	: if true, encrypt/decrypt attribute value
-- disp_len	: Display length
-- auth		: Type (0 - both, 1 - tacacs, 2 - radius)
-- vid		: Vendor ID

CREATE TABLE `attribute` (
  `id` int NOT NULL,
  `name` varchar(30) default NULL,
  `descr` varchar(255) default NULL,
  `type` int NOT NULL,
  `auth` int NOT NULL,
  `has_tag` int default '0',
  `encrypt` int default '0',
  `disp_len` int default '0',
  `vid` int NOT NULL,
  PRIMARY KEY  (`id`,`vid`,`auth`),
  KEY `name_index` (`name`) 
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `attribute` WRITE;
/*!40000 ALTER TABLE `attribute` DISABLE KEYS */;
INSERT INTO `attribute` VALUES (300,'priv-lvl','Privilege Level',1,0,NULL,NULL,NULL,0),(300,'foundry-privlvl','Privilege Level',1,0,NULL,NULL,NULL,1991),(300,'junos-exec','Juniper Exec Service',0,0,NULL,NULL,NULL,2636);
/*!40000 ALTER TABLE `attribute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `command`
--
-- id		: Identification
-- name		: Command name
-- descr	: Description
-- disp_len	: Display length
-- auth		: Type (0 - both, 1 - tacacs, 2 - radius)
-- vid		: Vendor ID

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

--
-- Table structure for table `component`
--
-- id           : Identification
-- description  : Description of the component

CREATE TABLE `component` (
  `id` int NOT NULL PRIMARY KEY,
  `description` VARCHAR(50)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `component` WRITE;
/*!40000 ALTER TABLE `component` DISABLE KEYS */;
INSERT INTO `component` VALUES (1,'desktop switch'),(2,'chassis'),(3,'supervisor/mgmt'),(4,'board/card/module'),(5,'power supply'),(6,'flash card'),(7,'fan tray'),(8,'memory'),(9,'firmware'),(10,'gbic'),(11,'mini-gbic'),(12,'xenpak'),(13,'sfp'),(14,'xfp');
/*!40000 ALTER TABLE `component` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--
-- version	: tac_plus version
-- release	: tac_plus relase
-- description	: description

CREATE TABLE `config` (
  `version` double NOT NULL,
  `release` int NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `contact_info`
--
-- uid		: User ID
-- fname	: First Name
-- surname	: Surname (last name)
-- address1	: Address
-- addres2	: Address additional if need more space
-- city		: City
-- state	: State
-- zip		: Zip code
-- phone	: Phone number
-- email	: Email

CREATE TABLE `contact_info` (
  `uid` varchar(20) NOT NULL,
  `fname` varchar(40) NOT NULL,
  `surname` varchar(40) NOT NULL,
  `address1` varchar(50) default NULL,
  `address2` varchar(50) default NULL,
  `address3` varchar(50) default NULL,
  `phone` varchar(14) default NULL,
  `email` varchar(100) default NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `failure`
--
-- id           : Identification
-- nas		: NAS
-- date 	: Failed date
-- impact	: Impact (0->none, 1->severe, 2->major, 3->minor)
-- cid   	: Component Identification
-- ticket_no	: Ticket number
-- vticket_no	: Vendor ticket number
-- rma_no	: RMA number
-- description  : Description of the failure
-- resolution	: Resoluttion of the failure

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

--
-- Table structure for table `host`
--
-- ip		: IP V4 address/subnet-bits of NAS
-- name		: NAS or NAS Group name
-- hostgroup	: Group that the NAS belongs to
-- hkey		: Decryption/Encryption key
-- enable	: Enable password for host that overrides all other enable passwords
-- prompt	: Banner to display on the NAS
-- network	: Network of the IP
-- submask	: Subnet mask
-- loginacl	: Login ACL to allow/deny access to NAS
-- enableacl	: Enable ACL to allow/deny access to NAS
-- host		: 1 - NAS, 2 - NAS group
-- vendor	: Vendor ID that the device belongs to

CREATE TABLE `host` (
  `ip` varchar(20) NOT NULL,
  `name` varchar(20) default NULL,
  `hostgroup` varchar(20) default NULL,
  `hkey` varchar(20) default NULL,
  `enable` varchar(35) default NULL,
  `prompt` text,
  `network` double NOT NULL,
  `submask` double NOT NULL,
  `loginacl` int default NULL,
  `enableacl` int default NULL,
  `host` int(1) default NULL,
  `vendor` int default '0',
  PRIMARY KEY  (`ip`),
  KEY `net` (`network`,`submask`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `node`
--
-- id		: Identification
-- uid		: User identification
-- seq		: Sequence number
-- service	: Service Type (N_svc_cmd, N_svc_exec, N_svc_ppp, etc)
-- type		: Node type (N_arg, N_optarg, N_permit, N_deny, etc)
-- value	: node value
-- value1	: node value1
-- attr		: Attribute/Command ID
-- vid		: Vendor ID

CREATE TABLE `node` (
  `id` double NOT NULL,
  `uid` varchar(20) NOT NULL,
  `seq` int NOT NULL,
  `service` int NOT NULL,
  `type` int default NULL,
  `value` varchar(50) NOT NULL,
  `value1` varchar(50) default NULL,
  `attr` int default NULL,
  `vid` int default NULL,
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `profile`
--
-- id		: Profile identification
-- uid		: Profile name (similar to unix id)

CREATE TABLE `profile` (
  `id` double NOT NULL auto_increment,
  `uid` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `user`
--
-- id		: User/Group identification
-- uid		: User/Group name (matches unix id)
-- gid		: Group name (only used when user is part of a group)
-- comment	: Description about the user
-- auth		: Authentication method (1-local, 5-SecurID)
-- flags	: Use for changing password, etc
-- password	: Password
-- enable	: Enable password
-- arap		: ARAP password
-- pap		: PAP password
-- chap		: CHAP password
-- mschap	: MSCHAP password
-- expires	: Expiration date and time
-- disable	: disable user account
-- b_author	: Script to run before authorization
-- a_author	: Script to run after authorization
-- svc_dflt	: Default behaviour for service authorization
-- cmd_dflt	: Default behaviour for command authorization
-- maxsess	: Maximum sessions allowed
-- acl_id	: ACL that limits where the user/group can communicate from
-- sess		: Current number of open sessions
-- shell	: Unix shell
-- homedir	: Unix home directory

CREATE TABLE `user` (
  `id` double NOT NULL,
  `uid` varchar(20) NOT NULL,
  `gid` varchar(20) default NULL,
  `groupid` double NOT NULL default '65534',
  `comment` text,
  `auth` int default '1',
  `flags` int default '1',
  `password` varchar(35) default NULL,
  `enable` varchar(35) default NULL,
  `arap` varchar(35) default NULL,
  `pap` varchar(35) default NULL,
  `chap` varchar(35) default NULL,
  `mschap` varchar(35) default NULL,
  `expires` datetime default NULL,
  `disable` int NOT NULL default '0',
  `b_author` varchar(20) default NULL,
  `a_author` varchar(20) default NULL,
  `svc_dflt` int NOT NULL default '0',
  `cmd_dflt` int NOT NULL default '0',
  `maxsess` int default NULL,
  `user` int NOT NULL default '1',
  `acl_id` int default NULL,
  `sess` int default NULL,
  `shell` varchar(255) default NULL,
  `homedir` varchar(255) default NULL,
  PRIMARY KEY  (`uid`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `vcomponent`
--
-- id           : Identification
-- vid          : Vendor identification
-- type         : Type of component
-- description  : description of the component

CREATE TABLE `vcomponent` (
  `id` INT(5) NOT NULL auto_increment,
  `vid` INT(5) NOT NULL,
  `component` INT(5) NOT NULL,
  `description` varchar(50),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Table structure for table `vendor`
--
-- id		: identification of the vendor
-- name		: name of the vendor
-- url		: vendor website
-- scname	: Sales Contact name
-- scphone	: Sales Conatact phone
-- tsphone	: Technical Support phone
-- tsemail	: Technical Support email
-- contract	: Contract number(s)
-- start	: Contract Start date
-- end		: Contract End date

CREATE TABLE `vendor` (
  `id` int NOT NULL,
  `name` varchar(30) default NULL,
  `url` varchar(200) default NULL,
  `scname` varchar(100) default NULL,
  `scphone` varchar(15) default NULL,
  `scemail` varchar(200) default NULL,
  `tsphone` varchar(15) default NULL,
  `tsemail` varchar(200) default NULL,
  `contract` varchar(200) default NULL,
  `start` date default '0000-00-00',
  `end` date default '0000-00-00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendor`
--

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;
INSERT INTO `vendor` VALUES (0,'All',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,'Cisco','http://www.cisco.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(52,'Cabletron/Enterasys',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(166,'Shiva',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(307,'Livingston',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(311,'Microsoft','http://www.microsoft.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(429,'Usr',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(529,'Ascend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(1584,'Bay-Networks',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(1916,'Extreme-Networks',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(1991,'Foundry-Networks',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2180,'Versanet',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2352,'Redback',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2636,'Juniper','http://www.juniper.net',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3224,'Juniper (Netscreen)','http://www.juniper.net',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3551,'SprindTide',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4874,'ERX',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8246,'Cistron',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Create user needed to administrate tacacs
--
GRANT ALL ON tacacs.* TO tacacs@localhost IDENTIFIED BY 'tac_plus';
GRANT ALL ON tacacs.* TO tacacs IDENTIFIED BY 'tac_plus';
