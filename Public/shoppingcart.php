<?php
require_once __DIR__ . "/../Src/header.php";

/** @var Cart $cart */
$cart = unserialize(session_get('cart'), [Cart::class]);
$products = $cart->getItems();

if (isset($_POST["Min_Product"])) {
    if (isset($_POST["product_id"])) {
        $cart->setItemCount($_POST["product_id"], $cart->getItemCount($_POST["product_id"]) - 1);
    }
}
elseif (isset($_POST["Add_Product"])) {
    if (isset($_POST["product_id"])) {
        $cart->setItemCount($_POST["product_id"], $cart->getItemCount($_POST["product_id"]) + 1);
    }
}
?>

<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-8">
        <h1 class="pb-2">Winkelwagen</h1>

        <div class="cart-products">
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
            <div class="row border border-white p-2 mr-4">
                <div class="col-sm-4 pl-0">
                    <div id="ImageFrame" style="background-image: url('<?= get_asset_url('StockItemIMG/' . $image['ImagePath'] ?? '') ?>');
                            background-size: 200px; background-repeat: no-repeat; background-position: center;"></div>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5>#<?= $productId ?></h5>
                            <h3><?= $productFromDb['StockItemName'] ?? '' ?></h3>
                        </div>
                        <div class="col-sm-4">
                            <form class="form-inline float-right mr-3" style="position: absolute; top: 50%; right: 0; left: 0;"
                                  method="post" action="<?= get_current_url() ?>">
                                <input type="hidden" name="product_id" value="<?= $productId ?>">

                                <button type="submit" class="btn btn-outline-danger ml-auto mr-2" name="Min_Product">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="submit" class="btn btn-outline-success mr-2" name="Add_Product">
                                    <i class="fas fa-plus"></i>
                                </button>

                                <p class="h5">Aantal</p>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-9" style="position: absolute; bottom: 0;">
                            <h4 class="mb-3">Garantie</h4>
                            <h6>Aantal producten op <?= strtolower($productFromDb['QuantityOnHand'] ?? 0 )?></h6>
                        </div>
                        <div class="col-sm-3 text-right" style="position: absolute; bottom: 0; right: 0;">
                            <h3>&euro; <?=number_format($productPriceTotal, 2, ",", ".")?></h3>
                            <h5>Inclusief BTW</h5>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
        <div class="border border-white m-5">
            <h1 class="p-2">
                <b>Totale kosten: </b> &euro; <?= number_format($priceTotal, 2, ',', '.') ?>
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
        <div class="border border-white mr-5 ml-5 mt-4 mb-5">
            <a href="<?= get_url('products-overview.php') ?>">
                <h1 class="p-2 font-weight-bold text-white">Koop producten</h1>
            </a>
        </div>
    </div>
</div>


<?php
require_once __DIR__ . "/../Src/footer.php";
?>
