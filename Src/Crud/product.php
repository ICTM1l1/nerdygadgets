<?php

function getProduct(int $product_id) {
    return selectFirst("SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            CONCAT('Voorraad: ',QuantityOnHand)AS QuantityOnHand,
            SearchDetails, 
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = :stockitemid
            GROUP BY StockItemID", ['stockitemid' => $product_id]);
}

function getProductWithImage(int $product_id) {
    return selectFirst("SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            CONCAT('Voorraad: ',QuantityOnHand)AS QuantityOnHand,
            SearchDetails, 
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = :stockitemid
            AND SI.StockItemID IN (SELECT SIMG.StockItemID FROM stockitemimages SIMG)
            GROUP BY StockItemID", ['stockitemid' => $product_id]);
}

function getProductImages(int $product_id) {
    return select("
                SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = :stockitemid", ['stockitemid' => $product_id]);
}

function getProducts(string $queryBuildResult, string $sort, int $showStockLevel, int $productsOnPage, int $offset) {
    return select("
                SELECT SI.StockItemID, SI.StockItemName, SI.MarketingComments, ROUND(TaxRate * RecommendedRetailPrice / 100 + RecommendedRetailPrice,2) as SellPrice,
                (CASE WHEN (SIH.QuantityOnHand) >= :showStockLevel THEN 'Ruime voorraad beschikbaar.' ELSE CONCAT('Voorraad: ',QuantityOnHand) END) AS QuantityOnHand, 
                (SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = SI.StockItemID LIMIT 1) as ImagePath,
                (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath
                FROM stockitems SI
                JOIN stockitemholdings SIH USING(stockitemid)
                {$queryBuildResult}
                GROUP BY StockItemID
                ORDER BY {$sort}
                LIMIT :limit OFFSET :offset", ['showStockLevel' => $showStockLevel, 'limit' => $productsOnPage, 'offset' => $offset]);
}

function getProductsAmount(string $queryBuildResult = '') {
    return selectFirst("
            SELECT count(*)
            FROM stockitems SI
            {$queryBuildResult}");
}

function getProductsForCategory(string $queryBuildResult, string $sort, int $showStockLevel, int $categoryID, int $productsOnPage, int $offset) {
    return select("
                SELECT SI.StockItemID, SI.StockItemName, SI.MarketingComments, 
                ROUND(SI.TaxRate * SI.RecommendedRetailPrice / 100 + SI.RecommendedRetailPrice,2) as SellPrice, 
                (CASE WHEN (SIH.QuantityOnHand) >= :showStockLevel THEN 'Ruime voorraad beschikbaar.' ELSE CONCAT('Voorraad: ',QuantityOnHand) END) AS QuantityOnHand,
                (SELECT ImagePath FROM stockitemimages WHERE StockItemID = SI.StockItemID LIMIT 1) as ImagePath,
                (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath           
                FROM stockitems SI 
                JOIN stockitemholdings SIH USING(stockitemid)
                JOIN stockitemstockgroups USING(StockItemID)
                JOIN stockgroups ON stockitemstockgroups.StockGroupID = stockgroups.StockGroupID
                WHERE {$queryBuildResult} :categoryId IN (SELECT StockGroupID FROM stockitemstockgroups WHERE StockItemID = SI.StockItemID)
                GROUP BY StockItemID
                ORDER BY {$sort}
                LIMIT :limit OFFSET :offset", ['showStockLevel' => $showStockLevel, 'categoryId' => $categoryID, 'limit' => $productsOnPage, 'offset' => $offset]);
}

function getProductsAmountForCategory(string $queryBuildResult, int $categoryID) {
    return selectFirst("
                SELECT count(*)
                FROM stockitems SI 
                WHERE {$queryBuildResult} :categoryId IN (SELECT SS.StockGroupID FROM stockitemstockgroups SS WHERE SS.StockItemID = SI.StockItemID)",
        ['categoryId' => $categoryID]);
}