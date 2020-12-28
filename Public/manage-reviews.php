<?php
require_once __DIR__ . '/../Src/header.php';

csrfValidate(getCurrentUrl());

if (!authorizeAdmin()) {
    redirect('Config');
}

$reviews = getReviewedProducts();
$inputDate = getFormDataGet('date');
if (!empty($inputDate)) {
    $reviews = getReviewedProductsByDate($inputDate);
}

$amountReviews = count($reviews);

if (isset($_POST['delete_review'])) {
    $reviewId = getFormDataPost('review_id');
    if (empty($reviewId)) {
        addUserError('Review kon niet worden verwijderd.');
        redirect(getCurrentUrl());
    }

    deleteReviewById($reviewId);
    addUserMessage('Review is succesvol verwijderd.');
    redirect(getCurrentUrl());
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
                                        <form class="form-inline float-right" method="get" action="<?= getUrl('manage-reviews.php') ?>">
                                            <div class="form-group">
                                                <label for="date" class="d-none">Datum</label>
                                                <input type="date" id="date" class="form-control submit-form-on-change" name="date"
                                                       value="<?= $inputDate ?>">
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row">
                                    <?php if ($amountReviews < 1) : ?>
                                        <div class="col-md-12">
                                            <p class="mt-2 font-weight-bold">
                                                Er zijn geen gereviewde producten gevonden.
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
                                                            <?= $review['StockItemName'] ?? '' ?>
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
                                                    $productReviews = getReviewsForProduct($review['StockItemID'][0] ?? 0);
                                                    if (!empty($inputDate)) {
                                                        $productReviews = getReviewsForProductByDate($review['StockItemID'][0] ?? 0, $inputDate);
                                                    }
                                                    ?>
                                                    <div class="tab-pane fade show <?= $active ?>"
                                                         id="list-<?= $key ?>" role="tabpanel"
                                                         aria-labelledby="list-<?= $key ?>">
                                                        <div class="row mb-4">
                                                            <div class="col-sm-12">
                                                                <h3>Reviews voor <?= $review['StockItemName'] ?? '' ?></h3>
                                                            </div>
                                                        </div>

                                                        <table class="table text-white">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Naam</th>
                                                                <th scope="col">Score</th>
                                                                <th scope="col">Review</th>
                                                                <th scope="col">Datum</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach ($productReviews as $productReview) : ?>
                                                                <tr>
                                                                    <th scope="row" width="25px;">
                                                                        <?= $productReview['ReviewID'] ?? 0 ?>
                                                                    </th>
                                                                    <td><?= getReviewAuthor($productReview['ReviewID'])['PreferredName'] ?? '' ?></td>
                                                                    <td style="color: goldenrod;">
                                                                        <?= getRatingStars($productReview['Score']) ?? '' ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if (!empty($productReview['Review'])) : ?>
                                                                            <?= $productReview['Review'] ?? '' ?>
                                                                        <?php else : ?>
                                                                            Geen review geschreven
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?= dateTimeFormatShort($productReview['ReviewDate'] ?? '') ?>
                                                                    </td>
                                                                    <td>
                                                                        <form class="text-right" method="post" action="<?= getCurrentUrl()?>">
                                                                            <input type="hidden" name="token" value="<?=csrfGetToken()?>"/>
                                                                            <input type="hidden" name="review_id" value="<?= $productReview['ReviewID'] ?? 0 ?>">

                                                                            <button type="submit" class="btn btn-outline-danger"
                                                                                    data-confirm="Weet u zeker dat u de review van <?= getReviewAuthor($review['ReviewID'])['FullName'] ?? '' ?> wilt verwijderen?"
                                                                                    name="delete_review">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
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
require_once __DIR__ . '/../Src/footer.php';
?>