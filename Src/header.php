<?php
ob_start();

require_once __DIR__ . '/../Src/Core/core.php';
require_once __DIR__ . '/../Src/Crud/crud.php';

// Server should keep session data for AT LEAST 1 hour.
ini_set('session.gc_maxlifetime', configGet('session_lifetime', 3600));

// Each client should remember their session id for EXACTLY 1 hour.
session_set_cookie_params(configGet('session_lifetime', 3600));

securityHeaders();
session_start();
secureSession();

$cart = getCart();
$loggedIn = sessionGet('LoggedIn');

$categories = getCategories();
$countedCategories = count($categories);
?>
<!DOCTYPE html>
<html lang="en" style="background-color: rgb(35, 35, 47);">
<head>
    <meta charset="ISO-8859-1">
    <meta http-equiv="Content-Security-Policy" content="
        default-src 'none';
        connect-src 'self';
        script-src 'self' https://www.google.com https://www.gstatic.com;
        script-src-elem 'self' https://www.google.com https://www.gstatic.com;
        font-src 'self' https://use.typekit.net https://p.typekit.net https://kit-free.fontawesome.com;
        img-src 'self' http://www.w3.org;
        style-src 'self' 'unsafe-inline' https://p.typekit.net https://use.typekit.net https://kit-free.fontawesome.com;
        base-uri 'self';
        manifest-src 'self';
        form-action 'self' https://www.mollie.com/;
        frame-src 'self' https://www.google.com https://www.youtube.com/;
    ">
    <link rel="manifest" href="<?= getAssetUrl('/Favicon/manifest.json') ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?= getAssetUrl('Favicon/ms-icon-144x144.png') ?>">
    <meta name="theme-color" content="#ffffff">

    <title>NerdyGadgets</title>

    <link rel="stylesheet" href="<?= getAssetUrl('CSS/Style.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= getAssetUrl('CSS/bootstrap.min.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= getAssetUrl('CSS/fonts.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= getAssetUrl('vendor/password-strength-meter/password.min.css') ?>" type="text/css">

    <link rel="apple-touch-icon" sizes="57x57" href="<?= getAssetUrl('Favicon/apple-icon-57x57.png') ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= getAssetUrl('Favicon/apple-icon-60x60.png') ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= getAssetUrl('Favicon/apple-icon-72x72.png') ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= getAssetUrl('Favicon/apple-icon-76x76.png') ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= getAssetUrl('Favicon/apple-icon-114x114.png') ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= getAssetUrl('Favicon/apple-icon-120x120.png') ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= getAssetUrl('Favicon/apple-icon-144x144.png') ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= getAssetUrl('Favicon/apple-icon-152x152.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= getAssetUrl('Favicon/apple-icon-180x180.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= getAssetUrl('Favicon/android-icon-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= getAssetUrl('Favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= getAssetUrl('Favicon/favicon-96x96.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= getAssetUrl('Favicon/favicon-16x16.png') ?>">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<div class="Background">
    <div class="row" id="Header">
        <div class="col-2"><a href="<?= get_url('index.php') ?>" id="LogoA">
                <div id="LogoImage"></div>
            </a></div>
        <div class="col-6" id="CategoriesBar">
            <ul id="ul-class">
                <?php for ($x = 0; $x < $countedCategories && $x < 4; $x++) :
                    $category_id = $categories[$x]['StockGroupID'] ?? '';
                    ?>
                    <li>
                        <a href="<?= get_url("browse.php?category_id={$category_id}") ?>"
                           class="HrefDecoration <?= strpos(get_current_url(), "category_id={$category_id}") !== false ? 'active' : '' ?>">
                            <?= $categories[$x]['StockGroupName'] ?? '' ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <li>
                    <a href="<?= get_url('categories.php') ?>"
                       class="HrefDecoration <?= strpos(get_current_url(), 'categories') !== false ? 'active' : '' ?>">
                        Alle categorieën
                    </a>
                </li>
            </ul>
        </div>
        <ul id="ul-class-navigation">
            <li>
                <?php if ($loggedIn) : ?>
                    <a href="<?= get_url('account.php') ?>"
                       class="HrefDecoration mr-3 <?= strpos(get_current_url(), 'account') !== false ? 'active' : '' ?>">
                        <i class="fas fa-user" style="color:#676EFF;"></i>
                        Account
                    </a>
                <?php else : ?>
                    <a href="<?= get_url('login.php') ?>"
                       class="HrefDecoration mr-3 <?= strpos(get_current_url(), 'login') !== false ? 'active' : '' ?>">
                        <i class="fas fa-user" style="color:#676EFF;"></i>
                        Inloggen
                    </a>
                <?php endif; ?>
            </li>
            <li>
                <a href="<?= get_url('shoppingcart.php') ?>"
                   class="HrefDecoration mr-3 <?= strpos(get_current_url(), 'shoppingcart') !== false ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart" style="color:#676EFF;"></i>
                    Winkelwagen <?php if ($cart->getCount() > 0) : ?> <b>(<?= $cart->getCount() ?>)</b><?php endif; ?>
                </a>
            </li>
            <li>
                <a href="<?= get_url('browse.php') ?>"
                   class="HrefDecoration  <?= strpos(get_current_url(), 'browse') !== false && strpos(get_current_url(), 'category_id') === false ? 'active' : '' ?>">
                    <i class="fas fa-search" style="color:#676EFF;"></i> Zoeken
                </a>
            </li>
        </ul>
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">

                <?php include __DIR__ . '/Html/alert.php'; ?>
