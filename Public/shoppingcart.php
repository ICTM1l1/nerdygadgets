<?php
require_once __DIR__ . "/../Src/header.php";

/** @var Cart $cart */
$cart = session_get("cart");
$cartItems = $cart->getItems();

if(get_form_data_post("Add_Product", NULL) != NULL){
    $id = get_form_data_post("Add_Product");
    $count = $cart->getItemCount($id);
    $cart->setItemCount($id, $count+1);

    add_user_message('Product aantal is succesvol verhoogd.');
    redirect(get_current_url());
}
elseif(get_form_data_post("Min_Product", NULL) != NULL){
    $id = get_form_data_post("Min_Product");
    $count = $cart->getItemCount($id);
    $cart->setItemCount($id, $count-1 ?: $count);

    add_user_message('Product aantal is succesvol verminderd.');
    redirect(get_current_url());
}
elseif(get_form_data_post("Del_Product", NULL) != NULL){
    $id = get_form_data_post("Del_Product");
    $cart->removeItem($id);

    add_user_message('Product is succesvol verwijderd uit de winkelwagen.');
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

        if (count($cartItems) > 0) :
            foreach ($cartItems as $cartItem) :
            $product = getProduct($cartItem["id"] ?? 0);
            $productId = $product['StockItemID'] ?? 0;
            $image = getProductImage($productId);

            $quantityOnHandRaw = (int) ($product['QuantityOnHandRaw'] ?? 0);
            $pricePerPiece = $product['SellPrice'] ?? 0;
            $productQuantity = $cartItem["amount"];
            $productPriceTotal = $pricePerPiece * $productQuantity;
            $priceTotal += $productPriceTotal;
        ?>
            <div class="row border border-white p-2 mr-4">
                <div class="col-sm-3 pl-0">
                    <?php if (isset($image['ImagePath'])) : ?>
                        <div class="ImgFrame"
                             style="width: 230px; height: 230px; background-image: url('<?= get_asset_url('StockItemIMG/' . $image['ImagePath'] ?? '') ?>');
                                     background-size: 200px; background-repeat: no-repeat; background-position: center;"></div>
                    <?php elseif (isset($product['BackupImagePath'])) : ?>
                        <div class="ImgFrame"
                             style="width: 230px; height: 230px; background-image: url('<?= get_asset_url('StockGroupIMG/' . $product['BackupImagePath'] ?? '') ?>');
                                     background-size: cover;"></div>
                    <?php endif; ?>
                </div>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-9">
                            <a class="ListItem text-white" href='<?= get_url('view.php?id=' . $productId) ?>'>
                                <h5>#<?= $productId ?></h5>
                                <h4><?= $product['StockItemName'] ?? '' ?></h4>
                            </a>
                        </div>
                        <div class="col-sm-3 mt-1">
                            <form class="form-inline float-right w-100" method="post" action="shoppingcart.php">
                                <div class="edit-actions w-100 mb-2">
                                    <button type="submit" class="btn btn-outline-danger mr-2" name="Min_Product" value="<?= $productId ?>">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="submit" class="btn btn-outline-success" name="Add_Product" value="<?= $productId ?>">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <p class="h4 font-weight-bold float-right"><?= $cartItem["amount"] ?? 0 ?>x</p>
                                </div>

                                <button class="btn btn-outline-danger float-right w-100"
                                        type="submit" name="Del_Product"
                                        onclick="return confirm('Weet u zeker dat u `<?= $product['StockItemName'] ?? "" ?>` wilt verwijderen?')"
                                        value="<?= $productId ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 80px;">
                        <div class="col-sm-9">
                            <?php if ($quantityOnHandRaw < 0) : ?>
                                <h6 class="mt-5 text-danger">
                                    Dit product is niet op voorraad.
                                </h6>
                            <?php else: ?>
                                <h6 class="mt-5"><?= $product['QuantityOnHand'] ?? '' ?></h6>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-3 mt-2 text-right">
                            <h4>&euro; <?=number_format($productPriceTotal, 2, ",", ".")?></h4>
                            <h6>Inclusief BTW</h6>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;
        else :
            echo 'Er staan geen producten in de winkelwagen.';
        endif;
        ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-7"></div>
    <div class="col-sm-5">
        <div class="border border-white m-5">
            <h1 class="p-2">
                <b>Totale kosten: </b> &euro; <?= number_format($priceTotal, 2, ',', '.') ?>
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-7"></div>
    <div class="col-sm-5">
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
