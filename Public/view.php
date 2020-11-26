<?php
require_once __DIR__ . "/../Src/header.php";

/** @var Cart $cart */
$cart = session_get("cart");

$product_id = (int) get_form_data_get('id');
$product = getProduct($product_id);
$images = getProductImages($product_id);
$categories = getCategoryIdForProduct($product_id);

$relatedProductIds = [];
$relatedProductImages = [];
for($i = 0; $i < 6; $i++){
    $relatedProductIds[$i] = getRandomProductForCategory($categories[random_int(0, count($categories) - 1)] ['StockGroupID'] ?? '');

    $image = getProductImages($relatedProductIds[$i] ?? 0);
    $fallbackImage = getBackupProductImage($relatedProductIds[$i] ?? 0);

    $relatedProductImages[$i]['ImagePath'] = $image[0]['ImagePath'] ?? '';
    $relatedProductImages[$i]['BackupImagePath'] = $fallbackImage['BackupImagePath'] ?? '';
}

$quantityOnHandRaw = (int) ($product['QuantityOnHandRaw'] ?? 0);
$productCustomFields = $product['CustomFields'] ?? null;
$customFields = [];
if (!empty($productCustomFields)) {
    $customFields = json_decode($productCustomFields, true, 512, JSON_THROW_ON_ERROR);
}

$productInCart = $cart->getItemCount($product_id) > 0;
if ($id = get_form_data_post("Add_Cart", NULL)) {
    $cart->addItem($id, 1);

    add_user_message('Item is toegevoegd aan de winkelwagen.');
    redirect(get_current_url());
}
elseif ($id = get_form_data_post("Del_Cart", NULL)) {
    $cart->removeItem($id);

    add_user_message('Product is succesvol verwijderd uit de winkelwagen.');
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
                                            data-slide-to="<?= $key ?>" <?= (($key === 1) ? 'class="active"' : ''); ?>></li>
                                    <?php endforeach; ?>
                                </ul>

                                <!-- The slideshow -->
                                <div class="carousel-inner">
                                    <?php foreach ($images as $key => $image) : $key++; ?>
                                        <div class="carousel-item <?= ($key === 1) ? 'active' : ''; ?>">
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
                    <?= $product['StockItemName'] ?? '' ?>
                </h2>
                <?php if ($quantityOnHandRaw < 0) : ?>
                    <div class="QuantityText text-danger">
                        Dit product is niet op voorraad.
                    </div>
                <?php else: ?>
                    <div class="QuantityText"><?= $product['QuantityOnHand'] ?? 0 ?></div>
                <?php endif; ?>
                <div id="StockItemHeaderLeft">
                    <div class="CenterPriceLeft">
                        <div class="CenterPriceCartButton">
                            <form class="text-center" style="margin-top: 65px;" method="post"
                                  action="<?= get_current_url() ?>">

                                <?php if ($productInCart) : ?>
                                    <button type="submit" class="btn btn-outline-danger w-100"
                                            name="Del_Cart" value="<?= $product["StockItemID"] ?? 0 ?>">
                                        <i class="fas fa-shopping-cart h1">-</i>
                                        <i class="far fa-trash-alt h1"></i>
                                    </button>
                                <?php else : ?>
                                    <button type="submit" class="btn btn-outline-success w-100"
                                            name="Add_Cart" value="<?= $product["StockItemID"] ?? 0 ?>"
                                            <?= $quantityOnHandRaw < 0 ? 'disabled' : '' ?>>
                                        <i class="fas fa-cart-plus h1"></i>
                                    </button>
                                <?php endif; ?>
                            </form>
                            <p class="StockItemPriceText">
                                <b>&euro; <?= number_format($product['SellPrice'] ?? 0, 2, ',', '.') ?></b>
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
            <?php foreach($relatedProductIds as $key => $productId) : ?>
            <div class="col-sm-2">
                <?php if (isset($relatedProductImages[$key])) : ?>
                    <?php $relatedImage = $relatedProductImages[$key];
                    $imagePath = $relatedImage['ImagePath'] ?? '';
                    $backupImagePath = $relatedImage['BackupImagePath'] ?? '';
                ?>
                <a href="<?= get_url("view.php?id={$relatedProductIds[$key]}") ?>">
                    <?php if (!empty($imagePath)) : ?>
                        <div class="ImgFrame"
                             style="background-image: url('<?= get_asset_url('StockItemIMG/' . $imagePath) ?>');
                                     background-size: 175px; width: 159px; height: 159px; background-repeat: no-repeat;  margin-bottom: 20%; background-position: center;"></div>
                    <?php elseif (!empty($backupImagePath)) : ?>
                        <div class="ImgFrame"
                             style="background-image: url('<?= get_asset_url('StockGroupIMG/' . $backupImagePath) ?>');
                                     background-size: cover; width: 159px; height: 159px; "></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
            <h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2>
        <?php endif; ?>
    </div>



<?php
require_once __DIR__ . "/../Src/footer.php";
?>