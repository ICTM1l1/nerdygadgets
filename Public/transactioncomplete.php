<?php
require_once __DIR__ . "/../Src/header.php";
require_once __DIR__ . "/../Src/payment.php";
?>

<?php
$paymentStatus = checkPayment($_SESSION['paymentId']);
?><br><br>
<div class="col-sm-12 mt-2 text-center">
    <h1 style=<?= $paymentStatus ? "color:green" : "color:red" ?>><?= $paymentStatus ? "Transactie compleet" : "Transactie mislukt" ?></h1>
</div>
<?php
require_once __DIR__ . "/../Src/footer.php";
?>
