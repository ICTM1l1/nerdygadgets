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

CREATE TABLE `nerdygadgets`.`contact_requests` (
   `ContactRequestID` INT NOT NULL AUTO_INCREMENT,
   `ContactRequestName` VARCHAR(100) NOT NULL,
   `ContactRequestEmail` VARCHAR(100) NOT NULL,
   `ContactRequestSubject` VARCHAR(100) NOT NULL,
   `ContactRequestMessage` TEXT(2000) NOT NULL,
   `ContactRequestDate` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP),
   PRIMARY KEY (`ContactRequestID`),
   UNIQUE INDEX `idcontact_requests_UNIQUE` (`ContactRequestID` ASC)
);
