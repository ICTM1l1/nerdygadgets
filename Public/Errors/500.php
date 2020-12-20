<!DOCTYPE html>
<html lang="en" style="background-color: rgb(35, 35, 47);">
<head>
    <meta charset="ISO-8859-1">
    <title>NerdyGadgets</title>

    <link rel="stylesheet" href="<?= getAssetUrl('CSS/Style.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= getAssetUrl('CSS/bootstrap.min.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= getAssetUrl('CSS/fonts.css') ?>">

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
    <link rel="manifest" href="<?= getAssetUrl('/Favicon/manifest.json') ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?= getAssetUrl('Favicon/ms-icon-144x144.png') ?>">
    <meta name="theme-color" content="#ffffff">
    <style>
        @font-face {
            font-family: MmrText;
            src: url("<?= getAssetUrl('fonts/mmrtext.ttf') ?>");
        }
    </style>
</head>
<body>
<div class="Background">
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">
                <div class="row">
                    <div class="col-sm-12 mt-2 text-center">
                        <h1>500 - Interne fout</h1>
                        <p>Er is een interne fout opgetreden, neem contact op met de beheerder.</p>
                    </div>

                    <div class="col-sm-12 mt-2 text-center">
                        <a href="<?= get_url('index.php') ?>">Ga terug naar de homepagina.</a>
                    </div>
                </div>
<?php
require_once __DIR__ . "/../../Src/footer.php";
?>

