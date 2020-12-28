<?php
require_once __DIR__ . '/../Src/header.php';

$products = getRandomProducts(10);
?>
<div class="IndexStyle">
    <div class="col-11 m-auto">
        <?php if (!empty($products)) : ?>
            <?php foreach($products as $key => $product) : ?>
                <?php if (!empty($product)) : ?>
                    <?php
                    $productId = $product['StockItemID'] ?? 0;
                    $images = getProductImages($productId);
                    ?>

                    <a href="<?= getUrl("view.php?id={$productId}") ?>">
                        <div class="TextPrice">
                                <div class="TextMain">
                                    <?= $product['StockItemName'] ?? '' ?>
                                </div>
                                <ul id="ul-class-price">
                                    <li class="HomePagePrice">
                                        &euro; <?= priceFormat($product['SellPrice'] ?? 0) ?>
                                    </li>
                                </ul>
                        </div>

                        <div style="background-image: url('<?= getAssetUrl('StockItemIMG/' . $images[0]['ImagePath'] ?? '') ?>'); background-size: 100% 100%; width: 477px; height: 477px; background-repeat: no-repeat; margin-left: 55%; margin-top: -30%;"></div>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <h1 class="text-center mt-5 pt-5">Er zijn geen producten gevonden</h1>
        <?php endif; ?>
    </div>
</div>
<?php
require_once __DIR__ . '/../Src/footer.php';
?>

