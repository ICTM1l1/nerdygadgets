<?php

/**
 * Retrieve all reviews for product.
 *
 * @param int $product
 *   Id of product to retrieve reviews for.
 * 
 * @return array
 *   Retrieved reviews.
 */
function getReviewsForProduct(int $product) {
    return select('
        SELECT * FROM review
        WHERE StockItemID = :product',
    ['product' => $product]);
}

/**
 * Retrieve all reviews for product.
 *
 * @param int $product
 *   Id of product to retrieve reviews for.
 * @param string $date
 *   The date of which the reviews are to be retrieved.
 *
 * @return array
 *   Retrieved reviews.
 */
function getReviewsForProductByDate(int $product, string $date) {
    return select('
        SELECT * FROM review
        WHERE StockItemID = :product
        AND DATE(ReviewDate) = :date
    ', ['product' => $product, 'date' => $date]);
}

/**
 * Gets all reviews.
 *
 * @return array
 *   The reviews.
 */
function getReviewedProducts() {
    return select('
        SELECT * 
        FROM review R
        JOIN stockitems SI ON SI.StockItemID = R.StockItemID
        GROUP BY SI.StockItemID
    ');
}

/**
 * Gets the reviews for an author.
 *
 * @param int $review
 *   The id of the author.
 *
 * @return array
 *   The reviews for the author.
 */
function getReviewAuthor(int $review) {
    return selectFirst('
        SELECT * FROM people JOIN review
        ON people.personid = review.personid
        WHERE reviewid = :review
    ', ['review' => $review]);
}

/**
 * Get all reviews added on a specific date.
 *
 * @param string $date
 *   The date of which the reviews are to be retrieved.
 *
 * @return array
 *   An array of the reviews filed on the specified date.
 */
function getReviewedProductsByDate(string $date) {
    return select('
        SELECT * 
        FROM review R
        JOIN stockitems SI ON SI.StockItemID = R.StockItemID
        WHERE DATE(ReviewDate) = :date
        GROUP BY SI.StockItemID
    ', ['date' => $date]);
}

/**
 * Retrieve all reviews for product.
 *
 * @param int $product
 *   Id of product to retrieve reviews for.
 * @param int $limit
 *   Limit the number of returned reviews.
 *
 * @return array
 *   Retrieved reviews.
 */
function getLimitedReviewsForProduct(int $product, int $limit = 3) {
    return select('
        SELECT * FROM review
        WHERE StockItemID = :product
        ORDER BY ReviewID DESC
        LIMIT :limit
    ', ['product' => $product, 'limit' => $limit]);
}

/**
 * Retrieve average score of all reviews for a product.
 *
 * @param int $product
 *   Id of product to retrieve average for.
 *
 * @return int
 *   Average score of all reviews. 0 when an error occurred.
 */
function getReviewAverageByProduct(int $product) {
    return selectFirst('
        SELECT Average FROM average_score
        WHERE StockItemID = :id
    ', ['id' => $product])['Average'] ?? 0;
}

/**
 * Update average score for an product.
 *
 * @param int $product
 *   Product to update average for.
 *
 * @return bool
 *   Whether the update was successful.
 */
function updateAverageByProduct(int $product) {
    $reviews = getReviewsForProduct($product);

    $sum = 0;
    foreach ($reviews as $review) {
        $sum += (int) ($review['Score'] ?? 0);
    }

    $amountReviews = count($reviews);
    if ($amountReviews === 0) {
        delete('average_score', ['StockItemID' => $product]);
        return 0;
    }

    $avg = $sum / $amountReviews;

    delete('average_score', ['StockItemID' => $product]);
    $product = insert('average_score', ['StockItemID' => $product, 'Average' => $avg]);

    return !empty($product);
}

/**
 * Add review to database.
 *
 * @param int $product
 *   Id of product to add review for.
 * @param int $person
 *   Id of person submitting review.
 * @param int $score
 *   Score of the review.
 * @param string $review
 *   Text contents of the review.
 *
 * @return int
 *   Last inserted product ID.
 */
function createReviewForProduct(int $product, int $person, int $score, string $review) {
    $id = insert('review', [
        'StockItemID' => $product,
        'Review' => $review,
        'PersonID' => $person,
        'Score' => $score
    ]);

    if (!empty($id)) {
        updateAverageByProduct($product);

        return $id;
    }

    return 0;
}

/**
 * Checks if a product was reviewed by a customer.
 *
 * @param int $product
 *   Id of the product to check.
 * @param int $person
 *   Id of the person to check.
 *
 * @return bool
 *   True if the product was reviewed by the customer.
 */
function productWasReviewedByCustomer(int $product, int $person) {
    $reviews = select('
        SELECT * FROM review
        WHERE StockItemID = :product
        AND PersonID = :person
    ',['product' => $product, 'person' => $person]);

    return !(count($reviews) === 0);
}

/**
 * Gets the review for a product by a customer.
 *
 * @param int $product
 *   Id of the product to check.
 * @param int $person
 *   Id of the person to check.
 *
 * @return array
 *   The review for the product.
 */
function getProductReviewByCustomer(int $product, int $person) {
    return selectFirst('
        SELECT * FROM review
        WHERE StockItemID = :product
        AND PersonID = :person
    ',['product' => $product, 'person' => $person]);
}

/**
 * Deletes a review by a user.
 *
 * @param int $product
 *   Id of the product to delete the review from.
 * @param int $person
 *   Id of the people who wrote the review.
 *
 * @return bool
 *   True if deletion was successful.
 */
function deleteReview(int $product, int $person) {
    return !delete('review',[
        'StockItemID' => $product,
        'PersonID' => $person
    ]) && updateAverageByProduct($product);
}

/**
 * Deletes a review by Id.
 *
 * @param int $review
 *   Id of the review to delete.
 *
 * @return bool
 *   True if deletion was successful.
 */
function deleteReviewById(int $review) {
    $productId = selectFirst('
        SELECT StockItemID FROM review
        WHERE ReviewID = :id
    ', ['id' => $review])['StockItemID'] ?? 0;

    return !delete('review',[
        'ReviewID' => $review
    ]) && updateAverageByProduct($productId);
}