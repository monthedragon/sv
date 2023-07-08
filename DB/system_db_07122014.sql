/*
SQLyog Community v11.31 (32 bit)
MySQL - 5.6.16 : Database - system_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`system_db` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `system_db`;

/*Table structure for table `crm` */

DROP TABLE IF EXISTS `crm`;

CREATE TABLE `crm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crm_code` char(50) NOT NULL,
  `name` char(50) NOT NULL,
  `path` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `db_name` char(50) NOT NULL,
  `db_username` char(50) NOT NULL,
  `db_password` char(50) NOT NULL,
  `db_ip` char(50) NOT NULL,
  `main_table` char(50) NOT NULL DEFAULT 'contact_list',
  `sale_tag` char(10) NOT NULL DEFAULT 'AG',
  `callresult_field` char(10) NOT NULL DEFAULT 'callresult',
  `firstname_field` char(30) NOT NULL DEFAULT 'firstname',
  `lastname_field` char(30) NOT NULL DEFAULT 'lastname',
  `calldate_field` char(30) NOT NULL DEFAULT 'calldate',
  `agent_field` char(30) NOT NULL DEFAULT 'agent',
  `user_table` char(20) NOT NULL DEFAULT 'users',
  `lookup_table` char(20) NOT NULL DEFAULT 'lookup',
  `id_field` char(20) NOT NULL DEFAULT 'id',
  PRIMARY KEY (`id`,`crm_code`,`name`,`is_active`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `crm` */

insert  into `crm`(`id`,`crm_code`,`name`,`path`,`is_active`,`time_stamp`,`db_name`,`db_username`,`db_password`,`db_ip`,`main_table`,`sale_tag`,`callresult_field`,`firstname_field`,`lastname_field`,`calldate_field`,`agent_field`,`user_table`,`lookup_table`,`id_field`) values (1,'template','Template','http://localhost/template/',1,'2014-07-05 15:35:39','template_db','root','','localhost','contact_list','AG','callresult','firstname','lastname','calldate','agent','users','lookup','id');

/*Table structure for table `field_mapping` */

DROP TABLE IF EXISTS `field_mapping`;

CREATE TABLE `field_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crm_code` char(50) NOT NULL,
  `field_name` char(50) NOT NULL,
  `mask_name` char(50) NOT NULL,
  `related_table_id` int(11) NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order_by` int(11) NOT NULL,
  `field_type` char(50) NOT NULL,
  `lu_cat` char(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_code` (`crm_code`),
  KEY `field_name` (`field_name`,`related_table_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `field_mapping` */

insert  into `field_mapping`(`id`,`crm_code`,`field_name`,`mask_name`,`related_table_id`,`is_required`,`is_active`,`order_by`,`field_type`,`lu_cat`) values (1,'template','c_firstname','Firstname',0,1,1,1,'input',''),(2,'template','c_middlename','Middlename',0,0,1,2,'input',''),(3,'template','c_lastname','Lastname',0,1,1,3,'input',''),(4,'template','c_gender','Gender',0,0,1,4,'select','gender'),(6,'template','firstname','Supple Firstname',1,0,1,1,'input',''),(7,'template','lastname','Supple Lastname',1,0,1,2,'input',''),(8,'template','gender','Supple Gender',1,1,1,3,'select','gender'),(9,'template','issuer','Issuer',2,0,1,1,'input',''),(10,'template','card_no','Card No.',2,1,1,2,'input',''),(11,'template','credit_limit','Credit Limit',2,0,1,3,'input',''),(12,'template','issue_date','Issue Date',2,0,1,4,'input',''),(13,'template','c_nationality','Nationality',0,0,1,5,'input','');

/*Table structure for table `lookup` */

DROP TABLE IF EXISTS `lookup`;

CREATE TABLE `lookup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lu_code` char(30) NOT NULL,
  `lu_desc` char(100) NOT NULL,
  `lu_cat` char(30) NOT NULL,
  `order_by` tinyint(5) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_legacy` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if 1 then editing is not allowed legacy data!',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=latin1;

/*Data for the table `lookup` */

insert  into `lookup`(`id`,`lu_code`,`lu_desc`,`lu_cat`,`order_by`,`is_active`,`is_legacy`) values (116,'0','ON PROGRESS','ol_status',1,1,0),(117,'1','VALID','ol_status',2,1,0),(118,'2','PENDING','ol_status',3,1,0),(1,'agent','Agent','user_types',1,1,1),(2,'client','Client','user_types',1,1,1),(3,'admin','Administrator','user_types',1,1,1),(119,'1','VALID','sv_status',1,1,0),(120,'2','PENDING','sv_status',2,1,0),(121,'1','SV ALERT','ol_alert',2,1,0),(122,'2','SUP ALERT','ol_alert',1,1,0);

/*Table structure for table `privilege` */

DROP TABLE IF EXISTS `privilege`;

CREATE TABLE `privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `right_id` int(11) NOT NULL,
  `user_id` varchar(30) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `right_id` (`right_id`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47544 DEFAULT CHARSET=latin1;

/*Data for the table `privilege` */

insert  into `privilege`(`id`,`right_id`,`user_id`,`is_active`) values (46682,175,'admin',0),(46683,176,'admin',0),(46686,174,'admin',0),(46688,177,'admin',0),(46700,178,'admin',1),(46701,179,'admin',1),(46716,183,'admin',0),(46717,184,'admin',0),(46726,180,'admin',1),(46735,182,'admin',1),(46737,181,'admin',1),(46754,185,'admin',0),(46762,186,'admin',0),(46776,187,'admin',0),(46806,188,'admin',0),(46807,190,'admin',0),(46809,189,'admin',0),(46816,191,'admin',0),(46866,193,'admin',0),(47062,194,'admin',0),(47113,195,'admin',0),(47114,196,'admin',0),(47120,197,'admin',0),(47202,198,'admin',0),(47211,199,'admin',1),(47295,200,'admin',1),(47307,202,'admin',1),(47308,201,'admin',1),(47319,204,'admin',1),(47320,203,'admin',1),(47339,206,'admin',1),(47340,207,'admin',1),(47345,205,'admin',1),(47452,178,'Jeannie Salem',1),(47453,206,'Jeannie Salem',1),(47454,200,'Jeannie Salem',1),(47455,202,'Jeannie Salem',1),(47456,205,'Jeannie Salem',1),(47457,201,'Jeannie Salem',1),(47458,203,'Jeannie Salem',1),(47459,178,'mon',1),(47460,206,'mon',1),(47461,200,'mon',1),(47462,202,'mon',1),(47463,205,'mon',1),(47464,201,'mon',1),(47465,203,'mon',1),(47467,178,'Teacher Jeannie',1),(47468,206,'Teacher Jeannie',1),(47469,200,'Teacher Jeannie',1),(47470,202,'Teacher Jeannie',1),(47471,205,'Teacher Jeannie',1),(47472,201,'Teacher Jeannie',1),(47473,203,'Teacher Jeannie',1),(47474,178,'jeannie_1',1),(47475,206,'jeannie_1',1),(47476,200,'jeannie_1',1),(47477,202,'jeannie_1',1),(47478,205,'jeannie_1',1),(47479,201,'jeannie_1',1),(47480,203,'jeannie_1',1),(47481,178,'jeannie_3',1),(47482,206,'jeannie_3',1),(47483,200,'jeannie_3',1),(47484,202,'jeannie_3',1),(47485,205,'jeannie_3',1),(47486,201,'jeannie_3',1),(47487,203,'jeannie_3',1),(47488,178,'jeannie',1),(47489,206,'jeannie',1),(47490,200,'jeannie',1),(47491,202,'jeannie',1),(47492,205,'jeannie',1),(47493,201,'jeannie',1),(47494,203,'jeannie',1),(47509,178,'JayR Flores',1),(47510,206,'JayR Flores',1),(47511,200,'JayR Flores',1),(47512,202,'JayR Flores',1),(47513,205,'JayR Flores',1),(47514,201,'JayR Flores',1),(47515,203,'JayR Flores',1),(47516,178,'<div style=\"border:1px solid #',1),(47517,206,'<div style=\"border:1px solid #',1),(47518,200,'<div style=\"border:1px solid #',1),(47519,202,'<div style=\"border:1px solid #',1),(47520,205,'<div style=\"border:1px solid #',1),(47521,201,'<div style=\"border:1px solid #',1),(47522,203,'<div style=\"border:1px solid #',1),(47523,178,'teacher1',1),(47524,206,'teacher1',1),(47525,200,'teacher1',1),(47526,202,'teacher1',1),(47527,205,'teacher1',1),(47528,201,'teacher1',1),(47529,203,'teacher1',1),(47530,178,'teacher2',1),(47531,206,'teacher2',1),(47532,200,'teacher2',1),(47533,202,'teacher2',1),(47534,205,'teacher2',1),(47535,201,'teacher2',1),(47536,203,'teacher2',1),(47537,178,'aaaa',1),(47538,206,'aaaa',1),(47539,200,'aaaa',1),(47540,202,'aaaa',1),(47541,205,'aaaa',1),(47542,201,'aaaa',1),(47543,203,'aaaa',1);

/*Table structure for table `related_tables` */

DROP TABLE IF EXISTS `related_tables`;

CREATE TABLE `related_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crm_code` char(50) NOT NULL,
  `foreign_table_name` char(50) NOT NULL,
  `foreign_mask_name` char(50) DEFAULT NULL,
  `foreign_field_name` char(50) NOT NULL DEFAULT 'baserecid',
  `order_by` tinyint(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_code` (`crm_code`),
  KEY `foreign_table_name` (`foreign_table_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `related_tables` */

insert  into `related_tables`(`id`,`crm_code`,`foreign_table_name`,`foreign_mask_name`,`foreign_field_name`,`order_by`) values (1,'template','supplementary','Supplementary Details','baserecid',1),(2,'template','card_details','Card Details','baserecid',2);

/*Table structure for table `remarks` */

DROP TABLE IF EXISTS `remarks`;

CREATE TABLE `remarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crm_code` char(50) NOT NULL,
  `table_recid` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `alert` tinyint(5) NOT NULL,
  `user_id` char(50) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `crm_code` (`crm_code`,`table_recid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `remarks` */

insert  into `remarks`(`id`,`crm_code`,`table_recid`,`remarks`,`alert`,`user_id`,`time_stamp`) values (3,'template',170331,'My Remarks here',2,'1','2014-07-12 09:00:40'),(4,'template',170331,'My Remarks here',2,'1','2014-07-12 09:01:45'),(5,'template',170331,'My Remarks here',2,'1','2014-07-12 09:02:08'),(6,'template',170331,'My Remarks here',2,'1','2014-07-12 09:02:22'),(7,'template',170333,'pending kasi\r\n',0,'1','2014-07-12 09:15:18'),(8,'template',170340,'test',2,'1','2014-07-12 09:34:31'),(9,'template',170340,'',0,'1','2014-07-12 09:38:42'),(10,'template',170340,'',0,'1','2014-07-12 09:42:13'),(11,'template',170340,'',0,'1','2014-07-12 09:42:55'),(12,'template',170340,'',0,'1','2014-07-12 09:43:16'),(13,'template',170340,'test',0,'1','2014-07-12 09:43:47'),(14,'template',170340,'test',0,'1','2014-07-12 09:48:16'),(15,'template',170340,'please supply supplementary!',2,'1','2014-07-12 09:48:42');

/*Table structure for table `rights` */

DROP TABLE IF EXISTS `rights`;

CREATE TABLE `rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `right_name` varchar(60) CHARACTER SET utf8 NOT NULL,
  `right_group` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `right_desc` tinytext CHARACTER SET utf8 NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `rights` */

/*Table structure for table `sales` */

DROP TABLE IF EXISTS `sales`;

CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crm_code` char(50) NOT NULL COMMENT 'unique combination',
  `table_recid` int(11) NOT NULL COMMENT 'unique combination',
  `status` tinyint(5) NOT NULL DEFAULT '0',
  `alert` tinyint(5) NOT NULL DEFAULT '0',
  `calldate` datetime NOT NULL COMMENT 'unique combination',
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` char(50) NOT NULL,
  `sv_code` char(50) NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '1',
  `firstname` char(50) NOT NULL,
  `lastname` char(50) NOT NULL,
  `agent_id` char(50) NOT NULL,
  `agent_name` char(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_code` (`crm_code`,`table_recid`,`calldate`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Data for the table `sales` */

insert  into `sales`(`id`,`crm_code`,`table_recid`,`status`,`alert`,`calldate`,`time_stamp`,`user_id`,`sv_code`,`is_locked`,`firstname`,`lastname`,`agent_id`,`agent_name`) values (36,'template',170333,2,0,'2014-07-12 02:12:06','2014-07-12 09:35:03','admin','DRAGON',0,'MON','TUYAy','admin','Administrator'),(37,'template',170331,0,0,'2014-07-12 02:12:18','2014-07-12 09:35:03','admin','DRAGON',1,'Mon','Tuyay','admin','Administrator'),(38,'template',170340,2,2,'2014-07-12 03:12:29','2014-07-12 09:48:42','admin','DRAGON',0,'MON','TUYAy','admin','Administrator');

/*Table structure for table `sales_details` */

DROP TABLE IF EXISTS `sales_details`;

CREATE TABLE `sales_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) NOT NULL,
  `field_name` char(30) NOT NULL,
  `original_value` char(200) NOT NULL,
  `new_value` char(200) NOT NULL,
  `related_table_id` int(11) NOT NULL,
  `foreign_table_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=525 DEFAULT CHARSET=latin1;

/*Data for the table `sales_details` */

insert  into `sales_details`(`id`,`sales_id`,`field_name`,`original_value`,`new_value`,`related_table_id`,`foreign_table_id`) values (380,29,'c_firstname','MONAYSS','',0,0),(381,29,'c_middlename','GG','',0,0),(382,29,'c_lastname','DDDTuyay','',0,0),(383,29,'c_gender','male','',0,0),(384,29,'c_nationality','GG','',0,0),(385,29,'firstname','Marvin Angelo','',1,12),(386,29,'lastname','SS','',1,12),(387,29,'gender','male','',1,12),(388,29,'firstname','Mon Alvin','',1,13),(389,29,'lastname','Luya Tuyay','',1,13),(390,29,'gender','male','',1,13),(391,29,'firstname','Mark Andrian','',1,16),(392,29,'lastname','Luay Tuyay','',1,16),(393,29,'gender','male','',1,16),(394,29,'firstname','marivna','',1,17),(395,29,'lastname','','',1,17),(396,29,'gender','male','',1,17),(397,29,'issuer','MASTER_CARD','',2,28),(398,29,'card_no','1000-0000-0000-0113','',2,28),(399,29,'credit_limit','5000','',2,28),(400,29,'issue_date','03/22/1990','',2,28),(401,30,'c_firstname','firstname','',0,0),(402,30,'c_middlename','middlename','',0,0),(403,30,'c_lastname','lastname','',0,0),(404,30,'c_gender','male','',0,0),(405,30,'c_nationality','nationality','',0,0),(406,30,'firstname','marivna','',1,14),(407,30,'lastname','test','',1,14),(408,30,'gender','male','',1,14),(409,30,'firstname','marivna','',1,15),(410,30,'lastname','312312','',1,15),(411,30,'gender','male','',1,15),(412,30,'issuer','HSBC','',2,25),(413,30,'card_no','0000-0000-0000-0000','',2,25),(414,30,'credit_limit','80000','',2,25),(415,30,'issue_date','03/22/2013','',2,25),(416,30,'issuer','MASTER CARD','',2,26),(417,30,'card_no','0000-0000-0000-0000','',2,26),(418,30,'credit_limit','50000','',2,26),(419,30,'issue_date','03/22/1990','',2,26),(420,30,'issuer','POLITICIAN','',2,27),(421,30,'card_no','0000-0000-0000-0000','',2,27),(422,30,'credit_limit','50000','',2,27),(423,30,'issue_date','03/22/2014','',2,27),(424,31,'c_firstname','MON','',0,0),(425,31,'c_middlename','LUA','',0,0),(426,31,'c_lastname','TUYAy','',0,0),(427,31,'c_gender','','',0,0),(428,31,'c_nationality','','',0,0),(429,31,'issuer','11','',2,29),(430,31,'card_no','0000-0000-0000-0000','',2,29),(431,31,'credit_limit','11','',2,29),(432,31,'issue_date','11/11/1111','',2,29),(433,32,'c_firstname','MON','MON',0,0),(434,32,'c_middlename','LUA','',0,0),(435,32,'c_lastname','TUYAy','TUYAy',0,0),(436,32,'c_gender','','',0,0),(437,32,'c_nationality','','',0,0),(438,32,'firstname','MOn','',1,18),(439,32,'lastname','Tuyay','',1,18),(440,32,'gender','male','male',1,18),(441,32,'firstname','Marvin','',1,19),(442,32,'lastname','Tuyay','',1,19),(443,32,'gender','male','male',1,19),(444,32,'firstname','Mark','',1,20),(445,32,'lastname','Tuyay','',1,20),(446,32,'gender','male','male',1,20),(447,32,'issuer','HSBC','',2,30),(448,32,'card_no','0000-0000-0000-0000','0000-0000-0000-0000',2,30),(449,32,'credit_limit','50000','',2,30),(450,32,'issue_date','03/22/1990','',2,30),(451,32,'issuer','MC','',2,31),(452,32,'card_no','0000-0000-0000-0000','0000-0000-0000-0000',2,31),(453,32,'credit_limit','50000','',2,31),(454,32,'issue_date','03/22/1960','',2,31),(455,33,'c_firstname','Marvin','Marvin',0,0),(456,33,'c_middlename','GG','',0,0),(457,33,'c_lastname','DDDTuyay','DDDTuyay',0,0),(458,33,'c_gender','male','',0,0),(459,33,'c_nationality','GG','',0,0),(460,33,'firstname','Marvin Angelo','',1,12),(461,33,'lastname','SS','',1,12),(462,33,'gender','male','male',1,12),(463,33,'firstname','Mon Alvin','',1,13),(464,33,'lastname','Luya Tuyay','',1,13),(465,33,'gender','male','male',1,13),(466,33,'firstname','Mark Andrian','',1,16),(467,33,'lastname','Luay Tuyay','',1,16),(468,33,'gender','male','male',1,16),(469,33,'firstname','marivna','',1,17),(470,33,'lastname','','',1,17),(471,33,'gender','male','male',1,17),(472,33,'issuer','MASTER_CARD','',2,28),(473,33,'card_no','1000-0000-0000-0113','1000-0000-0000-0113',2,28),(474,33,'credit_limit','5000','',2,28),(475,33,'issue_date','03/22/1990','',2,28),(476,34,'c_firstname','MONs','MONs',0,0),(477,34,'c_middlename','LUA','',0,0),(478,34,'c_lastname','TUYAy','TUYAy',0,0),(479,34,'c_gender','','',0,0),(480,34,'c_nationality','','',0,0),(481,35,'c_firstname','MON','',0,0),(482,35,'c_middlename','LUA','',0,0),(483,35,'c_lastname','TUYAy','',0,0),(484,35,'c_gender','','',0,0),(485,35,'c_nationality','','',0,0),(486,35,'issuer','11','',2,29),(487,35,'card_no','0000-0000-0000-0000','',2,29),(488,35,'credit_limit','11','',2,29),(489,35,'issue_date','11/11/1111','',2,29),(490,36,'c_firstname','MONs','MONs',0,0),(491,36,'c_middlename','LUA','',0,0),(492,36,'c_lastname','TUYAy','TUYAy',0,0),(493,36,'c_gender','','',0,0),(494,36,'c_nationality','','',0,0),(495,37,'c_firstname','Marvin','',0,0),(496,37,'c_middlename','GG','',0,0),(497,37,'c_lastname','DDDTuyay','',0,0),(498,37,'c_gender','male','',0,0),(499,37,'c_nationality','GG','',0,0),(500,37,'firstname','Marvin Angelo','',1,12),(501,37,'lastname','SS','',1,12),(502,37,'gender','male','',1,12),(503,37,'firstname','Mon Alvin','',1,13),(504,37,'lastname','Luya Tuyay','',1,13),(505,37,'gender','male','',1,13),(506,37,'firstname','Mark Andrian','',1,16),(507,37,'lastname','Luay Tuyay','',1,16),(508,37,'gender','male','',1,16),(509,37,'firstname','marivna','',1,17),(510,37,'lastname','','',1,17),(511,37,'gender','male','',1,17),(512,37,'issuer','MASTER_CARD','',2,28),(513,37,'card_no','1000-0000-0000-0113','',2,28),(514,37,'credit_limit','5000','',2,28),(515,37,'issue_date','03/22/1990','',2,28),(516,38,'c_firstname','pending','pending',0,0),(517,38,'c_middlename','LUA','',0,0),(518,38,'c_lastname','TUYAy','TUYAy',0,0),(519,38,'c_gender','','',0,0),(520,38,'c_nationality','','',0,0),(521,38,'issuer','11','',2,29),(522,38,'card_no','0000-0000-0000-0000','0000-0000-0000-0000',2,29),(523,38,'credit_limit','11','',2,29),(524,38,'issue_date','11/11/1111','',2,29);

/*Table structure for table `user_rights_template` */

DROP TABLE IF EXISTS `user_rights_template`;

CREATE TABLE `user_rights_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `right_id` int(11) NOT NULL,
  `contact_type` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user_rights_template` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` char(50) NOT NULL,
  `user_password` char(128) NOT NULL,
  `firstname` char(50) NOT NULL,
  `middlename` char(50) NOT NULL,
  `lastname` char(50) NOT NULL,
  `mobile_no` char(50) NOT NULL,
  `log_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '1=login;0=logout',
  `last_login` datetime NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ipaddress` char(50) NOT NULL,
  `user_type` char(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `contact_list_id` int(11) NOT NULL,
  `mask_name` char(50) NOT NULL DEFAULT 'MASKNAME',
  PRIMARY KEY (`id`),
  KEY `NewIndex1` (`user_name`,`user_password`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`user_name`,`user_password`,`firstname`,`middlename`,`lastname`,`mobile_no`,`log_status`,`last_login`,`last_activity`,`ipaddress`,`user_type`,`is_active`,`contact_list_id`,`mask_name`) values (1,'admin','6df514224405ef0f441f5fda57ff5ce5','Admin','.','.','09160000000',1,'2013-08-06 19:08:20','2014-07-12 09:32:42','','admin',1,1,'DRAGON');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
