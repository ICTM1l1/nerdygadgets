<?php
//Example file, can be deleted or overwritten later.

require_once __DIR__ . "/../Src/Core/core.php";
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION["cart"])){
    $_SESSION["cart"] = new Cart();
}
//print($_SESSION["cart"]->cleanCart());
?>

<html>
<head>
    <title>View cart.</title>
</head>
<body>
<p>
    <ul>
<?php foreach($_SESSION["cart"]->getItems() as $item):?>
    <li>Item:<ul>
        <li>Product ID: <?php print($item["id"]); ?></li>
        <li>Item count: <?php print($item["amount"]);?></li>
    </ul></li>
<?php endforeach;?>
    </ul>
</p>
<?php
print("Total: " . $_SESSION["cart"]->getTotalPrice());
?>
</body>
</html>
