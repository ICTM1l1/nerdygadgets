# Make all primary keys auto increment.
SET FOREIGN_KEY_CHECKS = 0;

LOCK TABLES `people` WRITE;
ALTER TABLE `people`
CHANGE COLUMN `PersonID` `PersonID` INT(11) NOT NULL AUTO_INCREMENT;

LOCK TABLES `customers` WRITE;
ALTER TABLE `customers`
CHANGE COLUMN `CustomerID` `CustomerID` INT(11) NOT NULL AUTO_INCREMENT;

LOCK TABLES `orders` WRITE;
ALTER TABLE `orders`
CHANGE COLUMN `OrderID` `OrderID` INT(11) NOT NULL AUTO_INCREMENT;
SET max_statement_time=0;

LOCK TABLES `orderlines` WRITE;
ALTER TABLE `orderlines`
CHANGE COLUMN `OrderLineID` `OrderLineID` INT(11) NOT NULL AUTO_INCREMENT;

LOCK TABLES `cities` WRITE;
ALTER TABLE `cities`
CHANGE COLUMN `CityID` `CityID` INT(11) NOT NULL AUTO_INCREMENT;

SET FOREIGN_KEY_CHECKS = 1;
UNLOCK TABLES;

# Triggers.
#Trigger on people insert, check if email is valid

DROP TRIGGER IF EXISTS correcte_email;
DELIMITER //
CREATE TRIGGER correcte_email
    BEFORE INSERT ON people
    FOR EACH ROW
       BEGIN
         IF NEW.EmailAddress NOT LIKE '_%@_%.__%' THEN
          SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email field is invalid';
         END IF;
       END;
//
DELIMITER ;

#Trigger on customer insert, check if postalcode is valid

DROP TRIGGER IF EXISTS insert_correcte_postcode_customer;
DELIMITER //
CREATE TRIGGER insert_correcte_postcode_customer
    BEFORE INSERT ON customers
    FOR EACH ROW
       BEGIN
         IF REGEXP_INSTR(NEW.PostalPostalCode, '[0-9][0-9][0-9][0-9][a-zA-Z][a-zA-Z]') THEN
          SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Postal code is not valid';
         END IF;
        IF NOT REGEXP_INSTR(NEW.DeliveryPostalCode, '[0-9][0-9][0-9][0-9][a-zA-Z][a-zA-Z]')
            THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Postal code is not valid';
        END IF;
       END;
//
DELIMITER ;

DROP TRIGGER IF EXISTS update_correcte_postcode_customer;
DELIMITER //
CREATE TRIGGER update_correcte_postcode_customer
    BEFORE UPDATE ON customers
    FOR EACH ROW
BEGIN
    IF REGEXP_INSTR(NEW.PostalPostalCode, '[0-9][0-9][0-9][0-9][a-zA-Z][a-zA-Z]') THEN
          SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Postal code is not valid';
END IF;
IF NOT REGEXP_INSTR(NEW.DeliveryPostalCode, '[0-9][0-9][0-9][0-9][a-zA-Z][a-zA-Z]')
            THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Postal code is not valid';
END IF;
END;
//
DELIMITER ;

DROP TRIGGER IF EXISTS insert_correcte_postcode_privatecustomer;
DELIMITER //
CREATE TRIGGER insert_correcte_postcode_privatecustomer
    BEFORE INSERT ON privatecustomer
    FOR EACH ROW
    BEGIN
        IF NOT REGEXP_INSTR(NEW.DeliveryPostalCode, '[0-9][0-9][0-9][0-9][a-zA-Z][a-zA-Z]')
            THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Postal code is not valid';
        END IF;
    END;
//
DELIMITER ;

DROP TRIGGER IF EXISTS update_correcte_postcode_privatecustomer;
DELIMITER //
CREATE TRIGGER update_correcte_postcode_privatecustomer
    BEFORE UPDATE ON privatecustomer
                         FOR EACH ROW
BEGIN
    IF NOT REGEXP_INSTR(NEW.DeliveryPostalCode, '[0-9][0-9][0-9][0-9][a-zA-Z][a-zA-Z]')
        THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Postal code is not valid';
    END IF;
END;
//
DELIMITER ;

#Trigger on orderline insert, call procedure "UpdateProductVoorraad"

DROP TRIGGER IF EXISTS orderlineInserted;
DELIMITER //
CREATE TRIGGER orderlineInserted
    BEFORE INSERT ON orderlines
    FOR EACH ROW
    BEGIN
        IF new.quantity > 0 AND (SELECT QuantityOnHand
        FROM stockitemholdings s
        JOIN orderlines o ON s.StockItemID = o.StockItemID
        WHERE s.StockItemID = 86
        AND QuantityOnHand - new.quantity > 0
        ORDER BY orderlineID LIMIT 1)
        THEN
            CALL UpdateProductVoorraad(new.stockitemid, new.quantity);
        ELSE
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'This product will be out of stock after this change.';
        END IF;
    END;
//
DELIMITER ;

# Procedures
#Procedure to update stock quantity on orderline insert

DROP PROCEDURE IF EXISTS UpdateProductVoorraad;
DELIMITER //
CREATE PROCEDURE UpdateProductVoorraad(StockItemID int(11), Quantity int(11))
BEGIN
    UPDATE stockitemholdings AS s
    SET s.QuantityOnHand = s.QuantityOnHand - Quantity
    WHERE s.stockitemid = stockitemid;
END;
//
DELIMITER ;


# Authorisation
DROP USER IF EXISTS'nerdygadgets_read'@'localhost';
DROP USER IF EXISTS'nerdygadgets_create'@'localhost';
DROP USER IF EXISTS'nerdygadgets_update'@'localhost';
DROP USER IF EXISTS'nerdygadgets_create_or_update'@'localhost';
DROP USER IF EXISTS'nerdygadgets_delete'@'localhost';

CREATE USER 'nerdygadgets_read'@'localhost' IDENTIFIED BY '^jnx$PK&hHg3Cz6y#V#S';
REVOKE ALL PRIVILEGES ON * . * FROM 'nerdygadgets_read'@'localhost';
GRANT SELECT ON * . * TO 'nerdygadgets_read'@'localhost';

CREATE USER 'nerdygadgets_create'@'localhost' IDENTIFIED BY '9xGK^uV9q9RF*Zkx6t%D';
REVOKE ALL PRIVILEGES ON * . * FROM 'nerdygadgets_create'@'localhost';
GRANT SELECT, INSERT ON * . * TO 'nerdygadgets_create'@'localhost';

CREATE USER 'nerdygadgets_update'@'localhost' IDENTIFIED BY 'hiU1!L01685I%!nyyvyQ';
REVOKE ALL PRIVILEGES ON * . * FROM 'nerdygadgets_update'@'localhost';
GRANT SELECT, UPDATE ON * . * TO 'nerdygadgets_update'@'localhost';

CREATE USER 'nerdygadgets_create_or_update'@'localhost' IDENTIFIED BY 'mkotQ1Osqa231Bp%2&rL';
REVOKE ALL PRIVILEGES ON * . * FROM 'nerdygadgets_create_or_update'@'localhost';
GRANT SELECT, INSERT, UPDATE ON * . * TO 'nerdygadgets_create_or_update'@'localhost';

CREATE USER 'nerdygadgets_delete'@'localhost' IDENTIFIED BY 'KKP7Ylcw$A0t1Kx95D2c';
REVOKE ALL PRIVILEGES ON * . * FROM 'nerdygadgets_delete'@'localhost';
GRANT SELECT, DELETE ON * . * TO 'nerdygadgets_delete'@'localhost';

# Storage engines changes
ALTER TABLE `nerdygadgets`.`coldroomtemperatures` ENGINE = MEMORY;

CREATE TABLE `nerdygadgets`.`buyinggroups_archive_old` AS (SELECT * FROM `nerdygadgets`.`buyinggroups_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`buyinggroups_archive`;
CREATE TABLE `nerdygadgets`.`buyinggroups_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`buyinggroups_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`buyinggroups_archive_old`;

CREATE TABLE `nerdygadgets`.`cities_archive_old` AS (SELECT * FROM `nerdygadgets`.`cities_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`cities_archive`;
CREATE TABLE `nerdygadgets`.`cities_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`cities_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`cities_archive_old`;

CREATE TABLE `nerdygadgets`.`coldroomtemperatures_archive_old` AS (SELECT * FROM `nerdygadgets`.`coldroomtemperatures_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`coldroomtemperatures_archive`;
CREATE TABLE `nerdygadgets`.`coldroomtemperatures_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`coldroomtemperatures_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`coldroomtemperatures_archive_old`;

CREATE TABLE `nerdygadgets`.`colors_archive_old` AS (SELECT * FROM `nerdygadgets`.`colors_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`colors_archive`;
CREATE TABLE `nerdygadgets`.`colors_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`colors_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`colors_archive_old`;

CREATE TABLE `nerdygadgets`.`countries_archive_old` AS (SELECT * FROM `nerdygadgets`.`countries_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`countries_archive`;
CREATE TABLE `nerdygadgets`.`countries_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`countries_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`countries_archive_old`;

CREATE TABLE `nerdygadgets`.`customercategories_archive_old` AS (SELECT * FROM `nerdygadgets`.`customercategories_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`customercategories_archive`;
CREATE TABLE `nerdygadgets`.`customercategories_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`customercategories_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`customercategories_archive_old`;

CREATE TABLE `nerdygadgets`.`customers_archive_old` AS (SELECT * FROM `nerdygadgets`.`customers_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`customers_archive`;
CREATE TABLE `nerdygadgets`.`customers_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`customers_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`customers_archive_old`;

CREATE TABLE `nerdygadgets`.`deliverymethods_archive_old` AS (SELECT * FROM `nerdygadgets`.`deliverymethods_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`deliverymethods_archive`;
CREATE TABLE `nerdygadgets`.`deliverymethods_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`deliverymethods_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`deliverymethods_archive_old`;

CREATE TABLE `nerdygadgets`.`packagetypes_archive_old` AS (SELECT * FROM `nerdygadgets`.`packagetypes_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`packagetypes_archive`;
CREATE TABLE `nerdygadgets`.`packagetypes_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`packagetypes_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`packagetypes_archive_old`;

CREATE TABLE `nerdygadgets`.`paymentmethods_archive_old` AS (SELECT * FROM `nerdygadgets`.`paymentmethods_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`paymentmethods_archive`;
CREATE TABLE `nerdygadgets`.`paymentmethods_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`paymentmethods_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`paymentmethods_archive_old`;

CREATE TABLE `nerdygadgets`.`people_archive_old` AS (SELECT * FROM `nerdygadgets`.`people_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`people_archive`;
CREATE TABLE `nerdygadgets`.`people_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`people_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`people_archive_old`;

CREATE TABLE `nerdygadgets`.`stateprovinces_archive_old` AS (SELECT * FROM `nerdygadgets`.`stateprovinces_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`stateprovinces_archive`;
CREATE TABLE `nerdygadgets`.`stateprovinces_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`stateprovinces_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`stateprovinces_archive_old`;

CREATE TABLE `nerdygadgets`.`stockgroups_archive_old` AS (SELECT * FROM `nerdygadgets`.`stockgroups_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`stockgroups_archive`;
CREATE TABLE `nerdygadgets`.`stockgroups_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`stockgroups_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`stockgroups_archive_old`;

CREATE TABLE `nerdygadgets`.`stockitems_archive_old` AS (SELECT * FROM `nerdygadgets`.`stockitems_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`stockitems_archive`;
CREATE TABLE `nerdygadgets`.`stockitems_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`stockitems_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`stockitems_archive_old`;

CREATE TABLE `nerdygadgets`.`suppliercategories_archive_old` AS (SELECT * FROM `nerdygadgets`.`suppliercategories_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`suppliercategories_archive`;
CREATE TABLE `nerdygadgets`.`suppliercategories_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`suppliercategories_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`suppliercategories_archive_old`;

CREATE TABLE `nerdygadgets`.`suppliers_archive_old` AS (SELECT * FROM `nerdygadgets`.`suppliers_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`suppliers_archive`;
CREATE TABLE `nerdygadgets`.`suppliers_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`suppliers_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`suppliers_archive_old`;

CREATE TABLE `nerdygadgets`.`transactiontypes_archive_old` AS (SELECT * FROM `nerdygadgets`.`transactiontypes_archive`);
DROP TABLE IF EXISTS `nerdygadgets`.`transactiontypes_archive`;
CREATE TABLE `nerdygadgets`.`transactiontypes_archive` ENGINE = ARCHIVE AS (SELECT * FROM `nerdygadgets`.`transactiontypes_archive_old`);
DROP TABLE IF EXISTS `nerdygadgets`.`transactiontypes_archive_old`;