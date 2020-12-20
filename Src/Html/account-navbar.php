<div class="account-navbar">
    <nav class="navbar navbar-expand-sm navbar-dark border-bottom border-white">
        <ul class="navbar-nav w-100">
            <li class="nav-item <?= strpos(get_current_url(), 'account') !== false ? 'active' : '' ?>">
                <a class="nav-link" href="<?= getUrl('account.php') ?>">Account</a>
            </li>
            <?php if (!authorizeAdmin()) : ?>
            <li class="nav-item <?= strpos(get_current_url(), 'orders') !== false ? 'active' : '' ?>">
                <a class="nav-link" href="<?= getUrl('orders.php') ?>">Bestelgeschiedenis</a>
            </li>
            <?php endif; ?>
            <?php if (authorizeAdmin()) : ?>
                <li class="nav-item <?= strpos(get_current_url(), 'contact-requests') !== false ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= getUrl('contact-requests.php') ?>">Contact aanvragen</a>
                </li>
                <li class="nav-item <?= strpos(get_current_url(), 'manage-reviews') !== false ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= getUrl('manage-reviews.php') ?>">Reviews beheren</a>
                </li>
            <?php endif; ?>
            <li class="nav-item ml-auto <?= strpos(get_current_url(), 'logout') !== false ? 'active' : '' ?>">
                <a class="nav-link" href="<?= getUrl('logout.php') ?>">Uitloggen</a>
            </li>
        </ul>
    </nav>
</div>