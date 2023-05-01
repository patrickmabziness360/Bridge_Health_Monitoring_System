
CREATE TABLE `tblBridge` (
  `BridgeID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Cordinates` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Vibrations` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Strain` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Water_Level` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Accelerometer` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Crack` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `RoadStatus` enum('OPENED','CLOSED') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'OPENED',
  `BridgeStatus` enum('Normal','Moderate','Critical') CHARACTER SET utf8 COLLATE utf8_unicode_ci  DEFAULT 'Normal',
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`BridgeID`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tblBridgeImages` (
  `ImageID` int NOT NULL AUTO_INCREMENT,
  `BridgeID` int NOT NULL,
  `Caption` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `AttachmentName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`ImageID`),
  KEY `BridgeID` (`BridgeID`),
  CONSTRAINT `tblPostFiles_ibfk_1` FOREIGN KEY (`BridgeID`) REFERENCES `tblBridge` (`BridgeID`) ON DELETE CASCADE ON UPDATE CASCADE
)
CREATE TABLE `tblUsers` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `UserName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `UserName` (`UserName`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tblUsers` VALUES (1,'admin','admin@gmail.com','$2y$10$F1UOLQYX.asaUpYJiFCWlu1.UUmt9BT82RcgF.pPT7ZwBIixHmFya');

