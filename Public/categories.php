<?php
require_once __DIR__ . '/../Src/header.php';

$categories = getCategories();
?>
<div id="Wrap">
    <?php if (!empty($categories)) : ?>
        <?php foreach($categories as $key => $category) : $key++; ?>
            <a href="<?= get_url('browse.php?category_id=' . $category['StockGroupID'] ?? 0) ?>">
                <div id="StockGroup<?= $key ?>"
                     style="background-image: url('<?= getAssetUrl('StockGroupIMG/' . $category['ImagePath'] ?? '') ?>')"
                     class="StockGroups">
                    <h1><?= $category['StockGroupName'] ?? '' ?></h1>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Er zijn geen categorieën gevonden.</p>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../Src/footer.php';
?>