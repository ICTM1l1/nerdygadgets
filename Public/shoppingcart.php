<?php
require_once __DIR__ . '/../Src/header.php';

csrfValidate(getCurrentUrl());

$cart = getCart();
$cartItems = $cart->getItems();
$amountCartItems = $cart->getCount();

if ($id = getFormDataPost('Add_Product', NULL)){
    $cart->increaseItemCount($id);
    redirect(getCurrentUrl());
}
elseif ($id = getFormDataPost('Min_Product', NULL)){
    $cart->decreaseItemCount($id);
    redirect(getCurrentUrl());
}
elseif ($id = getFormDataPost('Del_Product', NULL)){
    $cart->removeItem($id);
    redirect(getCurrentUrl());
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
                        $productId = (int) ($cartItem['id'] ?? 0);
                        $productFromDb = getProduct($productId);
                        $image = getProductImage($productId);

                        $quantityOnHandRaw = (int) ($productFromDb['QuantityOnHandRaw'] ?? 0);
                        $pricePerPiece = (float) ($productFromDb['SellPrice'] ?? 0);
                        $productQuantity = (int) ($cartItem['amount'] ?? 0);
                        $productPriceTotal = $pricePerPiece * $productQuantity;
                        $priceTotal += $productPriceTotal;
                ?>
                    <div class="row mb-4 border border-white">
                        <div class="col-sm-3 pl-0 pb-2">
                            <?php if (isset($image['ImagePath'])) : ?>
                                <div class="ImgFrame"
                                     style="width: 220px; height: 220px; background-image: url('<?= getAssetUrl('StockItemIMG/' . $image['ImagePath'] ?? '') ?>');
                                             background-size: 190px; background-repeat: no-repeat; background-position: center;"></div>
                            <?php elseif (isset($productFromDb['BackupImagePath'])) : ?>
                                <div class="ImgFrame"
                                     style="width: 220px; height: 220px; background-image: url('<?= getAssetUrl('StockGroupIMG/' . $productFromDb['BackupImagePath'] ?? '') ?>');
                                             background-size: cover;"></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-9 pt-2">
                            <div class="row">
                                <div class="col-sm-9">
                                    <a class="ListItem text-white" href='<?= getUrl('view.php?id=' . $productId) ?>'>
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

                                        <input type="hidden" name="token" value="<?=csrfGetToken()?>"/>

                                        <button class="btn btn-outline-danger float-right w-100"
                                                data-confirm="Weet u zeker dat u `<?= replaceDoubleQuotesForWhiteSpaces($productFromDb['StockItemName'] ?? '') ?>` wilt verwijderen?"
                                                type="submit" name="Del_Product" value="<?= $productId ?>">
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
                                    <h4>&euro; <?= priceFormat($productPriceTotal) ?></h4>
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
                    Totale kosten: &euro; <?= priceFormat($priceTotal) ?>
                </p>

                <a class="btn btn-success float-right" href="<?= getUrl('products-overview.php') ?>">
                    Koop producten
                </a>
            </div>
        </div>
        <div class="mt-5">
            <?php
            if ($amountCartItems > 0) :
            ?>
            <div id="RelatedProductText" class="pt-5">
                <h3>Gerelateerde Producten</h3>
            </div>
                <?php
                $firstProductId = $cartItems[array_key_first($cartItems)]['id'] ?? 0;
                $categories = getCategoryIdForProduct($firstProductId);

                $relatedProductIds = [];
                $relatedProductImages = [];
                for ($i = 0; $i < 4; $i++) {
                    $relatedProductIds[$i] = getRandomProductForCategory($categories[random_int(0, count($categories) - 1)] ['StockGroupID'] ?? '');

                    $image = getProductImages($relatedProductIds[$i] ?? 0);
                    $fallbackImage = getBackupProductImage($relatedProductIds[$i] ?? 0);

                    $relatedProductImages[$i]['ImagePath'] = $image[0]['ImagePath'] ?? '';
                    $relatedProductImages[$i]['BackupImagePath'] = $fallbackImage['BackupImagePath'] ?? '';
                }?>
            <div class="row" id="RelatedCartProducts">
                <?php foreach ($relatedProductIds as $key => $productId) : ?>
                <div class="col-sm-3">
                    <?php if (isset($relatedProductImages[$key])) : ?>
                        <?php
                        $relatedImage = $relatedProductImages[$key];
                        $imagePath = $relatedImage['ImagePath'] ?? '';
                        $backupImagePath = $relatedImage['BackupImagePath'] ?? '';
                        ?>
                    <a href="<?= getUrl("view.php?id={$relatedProductIds[$key]}") ?>">
                        <?php if (!empty($imagePath)) : ?>
                            <div class="ImgFrame"
                                style="background-image: url('<?= getAssetUrl('StockItemIMG/' . $imagePath) ?>');
                                        background-size: 175px; width: 170px; height: 170px; background-repeat: no-repeat;  margin-bottom: 20%; margin-top: 10%; background-position: center;"></div>
                        <?php elseif (!empty($backupImagePath)) : ?>
                            <div class="ImgFrame"
                                style="background-image: url('<?= getAssetUrl('StockGroupIMG/' . $backupImagePath) ?>');
                                        background-size: cover; width: 170px; height: 170px; margin-top: 10%; "></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php
require_once __DIR__ . '/../Src/footer.php';
?>
