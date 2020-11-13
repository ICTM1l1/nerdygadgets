<?php
require_once __DIR__ . "/../Src/header.php";

$searchString = get_form_data_get('search_string');
$categoryID = (int) get_form_data_get('category_id');
$products = null;

$sortOnPage = get_form_data_get('sort', 'price_low_high');
$productsOnPage = (int) get_form_data_get('products_on_page', '25');
$pageNumber = (int) get_form_data_get('page_number');

$queryBuildResult = "";
switch ($sortOnPage) {
    case "price_high_low":
        $sort = "SellPrice DESC";
        $sortName = "price_high_low";
        break;
    case "name_low_high":
        $sort = "StockItemName";
        $sortName = "name_low_high";
        break;
    case "name_high_low";
        $sort = "StockItemName DESC";
        $sortName = "name_high_low";
        break;
    case "price_low_high":
        $sort = "SellPrice";
        $sortName = "price_low_high";
        break;
    default:
        $sort = "SellPrice";
        $sortName = "price_low_high";
}

$searchValues = explode(" ", $searchString);

$queryBuildResult = '';
if ($searchString !== '') {
    $countedSearchValues = count($searchValues);
    for ($i = 0; $i < $countedSearchValues; $i++) {
        if ($i !== 0) {
            $queryBuildResult .= "AND ";
        }
        $queryBuildResult .= "SI.SearchDetails LIKE '%$searchValues[$i]%' ";
    }
    if ($queryBuildResult !== "") {
        $queryBuildResult .= " OR ";
    }

    if (!empty($searchString)) {
        $queryBuildResult .= "SI.StockItemID = '$searchString'";
    }
}

$offset = $pageNumber * $productsOnPage;

$showStockLevel = 1000;
if (empty($categoryID)) {
    if ($queryBuildResult !== '') {
        $queryBuildResult = "WHERE {$queryBuildResult}";
    }

    $products = getProducts($queryBuildResult, $sort, $showStockLevel, $productsOnPage, $offset);
    $amountProducts = getProductsAmount($queryBuildResult);
} else {
    if ($queryBuildResult !== '') {
        $queryBuildResult .= " AND ";
    }

    $products = getProductsForCategory($queryBuildResult, $sort, $showStockLevel, $categoryID, $productsOnPage, $offset);
    $amountProducts = getProductsAmountForCategory($queryBuildResult, $categoryID);
}

$amountOfPages = 0;
if ($amountProducts !== 0) {
    $amountOfPages = ceil($amountProducts / $productsOnPage);
}
?>
<div id="FilterFrame"><h2 class="FilterText"><i class="fas fa-filter"></i> Filteren </h2>
    <form method="get" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <input type="hidden" name="category_id" id="category_id" value="<?= $categoryID ?>">

        <div id="FilterOptions">
            <div class="form-group">
                <h4 class="FilterTopMargin"><i class="fas fa-search"></i> Zoeken</h4>
                <input type="text" name="search_string" id="search_string" value="<?= $searchString ?>" class="form-submit">
            </div>

            <div class="form-group">
                <h4 class="FilterTopMargin"><i class="fas fa-list-ol"></i> Aantal producten op pagina</h4>
                <select name="products_on_page" id="products_on_page" onchange="this.form.submit()">>
                    <option value="25" <?= $productsOnPage === 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= $productsOnPage === 50 ? 'selected' : '' ?>>50</option>
                    <option value="75" <?= $productsOnPage === 75 ? 'selected' : '' ?>>75</option>
                </select>
            </div>

            <div class="form-group">
                <h4 class="FilterTopMargin"><i class="fas fa-sort"></i> Sorteren</h4>
                <select name="sort" id="sort" onchange="this.form.submit()">>
                    <option value="price_low_high" <?= $sortName === "price_low_high" ? 'selected' : '' ?>>
                        Prijs oplopend
                    </option>
                    <option value="price_high_low" <?= $sortName === "price_high_low" ? 'selected' : '' ?>>
                        Prijs aflopend
                    </option>
                    <option value="name_low_high" <?= $sortName === "name_low_high" ? 'selected' : '' ?>>
                        Naam oplopend
                    </option>
                    <option value="name_high_low" <?= $sortName === "name_high_low" ? 'selected' : '' ?>>
                        Naam aflopend
                    </option>
                </select>
            </div>
    </form>
</div>
</div>
<div id="ResultsArea" class="Browse">
    <?php if (!empty($products)) : ?>
        <div class="products-view">
            <?php foreach ($products as $product) : ?>
                <a class="ListItem" href='view.php?id=<?= $product['StockItemID'] ?? '' ?>'>
                    <div id="ProductFrame">
                        <?php if (isset($product['ImagePath'])) : ?>
                            <div class="ImgFrame"
                                 style="background-image: url('<?= get_asset_url('StockItemIMG/' . $product['ImagePath'] ?? '') ?>'); background-size: 230px; background-repeat: no-repeat; background-position: center;"></div>
                        <?php elseif (isset($product['BackupImagePath'])) : ?>
                            <div class="ImgFrame"
                                 style="background-image: url('<?= get_asset_url('StockGroupIMG/' . $product['BackupImagePath'] ?? '') ?>'); background-size: cover;"></div>
                        <?php endif; ?>

                        <div id="StockItemFrameRight">
                            <div class="CenterPriceLeftChild">
                                <h1 class="StockItemPriceText">
                                    &euro; <?= number_format($product["SellPrice"] ?? 0, 2, ',', '.') ?>
                                </h1>
                                <h6>Inclusief BTW </h6>
                            </div>
                        </div>
                        <h1 class="StockItemID">Artikelnummer: <?= $product["StockItemID"] ?? 0 ?></h1>
                        <p class="StockItemName"><?= $product["StockItemName"] ?? '' ?></p>
                        <p class="StockItemComments"><?= $product["MarketingComments"] ?? '' ?></p>
                        <h4 class="ItemQuantity"><?= $product["QuantityOnHand"] ?? '' ?></h4>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="pagination-container">
            <form id="PageSelector">
                <input type="hidden" name="search_string" id="search_string" value="<?= $searchString ?>">
                <input type="hidden" name="category_id" id="category_id" value="<?= $categoryID ?>">
                <input type="hidden" name="result_page_numbers" id="result_page_numbers" value="<?= $amountOfPages ?>">
                <input type="hidden" name="products_on_page" id="products_on_page" value="<?= $productsOnPage ?>">
                <input type="hidden" name="sort" id="sort" value="<?= $sort ?>">

                <?php if ($amountOfPages > 0) : ?>
                    <?php for ($x = 1; $x <= $amountOfPages; $x++) : ?>
                        <?php if ($pageNumber === ($x - 1)) : ?>
                            <div id="SelectedPage"><?= $x ?></div>
                        <?php else : ?>
                            <button id="page_number" class="PageNumber" value="<?= $x - 1 ?>" type="submit" name="page_number">
                                <?= $x ?>
                            </button>
                        <?php endif; ?>
                    <?php endfor; ?>
                <?php endif; ?>
            </form>
        </div>
    <?php else : ?>
        <div class="container mt-2">
            <h2 id="emptySearchResults" class="m-auto">
                Helaas, er zijn geen resultaten gevonden.
            </h2>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>
