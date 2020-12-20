<div class="row">
    <div class="col-sm-12">
        <h1 class="mb-5 float-left">
            <?php
            if (strpos(get_current_url(), 'checkout') !== false) {
                echo '1. Bezorggegevens';
            } elseif (strpos(get_current_url(), 'payment') !== false) {
                echo '2. Afrekenen';
            } elseif (strpos(get_current_url(), 'transactioncomplete') !== false) {
                echo '3. Afronden';
            }
            ?>
        </h1>

        <div class="form-progress float-right">
            <nav class="navbar navbar-expand-sm bg-primary navbar-dark border border-white" style="border-radius: 5px">
                <ul class="navbar-nav">
                    <li class="nav-item border-right border-white <?= strpos(get_current_url(), 'checkout') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= getUrl('checkout.php') ?>">1. Bezorggegevens</a>
                    </li>
                    <li class="nav-item border-right border-white  <?= strpos(get_current_url(), 'payment') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="#">2. Afrekenen</a>
                    </li>
                    <li class="nav-item  <?= strpos(get_current_url(), 'transactioncomplete') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="#">3. Afronden</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>