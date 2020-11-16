<?php

/**
 * Gets all categories.
 *
 * @return array
 *   The found categories.
 */
function getCategories() {
    return select("
                SELECT StockGroupID, StockGroupName, ImagePath
                FROM stockgroups 
                WHERE StockGroupID IN (SELECT StockGroupID FROM stockitemstockgroups) 
                AND ImagePath IS NOT NULL
                ORDER BY StockGroupID ASC");
}