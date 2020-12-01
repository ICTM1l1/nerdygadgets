<?php

/**
 * Gets orders for a given customer.
 *
 * @param int $people
 *   The id of the people.
 *
 * @return array
 *   The orders.
 */
function getOrdersByCustomer(int $people) {
    return select('
        SELECT OrderID, OrderDate, ExpectedDeliveryDate  
        FROM orders
        JOIN PrivateCustomer ON PrivateCustomerID = CustomerID
        WHERE PeopleID = :peopleID
        ORDER BY OrderDate DESC;
    ', ['peopleID' => $people]);
}

/**
 * Gets order lines for a given order.
 *
 * @param int $order_id
 *   The id of the order.
 *
 * @return array
 *   The order lines.
 */
function getOrderLinesByOrder(int $order_id) {
    return select('
        SELECT OL.OrderID, OL.Description, OL.Quantity, OL.UnitPrice, OL.TaxRate, OL.UnitPrice * (1 + (OL.TaxRate / 100)) AS SoldPrice,
        (SELECT ImagePath FROM stockitemimages WHERE StockItemID = OL.StockItemID LIMIT 1) AS ImagePath,
        (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = OL.StockItemID LIMIT 1) as BackupImagePath
        FROM orderlines OL
        WHERE OrderID = :orderID
    ', ['orderID' => $order_id]);
}