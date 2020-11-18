<?php
require_once __DIR__ . "/../Src/header.php";

if(get_form_data_post("Add_Product", NULL) != NULL){
    $id = get_form_data_post("Add_Product");
    $cart = session_get("cart");
    $count = $cart->getItemCount($id);
    $cart->setItemCount($id, $count+1);
    redirect(get_current_url());
}
elseif(get_form_data_post("Min_Product", NULL) != NULL){
    $id = get_form_data_post("Min_Product");
    $cart = session_get("cart");
    $count = $cart->getItemCount($id);
    $cart->setItemCount($id, $count-1 ?: $count);
    redirect(get_current_url());
}
elseif(get_form_data_post("Del_Product", NULL) != NULL){
    $id = get_form_data_post("Del_Product");
    $cart = session_get("cart");
    $cart->removeItem($id);
    redirect(get_current_url());
}
?>

<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-8">
        <h1 class="pb-2">Winkelwagen</h1>

        <div class="cart-products">
        <?php
        $priceTotal = 0;

        foreach ($_SESSION["cart"]->getItems() as $cartItem) :
            $product = getProduct($cartItem["id"]);
            $productId = $product['StockItemID'] ?? 0;
            $image = getProductImage($productId);

            $pricePerPiece = $product['SellPrice'] ?? 0;
            $productQuantity = $cartItem["amount"];
            $productPriceTotal = $pricePerPiece * $productQuantity;
            $priceTotal += $productPriceTotal;
        ?>
            <div class="row border border-white p-2 mr-4">
                <div class="col-sm-3 pl-0">
                    <div id="ImageFrame" style="background-image: url('<?= get_asset_url('StockItemIMG/' . ($image['ImagePath'] ?? '')) ?>');
                            background-size: 200px; background-repeat: no-repeat; background-position: center;"></div>
                </div>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-9">
                            <h5>#<?= $productId ?></h5>
                            <h3><?= $product['StockItemName'] ?? '' ?></h3>
                        </div>
                        <div class="col-sm-3">
                            <form class="form-inline float-right mr-3 w-100" method="post" action="shoppingcart.php">
                                <div class="edit-actions"  style="position: absolute; top: 30px; right: 75px;">
                                    <button type="submit" class="btn btn-outline-danger mr-2" name="Min_Product" value="<?=$cartItem["id"]?>">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="submit" class="btn btn-outline-success mr-3" name="Add_Product" value="<?=$cartItem["id"]?>">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <p class="h4 font-weight-bold float-right mr-1"><?= $cartItem["amount"];?>x</p>
                                </div>

                                <button class="btn btn-outline-danger float-right mr-2" style="position: absolute; top: 80px; right: 75px; width: 60%;"
                                        type="submit" name="Del_Product" onclick="return confirm('Wilt u dit product verwijderen?')"
                                        name="Del_Product" value="<?=$cartItem["id"]?>">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <p class="h5">Aantal</p>
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
