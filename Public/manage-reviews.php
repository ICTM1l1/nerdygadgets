<?php
require_once __DIR__ . "/../Src/header.php";

csrf_validate(get_current_url());

if (!authorizeAdmin()) {
    redirect('Config');
}

$reviews = getReviewedProducts();
$input_date = get_form_data_get('date');
if (!empty($input_date)) {
    $reviews = getReviewedProductsByDate($input_date);
}

$amountReviews = count($reviews);

if (isset($_POST['delete_review'])) {
    $reviewid = get_form_data_post('reviewid');
    if (empty($reviewid)) {
        add_user_error('Review kon niet worden verwijderd.');
        redirect(get_current_url());
    }

    deleteReviewByID($reviewid);
    add_user_message('Review is succesvol verwijderd.');
    redirect(get_current_url());
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
                                        <?php if ($amountReviews === 1) : ?>
                                            <?= $amountReviews ?> gereviewde product
                                        <?php else : ?>
                                            <?= $amountReviews ?> gereviewde producten
                                        <?php endif; ?>
                                    </div>
                                    <form class="form-inline float-right" method="get" action="<?= get_url('manage-reviews.php') ?>">
                                        <div class="form-group">
                                            <label for="date" class="d-none">Datum</label>
                                            <input type="date" id="date" class="form-control submit-form-on-change" name="date"
                                                   value="<?= $input_date ?>">
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                <?php if ($amountReviews < 1) : ?>
                                    <div class="col-md-12">
                                        <p class="mt-2 font-weight-bold">
                                            Er zijn geen reviews gevonden.
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <?php if ($amountReviews > 0) : ?>
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
                                                foreach ($reviews as $key => $review) : ?>
                                                    <a class="list-group-item list-group-item-action <?= $active ?>"
                                                       id="list-<?= $key ?>-list"
                                                       data-toggle="list" style="z-index: 0;"
                                                       href="#list-<?= $key ?>" role="tab"
                                                       aria-controls="<?= $key ?>">
                                                        <div class="row">
                                                            <div class="col-sm-7">
                                                                <?= $review['StockItemName'] ?? '' ?>
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <?= dateTimeFormatShort($review['ReviewDate'] ?? '') ?>
                                                            </div>
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
                                            foreach ($reviews as $key => $review) :
                                                $productReviews = getAllReviewsForItem($review['StockItemID'][0] ?? 0);
                                                ?>
                                                <div class="tab-pane fade show <?= $active ?>"
                                                     id="list-<?= $key ?>" role="tabpanel"
                                                     aria-labelledby="list-<?= $key ?>">
                                                    <?php if (!empty($productReviews)) : ?>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="row border-bottom mb-3">
                                                                    <div class="col-sm-12 h4">
                                                                        Reviews voor <?= $review['StockItemName'] ?? '' ?>
                                                                    </div>
                                                                </div>
                                                                <?php foreach ($productReviews as $productReview) : ?>
                                                                    <div class="row">
                                                                        <div class="col-sm-11">
                                                                            <h3><?= dateTimeFormatShort($review["ReviewDate"] ?? '') ?></h3>
                                                                        </div>
                                                                        <div class="col-sm-1">
                                                                            <form class="text-right" method="post" action="<?= get_current_url()?>">
                                                                                <input type="hidden" name="token" value="<?=csrf_get_token()?>"/>
                                                                                <input type="hidden" name="reviewid" value="<?= $reviewID ?? 0 ?>">

                                                                                <button type="submit" class="btn btn-outline-danger"
                                                                                        data-confirm="Weet u zeker dat u de review van <?= getReviewAuthor($review["ReviewID"])["FullName"] ?? "" ?> wilt verwijderen?"
                                                                                        name="delete_review">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>

                                                                        <div class="col-sm-12">
                                                                            <div class="row mt-4 border-bottom border-white pb-3">
                                                                                <div class="col-sm-3 font-weight-bold h4">Naam: </div>
                                                                                <div class="col-sm-9">
                                                                                    <h3><?= getReviewAuthor($review["ReviewID"])["FullName"] ?? '' ?></h3>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mt-4 border-bottom border-white pb-3">
                                                                                <div class="col-sm-3 font-weight-bold h4">Score: </div>
                                                                                <div class="col-sm-9">
                                                                                    <h3 style="color: goldenrod;"><?= getRatingStars($review["Score"]) ?? '' ?></h3>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mt-4 border-bottom border-white pb-3">
                                                                                <div class="col-sm-3 font-weight-bold h4">Review: </div>
                                                                                <div class="col-sm-9">
                                                                                    <h3><?= $review["Review"] ?? '' ?></h3>
                                                                                </div>
                                                                            </div
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    <?php else : ?>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p>Er zijn geen reviews gevonden voor dit product.</p>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
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