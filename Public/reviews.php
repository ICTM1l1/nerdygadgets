<?php
require_once __DIR__ . "/../Src/header.php";

csrf_validate(get_current_url());

$cart = get_cart();

$product_id = (int) get_form_data_get('id');
$product = getProduct($product_id);
$images = getProductImages($product_id);
$reviews = getAllReviewsForItem($product_id);

$quantityOnHandRaw = (int) ($product['QuantityOnHandRaw'] ?? 0);
$productCustomFields = $product['CustomFields'] ?? null;
$customFields = [];
if (!empty($productCustomFields)) {
    $customFields = json_decode($productCustomFields, true, 512, JSON_THROW_ON_ERROR);
}

$productInCart = $cart->getItemCount($product_id) > 0;
if ($id = get_form_data_post("Add_Cart", NULL)) {
    $cart->addItem($id);
    redirect(get_current_url());
}
elseif ($id = get_form_data_post("Min_Cart", NULL)) {
    $cart->decreaseItemCount($id);
    redirect(get_current_url());
}
elseif ($id = get_form_data_post("Increase_Cart", NULL)) {
    $cart->increaseItemCount($id);
    redirect(get_current_url());
}
elseif ($id = get_form_data_post("Del_Cart", NULL)) {
    $cart->removeItem($id);
    redirect(get_current_url());
}
?>
    <div id="CenteredContent">
        <?php if (!empty($product)) : ?>
            <?php if (isset($product['Video'])) : ?>
                <div id="VideoFrame">
                    <?= $product['Video'] ?? '' ?>
                </div>
            <?php endif; ?>

            <div id="ArticleHeader">
                <?php if (!empty($images)) : ?>
                    <?php if (count($images) === 1) : ?>
                        <div id="ImageFrame"
                             style="background-image: url('<?= get_asset_url('StockItemIMG/' . $images[0]['ImagePath'] ?? '') ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
                    <?php else : ?>
                        <div id="ImageFrame">
                            <div id="ImageCarousel" class="carousel slide" data-interval="false">
                                <!-- Indicators -->
                                <ul class="carousel-indicators">
                                    <?php foreach ($images as $key => $image) : $key++; ?>
                                        <li data-target="#ImageCarousel"
                                            data-slide-to="<?= $key ?>" <?= (($key === 1) ? 'class="active"' : '') ?>></li>
                                    <?php endforeach; ?>
                                </ul>

                                <!-- The slideshow -->
                                <div class="carousel-inner">
                                    <?php foreach ($images as $key => $image) : $key++; ?>
                                        <div class="carousel-item <?= ($key === 1) ? 'active' : '' ?>">
                                            <img alt="Product foto" src="<?= get_asset_url('StockItemIMG/' . $image['ImagePath'] ?? '') ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Left and right controls -->
                                <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </a>
                                <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <div id="ImageFrame"
                         style="background-image: url('<?= get_asset_url('StockGroupIMG/' . $product['BackupImagePath'] ?? '') ?>'); background-size: cover;"></div>
                <?php endif; ?>

                <h1 class="StockItemID">Artikelnummer: <?= $product["StockItemID"] ?? 0 ?></h1>
                <h2 class="StockItemNameViewSize StockItemName">
                    <a class="text-white" href="<?= get_url("view.php?id=" . $product["StockItemID"])?>"><?= $product['StockItemName'] ?? '' ?></a>
                </h2>
                <?php
                $averageScore = round(getReviewAverageByID($product["StockItemID"]));
                if($averageScore > 0) : ?>
                    <!--<h3 style="color: goldenrod;"><?=round(getReviewAverageByID($product["StockItemID"])) ?: "Geen reviews."?></h3>-->
                    <h3 style="color: goldenrod;"><?=getRatingStars($averageScore)?></h3>
                <?php else : ?>
                    <h3 class="text-white">Geen reviews.</h3>
                <?php endif; ?>
                <?php if ($quantityOnHandRaw <= 0) : ?>
                    <div class="QuantityText text-danger">
                        Dit product is niet op voorraad.
                    </div>
                <?php else: ?>
                    <div class="QuantityText"><?= $product['QuantityOnHand'] ?? 0 ?></div>
                <?php endif; ?>
                <div id="StockItemHeaderLeft">
                    <div class="CenterPriceLeft">
                        <div class="CenterPriceCartButton">
                            <form class="form-inline float-right mt-5 pt-2 w-100" method="post"
                                  action="<?= get_current_url() ?>">
                                <input type="hidden" name="token" value="<?=csrf_get_token()?>"/>
                                <div class="edit-actions w-100 mb-2">
                                    <?php if ($productInCart) : ?>
                                        <button type="submit" class="btn btn-outline-danger mr-2"
                                                name="Min_Cart" value="<?= $product_id ?>">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="submit" class="btn btn-outline-success mr-2"
                                                name="Increase_Cart" value="<?= $product_id ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>

                                        <p class="h4 font-weight-bold float-right">
                                            <?= $cart->getItemCount($product_id) ?>x
                                        </p>

                                        <button class="btn btn-outline-danger float-right w-75 mt-2"
                                                type="submit" name="Del_Cart" value="<?= $product_id ?>"
                                                data-confirm="Weet u zeker dat u `<?= replaceDoubleQuotesForWhiteSpaces($product['StockItemName'] ?? "") ?>` wilt verwijderen?">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php else : ?>
                                        <button type="submit" class="btn btn-outline-success w-100"
                                                name="Add_Cart" value="<?= $product_id ?>"
                                            <?= $quantityOnHandRaw <= 0 ? 'disabled' : '' ?>>
                                            <i class="fas fa-cart-plus h1"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>

                            <p class="StockItemPriceText">
                                <b>&euro; <?= price_format($product['SellPrice'] ?? 0) ?></b>
                            </p>
                            <h6>Inclusief BTW </h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-4 mb-4">
                <div class="row">
                    <div class="col-sm">
                        <h1>Reviews</h1>
                        <hr/>
                    </div>
                </div>
                <div class="row mt-1">
                    <?php if(empty($reviews)):?>
                    <div class="col-sm">
                        <h2 class="text-center text-white">Geen reviews voor dit product beschikbaar.</h2>
                    </div>
                    <?php else:?>
                        <div class="col-sm-12">
                            <div class="row d-flex justify-content-center">
                                <?php foreach($reviews as $review):?>
                                    <div class="col-sm-3 border border-white mr-4 mt-4">
                                        <div class="row">
                                            <div class="col-sm-6 text-left">
                                                <h3 class="mt-2 ml-2"><?= getCustomerByPeople($review["PrivateCustomerID"])["FullName"]?></h3>
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                <?= dateTimeFormatShort($review["ReviewDate"])?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6" style="color: goldenrod">
                                                <?= getRatingStars((int)$review["Score"])?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <p class="mt-2 ml-4"><?= $review["Review"]?></p>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        <?php else : ?>
            <h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2>
        <?php endif; ?>
    </div>



<?php
require_once __DIR__ . "/../Src/footer.php";
?>