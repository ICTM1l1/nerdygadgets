<?php
require_once __DIR__ . "/../Src/cart.php";
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
        <li>Product ID: <?php print($item->getCode()); ?></li>
        <li>Item count: <?php print($item->getCount());?></li>
    </ul></li>
<?php endforeach;?>
    </ul>
</p>
</body>
</html>
