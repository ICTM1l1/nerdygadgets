<?php
require_once __DIR__ . "/../Src/header.php";

csrfValidate(getCurrentUrl());

$cart = getCart();

$searchString = getFormDataGet('search_string');
$categoryId = (int) getFormDataGet('category_id');
$products = null;

$sortOnPage = getFormDataGet('sort', 'price_low_high');
$productsOnPage = (int) getFormDataGet('products_on_page', '25');
$pageNumber = (int) getFormDataGet('page_number');

$queryBuildResult = "";
switch ($sortOnPage) {
    case 'price_high_low':
        $sort = 'SellPrice DESC';
        $sortName = 'price_high_low';
        break;
    case 'name_low_high':
        $sort = 'StockItemName';
        $sortName = 'name_low_high';
        break;
    case 'name_high_low';
        $sort = 'StockItemName DESC';
        $sortName = 'name_high_low';
        break;
    case 'price_low_high':
        $sort = 'SellPrice';
        $sortName = 'price_low_high';
        break;
    default:
        $sort = 'SellPrice';
        $sortName = 'price_low_high';
}

$searchValues = explode(' ', $searchString);

$queryBuildResult = '';
if ($searchString !== '') {
    $countedSearchValues = count($searchValues);
    for ($i = 0; $i < $countedSearchValues; $i++) {
        if ($i !== 0) {
            $queryBuildResult .= 'AND ';
        }
        $queryBuildResult .= 'SI.SearchDetails LIKE '%$searchValues[$i]%' ';
    }
    if ($queryBuildResult !== '') {
        $queryBuildResult .= ' OR ';
    }

    if (!empty($searchString)) {
        $queryBuildResult .= "SI.StockItemID = '$searchString'"; /*TODO: $searchString is direct user-input, sanitize for safety*/
    }
}

$offset = $pageNumber * $productsOnPage;

$showStockLevel = 1000;
if (empty($categoryId)) {
    if ($queryBuildResult !== '') {
        $queryBuildResult = 'WHERE {$queryBuildResult}';
    }

    $products = getProducts($queryBuildResult, $sort, $showStockLevel, $productsOnPage, $offset);
    $amountProducts = getProductsAmount($queryBuildResult);
} else {
    if ($queryBuildResult !== '') {
        $queryBuildResult .= ' AND ';
    }

    $products = getProductsForCategoryWithFilter($queryBuildResult, $sort, $showStockLevel, $categoryId, $productsOnPage, $offset);
    $amountProducts = getProductsAmountForCategoryWithFilter($queryBuildResult, $categoryId);
}

$amountOfPages = 0;
if ($amountProducts !== 0) {
    $amountOfPages = ceil($amountProducts / $productsOnPage);
}

if ($id = getFormDataPost('Add_Cart', NULL)) {
    $cart->addItem($id);
    redirect(getCurrentUrl());
}
elseif ($id = getFormDataPost('Del_Cart', NULL)) {
    $cart->removeItem($id);
    redirect(getCurrentUrl());
}
?>
<div id="FilterFrame"><h2 class="FilterText"><i class="fas fa-filter"></i> Filteren </h2>
    <form method="get" action="<?= getCurrentUrl() ?>">
        <input type="hidden" name="category_id" id="category_id" value="<?= $categoryId ?>">

        <div id="FilterOptions">
            <div class="form-group">
                <label for="search_string" class="h4 FilterTopMargin">
                    <i class="fas fa-search"></i> Zoeken
                </label>
                <input type="text" name="search_string" id="search_string" value="<?= $searchString ?>" class="form-submit">
            </div>

            <div class="form-group">
                <label for="products_on_page" class="h4 FilterTopMargin">
                    <i class="fas fa-list-ol"></i> Aantal producten op pagina
                </label>
                <select class="submit-form-on-change" name="products_on_page" id="products_on_page">
                    <option value="25" <?= $productsOnPage === 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= $productsOnPage === 50 ? 'selected' : '' ?>>50</option>
                    <option value="75" <?= $productsOnPage === 75 ? 'selected' : '' ?>>75</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sort" class="h4 FilterTopMargin"><i class="fas fa-sort"></i> Sorteren</label>
                <select class="submit-form-on-change" name="sort" id="sort">
                    <option value="price_low_high" <?= $sortName === 'price_low_high' ? 'selected' : '' ?>>
                        Prijs oplopend
                    </option>
                    <option value="price_high_low" <?= $sortName === 'price_high_low' ? 'selected' : '' ?>>
                        Prijs aflopend
                    </option>
                    <option value="name_low_high" <?= $sortName === 'name_low_high' ? 'selected' : '' ?>>
                        Naam oplopend
                    </option>
                    <option value="name_high_low" <?= $sortName === 'name_high_low' ? 'selected' : '' ?>>
                        Naam aflopend
                    </option>
                </select>
            </div>

            <div class="form-group mt-4">
                <a type="button" href="<?= getUrl('browse.php?category_id=' . $categoryId) ?>" class="button button-danger float-left">
                    Reset
                </a>

                <button type="submit" class="button float-right btn-success">
                    Filter
                </button>
            </div>
    </form>
</div>
</div>
<div id="ResultsArea" class="Browse">
    <?php if (!empty($products)) : ?>
        <div class="products-view">
            <?php foreach ($products as $product) :
                $productInCart = $cart->getItemCount($product['StockItemID' ?? 0]) > 0;
                $quantityOnHandRaw = (int) ($product['QuantityOnHandRaw'] ?? 0);
                ?>
                <a class="ListItem" href='<?= getUrl('view.php?id=' . $product['StockItemID'] ?? 0) ?>'>
                    <div id="ProductFrame">
                        <?php if (isset($product['ImagePath'])) : ?>
                            <div class="ImgFrame"
                                 style="background-image: url('<?= getAssetUrl('StockItemIMG/' . $product['ImagePath'] ?? '') ?>'); background-size: 230px; background-repeat: no-repeat; background-position: center;"></div>
                        <?php elseif (isset($product['BackupImagePath'])) : ?>
                            <div class="ImgFrame"
                                 style="background-image: url('<?= getAssetUrl('StockGroupIMG/' . $product['BackupImagePath'] ?? '') ?>'); background-size: cover;"></div>
                        <?php endif; ?>

                        <div id="StockItemFrameRight">
                            <div class="CenterPriceLeftChild">
                                <form class="text-center" style="margin-top: 65px;" method="post" action="<?= getCurrentUrl() ?>">
                                    <?php if ($productInCart) : ?>
                                        <button type="submit" class="btn btn-outline-danger w-100"
                                                data-confirm="Weet u zeker dat u `<?= replaceDoubleQuotesForWhiteSpaces($product['StockItemName'] ?? '') ?>` wilt verwijderen?"
                                                name="Del_Cart" value="<?= $product['StockItemID'] ?? 0 ?>">
                                            <i class="fas fa-shopping-cart h1">-</i>
                                            <i class="far fa-trash-alt h1"></i>
                                        </button>
                                    <?php else : ?>
                                        <button type="submit" class="btn btn-outline-success w-100"
                                                name="Add_Cart" value="<?= $product['StockItemID'] ?? 0 ?>"
                                            <?= $quantityOnHandRaw <= 0 ? 'disabled' : '' ?>>
                                            <i class="fas fa-cart-plus h1"></i>
                                        </button>
                                    <?php endif; ?>
                                    <input type="hidden" name="token" value="<?=csrfGetToken()?>"/>
                                </form>
                                <h1 class="StockItemPriceText">
                                    &euro; <?= priceFormat($product['SellPrice'] ?? 0) ?>
                                </h1>
                                <h6>Inclusief BTW </h6>
                            </div>
                        </div>
                        <h1 class="StockItemID">Artikelnummer: <?= $product['StockItemID'] ?? 0 ?></h1>
                        <p class="StockItemName"><?= $product['StockItemName'] ?? '' ?></p>
                        <p class="StockItemComments"><?= $product['MarketingComments'] ?? '' ?></p>
                        <?php
                        $averageScore = round(getReviewAverageByProduct($product['StockItemID']));
                        if ($averageScore > 0) : ?>
                            <h3 style="color: goldenrod;"><?=getRatingStars($averageScore)?></h3>
                        <?php endif; ?>
                        <?php if ($quantityOnHandRaw <= 0) : ?>
                            <h4 class="ItemQuantity text-danger">
                                Dit product is niet op voorraad.
                            </h4>
                        <?php else: ?>
                            <h4 class="ItemQuantity"><?= $product['QuantityOnHand'] ?? 0 ?></h4>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="pagination-container">
            <form id="PageSelector" method="get" action="<?= getCurrentUrl() ?>">
                <input type="hidden" name="search_string" id="search_string" value="<?= $searchString ?>">
                <input type="hidden" name="category_id" id="category_id" value="<?= $categoryId ?>">
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
require_once __DIR__ . '/../Src/footer.php';
?>
