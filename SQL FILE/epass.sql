-- E-Pass System — schema + seed data
-- Passwords are bcrypt hashes (password_hash / PASSWORD_BCRYPT), never plaintext.
-- Seeded logins: admin / admin (superadmin), Nobita Nobi / 0000 (admin)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
CREATE TABLE `login` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(30) NOT NULL,
  `Pass` varchar(255) NOT NULL COMMENT 'bcrypt hash',
  `Mobile` varchar(15) NOT NULL,
  `email` varchar(80) NOT NULL,
  `Role` enum('superadmin','admin') NOT NULL DEFAULT 'admin',
  `Status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uniq_login_user` (`user`),
  UNIQUE KEY `uniq_login_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `login` (`ID`, `user`, `Pass`, `Mobile`, `email`, `Role`, `Status`) VALUES
(24, 'Nobita Nobi', '$2y$10$xufv4n/BUBW2O/Ts3IGr0ODVIzfnJ.bFuYAOlXSjFuNdevop2FDUu', '9876543210', 'nobi123@gmail.com', 'admin', 'active'),
(25, 'admin', '$2y$10$xJkwFsUdVGFEtjfc4JFlR.91taPr2Ypfu2k06SKJ5fciv.fDIQjYS', '9876543210', 'admin@gmail.com', 'superadmin', 'active');

ALTER TABLE `login` AUTO_INCREMENT = 26;

--
-- Table structure for table `tblcategory`
--

DROP TABLE IF EXISTS `tblcategory`;
CREATE TABLE `tblcategory` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(50) NOT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uniq_category_name` (`CategoryName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tblcategory` (`ID`, `CategoryName`) VALUES
(1, 'Logistic Deliveries'),
(2, 'Cleaning'),
(3, 'Essential Services'),
(4, 'Ecommerce Delivery'),
(5, 'Medical Supply'),
(17, 'Examination'),
(18, 'Emergency Work');

ALTER TABLE `tblcategory` AUTO_INCREMENT = 19;

--
-- Table structure for table `tblpass`
--

DROP TABLE IF EXISTS `tblpass`;
CREATE TABLE `tblpass` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PassNumber` int(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Mobile` varchar(15) NOT NULL,
  `email` varchar(80) NOT NULL,
  `IdentityType` varchar(50) NOT NULL,
  `IdentityCardNo` varchar(50) NOT NULL,
  `Category` varchar(50) NOT NULL,
  `FromDate` date NOT NULL,
  `ToDate` date NOT NULL,
  `Status` enum('active','revoked') NOT NULL DEFAULT 'active',
  `CreatedBy` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uniq_pass_number` (`PassNumber`),
  KEY `idx_pass_mobile` (`Mobile`),
  KEY `idx_pass_status` (`Status`),
  CONSTRAINT `fk_pass_created_by` FOREIGN KEY (`CreatedBy`) REFERENCES `login` (`ID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tblpass` (`ID`, `PassNumber`, `Name`, `Mobile`, `email`, `IdentityType`, `IdentityCardNo`, `Category`, `FromDate`, `ToDate`, `Status`, `CreatedBy`) VALUES
(24, 799610586, 'Nobita Nobi', '9876543210', 'nobi123@gmail.com', 'Adhar Card', '784456522652', 'Examination', '2021-02-12', '2021-02-15', 'active', 25);

ALTER TABLE `tblpass` AUTO_INCREMENT = 25;

SET FOREIGN_KEY_CHECKS = 1;
