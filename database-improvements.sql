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


/**
  Transactions.
 */

