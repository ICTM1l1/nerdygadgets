<?php
require_once __DIR__ . "/../Src/header.php";

$paymentPaid = checkPayment(session_get('paymentId'));
session_key_unset('paymentId');
?>

<div class="container-fluid">
    <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="mb-5 float-left">3. Afronden</h1>

                <div class="form-progress float-right">
                    <!-- Grey with black text -->
                    <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
                        <ul class="navbar-nav">
                            <li class="nav-item border-right border-white">
                                <a class="nav-link" href="#">Bezorggegevens</a>
                            </li>
                            <li class="nav-item border-right border-white">
                                <a class="nav-link" href="#">Afrekenen</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="#">Afronden</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h1 style=<?= $paymentPaid ? "color:green" : "color:red" ?>>
                    <?= $paymentPaid ? "Transactie compleet" : "Transactie mislukt" ?>
                </h1>

                <div class="form-group">
                    <button class="btn btn-danger float-right my-4" type="button" name="back"
                            onclick="window.location.href='<?= get_url('index.php') ?>'">
                        3. Afronden
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>