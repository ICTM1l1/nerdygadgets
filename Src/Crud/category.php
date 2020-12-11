<?php

/**
 * Gets all categories.
 *
 * @return array
 *   The found categories.
 */
function getCategories() {
    return select("
        SELECT SG.StockGroupID, SG.StockGroupName, SG.ImagePath
        FROM stockgroups SG
        JOIN stockitemstockgroups SISG ON SG.StockGroupID = SISG.StockGroupID
        AND SG.ImagePath IS NOT NULL
        GROUP BY SG.StockGroupID
        ORDER BY SG.StockGroupID ASC
    ");
}