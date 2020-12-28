<?php

/**
 * Gets a product for the given id.
 *
 * @param int $product
 *   The id to search for.
 *
 * @return array
 *   The found product.
 */
function getProduct(int $product) {
    return selectFirst("
        SELECT SI.StockItemID, SI.IsChillerStock AS 'IsChillerStock',
        (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
        StockItemName, SIH.QuantityOnHand AS 'QuantityOnHandRaw',
        CONCAT('Voorraad: ',QuantityOnHand)AS QuantityOnHand,
        SearchDetails, 
        (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
        (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
        FROM stockitems SI 
        JOIN stockitemholdings SIH USING(stockitemid)
        JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
        JOIN stockgroups USING(StockGroupID)
        WHERE SI.stockitemid = :stockitemid
        GROUP BY StockItemID
    ", ['stockitemid' => $product]);
}

/**
 * Gets the images for a product for the given id.
 *
 * @param int $product
 *   The id to search for.
 *
 * @return array
 *   The found product images.
 */
function getProductImages(int $product) {
    return select('
        SELECT ImagePath
        FROM stockitemimages 
        WHERE StockItemID = :stockitemid
    ', ['stockitemid' => $product]);
}

/**
 * Gets the image for a product for the given id.
 *
 * @param int $product
 *   The id to search for.
 *
 * @return array
 *   The found product image.
 */
function getProductImage(int $product) {
    return selectFirst('
        SELECT ImagePath
        FROM stockitemimages 
        WHERE StockItemID = :stockitemid
    ', ['stockitemid' => $product]);
}

/**
 * Gets all products.
 *
 * @param string $queryBuildResult
 *   Provides extra select query statements.
 * @param string $sort
 *   The sorting of the products.
 * @param int $showStockLevel
 *   The minimum for showing the stock level.
 * @param int $productsOnPage
 *   The amount of products to show on one page.
 * @param int $offset
 *   The offset to start selecting from.
 *
 * @return array
 *   The found products.
 */
function getProducts(string $queryBuildResult, string $sort, int $showStockLevel, int $productsOnPage, int $offset) {
    return select("
        SELECT SI.StockItemID, SI.StockItemName, SI.MarketingComments, ROUND(TaxRate * RecommendedRetailPrice / 100 + RecommendedRetailPrice,2) as SellPrice,
        SIH.QuantityOnHand AS 'QuantityOnHandRaw',
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
        LIMIT :limit OFFSET :offset
    ", ['showStockLevel' => $showStockLevel, 'limit' => $productsOnPage, 'offset' => $offset]);
}

/**
 * Gets the amount of products.
 *
 * @param string $queryBuildResult
 *   Provides extra select query statements.
 *
 * @return int
 *   The amount of products.
 */
function getProductsAmount(string $queryBuildResult = '') {
    $productsAmount = selectFirst("
        SELECT count(*)
        FROM stockitems SI
        {$queryBuildResult}
    ");

    return $productsAmount['count(*)'] ?? 0;
}

/**
 * Gets all products for a specified category.
 *
 * @param string $queryBuildResult
 *   Provides extra select query statements.
 * @param string $sort
 *   The sorting of the products.
 * @param int $showStockLevel
 *   The minimum for showing the stock level.
 * @param int $category
 *   The category ID to search for.
 * @param int $productsOnPage
 *   The amount of products to show on one page.
 * @param int $offset
 *   The offset to start selecting from.
 *
 * @return array
 *   The found products for the category.
 */
function getProductsForCategoryWithFilter(string $queryBuildResult, string $sort, int $showStockLevel, int $category, int $productsOnPage, int $offset) {
    return select("
        SELECT SI.StockItemID, SI.StockItemName, SI.MarketingComments, SIH.QuantityOnHand AS 'QuantityOnHandRaw',
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
        LIMIT :limit OFFSET :offset
    ", ['showStockLevel' => $showStockLevel, 'categoryId' => $category, 'limit' => $productsOnPage, 'offset' => $offset]);
}

/**
 * Gets the amount of products for a specific category.
 *
 * @param string $queryBuildResult
 *   Provides extra select query statements.
 * @param int $category
 *   The category ID to search for.
 *
 * @return int
 *   The amount of products for the category.
 */
function getProductsAmountForCategoryWithFilter(string $queryBuildResult, int $category) {
    $productsAmount = selectFirst("
        SELECT count(*)
        FROM stockitems SI 
        WHERE {$queryBuildResult} :categoryId IN (SELECT SS.StockGroupID FROM stockitemstockgroups SS WHERE SS.StockItemID = SI.StockItemID)
    ", ['categoryId' => $category]);

    return $productsAmount["count(*)"] ?? 0;
}
/**
 * Gets the IDs of products from category.
 *
 * @param int $category
 *   The category ID to search for.
 *
 * @return array
 *   The randomly found product IDs from category.
 */
function getProductIdsForCategory(int $category) {
    return select('
        SELECT SI.StockItemID
        FROM stockitems SI 
        WHERE :categoryId IN (SELECT SS.StockGroupID FROM stockitemstockgroups SS WHERE SS.StockItemID = SI.StockItemID)
    ', ['categoryId' => $category]);
}

/**
 * Gets a random product id from category.
 *
 * @param int $category
 *   The category ID to search for.
 *
 * @return int
 *   The ID of the randomly found product from category or 0.
 */
function getRandomProductForCategory(int $category){
    $productIds = getProductIdsForCategory($category);
    if (!empty($productIds)) {
        $selectedProduct = $productIds[array_rand($productIds)];
    }

    return $selectedProduct['StockItemID'] ?? 0;
}

/**
 * Gets a random amount of products.
 *
 * @param int $amountOfProducts
 *   The amount of products.
 *
 * @return array
 *   The randomly found products.
 */
function getRandomProducts(int $amountOfProducts) {
    $productPlaceholders = '';
    $productIds = [];
    for ($categoryId = 1; $categoryId <= $amountOfProducts; $categoryId++) {
        $productIds["product_$categoryId"] = getRandomProductForCategory($categoryId);
        $productPlaceholders .= $categoryId !== $amountOfProducts ? ":product_$categoryId, " : ":product_$categoryId";
    }

    return select("
        SELECT SI.StockItemID, 
        (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
        StockItemName, SIH.QuantityOnHand AS 'QuantityOnHandRaw',
        CONCAT('Voorraad: ',QuantityOnHand)AS QuantityOnHand,
        SearchDetails, 
        (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
        (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
        FROM stockitems SI 
        JOIN stockitemholdings SIH USING(stockitemid)
        JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
        JOIN stockgroups USING(StockGroupID)
        WHERE SI.stockitemid IN ($productPlaceholders) 
        AND SI.StockItemID IN (SELECT SIMG.StockItemID FROM stockitemimages SIMG)
        GROUP BY StockItemID
    ", $productIds);

}
/**
 * Gets category id of product.
 *
 * @param int $product
 *   The id of the product
 *
 * @return array
 *   The array of category ids the product is in
 */
function getCategoryIdForProduct(int $product) {
    return select('
        SELECT StockGroupID 
        FROM stockitemstockgroups 
        WHERE StockItemID = :stockitemid
    ', ['stockitemid' => $product]);
}

/**
 * Gets the image for a product for the given id.
 *
 * @param int $product
 *   The id to search for.
 *
 * @return array
 *   The found product image.
 */
function getBackupProductImage(int $product) {
    return selectFirst('
        SELECT ImagePath as BackupImagePath
        FROM stockgroups 
        JOIN stockitemstockgroups USING(StockGroupID) 
        WHERE StockItemID = :StockItemID 
        LIMIT 1
    ', ['StockItemID' => $product]);
}