<?php
require_once __DIR__ . "/../Src/header.php";

// TODO: Get the customer id based on the logged in user.
$orders = getOrdersByCustomer(1063);
$amountOrders = count($orders);
?>

    <div class="container-fluid">
        <div class="w-75 mt-1 ml-auto mr-auto">
            <?php include_once __DIR__ . '/../Src/Html/account-navbar.php'; ?>

            <div class="order-overview mt-3 mb-5">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card bg-dark shadow h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-md-12 mr-2 mb-4">
                                        <div class="h2 font-weight-bold text-primary text-uppercase float-left">
                                            Bestelhistorie
                                        </div>
                                        <div class="h3 font-weight-bold text-primary text-uppercase mb-1 float-right">
                                            <?php if ($amountOrders === 1) : ?>
                                                <?= $amountOrders ?> bestelling
                                            <?php else : ?>
                                                <?= $amountOrders ?> bestellingen
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <?php if ($amountOrders < 1) : ?>
                                        <div class="col-md-12">
                                            <p class="mt-2 font-weight-bold">
                                                Er zijn geen bestellingen gevonden.
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($amountOrders > 0) : ?>
                                        <div class="col-sm-3 pt-0 mt-0">
                                            <div class="form-label-group">
                                                <label for="searchListGroupItems" class="d-none">
                                                    <b>Zoeken</b>
                                                </label>
                                                <input type="text" id="searchListGroupItems"
                                                       class="form-control mb-2"
                                                       autocomplete="off" placeholder="Zoeken">
                                            </div>

                                            <div class="scrollbox-vertical h-500">
                                                <div class="list-group overflow-hidden"
                                                     id="list-tab" role="tablist">
                                                    <?php $active = 'active';
                                                    foreach ($orders as $key => $order) : ?>
                                                        <a class="list-group-item list-group-item-action <?= $active ?>"
                                                           id="list-<?= $key ?>-list"
                                                           data-toggle="list" style="z-index: 0;"
                                                           href="#list-<?= $key ?>" role="tab"
                                                           aria-controls="<?= $key ?>">
                                                            <div class="float-left">
                                                                #<?= $order['OrderID'] ?? 0 ?>
                                                            </div>
                                                            <div class="float-right">
                                                                <?= dateFormatShort($order['OrderDate'] ?? '') ?>
                                                            </div>
                                                        </a>
                                                        <?php $active = '';
                                                    endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="tab-content text-white" id="nav-tabContent">
                                                <?php $active = 'active';
                                                foreach ($orders as $key => $order) :
                                                    $order_id = $order['OrderID'] ?? 0;
                                                    $priceTotal = 0;

                                                    $orderLines = getOrderLinesByOrder($order_id);
                                                    ?>
                                                    <div class="tab-pane fade show <?= $active ?>"
                                                         id="list-<?= $key ?>" role="tabpanel"
                                                         aria-labelledby="list-<?= $key ?>">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3 class="mt-0 pt-0">
                                                                    Bestelling #<?= $order_id ?>
                                                                </h3>

                                                                <h5 class="mt-2 float-left">
                                                                    Geplaatst op <?= dateFormatFull($order['OrderDate'] ?? '') ?>
                                                                </h5>
                                                                <h5 class="mt-2 float-right">
                                                                    Bezorgd op <?= dateFormatFull($order['ExpectedDeliveryDate'] ?? '') ?>
                                                                </h5>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-2 pt-2 border-top border-white">
                                                            <div class="col-md-12">
                                                                <?php if (empty($orderLines)) : ?>
                                                                    <p>Er zijn geen gegevens gevonden voor deze bestelling.</p>
                                                                <?php else : ?>
                                                                    <?php foreach ($orderLines as $orderLine) :
                                                                        $pricePerPiece = (float) ($orderLine['SoldPrice'] ?? 0);
                                                                        $productQuantity = (int) ($orderLine["Quantity"] ?? 0);
                                                                        $productPriceTotal = $pricePerPiece * $productQuantity;
                                                                        $priceTotal += $productPriceTotal;
                                                                        ?>
                                                                        <div class="row border-bottom border-white pb-2">
                                                                            <div class="col-sm-2">
                                                                                <?php if (isset($orderLine['ImagePath'])) : ?>
                                                                                    <div class="ImgFrame"
                                                                                         style="width: 100px; height: 100px; background-image: url('<?= get_asset_url('StockItemIMG/' . $orderLine['ImagePath'] ?? '') ?>');
                                                                                                 background-size: 75px; background-repeat: no-repeat; background-position: center;"></div>
                                                                                <?php elseif (isset($orderLine['BackupImagePath'])) : ?>
                                                                                    <div class="ImgFrame"
                                                                                         style="width: 100px; height: 100px; background-image: url('<?= get_asset_url('StockGroupIMG/' . $orderLine['BackupImagePath'] ?? '') ?>');
                                                                                                 background-size: cover;"></div>
                                                                                <?php else : ?>
                                                                                    <div class="ImgFrame" style="width: 100px; height: 100px;"></div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div class="col-sm-10">
                                                                                <div class="product-details" style="position: absolute; top: 35%; right: 0; left: 0;">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-1">
                                                                                            <p class="h4"><?= $productQuantity ?>x</p>
                                                                                        </div>
                                                                                        <div class="col-sm-8">
                                                                                            <p class="h4"><?= $orderLine['Description'] ?? '' ?></p>
                                                                                        </div>
                                                                                        <div class="col-sm-3">
                                                                                            <p class="h4">&euro; <?=number_format($productPriceTotal, 2, ",", ".")?></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </div>

                                                            <div class="col-md-12 mt-3">
                                                                <p class="h4 pl-2">Totaal kosten: &euro; <?=number_format($priceTotal, 2, ",", ".") ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php $active = '';
                                                endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>