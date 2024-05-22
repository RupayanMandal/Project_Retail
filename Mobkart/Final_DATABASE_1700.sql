-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2024 at 01:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_retail`
--
DROP DATABASE IF EXISTS `project_retail`;
CREATE DATABASE IF NOT EXISTS `project_retail` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `project_retail`;

DELIMITER $$
--
-- Functions
--
DROP FUNCTION IF EXISTS `Checkout`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `Checkout` (`tid` VARCHAR(100), `ttime` DATETIME, `tuid` INT) RETURNS INT(11)  BEGIN

   DECLARE total double(50,2);
   DECLARE temp_total double(50,2);
   DECLARE id int;
   DECLARE quan int;
   set total=0;
   WHILE ( SELECT EXISTS(select * from cart where uid=tuid)=1) DO
    	set id=(SELECT pid from cart WHERE uid=tuid LIMIT 1);
        set quan=(SELECT quantity from cart where uid=tuid and pid=id);
        set temp_total= ((select pprice from product where pid=id) * quan);
INSERT into transaction(tid,ttime,uid,pid,quantity,ttotal) values(tid,ttime,tuid,id,quan,temp_total);
UPDATE product set pavailable = pavailable - quan where pid=id;
        DELETE from cart where uid=tuid and pid=id;
    	SET total  = total + temp_total;
	END WHILE;
   
   RETURN total;

END$$

DROP FUNCTION IF EXISTS `ShowTotalPurchase`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `ShowTotalPurchase` (`curtid` VARCHAR(100)) RETURNS DOUBLE(10,2)  BEGIN

   DECLARE total_amount double(50,2);

   IF (SELECT EXISTS(select * from transaction where tid=curtid))=1 THEN
   		set total_amount = (SELECT SUM(ttotal) from transaction WHERE tid =curtid);
      
   ELSE
      set total_amount = 0.00;
   END IF;
   RETURN total_amount;

END$$

DROP FUNCTION IF EXISTS `UpdateCart`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `UpdateCart` (`curuid` INT, `curpid` INT, `quan` INT) RETURNS INT(11)  BEGIN

   DECLARE cart_items int;

   IF (SELECT EXISTS(select * from cart where uid=curuid and pid=curpid))=1 THEN
      UPDATE cart set quantity = quantity + quan where uid=curuid and pid=curpid;
   IF (SELECT quantity from cart where uid=curuid and pid=curpid)<=0 THEN
      DELETE FROM cart WHERE uid=curuid and pid=curpid;
   END IF;    
   END IF;
   set cart_items = (select count(*) from cart where uid=curuid);
   RETURN cart_items;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `email` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`email`, `password`) VALUES
('admin@mobkart.com', '$2y$10$ocSQ0w6RIfpmeiN1KqYnT.LnNTqUwsgEzb9Js2xKysj7rmZdi1LZ2');

--
-- Triggers `admin`
--
DROP TRIGGER IF EXISTS `noDeleteFromAdmin`;
DELIMITER $$
CREATE TRIGGER `noDeleteFromAdmin` BEFORE DELETE ON `admin` FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'table admin does not support Deletion'
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `noInsertOnAdmin`;
DELIMITER $$
CREATE TRIGGER `noInsertOnAdmin` BEFORE INSERT ON `admin` FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'table admin does not support Insertion'
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `noUpdateOnAdmin`;
DELIMITER $$
CREATE TRIGGER `noUpdateOnAdmin` BEFORE UPDATE ON `admin` FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'table admin does not support Updation'
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  KEY `foreign_pid` (`pid`),
  KEY `foreign_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`uid`, `pid`, `quantity`) VALUES
(3, 6, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `category`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
`pcatagory` varchar(100)
,`count` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `pname` varchar(100) NOT NULL,
  `pcatagory` varchar(100) NOT NULL,
  `pprice` double(10,2) NOT NULL DEFAULT 0.00,
  `pdesc` varchar(1000) NOT NULL,
  `ppic` varchar(1000) DEFAULT NULL,
  `pavailable` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pid`, `pname`, `pcatagory`, `pprice`, `pdesc`, `ppic`, `pavailable`) VALUES
(1, 'POCO M6 Pro 5G', 'Smartphone', 9999.00, 'Color: Sea Blue\n6 GB RAM | 128 GB ROM | Expandable Upto 1 TB\n17.25 cm (6.79 inch) Full HD+ Display\n50MP + 2MP | 8MP Front Camera\n5000 mAh Battery\nSnapdragon 4 Gen 2 Processor', 'https://rukminim2.flixcart.com/image/416/416/xif0q/mobile/s/9/i/m6-pro-5g-mzb0eqjin-poco-original-imags3e7dazavyje.jpeg?q=70&crop=false', 10),
(2, 'OnePlus Nord CE 3 Lite 5G', 'Smartphone', 16887.50, 'Color: Light Green \r\n8 GB RAM | 128 GB ROM\r\n17.07 cm (6.72 inch) Display\r\n108MP Rear Camera\r\n5000 mAh Battery', 'https://rukminim2.flixcart.com/image/416/416/xif0q/mobile/l/7/k/-original-imagtxvur9yrxvru.jpeg?q=70&crop=false', 16),
(3, 'REDMI 13C ', 'Smartphone', 7699.00, 'Color:Stardust Black \r\n4 GB RAM | 128 GB ROM | Expandable Upto 1 TB\r\n17.12 cm (6.74 inch) HD+ Display\r\n50MP Rear Camera | 8MP Front Camera\r\n5000 mAh Battery\r\nHelio G85 Processor', 'https://rukminim2.flixcart.com/image/416/416/xif0q/mobile/e/h/j/-original-imagxg47qr5wzvyh.jpeg?q=70&crop=false', 35),
(4, 'Apple iPhone 15', 'Smartphone', 101999.69, 'Color: Black\r\n512 GB ROM\r\n15.49 cm (6.1 inch) Super Retina XDR Display\r\n48MP + 12MP | 12MP Front Camera\r\nA16 Bionic Chip, 6 Core Processor Processor', 'https://rukminim2.flixcart.com/image/416/416/xif0q/mobile/h/d/9/-original-imagtc2qzgnnuhxh.jpeg?q=70&crop=false', 2),
(5, '33 W SuperVOOC 4 A Mobile Charger', 'Accessories', 359.00, 'Wall Charger\r\nSuitable For: Mobile\r\nNo Cable Included\r\nUniversal Voltage\r\nOutput Current : 4 A', 'https://rukminim2.flixcart.com/image/416/416/xif0q/battery-charger/j/7/t/33w-dash-dart-wrap-sn-05-fast-charging-adapter-travel-fast-cable-original-imagqjfyaggufyx4.jpeg?q=70&crop=false', 10),
(6, 'Mi 20000 mAh 18 W Power Bank', 'Accessories', 3499.00, 'Color- Black\r\nPort- Type C and Micro USB\r\nCable- Type C\r\nRecharging time- 6.9 hours (with 18 W charger and USB cable)\r\nWeight: 434 g | Capacity: 20000 mAh\r\nLithium Polymer Battery | Type-C Connector\r\nPower Source: Battery\r\nCharging Cable Included', 'https://rukminim2.flixcart.com/image/128/128/kfcv6vk0/power-bank/r/f/5/power-bank-20000-plm18zm-mi-original-imafvtc7x9zgrzbz.jpeg?q=70&crop=true', 1),
(7, 'Multi Angle Mobile Stand', 'Accessories', 167.00, 'Color- White\r\nPORTABLE SIZE\r\nMULTI ANGLEADJUSTABLE\r\nPREMIUM MATERIAL\r\nUNIVERSALCOMPATIBILITY', 'https://rukminim2.flixcart.com/image/128/128/kdqa4y80/mobile-holder/k/t/v/slick-multi-angle-mobile-stand-phone-holder-portable-foldable-original-imafukazkqpjezrs.jpeg?q=70&crop=false', 20),
(8, 'Back Cover for Apple iPhone 15 Pro', 'Accessories', 399.00, 'Color: Black | Magsafe\r\nSuitable For: Mobile\r\nMaterial: Polycarbonate\r\n', 'https://rukminim2.flixcart.com/image/416/416/xif0q/cases-covers/back-cover/n/j/p/enfl-magsafe-case-ip-15pro-black-enflamo-original-imagvsspszmufdsq.jpeg?q=70&crop=false', 11),
(9, 'Mivi DuoPods i2 TWS', 'Earphones', 999.00, 'Color: Black\r\nWith Mic:Yes\r\nWireless range: 10 m\r\n13mm Dynamic Drivers\r\nDual Mic - Artificial Intelligence enabled ENC (Environmental Noise Cancellation) for HD call clarity\r\nSwift charging | 10 mins charge = 500 mins playtime | Type-C Earphones\r\nGaming mode -Triple Tap Left earbud to activate\r\nVoice assistance -Triple Tap Right earbud to activate\r\nIPX 4.0 Sweat Resistant', 'https://rukminim2.flixcart.com/image/128/128/xif0q/headphone/l/a/7/-original-imagx8s5etjezqym.jpeg?q=70&crop=false', 25),
(10, 'Apple EarPods (USB-C) Wired Headset', 'Earphones', 2045.66, 'Color: White\r\nWith Mic:Yes\r\nConnector type: No\r\nThe speakers inside the EarPods have been engineered to maximise sound output, which means you get high-quality audio.\r\nThe EarPods (USB-C) also include a built-in remote that lets you adjust the volume, control the playback of music and video, and answer or end calls with a press of the remote.\r\nDesigned by Apple\r\nDeeper, richer bass tones\r\nGreater protection from sweat and water\r\nControl music and video playback\r\nAnswer and end calls', 'https://rukminim2.flixcart.com/image/128/128/xif0q/headphone/a/7/a/wired-headphones-crystal-clear-sound-earphones-for-oneplus-oppo-original-imagtmzfuqf8fe8a.jpeg?q=70&crop=false', 20),
(11, 'OnePlus Bullets Wireless Z2 Bluetooth Headset', 'Earphones', 1799.00, 'Color: Magico Black\r\nWith Mic:Yes\r\nBluetooth version: 5\r\nBattery life: 30 Hrs | Charging time: 10 min', 'https://rukminim2.flixcart.com/image/128/128/l0sgyvk0/headphone/e/w/c/buds-z2-oneplus-original-imagcg5gfpcg5ndv.jpeg?q=70&crop=false', 1),
(12, 'TRIGGR Kraken X1', 'Earphones', 999.00, 'Color: Green\r\nwith Battery Display | 40ms Latency\r\nQuad Mic ENC\r\n40 Hr Battery\r\nv5.3 Bluetooth Gaming Headset', 'https://rukminim2.flixcart.com/image/128/128/xif0q/headphone/r/y/e/-original-imagud7myncp3vks.jpeg?q=70&crop=false', 40),
(13, 'JBL C50HI Wired Headset', 'Earphones', 699.69, 'Color: Blue\r\nWith Mic:Yes\r\nConnector type: 3.5 mm\r\nExtra bass: Add extra thump to your music with JBL bass sound\r\nOne button universal remote allows you to answer and manage your calls effortlessly\r\nVoice assistant integration: Access your voice assistant from your earphone', 'https://rukminim2.flixcart.com/image/128/128/k0bbb0w0pkrrdj/headphone-refurbished/8/y/f/a-c50hiblu-jbl-original-imafhmzubkafgyzj.jpeg?q=70&crop=false', 30),
(14, 'OnePlus 11R 5G', 'Smartphone', 35817.00, 'Color: Galactic Silver\r\n16 GB RAM | 256 GB ROM\r\n17.02 cm (6.7 inch) Display\r\n50MP Rear Camera | 16MP Front Camera\r\n5000 mAh Battery\r\n', 'https://rukminim2.flixcart.com/image/128/128/xif0q/mobile/y/w/l/11r-5g-5011102527-oneplus-original-imagn3bq8t4ja5rx.jpeg?q=70&crop=false', 25),
(15, 'Mi USB Type C Cable', 'Accessories', 189.00, 'Color: Black\r\n3 A \r\nLength 1 m\r\nRound Cable\r\nConnector One: USB A-Type|Connector Two: Type-C\r\nCable Speed: 480 Mbps\r\nMobile, Tablet', 'https://rukminim2.flixcart.com/image/128/128/xif0q/data-cable/v/0/q/-original-imagqs9h9nfgmvm4.jpeg?q=70&crop=false', 100);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `tindex` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(100) NOT NULL,
  `ttime` datetime NOT NULL,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `ttotal` double(50,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`tindex`),
  KEY `foreign_tpid` (`pid`),
  KEY `foreign_tuid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`tindex`, `tid`, `ttime`, `uid`, `pid`, `quantity`, `ttotal`) VALUES
(1, 'T1901372151664dd2e2d87a60.93038950', '2024-05-21 16:41:30', 2, 2, 1, 16887.50),
(2, 'T1901372151664dd2e2d87a60.93038950', '2024-05-20 16:41:30', 2, 12, 1, 999.00),
(3, 'T1595265954664dd52345bad9.86393239', '2024-05-20 14:51:07', 1, 15, 1, 189.00),
(4, 'T1595265954664dd52345bad9.86393239', '2024-05-18 16:51:07', 1, 9, 1, 999.00),
(5, 'T48334940664dd565964d48.67096028', '2024-05-17 16:52:13', 4, 8, 1, 399.00),
(6, 'T48334940664dd565964d48.67096028', '2024-05-16 16:52:13', 4, 4, 1, 101999.69);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(100) NOT NULL,
  `uemail` varchar(100) NOT NULL,
  `uphone` varchar(100) NOT NULL,
  `upswd` varchar(100) NOT NULL,
  `uaddress` varchar(1000) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uemail` (`uemail`) USING BTREE,
  UNIQUE KEY `uphone` (`uphone`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `uname`, `uemail`, `uphone`, `upswd`, `uaddress`) VALUES
(1, 'Martin Kuhlman', 'billie.rowe@yahoo.com', '9876543210', '$2y$10$jLdNZrUvSy7rZfYwkOF4EuTcekQHZ8KfyqV2O.jL0Y6x8Yu89kU76', '87497 Beahan Point Suite 651'),
(2, 'Rupayan Mandal', 'coderupayanmandal@gmail.com', '9432531834', '$2y$10$jLdNZrUvSy7rZfYwkOF4EuTcekQHZ8KfyqV2O.jL0Y6x8Yu89kU76', '621 Aida Walk Suite 240'),
(3, 'Alexandria Feeney', 'destini.schneider@gmail.com', '6504718827', '$2y$10$jLdNZrUvSy7rZfYwkOF4EuTcekQHZ8KfyqV2O.jL0Y6x8Yu89kU76', '2933 Nigel View Apt. 854'),
(4, 'Verlie O\'Hara', 'gpfeffer@lindgren.net', '2186813428', '$2y$10$jLdNZrUvSy7rZfYwkOF4EuTcekQHZ8KfyqV2O.jL0Y6x8Yu89kU76', '5442 Bell Parkway Apt. 390'),
(5, 'Nicholas Johns', 'ladarius59@mann.com', '27652311287', '$2y$10$jLdNZrUvSy7rZfYwkOF4EuTcekQHZ8KfyqV2O.jL0Y6x8Yu89kU76', '6975 Ledner Shore Apt. 386'),
(6, 'Marilyne Flatl', 'sim.morar@yahoo.com', '7214231721', '$2y$10$jLdNZrUvSy7rZfYwkOF4EuTcekQHZ8KfyqV2O.jL0Y6x8Yu89kU76', '75419 Maribel Brook');

-- --------------------------------------------------------

--
-- Structure for view `category`
--
DROP TABLE IF EXISTS `category`;

DROP VIEW IF EXISTS `category`;
CREATE OR REPLACE VIEW `category`  AS SELECT `product`.`pcatagory` AS `pcatagory`, count(`product`.`pcatagory`) AS `count` FROM `product` GROUP BY `product`.`pcatagory` ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `foreign_pid` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreign_uid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `foreign_tpid` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `foreign_tuid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
