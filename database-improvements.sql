/**
  Make all primary keys auto increment.
 */
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

