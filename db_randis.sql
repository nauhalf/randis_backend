/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 5.7.24 : Database - randis
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`randis` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `randis`;

/*Table structure for table `fotolaporan_id` */

DROP TABLE IF EXISTS `fotolaporan_id`;

CREATE TABLE `fotolaporan_id` (
  `fotolaporan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `laporan_id` int(10) unsigned NOT NULL,
  `foto` varchar(15) NOT NULL,
  PRIMARY KEY (`fotolaporan`),
  KEY `laporan_id` (`laporan_id`),
  CONSTRAINT `fotolaporan_id_ibfk_1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan` (`laporan_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `fotopersonel` */

DROP TABLE IF EXISTS `fotopersonel`;

CREATE TABLE `fotopersonel` (
  `fotopersonel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `personel_id` int(10) unsigned NOT NULL,
  `foto` varchar(50) NOT NULL,
  PRIMARY KEY (`fotopersonel_id`),
  KEY `personel_id` (`personel_id`),
  CONSTRAINT `fotopersonel_ibfk_1` FOREIGN KEY (`personel_id`) REFERENCES `personel` (`personel_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `fotorandis` */

DROP TABLE IF EXISTS `fotorandis`;

CREATE TABLE `fotorandis` (
  `fotorandis_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `randis_id` int(10) unsigned NOT NULL,
  `foto` varchar(50) NOT NULL,
  PRIMARY KEY (`fotorandis_id`),
  KEY `randis_id` (`randis_id`),
  CONSTRAINT `fotorandis_ibfk_1` FOREIGN KEY (`randis_id`) REFERENCES `kendaraan` (`randis_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `kendaraan` */

DROP TABLE IF EXISTS `kendaraan`;

CREATE TABLE `kendaraan` (
  `randis_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `personel_id` int(10) unsigned NOT NULL,
  `nopol` varchar(10) NOT NULL,
  `merk` varchar(50) NOT NULL,
  `warna` varchar(50) NOT NULL,
  `tahun_pembuatan` year(4) NOT NULL,
  PRIMARY KEY (`randis_id`),
  KEY `personel_id` (`personel_id`),
  CONSTRAINT `kendaraan_ibfk_1` FOREIGN KEY (`personel_id`) REFERENCES `personel` (`personel_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `laporan` */

DROP TABLE IF EXISTS `laporan`;

CREATE TABLE `laporan` (
  `laporan_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `randis_id` int(10) unsigned NOT NULL,
  `nopol` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`laporan_id`),
  KEY `randis_id` (`randis_id`),
  CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`randis_id`) REFERENCES `kendaraan` (`randis_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `personel` */

DROP TABLE IF EXISTS `personel`;

CREATE TABLE `personel` (
  `personel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nrp` varchar(10) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `pkt` varchar(25) NOT NULL,
  `jabatan` varchar(25) NOT NULL,
  `satker` varchar(25) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  PRIMARY KEY (`personel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
