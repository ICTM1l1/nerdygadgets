<?php
require_once __DIR__ . "/../Src/header.php";

$products = getRandomProducts();
?>
<div class="IndexStyle">
    <div class="col-11">
        <?php if (!empty($products)) : ?>
            <?php foreach($products as $key => $product) : ?>
                <?php if (!empty($product)) : ?>
                    <?php
                    $product_id = $product["StockItemID"] ?? 0;
                    $images = getProductImages($product_id);
                    ?>

                    <a href="<?= get_url("view.php?id={$product_id}") ?>">
                        <div class="TextPrice">
                                <div class="TextMain">
                                    <?= $product['StockItemName'] ?? '' ?>
                                </div>
                                <ul id="ul-class-price">
                                    <li class="HomePagePrice">
                                        &euro; <?= number_format($product['SellPrice'] ?? 0, 2, ',', '.') ?>
                                    </li>
                                </ul>
                        </div>

                        <div style="background-image: url('<?= get_asset_url('StockItemIMG/' . $images[0]['ImagePath'] ?? '') ?>'); background-size: 100% 100%; width: 477px; height: 477px; background-repeat: no-repeat; margin-left: 55%; margin-top: -30%;"></div>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Er zijn geen producten gevonden.</p>
        <?php endif; ?>
    </div>
</div>
<?php
require_once __DIR__ . "/../Src/footer.php";
?>

