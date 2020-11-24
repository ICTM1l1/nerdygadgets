<?php
require_once __DIR__ . "/../Src/header.php";

$orders = getOrdersByCustomer(1063);
$amountOrders = count($orders);
?>

    <div class="container-fluid">
        <div class="w-75 mt-1 ml-auto mr-auto">
            <?php include_once __DIR__ . '/../Src/Html/account-navbar.php'; ?>

            <div class="order-overview mt-3 mb-5">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-md-12 mr-2 mb-4">
                                        <div class="h2 font-weight-bold text-primary text-uppercase float-left">
                                            Bestelhistorie
                                        </div>
                                        <div class="h3 font-weight-bold text-primary text-uppercase mb-1 float-right">
                                            <?php if ($amountOrders < 2) : ?>
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
                                                <label for="searchLog" class="d-none visually-hidden">
                                                    <b>Zoeken</b>
                                                </label>
                                                <input type="text" id="searchLog"
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
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    #<?= $order['OrderID'] ?? 0 ?>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <?= dateShortFormatted($order['OrderDate'] ?? '') ?>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <?php $active = '';
                                                    endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="tab-content text-dark" id="nav-tabContent">
                                                <?php $active = 'active';
                                                foreach ($orders as $key => $order) : ?>
                                                    <div class="tab-pane fade show <?= $active ?>"
                                                         id="list-<?= $key ?>" role="tabpanel"
                                                         aria-labelledby="list-<?= $key ?>">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3 class="mt-0 pt-0 float-left">
                                                                    Bestelling #<?= $order['OrderID'] ?? 0 ?>
                                                                </h3>

                                                                <h3 class="float-right mt-0 pt-0">
                                                                    Geplaatst op <?= dateFullFormatted($order['OrderDate'] ?? '') ?>
                                                                </h3>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-2 pt-2 border-top border-dark">
                                                            <div class="col-md-12">
                                                                bestelling details.
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