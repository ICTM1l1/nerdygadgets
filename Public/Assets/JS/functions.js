$(document).ready(function () {
    $("#searchListGroupItems").on("keyup", function () {
        const value = $(this).val().toLowerCase();
        $(".list-group a").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

/**
 * Counts the number of chars and updates it.
 *
 * @param selector
 *   Displays the counted chars.
 * @param str
 *   The string to be counted.
 * @param length
 *   The length of allowed chars.
 */
function charCountUpdate(selector, str, length) {
    var lng = str.length;
    document.getElementById(selector).innerHTML = lng + ' van de ' + length + ' maximale karakters';
}