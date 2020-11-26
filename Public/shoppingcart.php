<?php
require_once __DIR__ . "/../Src/header.php";

/** @var Cart $cart */
$cart = session_get("cart");
$cartItems = $cart->getItems();
$amountCartItems = $cart->getCount();

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

<div class="container-fluid">
    <div class="products-overview ml-auto mr-auto mt-5 mb-5" style="width: 60% !important;">
        <div class="row">
            <div class="col-sm-6 text-left">
                <h1 class="mb-5">Winkelwagen</h1>
            </div>
            <div class="col-sm-6 text-right">
                <h1 class="mb-5">
                    <?= $amountCartItems . ($amountCartItems === 1 ? ' product' : ' producten') ?>
                </h1>
            </div>
        </div>

        <div class="row ml-0 mr-0">
            <div class="col-sm-12">
                <?php
                $priceTotal = 0;

                if ($amountCartItems > 0) :
                    foreach ($cartItems as $cartItem) :
                        $productId = (int) ($cartItem["id"] ?? 0);
                        $productFromDb = getProduct($productId);
                        $image = getProductImage($productId);

                        $quantityOnHandRaw = (int) ($product['QuantityOnHandRaw'] ?? 0);
                        $pricePerPiece = (float) ($productFromDb['SellPrice'] ?? 0);
                        $productQuantity = (int) ($cartItem["amount"] ?? 0);
                        $productPriceTotal = $pricePerPiece * $productQuantity;
                        $priceTotal += $productPriceTotal;
                ?>
                    <div class="row mb-4 border border-white">
                        <div class="col-sm-3 pl-0 pb-2">
                            <?php if (isset($image['ImagePath'])) : ?>
                                <div class="ImgFrame"
                                     style="width: 220px; height: 220px; background-image: url('<?= get_asset_url('StockItemIMG/' . $image['ImagePath'] ?? '') ?>');
                                             background-size: 190px; background-repeat: no-repeat; background-position: center;"></div>
                            <?php elseif (isset($productFromDb['BackupImagePath'])) : ?>
                                <div class="ImgFrame"
                                     style="width: 150px; height: 150px; background-image: url('<?= get_asset_url('StockGroupIMG/' . $productFromDb['BackupImagePath'] ?? '') ?>');
                                             background-size: cover;"></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-9 pt-2">
                            <div class="row">
                                <div class="col-sm-9">
                                    <a class="ListItem text-white" href='<?= get_url('view.php?id=' . $productId) ?>'>
                                        <h5>#<?= $productId ?></h5>
                                        <h4><?= $productFromDb['StockItemName'] ?? '' ?></h4>
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
                            <div class="row mt-5 pt-2">
                                <div class="col-sm-9">
                                    <?php if ($quantityOnHandRaw < 0) : ?>
                                        <h6 class="mt-5 text-danger">
                                            Dit product is niet op voorraad.
                                        </h6>
                                    <?php else: ?>
                                        <h6 class="mt-5"><?= $productFromDb['QuantityOnHand'] ?? '' ?></h6>
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

        <div class="row mt-5">
            <div class="col-sm-12">
                <p class="h4 pt-1 float-left">
                    Totale kosten: &euro; <?=number_format($priceTotal, 2, ",", ".")?>
                </p>

                <a class="btn btn-success float-right" href="<?= get_url('products-overview.php') ?>">
                    Koop producten
                </a>
            </div>
        </div>
    </div>
</div>


<?php
require_once __DIR__ . "/../Src/footer.php";
?>
