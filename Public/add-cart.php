<?php
//Example file, can be deleted or overwritten later.
require_once __DIR__ . "/../Src/cart.php";
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<html>
<head>
    <title>Add to cart.</title>
</head>
<body>
<form action="add-cart.php" method="post">
    ID: <input type="text" name="prodid">
    N : <input type="text" name="amount">
    <input type="submit">
</form>

<?php
if(isset($_POST["prodid"], $_POST["amount"])){
    /** @var Cart $cart */
    $cart = session_get('cart');
    $cart->addItem($_POST["prodid"], $_POST["amount"]);
    session_save('cart', $cart, true);
}
?>
</body>
</html>
