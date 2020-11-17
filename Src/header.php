<?php
session_start();

require_once __DIR__ . '/../Src/Core/core.php';
require_once __DIR__ . '/../Src/Crud/crud.php';
require_once __DIR__ . '/cart.php';
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en" style="background-color: rgb(35, 35, 47);">
<head>
    <meta charset="ISO-8859-1">
    <title>NerdyGadgets</title>

    <link rel="stylesheet" href="<?= get_asset_url('CSS/Style.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= get_asset_url('CSS/bootstrap.min.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= get_asset_url('CSS/fonts.css') ?>">

    <link rel="apple-touch-icon" sizes="57x57" href="<?= get_asset_url('Favicon/apple-icon-57x57.png') ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= get_asset_url('Favicon/apple-icon-60x60.png') ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= get_asset_url('Favicon/apple-icon-72x72.png') ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= get_asset_url('Favicon/apple-icon-76x76.png') ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= get_asset_url('Favicon/apple-icon-114x114.png') ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= get_asset_url('Favicon/apple-icon-120x120.png') ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= get_asset_url('Favicon/apple-icon-144x144.png') ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= get_asset_url('Favicon/apple-icon-152x152.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= get_asset_url('Favicon/apple-icon-180x180.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= get_asset_url('Favicon/android-icon-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= get_asset_url('Favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= get_asset_url('Favicon/favicon-96x96.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= get_asset_url('Favicon/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= get_asset_url('/Favicon/manifest.json') ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?= get_asset_url('Favicon/ms-icon-144x144.png') ?>">
    <meta name="theme-color" content="#ffffff">
    <style>
        @font-face {
            font-family: MmrText;
            src: url("<?= get_asset_url('fonts/mmrtext.ttf') ?>");
        }
    </style>
</head>
<body>
<div class="Background">
    <div class="row" id="Header">
        <div class="col-2"><a href="<?= get_url('index.php') ?>" id="LogoA">
                <div id="LogoImage"></div>
            </a></div>
        <div class="col-8" id="CategoriesBar">
            <ul id="ul-class">
                <?php foreach($categories as $category) : ?>
                    <li>
                        <a href="<?= get_url('browse.php?category_id=' . $category['StockGroupID'] ?? '') ?>"
                           class="HrefDecoration"><?= $category['StockGroupName'] ?? '' ?></a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="<?= get_url('categories.php') ?>" class="HrefDecoration">Alle categorieën</a>
                </li>
            </ul>
        </div>
        <ul id="ul-class-navigation">
            <li>
                <a href="<?= get_url('browse.php') ?>" class="HrefDecoration"><i class="fas fa-search" style="color:#676EFF;"></i> Zoeken</a>
            </li>
        </ul>
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">


