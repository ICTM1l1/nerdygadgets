<?php
require_once __DIR__ . "/header.php";

$categories = getCategories();
?>
<div id="Wrap">
    <?php if (!empty($categories)) : ?>
        <?php foreach($categories as $key => $category) : $key++; ?>
            <a href="browse.php?category_id=<?= $category["StockGroupID"] ?? 0 ?>">
                <div id="StockGroup<?= $key ?>"
                     style="background-image: url('Public/StockGroupIMG/<?= $category["ImagePath"] ?? '' ?>')"
                     class="StockGroups">
                    <h1><?= $category["StockGroupName"] ?? '' ?></h1>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Er zijn geen categorieën gevonden.</p>
    <?php endif; ?>
</div>