<?php
require_once __DIR__ . '/../Src/header.php';

csrfValidate(getCurrentUrl());

$text = getFormDataPost('review-text');
$score = (int) getFormDataPost('score-value', '0');

if (isset($_POST['review'])) {
    $valid = true;
    if (!(bool) sessionGet('LoggedIn', false)) {
        addUserError('U moet ingelogd zijn om een review achter te kunnen laten.');
        $valid = false;
    }

    $reviewProductId = (int) getFormDataPost('itemid', '0');
    $personId = (int) sessionGet('personID', 0);
    $orders = getOrdersByCustomer($personId);

    if (productWasReviewedByCustomer($reviewProductId, $personId)) {
        addUserError('U kan een product maar 1 keer reviewen.');
        $valid = false;
    }

    $ordered = false;
    foreach ($orders as $order) {
        $orderLines = getOrderLinesByOrder($order['OrderID'] ?? 0);
        foreach ($orderLines as $orderLine) {
            if ((int) ($orderLine['StockItemID'] ?? '0') == $reviewProductId) {
                $ordered = true;
                break;
            }

            if ($ordered) {
                break;
            }
        }
    }

    if (!$ordered) {
        addUserError('U moet het product besteld hebben voordat u een review achter kan laten.');
        $valid = false;
    }

    if (strlen($text) > 250) {
        addUserError('De tekst van een review kan niet langer zijn dan 250 tekens.');
        $valid = false;
    }

    if (empty($score) || (1 > $score && $score > 5)) {
        addUserError('Uw beoordeling moet tussen de 1 en de 5 sterren vallen.');
        $valid = false;
    }

    if ($valid) {
        createReviewForProduct($reviewProductId, $personId, $score, $text);
    }
    redirect(getCurrentUrl());
}
elseif(isset($_POST['Delete_Review'])){
    if (!(bool) sessionGet('LoggedIn', false)) {
        addUserError('U moet ingelogd zijn om uw review te kunnen verwijderen.');
    }
    else {
        $reviewedProductId = (int) getFormDataPost('id', '0');
        $personId = (int) sessionGet('personID', 0);
        deleteReview($reviewedProductId, $personId);
    }
    redirect(getCurrentUrl());
}

$cart = getCart();

$productId = (int) getFormDataGet('id');
$product = getProduct($productId);
$images = getProductImages($productId);
$categories = getCategoryIdForProduct($productId);
$reviews = getLimitedReviewsForProduct($productId);
$productReview = getProductReviewByCustomer($productId, (int) sessionGet('personID', 0));

$relatedProductIds = [];
$relatedProductImages = [];

if (!empty($categories)) {
    $countedCategories = count($categories);
    for ($i = 0; $i < 6; $i++) {
        $randomCategories = random_int(0, $countedCategories - 1);
        $relatedProductIds[$i] = getRandomProductForCategory($categories[$randomCategories] ['StockGroupID'] ?? '');

        $image = getProductImages($relatedProductIds[$i] ?? 0);
        $fallbackImage = getBackupProductImage($relatedProductIds[$i] ?? 0);

        $relatedProductImages[$i]['ImagePath'] = $image[0]['ImagePath'] ?? '';
        $relatedProductImages[$i]['BackupImagePath'] = $fallbackImage['BackupImagePath'] ?? '';
    }
}

$quantityOnHandRaw = (int) ($product['QuantityOnHandRaw'] ?? 0);
$productCustomFields = $product['CustomFields'] ?? null;
$customFields = [];
if (!empty($productCustomFields)) {
    $customFields = json_decode($productCustomFields, true, 512, JSON_THROW_ON_ERROR);
}

$productInCart = $cart->getItemCount($productId) > 0;
if ($cartProductId = getFormDataPost('Add_Cart', NULL)) {
    $cart->addItem($cartProductId);
    redirect(getCurrentUrl());
}
elseif ($cartProductId = getFormDataPost('Min_Cart', NULL)) {
    $cart->decreaseItemCount($cartProductId);
    redirect(getCurrentUrl());
}
elseif ($cartProductId = getFormDataPost('Increase_Cart', NULL)) {
    $cart->increaseItemCount($cartProductId);
    redirect(getCurrentUrl());
}
elseif ($cartProductId = getFormDataPost('Del_Cart', NULL)) {
    $cart->removeItem($cartProductId);
    redirect(getCurrentUrl());
}

$temperature = getTemperature();
if (!empty($temperature)) {
    $temperature = 'Temperatuur: ' . ($temperature['Temperature'] ?? 0) . '℃';
}

include __DIR__ . '/../Src/Html/alert.php'; ?>

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
                    <?= $product['StockItemName'] ?? '' ?>
                </h2>
                <?php
                $averageScore = round(getReviewAverageByProduct($product['StockItemID'] ?? 0));
                if ($averageScore > 0) : ?>
                    <h3 class="mt-3" style="color: goldenrod;"><?= getRatingStars($averageScore) ?></h3>
                <?php else : ?>
                    <h3 class="text-white mt-3">Geen reviews</h3>
                <?php endif; ?>
                <?php if (isset($product['IsChillerStock']) && $product['IsChillerStock']) : ?>
                    <?php if (empty($temperature)) : ?>
                        <div class="QuantityText text-danger py-4">
                            Er is geen actuele temperatuur.
                        </div>
                    <?php else: ?>
                        <div class="QuantityText py-4"><?= $temperature ?></div>
                    <?php endif; ?>
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

            <div id="StockItemDescription">
                <h3>Artikel beschrijving</h3>
                <p><?= $product['SearchDetails'] ?? '' ?></p>
            </div>
            <div id="StockItemSpecifications">
                <h3>Artikel specificaties</h3>
                <?php if (is_array($customFields) && !empty($customFields)) : ?>
                    <table>
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Data</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($customFields as $name => $data) : ?>
                            <tr>
                                <td><?= $name ?></td>
                                <td>
                                    <?php if (is_array($data)) : ?>
                                        <?php foreach ($data as $text) : ?>
                                            <?= $text ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <?= $data ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p><?= $product['CustomFields'] ?? '' ?>.</p>
                <?php endif; ?>
            </div>
            <div class="row" id="RelatedProducts">
                <?php foreach($relatedProductIds as $key => $relatedProductId) : ?>
                    <div class="col-sm-2">
                        <?php if (isset($relatedProductImages[$key])) : ?>
                            <?php
                            $relatedImage = $relatedProductImages[$key] ?? [];
                            $imagePath = $relatedImage['ImagePath'] ?? '';
                            $backupImagePath = $relatedImage['BackupImagePath'] ?? '';
                            ?>
                            <a href="<?= getUrl("view.php?id={$relatedProductIds[$key]}") ?>">
                                <?php if (!empty($imagePath)) : ?>
                                    <div class="ImgFrame"
                                         style="background-image: url('<?= getAssetUrl('StockItemIMG/' . $imagePath) ?>');
                                                 background-size: 175px; width: 159px; height: 159px; background-repeat: no-repeat;  margin-bottom: 20%; background-position: center;"></div>
                                <?php elseif (!empty($backupImagePath)) : ?>
                                    <div class="ImgFrame"
                                         style="background-image: url('<?= getAssetUrl('StockGroupIMG/' . $backupImagePath) ?>');
                                                 background-size: cover; width: 159px; height: 159px; "></div>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="container-fluid">
                <div class="row mt-4 mb-4">
                    <div class="col-sm-12 text-left">
                        <?php if ((bool) sessionGet( 'LoggedIn', false)) : ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h2 class="text-white float-left">Schrijf een review</h2>
                                </div>
                                <div class="col-sm-6">
                                    <a href="<?= getUrl('reviews.php?id=' . $productId) ?>"
                                       class="float-right btn btn-success">
                                        Bekijk reviews
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-sm-6 pl-4 pr-4">
                                    <?php if (!empty($productReview)) : ?>
                                        <form action="<?= getCurrentUrl() ?>" class="text-center w-100" method="post">
                                            <input type="hidden" name="token" value="<?= csrfGetToken() ?>"/>
                                            <input type="hidden" name="id" value="<?= $productId ?>"/>

                                            <h4>U heeft een review geplaatst</h4>
                                            <div class="form-group form-row">
                                                <div class="col-sm-12 border border-white mt-3">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h3>
                                                                <?= getCustomerByPeople($productReview['PersonID'] ?? 0 )['PreferredName'] ?? '' ?>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6" style="color: goldenrod">
                                                            <?= getRatingStars((int) ($productReview['Score'] ?? 0))?>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?= dateTimeFormatShort($productReview['ReviewDate'] ?? '')?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p><?= $review['Review'] ?? '' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button name="Delete_Review" class="btn btn-danger ml-auto mr-auto mt-4"
                                                    data-confirm="Weet u zeker dat u uw review wilt verwijderen?">
                                                Review verwijderen
                                            </button>
                                        </form>
                                    <?php else : ?>
                                        <form class="text-center w-100" method="post" action="<?=getCurrentUrl()?>">
                                            <input type="hidden" name="token" value="<?= csrfGetToken() ?>"/>
                                            <input type="hidden" name="itemid" value="<?= $productId ?>">

                                            <div class="form-group form-row">
                                                <label for="score-input" class="col-sm-3 pt-2 mt-1">Score</label>
                                                <div class="score-container col-sm-9 pl-0 ml-0" id="score-container" style="color: goldenrod;">
                                                    <div class="rate pl-0 ml-0">
                                                        <input type="radio" id="star5" name="score-value" value="5" />
                                                        <label for="star5" title="text">5 stars</label>
                                                        <input type="radio" id="star4" name="score-value" value="4" />
                                                        <label for="star4" title="text">4 stars</label>
                                                        <input type="radio" id="star3" name="score-value" value="3" />
                                                        <label for="star3" title="text">3 stars</label>
                                                        <input type="radio" id="star2" name="score-value" value="2" />
                                                        <label for="star2" title="text">2 stars</label>
                                                        <input type="radio" id="star1" name="score-value" value="1" />
                                                        <label for="star1" title="text">1 star</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-row">
                                                <label for="review-text" class="col-sm-3">Review</label>
                                                <textarea id="review-text" name="review-text" autocomplete="off"
                                                          class="form-control count-characters-250 col-sm-9"
                                                          rows="5" maxlength="250"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" id="submit-review"
                                                        class="btn btn-success float-right my-4" name="review">Plaatsen</button>
                                            </div>
                                        </form>
                                    <?php endif;?>
                                </div>
                                <div class="col-sm-6 pl-4 pr-4">
                                    <?php if (!empty($reviews)) : ?>
                                        <div class="row d-flex justify-content-center">
                                            <?php foreach ($reviews as $review) : ?>
                                                <div class="col-sm-12 border border-white mt-3">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h3>
                                                                <?= getCustomerByPeople($review['PersonID'] ?? '' )['PreferredName'] ?? '' ?>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6" style="color: goldenrod">
                                                            <?= getRatingStars((int) ($review["Score"] ?? 0)) ?>
                                                        </div>
                                                        <div class="col-sm-6 text-right">
                                                            <?= dateTimeFormatShort($review["ReviewDate"] ?? '') ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p><?= $review['Review'] ?? '' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    <?php else : ?>
                                        <h2 class="text-center text-white">Geen reviews voor dit product beschikbaar</h2>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else :?>
                            <div class="row">
                                <div class="col-sm-10">
                                    <h2 class="text-white float-left">Log in of registreer om een review achter te laten</h2>
                                </div>
                                <div class="col-sm-2">
                                    <a href="<?= getUrl('reviews.php?id=' . $productId) ?>"
                                       class="float-right btn btn-success">
                                        Bekijk reviews
                                    </a>
                                </div>
                            </div>

                            <div class="row mt-4 mb-4">
                                <div class="col-sm-12 pl-4 pr-4">
                                    <?php if (!empty($reviews)) : ?>
                                        <div class="row d-flex justify-content-center">
                                            <?php foreach ($reviews as $review) : ?>
                                                <div class="col-sm-4 border border-white mt-3">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h3>
                                                                <?= getCustomerByPeople($review['PersonID'] ?? '' )['PreferredName'] ?? '' ?>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6" style="color: goldenrod">
                                                            <?= getRatingStars((int) ($review['Score'] ?? 0)) ?>
                                                        </div>
                                                        <div class="col-sm-6 text-right">
                                                            <?= dateTimeFormatShort($review['ReviewDate'] ?? '') ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p><?= $review['Review'] ?? '' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    <?php else : ?>
                                        <h2 class="text-center text-white">Geen reviews voor dit product beschikbaar</h2>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2>
        <?php endif; ?>
    </div>



<?php
require_once __DIR__ . '/../Src/footer.php';
?>