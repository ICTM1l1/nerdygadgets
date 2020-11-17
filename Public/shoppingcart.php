<?php
require_once __DIR__ . "/../Src/header.php";
$cart = unserialize($_SESSION["cart"]);
$productIds = $cart->getItems();

//Werkt niet want er word niks in POST megegeven en weet niet waarom
if (isset($_POST["Min_Product"])) {
    print("s");
    if (isset($_SESSION["currentProductId"])) {
        $cart->setItemCount($_SESSION["currentProductId"], $cart->getItemCount($_SESSION["currentProductId"]) - 1);
    }
}
elseif (isset($_POST["Add_Product"])) {
    if (isset($_SESSION["currentProductId"])) {
        $cart->setItemCount($_SESSION["currentProductId"], $cart->getItemCount($_SESSION["currentProductId"]) + 1);
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

        foreach ($productIds as $prod) :
            $productId = $prod["id"];
            $_SESSION['currentProductId'] = $productId;
            $product = getProduct($productId);
            $image = getProductImage($productId);

            $pricePerPiece = $product['SellPrice'] ?? 0;
            $productQuantity = $prod["amount"];
            $productPriceTotal = $pricePerPiece * $productQuantity;
            $priceTotal += $productPriceTotal;
            //deze prijs verschilt met degene die uit de cart komt
        ?>
            <div class="row border border-white p-2 mr-4">
                <div class="col-sm-3 pl-0">
                    <div id="ImageFrame" style="background-image: url('<?= get_asset_url('StockItemIMG/' . $image['ImagePath'] ?? '') ?>');
                            background-size: 200px; background-repeat: no-repeat; background-position: center;"></div>
                </div>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-9">
                            <h5>#<?= $productId ?></h5>
                            <h3><?= $product['StockItemName'] ?? '' ?></h3>
                        </div>
                        <div class="col-sm-3">
                            <form class="form-inline float-right mr-3" style="position: absolute; top: 50%; right: 0; left: 0;" method="post" action="<?= get_current_url() ?>">

                                <button type="submit" class="btn btn-outline-danger ml-auto mr-2" id="Min_Product" name="Min_Product" value="Remove">
                                <i class="fas fa-minus"></i>
                                </button>
                                <button type="submit" class="btn btn-outline-success mr-2" id="Add_Product" name="Add_Product" value="Add">
                                    <i class="fas fa-plus"></i>
                                </button>

                                <p class="h5">Aantal: <?= $productQuantity ?></p>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-9" style="position: absolute; bottom: 0;">
                            <h4 class="mb-3">Garantie</h4>
                            <h6>Aantal producten op <?= strtolower($product['QuantityOnHand'] ?? 0 )?></h6>
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
    <div class="col-sm-8"></div>
    <div class="col-sm-4">
        <div class="border border-white m-5">
            <h1 class="p-2">
                <b>Totale kosten: </b> &euro; <?= number_format($priceTotal, 2, ',', '.') ?>
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-8"></div>
    <div class="col-sm-4">
        <div class="border border-white mr-5 ml-5 mt-4 mb-5">
            <a href="<?= get_url('afrekenen.php') ?>">
                <h1 class="p-2 font-weight-bold text-white">Koop producten</h1>
            </a>
        </div>
    </div>
</div>


<?php
require_once __DIR__ . "/../Src/footer.php";
?>
