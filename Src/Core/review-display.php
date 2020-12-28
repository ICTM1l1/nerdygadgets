<?php

/**
 * Get a string representation of the stars to display for a certain rating.
 *
 * @param int $rating
 *   Rating to get the stars for.
 * @return string
 *   String containing the elements to display.
 */
function getRatingStars(int $rating){
    $estar = '<i class="far fa-star"></i>';
    $fstar = '<i class="fas fa-star"></i>';

    $result = "";
    $i = 0;
    for (; $i < $rating && $i < 5; $i++) {
        $result .= $fstar;
    }

    for (; $i < 5; $i++) {
        $result .= $estar;
    }

    return $result;
}