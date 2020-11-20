<?php
require_once __DIR__ . "/../Src/header.php";

/** @var Cart $cart */
$cart = session_get('cart');
$products = $cart->getItems();
$price = $cart->getTotalPrice();

if (empty($price) || empty($cart->getItems())) {
    add_user_error('Er zijn geen items in de winkelwagen gevonden om af te rekenen.');
    redirect(get_url('shoppingcart.php'));
}
?>

<div class="container-fluid">
    <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
        <h1 class="mb-5">Afrekenen producten</h1>

        <div class="row ml-0 mr-0">
            <div class="col-sm-12">
                <?php
                $priceTotal = 0;

                foreach ($products as $product) :
                    $productId = (int) ($product["id"] ?? 0);
                    $productFromDb = getProduct($productId);
                    $image = getProductImage($productId);

                    $pricePerPiece = (float) ($productFromDb['SellPrice'] ?? 0);
                    $productQuantity = (int) ($product["amount"] ?? 0);
                    $productPriceTotal = $pricePerPiece * $productQuantity;
                    $priceTotal += $productPriceTotal;
                    ?>
                    <div class="row mb-4">
                        <div class="col-sm-3 pl-0">
                            <?php if (isset($image['ImagePath'])) : ?>
                                <div class="ImgFrame"
                                     style="width: 150px; height: 150px; background-image: url('<?= get_asset_url('StockItemIMG/' . $image['ImagePath'] ?? '') ?>');
                                             background-size: 125px; background-repeat: no-repeat; background-position: center;"></div>
                            <?php elseif (isset($productFromDb['BackupImagePath'])) : ?>
                                <div class="ImgFrame"
                                     style="width: 150px; height: 150px; background-image: url('<?= get_asset_url('StockGroupIMG/' . $productFromDb['BackupImagePath'] ?? '') ?>');
                                             background-size: cover;"></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-9">
                            <div class="product-details" style="position: absolute; top: 35%; right: 0; left: 0;">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <p class="h4"><?= $productQuantity ?>x</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="h4"><?= $productFromDb['StockItemName'] ?? '' ?></p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="h4">&euro; <?=number_format($productPriceTotal, 2, ",", ".")?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-12">
                <p class="h4">Totaal prijs: &euro; <?=number_format($priceTotal, 2, ",", ".")?></p>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-sm-12">
                <a class="btn btn-primary float-left" href="<?= get_url('shoppingcart.php') ?>">
                    Terug naar winkelwagen
                </a>

                <a class="btn btn-success float-right" href="<?= get_url('checkout.php') ?>">
                    Afrekenen
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>
