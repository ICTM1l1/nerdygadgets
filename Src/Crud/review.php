<?php
function createReview(int $sid, int $pid, string $review){
    return insert("review", [
        "StockItemID" => $sid,
        "Review" => $review,
        "PrivateCustomerID" => $pid
    ]);
}