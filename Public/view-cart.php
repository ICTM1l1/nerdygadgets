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
<?php foreach($_SESSION["cart"]->get_items() as $item):?>
    <li>Item:<ul>
        <li>Product ID:<?php print($item->get_code()); ?></li>
        <li>Amount    :<?php print($item->get_count());?></li>
    </ul></li>
<?php endforeach;?>
    </ul>
</p>
</body>
</html>
