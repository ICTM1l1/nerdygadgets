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


/**
  Database rules additions.
 */


/**
  Storage engines changes.
 */


/**
  Transactions.
 */

