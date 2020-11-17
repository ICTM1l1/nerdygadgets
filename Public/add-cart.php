<?php
//Example file, can be deleted or overwritten later.
require_once __DIR__ . "/../Src/Core/core.php";
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

session_save('cart', new Cart());
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
        $_SESSION["cart"]->addItem($_POST["prodid"], $_POST["amount"]);
    }
    ?>
</body>
</html>
