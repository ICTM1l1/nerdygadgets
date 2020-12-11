<?php

/**
 * Retrieve all reviews for stock item.
 *
 * @param int $id
 *   ID of item to retrieve reviews for.
 * @return array
 *   Retrieved reviews.
 */
function getAllReviewsForItem(int $id){
    return select("
        SELECT * FROM review
        WHERE StockItemID = :id",
    ["id" => $id]);
}

/**
 * Retrieve all reviews for stock item.
 *
 * @param int $id
 *   ID of item to retrieve reviews for.
 * @param int $limit
 *   Limit the number of returned reviews.
 *
 * @return array
 *   Retrieved reviews.
 */
function getLimitedReviewsForItem(int $id, int $limit = 3){
    return select("
        SELECT * FROM review
        WHERE StockItemID = :id
        ORDER BY ReviewID DESC
        LIMIT :limit",
        ["id" => $id, 'limit' => $limit]);
}

/**
 * Retrieve average score of all reviews for an item.
 *
 * @param int $id
 *   ID of item to retrieve average for.
 * @return int
 *   Average score of all reviews. 0 when an error occurred.
 */
function getReviewAverageByID(int $id){
    return selectFirst("
        SELECT Average FROM average_score
        WHERE StockItemID = :id",
    ["id" => $id])["Average"] ?? 0;
}

/**
 * Update average score for an item.
 *
 * @param int $id
 *   Item to update average for.
 * @return int
 *   ID of last inserted average.
 */
function updateAverageByID(int $id){
    $reviews = getAllReviewsForItem($id);

    $sum = 0;
    foreach($reviews as $review){
        $sum += (int) ($review["Score"] ?? 0);
    }

    $amountReviews = count($reviews);
    $avg = $sum / $amountReviews;

    delete("average_score", ["StockItemID" => $id]);
    return insert("average_score", ["StockItemID" => $id, "Average" => $avg]);
}

/**
 * Add review to database.
 *
 * @param int $sid
 *   ID of stock item to add review for.
 * @param int $pid
 *   ID of person submitting review.
 * @param int $score
 *   Score of the review.
 * @param string $review
 *   Text contents of the review.
 * @return int
 *   Last inserted item ID.
 */
function createReview(int $sid, int $pid, int $score, string $review){
    $id = insert("review", [
        "StockItemID" => $sid,
        "Review" => $review,
        "PrivateCustomerID" => $pid,
        "Score" => $score
    ]);

    updateAverageByID($sid);

    return $id;
}