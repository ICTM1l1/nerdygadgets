<?php
require_once __DIR__ . "/../Src/header.php";

$product_id = (int) get_form_data_get('id');
$product = getProduct($product_id);
$images = getProductImages($product_id);

$productCustomFields = $product['CustomFields'] ?? null;
$customFields = [];
if (!empty($productCustomFields)) {
    $customFields = json_decode($productCustomFields, true, 512, JSON_THROW_ON_ERROR);
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
                         style="background-image: url('Assets/StockItemIMG/<?= $images[0]['ImagePath'] ?? '' ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
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
test
                            <!-- The slideshow -->
                            <div class="carousel-inner">
                                <?php foreach ($images as $key => $image) : $key++; ?>
                                    <div class="carousel-item <?= ($key === 1) ? 'active' : ''; ?>">
                                        <img alt="Product foto" src="Assets/StockItemIMG/<?= $image['ImagePath'] ?? '' ?>">
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
                     style="background-image: url('Assets/StockGroupIMG/<?= $product['BackupImagePath'] ?? '' ?>'); background-size: cover;"></div>
            <?php endif; ?>

            <h1 class="StockItemID">Artikelnummer: <?= $product["StockItemID"] ?? 0 ?></h1>
            <h2 class="StockItemNameViewSize StockItemName">
                <?= $product['StockItemName'] ?? '' ?>
            </h2>
            <div class="QuantityText"><?= $product['QuantityOnHand'] ?? 0 ?></div>
            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        <p class="StockItemPriceText">
                            <b>&euro; <?= number_format($product['SellPrice'] ?? 0, 2, '.', ',') ?></b>
                        </p>
                        <h6> Inclusief BTW </h6>
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
    <?php else : ?>
        <h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>