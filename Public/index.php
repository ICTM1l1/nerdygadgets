<?php
require_once __DIR__ . "/../Src/header.php";
$productAmount = getProductsAmount();
$productAmount = $productAmount["count(*)"] ?? 10;
$products = [
        getProduct(random_int(1, $productAmount)),
        getProduct(random_int(1, $productAmount)),
        getProduct(random_int(1, $productAmount)),
        getProduct(random_int(1, $productAmount)),
        getProduct(random_int(1, $productAmount)),
        getProduct(random_int(1, $productAmount)),
        getProduct(random_int(1, $productAmount)),
    ];
?>
<div class="IndexStyle">
    <div class="col-11">
        <?php if (!empty($products)) : ?>
        <?php foreach($products as $key => $product) : $key++; ?>
        <a href="view.php?id=<?= $product["StockItemID"] ?? 0 ?>">
            <?php
            $product_id = $product["StockItemID"] ?? 0;
            $images = getProductImages($product_id);
            ?>
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
        </a>
        <div style="background-image: url('Assets/StockItemIMG/<?= $images[0]['ImagePath'] ?? '' ?>'); background-size: 100% 100%; width: 477px; height: 477px; background-repeat: no-repeat; margin-left: 55%; margin-top: -30%;"></div>
        <?php endforeach; ?>
        <?php else : ?>
            <p>Er zijn geen producten gevonden.</p>
        <?php endif; ?>
    </div>
</div>
<?php
require_once __DIR__ . "/../Src/footer.php";
?>

