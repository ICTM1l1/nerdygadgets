/**
  Make all primary keys auto increment.
 */
SET FOREIGN_KEY_CHECKS = 0;

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

ALTER TABLE `buyinggroups`
CHANGE COLUMN `BuyingGroupID` `BuyingGroupID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cities`
CHANGE COLUMN `CityID` `CityID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `colors`
CHANGE COLUMN `ColorID` `ColorID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `countries`
CHANGE COLUMN `CountryID` `CountryID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `customercategories`
CHANGE COLUMN `CustomerCategoryID` `CustomerCategoryID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `customertransactions`
CHANGE COLUMN `CustomerTransactionID` `CustomerTransactionID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `deliverymethods`
CHANGE COLUMN `DeliveryMethodID` `DeliveryMethodID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `invoicelines`
CHANGE COLUMN `InvoiceLineID` `InvoiceLineID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `invoices`
CHANGE COLUMN `InvoiceID` `InvoiceID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `packagetypes`
CHANGE COLUMN `PackageTypeID` `PackageTypeID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `paymentmethods`
CHANGE COLUMN `PaymentMethodID` `PaymentMethodID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `purchaseorderlines`
CHANGE COLUMN `PurchaseOrderLineID` `PurchaseOrderLineID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `purchaseorders`
CHANGE COLUMN `PurchaseOrderID` `PurchaseOrderID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `specialdeals`
CHANGE COLUMN `SpecialDealID` `SpecialDealID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stateprovinces`
CHANGE COLUMN `StateProvinceID` `StateProvinceID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stockgroups`
CHANGE COLUMN `StockGroupID` `StockGroupID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stockitemholdings`
CHANGE COLUMN `StockItemID` `StockItemID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stockitemimages`
CHANGE COLUMN `StockItemID` `StockItemID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stockitems`
CHANGE COLUMN `StockItemID` `StockItemID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stockitemstockgroups`
CHANGE COLUMN `StockItemStockGroupID` `StockItemStockGroupID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stockitemtransactions`
CHANGE COLUMN `StockItemTransactionID` `StockItemTransactionID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `suppliercategories`
CHANGE COLUMN `SupplierCategoryID` `SupplierCategoryID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `suppliers`
CHANGE COLUMN `SupplierID` `SupplierID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `suppliertransactions`
CHANGE COLUMN `SupplierTransactionID` `SupplierTransactionID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `systemparameters`
CHANGE COLUMN `SystemParameterID` `SystemParameterID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `transactiontypes`
CHANGE COLUMN `TransactionTypeID` `TransactionTypeID` INT(11) NOT NULL AUTO_INCREMENT;

SET FOREIGN_KEY_CHECKS = 1;

/**
    Triggers.
 */
#Trigger on people insert, check if email is valid

DELIMITER //
CREATE TRIGGER correcte_email
    BEFORE INSERT ON people
    FOR EACH ROW
       BEGIN
         IF NEW.EmailAddress NOT LIKE '_%@_%.__%' THEN
          SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email field is not valid';
         END IF;
       END;
//
DELIMITER ;

#Trigger on customer insert, check if postalcode is valid

DELIMITER //
CREATE TRIGGER correcte_postcode
    BEFORE INSERT ON customers
    FOR EACH ROW
       BEGIN
         IF NEW.PostalPostalCode NOT LIKE '[0-9][0-9][0-9][0-9][a-zA-Z][a-zA-Z]' THEN
          SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Postcode is not valid';
         END IF;
       END;
//
DELIMITER ;

#Trigger on orderline insert, call procedure "UpdateProductVoorraad"

DELIMITER //
DROP TRIGGER IF EXISTS orderlineInserted//
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
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Het product is niet voorradig genoeg';
    END IF;
END;
//
DELIMITER ;

/**
    Procedures.
 */
#Procedure to update stock quantity on orderline insert

DELIMITER //
DROP PROCEDURE IF EXISTS UpdateProductVoorraad//
CREATE PROCEDURE UpdateProductVoorraad(StockItemID int(11), Quantity int(11))
BEGIN
    UPDATE stockitemholdings AS s
    SET s.QuantityOnHand = s.QuantityOnHand - Quantity
    WHERE s.stockitemid = stockitemid;
END;
//
DELIMITER ;

/**
  Performance improvements.
 */


/**
  Authorisation.
 */
DROP USER'nerdygadgets_read'@'localhost';
DROP USER'nerdygadgets_create'@'localhost';
DROP USER'nerdygadgets_update'@'localhost';
DROP USER'nerdygadgets_create_or_update'@'localhost';
DROP USER'nerdygadgets_delete'@'localhost';

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

/**
  Database rules additions.
 */


/**
  Storage engines changes.
 */

ALTER TABLE coldroomtemperatures ENGINE = MEMORY;

/**
  Transactions.
 */

