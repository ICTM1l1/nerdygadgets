$(document).ready(function () {
    $("#searchListGroupItems").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".list-group a").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});