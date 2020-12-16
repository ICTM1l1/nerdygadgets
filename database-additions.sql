DROP TABLE IF EXISTS `nerdygadgets`.`privatecustomer`;
CREATE TABLE `nerdygadgets`.`privatecustomer` (
  `PrivateCustomerID` INT NOT NULL AUTO_INCREMENT ,
  `PrivateCustomerName` VARCHAR(100) NOT NULL ,
  `DeliveryMethodID` INT NOT NULL ,
  `DeliveryCityID` INT NOT NULL ,
  `PhoneNumber` INT NOT NULL ,
  `PeopleID` INT NULL ,
  `DeliveryAddressLine1` VARCHAR(60) NOT NULL ,
  `DeliveryAddressLine2` VARCHAR(60) ,
  `DeliveryPostalCode` VARCHAR(6) NOT NULL ,
  PRIMARY KEY (`PrivateCustomerID`) ,
  CONSTRAINT `FK_People` FOREIGN KEY (PeopleID) REFERENCES people(`PersonID`) ,
  CONSTRAINT `FK_DeliveryCityID` FOREIGN KEY (DeliveryCityID) REFERENCES cities(`CityID`) ,
  CONSTRAINT `FK_DeliveryMethodID` FOREIGN KEY (DeliveryMethodID) REFERENCES deliverymethods(`DeliveryMethodID`)
);

DROP TABLE IF EXISTS `nerdygadgets`.`contact_requests`;
CREATE TABLE `nerdygadgets`.`contact_requests` (
   `ContactRequestID` INT NOT NULL PRIMARY KEY UNIQUE AUTO_INCREMENT,
   `ContactRequestName` VARCHAR(100) NOT NULL,
   `ContactRequestEmail` VARCHAR(100) NOT NULL,
   `ContactRequestSubject` VARCHAR(100) NOT NULL,
   `ContactRequestMessage` TEXT(2000) NOT NULL,
   `ContactRequestDate` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

/** Password for this user is 'nimda' */
INSERT INTO `nerdygadgets`.`people` (
    FullName, PreferredName, SearchName,
    IsPermittedToLogon, LogonName,
    IsExternalLogonProvider, HashedPassword,
    IsSystemUser, IsEmployee, IsSalesperson,
    PhoneNumber, EmailAddress, ValidFrom,
    ValidTo, LastEditedBy
) VALUES (
    'Admin', 'Admin', 'Admin',
    1, 'admin@admin.nl', 0,
    '$2y$10$.D3CZ9FSjEYCOoZwlr.WjekQaijWBo4KTW0I3rpgm4Ou60cknIIXi', 0, 1,
    0, '+310612345678', 'admin@admin.nl',
    '2020-12-8 23:59:59', '9999-12-31 23:59:59', 1
);

DROP TABLE IF EXISTS `nerdygadgets`.`review`;
CREATE TABLE IF NOT EXISTS `nerdygadgets`.`review`
(
    `ReviewID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `StockItemID` INT(11) NOT NULL ,
    `Review` VARCHAR(250) NOT NULL,
    `PersonID` INT(11) NOT NULL,
    `Score` TINYINT(255) NOT NULL,
    `ReviewDate` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    UNIQUE KEY (`StockItemID`, `PersonID`),
    FOREIGN KEY(`stockitemid`) REFERENCES `nerdygadgets`.`stockitems`(`stockitemid`),
    FOREIGN KEY(`PersonID`) REFERENCES `nerdygadgets`.`people`(`personid`)
);

DROP TABLE IF EXISTS `nerdygadgets`.`average_score`;
CREATE TABLE IF NOT EXISTS `nerdygadgets`.`average_score`
(
    `StockItemID` INT(11) NOT NULL PRIMARY KEY,
    `Average` FLOAT(24) NOT NULL DEFAULT 0,
    FOREIGN KEY(`StockItemID`) REFERENCES `nerdygadgets`.`stockitems`(`stockitemid`)
);