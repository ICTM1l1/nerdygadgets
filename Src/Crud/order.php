<?php

/**
 * Gets orders for a given customer.
 *
 * @param int $customer_id
 *   The id of the customer.
 *
 * @return array
 *   The orders.
 */
function getOrdersByCustomer(int $customer_id) {
    return select('
        SELECT OrderID, OrderDate, ExpectedDeliveryDate  
        FROM orders
        WHERE CustomerID = :customerID
        ORDER BY OrderDate DESC;
    ', ['customerID' => $customer_id]);
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