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
        AND YEAR(OrderDate) > 2019
        ORDER BY OrderID DESC;
    ', ['peopleID' => $people]);
}

/**
 * Gets orders for a given customer.
 *
 * @param int $people
 *   The id of the people.
 * @param string $date
 *   The date.
 *
 * @return array
 *   The orders.
 */
function getOrdersByCustomerByDate(int $people, string $date) {
    return select('
        SELECT OrderID, OrderDate, ExpectedDeliveryDate  
        FROM orders
        JOIN PrivateCustomer ON PrivateCustomerID = CustomerID
        WHERE PeopleID = :peopleID
        AND YEAR(OrderDate) > 2019
        AND DATE(OrderDate) = :date
        ORDER BY OrderID DESC;
    ', ['peopleID' => $people, 'date' => $date]);
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

/**
 * Creates an order.
 *
 * @param int $customerId
 *   The customer id.
 * @param string $currentDate
 *   The current date.
 * @param string $deliveryDate
 *   The delivery date.
 * @param PDO|null $connection
 *   The database connection.
 *
 * @return int
 *   The id of the order.
 */
function createOrder(int $customerId, string $currentDate, string $deliveryDate, PDO $connection = null) {
    return insert("orders", [
        "CustomerId" => $customerId,
        "SalespersonPersonID" => "2",
        "ContactPersonID" => "3032",
        "OrderDate" => $currentDate,
        "ExpectedDeliveryDate" => $deliveryDate,
        "IsUndersupplyBackordered" => 0,
        "LastEditedBy" => 7
    ], $connection);
}

/**
 * Creates an order line.
 *
 * @param int $orderId
 *   The order id.
 * @param array $product
 *   The product.
 * @param int $productAmount
 *   The product amount.
 * @param string $currentDate
 *   The current date.
 * @param PDO|null $connection
 *   The connection.
 */
function createOrderLine(int $orderId, array $product, int $productAmount, string $currentDate, PDO $connection = null) {
    $currentQuantity = (int) ($product["QuantityOnHandRaw"] ?? 0);

    insert("orderlines", [
        "OrderID" => $orderId,
        "StockItemID" => $product['StockItemID'] ?? null,
        "Description" => $product["StockItemName"] ?? null,
        "PackageTypeID" => '7',
        "Quantity" => $productAmount,
        "UnitPrice" => $product["SellPrice"] ?? null,
        "TaxRate" => '15',
        "PickedQuantity" => $productAmount,
        "PickingCompletedWhen" => $currentDate,
        "LastEditedBy" => "4"
    ], $connection);

    update("stockitemholdings", [
        "QuantityOnHand" => $currentQuantity - $productAmount,
    ], [
        "StockItemId" => $product['StockItemID'] ?? null,
    ], $connection);
}