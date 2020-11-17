<?php
require_once __DIR__ . "/../Src/header.php";
?>
<!--<div id="ProductFrameShoppingCart">-->
<!--    <div id="ImageCart">-->
<!--        <img scr="hoodie.png">-->
<!--    </div>-->
<!--    <div id="ProductAmount">-->
<!--        <h3>Aantal</h3>-->
<!--    </div>-->
<!--    <div id="CartPlus">-->
<!--        <p>+</p>-->
<!--    </div>-->
<!--    <div id="CartMin">-->
<!--        <p>-</p>-->
<!--    </div>-->
<!--    <div id="Product">-->
<!--        <h3>test</h3>-->
<!--        <h3>Productnaam</h3>-->
<!--    </div>-->
<!--    <div id="Price">-->
<!--        <h3>Prijs</h3>-->
<!--    </div>-->
<!--    <br>-->
<!--    <div id="BTW">-->
<!--        <h3>Include BTW</h3>-->
<!--    </div>-->
<!---->
<!--    <div id="Guarantee">-->
<!--        <h3>Garantie</h3>-->
<!--    </div>-->
<!--    <div id="ProductAmountStock">-->
<!--        <h3>Aantal producten</h3>-->
<!--    </div>-->
<!--</div>-->

<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-8">
        <h1>Winkelwagen</h1>

        <table class="w-100">
            <tbody>
            <?php for ($x = 0; $x < 10; $x++) : ?>
                <tr class="border border-white">
                    <td>
                        <div id="ImageFrame" style="background-image:
                        url('http://localhost/nerdygadgets/Assets/StockItemIMG/The gu (white).png');
                        background-size: 250px; background-repeat: no-repeat; background-position: center;"
                    </td>
                    <td style="margin-top: 5%">
                        <h1 style="float: left">product X</h1>
                    </td>
                    <td>
                        <?php
                        $pricePerPieceBTW = 99.99;
                        $priceTotalBTW = $pricePerPieceBTW * 20;
                        $pricePerPiece = ($pricePerPieceBTW / 121) * 21;
                        $priceTotal = $pricePerPieceBTW * 20;

                        ?>

                        <b>Per stuk: </b> &euro; <?= number_format($pricePerPieceBTW, 2, ",", ".") ?> <br>



                    <td style="float: right">
                        <div class="row">


                        <p style="text-align: right">
                        <form method="get" action="<?= get_current_url() ?>" style="margin-right: 20px; margin-top: 10px">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" name="Add_Product">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="submit" class="btn btn-danger" name="Min_Product">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </form>
                        <h1>20 X</h1>
                        </p>
                        </div>
                        <br>
                        <div style="margin-top: 100%">
                            <b>Totaal excl BTW: </b> &euro; <?= number_format($priceTotal, 2, ',', '.') ?>
                            <br>
                            <b>Totaal incl BTW: </b> &euro; <?= number_format($priceTotalBTW, 2, ',', '.') ?>
                        </div>
                    </td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>
