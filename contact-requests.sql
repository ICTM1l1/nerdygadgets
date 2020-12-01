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