<?php
session_start();

require_once __DIR__ . '/../Src/Core/environment.php';
require_once __DIR__ . '/../Src/Core/functions.php';
require_once __DIR__ . '/../Src/Core/database.php';
require_once __DIR__ . '/../Src/Crud/crud.php';

$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en" style="background-color: rgb(35, 35, 47);">
<head>
    <meta charset="ISO-8859-1">
    <title>NerdyGadgets</title>

    <link rel="stylesheet" href="Assets/CSS/Style.css" type="text/css">
    <link rel="stylesheet" href="Assets/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Assets/CSS/fonts.css">

    <link rel="apple-touch-icon" sizes="57x57" href="Assets/Favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="Assets/Favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="Assets/Favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="Assets/Favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="Assets/Favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="Assets/Favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="Assets/Favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="Assets/Favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="Assets/Favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="Assets/Favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Assets/Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="Assets/Favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Assets/Favicon/favicon-16x16.png">
    <link rel="manifest" href="Assets/Favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="Public/Favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <style>
        @font-face {
            font-family: MmrText;
            src: url(/Public/Assets/fonts/mmrtext.ttf);
        }
    </style>
</head>
<body>
<div class="Background">
    <div class="row" id="Header">
        <div class="col-2"><a href="index.php" id="LogoA">
                <div id="LogoImage"></div>
            </a></div>
        <div class="col-8" id="CategoriesBar">
            <ul id="ul-class">
                <?php foreach($categories as $category) : ?>
                    <li>
                        <a href="browse.php?category_id=<?= $category['StockGroupID'] ?? '' ?>"
                           class="HrefDecoration"><?= $category['StockGroupName'] ?? '' ?></a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="categories.php" class="HrefDecoration">Alle categorieën</a>
                </li>
            </ul>
        </div>
        <ul id="ul-class-navigation">
            <li>
                <a href="browse.php" class="HrefDecoration"><i class="fas fa-search" style="color:#676EFF;"></i> Zoeken</a>
            </li>
        </ul>
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">


