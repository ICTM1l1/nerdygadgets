<?php
require_once __DIR__ . "/../Src/header.php";

csrf_validate(get_current_url());

if (!authorizeAdmin()) {
    redirect('Config');
}

$contactRequests = getContactRequests();
if ($date = get_form_data_get('date')) {
    $contactRequests = getContactRequestsByDate($date);
}

$amountContactRequests = count($contactRequests);

if (isset($_POST['delete_contact_request'])) {
    $contact_request_id = get_form_data_post('contact_request_id');
    if (empty($contact_request_id)) {
        add_user_error('Contact aanvraag kon niet worden verwijderd.');
        redirect('contact-requests.php');
    }

    removeContactRequest($contact_request_id);
    add_user_message('Contact aanvraag is succesvol verwijderd.');
    redirect('contact-requests.php');
}
?>

<div class="container-fluid">
    <div class="w-75 mt-1 ml-auto mr-auto">
        <?php include_once __DIR__ . '/../Src/Html/account-navbar.php'; ?>

        <div class="order-overview mt-3 mb-5">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card bg-dark shadow h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col-md-12 mr-2 mb-4">
                                    <div class="h2 font-weight-bold text-primary text-uppercase float-left">
                                        <?php if ($amountContactRequests === 1) : ?>
                                            <?= $amountContactRequests ?> contact aanvraag
                                        <?php else : ?>
                                            <?= $amountContactRequests ?> contact aanvragen
                                        <?php endif; ?>
                                    </div>
                                    <form class="form-inline float-right" method="get" action="<?= get_url('contact-requests.php') ?>">
                                        <div class="form-group">
                                            <label for="date" class="d-none">Datum</label>
                                            <input type="date" id="date" class="form-control submit-form-on-change" name="date"
                                                   value="<?= get_form_data_get('date') ?>">
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                <?php if ($amountContactRequests < 1) : ?>
                                    <div class="col-md-12">
                                        <p class="mt-2 font-weight-bold">
                                            Er zijn geen contact aanvragen gevonden.
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <?php if ($amountContactRequests > 0) : ?>
                                    <div class="col-sm-4 pt-0 mt-0">
                                        <div class="form-label-group">
                                            <label for="searchListGroupItems" class="d-none">
                                                <b>Zoeken</b>
                                            </label>
                                            <input type="text" id="searchListGroupItems"
                                                   class="form-control mb-2"
                                                   autocomplete="off" placeholder="Zoeken">
                                        </div>

                                        <div class="scrollbox-vertical h-500">
                                            <div class="list-group overflow-hidden"
                                                 id="list-tab" role="tablist">
                                                <?php $active = 'active';
                                                foreach ($contactRequests as $key => $contactRequest) : ?>
                                                    <a class="list-group-item list-group-item-action <?= $active ?>"
                                                       id="list-<?= $key ?>-list"
                                                       data-toggle="list" style="z-index: 0;"
                                                       href="#list-<?= $key ?>" role="tab"
                                                       aria-controls="<?= $key ?>">
                                                        <div class="float-left">
                                                            <?= $contactRequest['ContactRequestSubject'] ?? 0 ?>
                                                        </div>
                                                        <div class="float-right">
                                                            <?= dateFormatShort($contactRequest['ContactRequestDate'] ?? '') ?>
                                                        </div>
                                                    </a>
                                                    <?php $active = '';
                                                endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="tab-content text-white" id="nav-tabContent">
                                            <?php $active = 'active';
                                            foreach ($contactRequests as $key => $contactRequest) :
                                                $requestID = $contactRequest['ContactRequestID'] ?? 0;
                                                ?>
                                                <div class="tab-pane fade show <?= $active ?>"
                                                     id="list-<?= $key ?>" role="tabpanel"
                                                     aria-labelledby="list-<?= $key ?>">
                                                    <div class="row">
                                                        <div class="col-sm-11">
                                                            <h1><?= dateFormatFull($contactRequest['ContactRequestDate'] ?? '') ?></h1>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <form class="text-right" method="post">
                                                                <input type="hidden" name="token" value="<?=csrf_get_token()?>"/>
                                                                <input type="hidden" name="contact_request_id"
                                                                       value="<?= $contactRequest['ContactRequestID'] ?? 0 ?>">

                                                                <button class="btn btn-outline-danger"
                                                                        onclick="return confirm('Weet u zeker dat u de contact aanvraag `<?= replaceDoubleQuotesForWhiteSpaces($contactRequest['ContactRequestSubject'] ?? "") ?>` wilt verwijderen?')"
                                                                        name="delete_contact_request">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-2 pt-2 border-top border-white">
                                                        <div class="col-md-12">
                                                            <div class="row mt-4 border-bottom border-white pb-3">
                                                                <div class="col-sm-3 font-weight-bold h4">Naam: </div>
                                                                <div class="col-sm-9">
                                                                    <?= $contactRequest['ContactRequestName'] ?? '' ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-4 border-bottom border-white pb-3">
                                                                <div class="col-sm-3 font-weight-bold h4">Email: </div>
                                                                <div class="col-sm-9">
                                                                    <?= $contactRequest['ContactRequestEmail'] ?? '' ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-4 border-bottom border-white pb-3">
                                                                <div class="col-sm-3 font-weight-bold h4">Onderwerp: </div>
                                                                <div class="col-sm-9">
                                                                    <?= $contactRequest['ContactRequestSubject'] ?? '' ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-4">
                                                                <div class="col-sm-3 font-weight-bold h4">Bericht: </div>
                                                                <div class="col-sm-9">
                                                                    <?= $contactRequest['ContactRequestMessage'] ?? '' ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php $active = '';
                                            endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>