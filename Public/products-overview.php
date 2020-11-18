<?php
require_once __DIR__ . "/../Src/header.php";

/** @var Cart $cart */
$cart = unserialize(session_get('cart'), [Cart::class]);
$products = $cart->getItems();
?>

<div class="container-fluid">
    <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
        <h1 class="mb-5">Afrekenen producten</h1>

        <div class="row ml-0 mr-0">
            <div class="col-sm-12">
                <?php
                $priceTotal = 0;

                foreach ($products as $product) :
                    $productId = $product["id"];
                    $productFromDb = getProduct($productId);
                    $image = getProductImage($productId);

                    $pricePerPiece = $productFromDb['SellPrice'] ?? 0;
                    $productQuantity = $product["amount"];
                    $productPriceTotal = $pricePerPiece * $productQuantity;
                    $priceTotal += $productPriceTotal;
                    ?>
                    <div class="row mb-4">
                        <div class="col-sm-3 pl-0">
                            <div id="ImageFrame" style="width: 150px; height: 150px; background-image: url('<?= get_asset_url('StockItemIMG/' . $image['ImagePath'] ?? '') ?>');
                                    background-size: 125px; background-repeat: no-repeat; background-position: center;"></div>
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
