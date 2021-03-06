<?php
require_once __DIR__ . '/../Src/header.php';

csrfValidate(getCurrentUrl());

$cart = getCart();

$productId = (int) getFormDataGet('id');
$product = getProduct($productId);
$images = getProductImages($productId);
$reviews = getReviewsForProduct($productId);

$quantityOnHandRaw = (int) ($product['QuantityOnHandRaw'] ?? 0);
$productCustomFields = $product['CustomFields'] ?? null;
$customFields = [];
if (!empty($productCustomFields)) {
    $customFields = json_decode($productCustomFields, true, 512, JSON_THROW_ON_ERROR);
}

$productInCart = $cart->getItemCount($productId) > 0;
if ($id = getFormDataPost('Add_Cart', NULL)) {
    $cart->addItem($id);
    redirect(getCurrentUrl());
}
elseif ($id = getFormDataPost('Min_Cart', NULL)) {
    $cart->decreaseItemCount($id);
    redirect(getCurrentUrl());
}
elseif ($id = getFormDataPost('Increase_Cart', NULL)) {
    $cart->increaseItemCount($id);
    redirect(getCurrentUrl());
}
elseif ($id = getFormDataPost('Del_Cart', NULL)) {
    $cart->removeItem($id);
    redirect(getCurrentUrl());
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
                             style="background-image: url('<?= getAssetUrl('StockItemIMG/' . $images[0]['ImagePath'] ?? '') ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
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
                                            <img alt="Product foto" src="<?= getAssetUrl('StockItemIMG/' . $image['ImagePath'] ?? '') ?>">
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
                         style="background-image: url('<?= getAssetUrl('StockGroupIMG/' . $product['BackupImagePath'] ?? '') ?>'); background-size: cover;"></div>
                <?php endif; ?>

                <h1 class="StockItemID">Artikelnummer: <?= $product['StockItemID'] ?? 0 ?></h1>
                <h2 class="StockItemNameViewSize StockItemName">
                    <a class="text-white" href="<?= getUrl('view.php?id=' . $product['StockItemID'])?>"><?= $product['StockItemName'] ?? '' ?></a>
                </h2>
                <?php
                $averageScore = round(getReviewAverageByProduct($product['StockItemID'] ?? 0));
                if ($averageScore > 0) : ?>
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
                                  action="<?= getCurrentUrl() ?>">
                                <input type="hidden" name="token" value="<?=csrfGetToken()?>"/>
                                <div class="edit-actions w-100 mb-2">
                                    <?php if ($productInCart) : ?>
                                        <button type="submit" class="btn btn-outline-danger mr-2"
                                                name="Min_Cart" value="<?= $productId ?>">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="submit" class="btn btn-outline-success mr-2"
                                                name="Increase_Cart" value="<?= $productId ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>

                                        <p class="h4 font-weight-bold float-right">
                                            <?= $cart->getItemCount($productId) ?>x
                                        </p>

                                        <button class="btn btn-outline-danger float-right w-75 mt-2"
                                                type="submit" name="Del_Cart" value="<?= $productId ?>"
                                                data-confirm="Weet u zeker dat u `<?= replaceDoubleQuotesForWhiteSpaces($product['StockItemName'] ?? '') ?>` wilt verwijderen?">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php else : ?>
                                        <button type="submit" class="btn btn-outline-success w-100"
                                                name="Add_Cart" value="<?= $productId ?>"
                                            <?= $quantityOnHandRaw <= 0 ? 'disabled' : '' ?>>
                                            <i class="fas fa-cart-plus h1"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>

                            <p class="StockItemPriceText">
                                <b>&euro; <?= priceFormat($product['SellPrice'] ?? 0) ?></b>
                            </p>
                            <h6>Inclusief BTW </h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-4 mb-4">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h1>Reviews</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="<?= getUrl('view.php?id=' . $productId) ?>" class="btn btn-success">Terug naar product</a>
                    </div>
                </div>
                <div class="border-bottom border-white"></div>
                <div class="row mt-1">
                    <?php if (empty($reviews)):?>
                    <div class="col-sm-12 mt-3">
                        <h2 class="text-center text-white">Geen reviews voor dit product beschikbaar.</h2>
                    </div>
                    <?php else:?>
                        <div class="col-sm-12">
                            <div class="row d-flex justify-content-center">
                                <?php foreach ($reviews as $review):?>
                                    <div class="col-sm-3 border border-white ml-2 mr-2 mt-3">
                                        <h4><?= getCustomerByPeople($review['PersonID'] ?? '' )['PreferredName'] ?? '' ?></h4>
                                        <div class="row">
                                            <div class="col-sm-6" style="color: goldenrod">
                                                <?= getRatingStars((int)$review['Score'])?>
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                <?= dateTimeFormatShort($review['ReviewDate'])?>
                                            </div>
                                        </div>
                                        <?php if (isset($review['Review'])) : ?>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p><?= $review['Review'] ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
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
require_once __DIR__ . '/../Src/footer.php';
?>