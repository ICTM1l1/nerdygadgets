<div class="account-navbar">
    <nav class="navbar navbar-expand-sm navbar-dark border-bottom border-white">
        <ul class="navbar-nav w-100">
            <li class="nav-item <?= strpos(get_current_url(), 'account') !== false ? 'active' : '' ?>">
                <a class="nav-link" href="<?= get_url('account.php') ?>">Account</a>
            </li>
            <li class="nav-item <?= strpos(get_current_url(), 'orders') !== false ? 'active' : '' ?>">
                <a class="nav-link" href="<?= get_url('orders.php') ?>">Bestelgeschiedenis</a>
            </li>
            <?php
            $personID = session_get('personID', 0);
            $account = getCustomerByPeople($personID);
            $account_name = $account['PrivateCustomerName'] ?? '';
            if (stripos($account_name, 'admin') !== false) : ?>
                <li class="nav-item <?= strpos(get_current_url(), 'contact-requests') !== false ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= get_url('contact-requests.php') ?>">Contact aanvragen</a>
                </li>
            <?php endif; ?>
            <li class="nav-item ml-auto <?= strpos(get_current_url(), 'logout') !== false ? 'active' : '' ?>">
                <a class="nav-link" href="<?= get_url('logout.php') ?>">Uitloggen</a>
            </li>
        </ul>
    </nav>
</div>