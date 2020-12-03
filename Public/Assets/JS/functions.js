$(document).ready(function () {
    $("#searchListGroupItems").on("keyup", function () {
        const value = $(this).val().toLowerCase();
        $(".list-group a").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#search_string").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".products-view .ListItem").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#password2").keyup(checkPasswordMatch);
});

function checkPasswordMatch() {
    var password = $("#password").val();
    var confirmPassword = $("#password2").val();

    if (password !== confirmPassword) {
        $("#divCheckPasswordMatch").html("Wachtwoorden zijn niet gelijk!").removeClass('text-success').addClass('text-danger');
    }
    else {
        $("#divCheckPasswordMatch").html("Wachtwoorden zijn gelijk.").removeClass('text-danger').addClass('text-success');
    }
}

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
    const lng = str.length;
    const remainingLengthInPercentage = lng / length * 100;
    if (remainingLengthInPercentage > 80) {
        document.getElementById(selector).innerHTML = lng + ' van de ' + length + ' maximale karakters';
    }
}
