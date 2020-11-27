$(document).ready(function () {
    $("#searchListGroupItems").on("keyup", function () {
        var value = $(this).val().toLowerCase();
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
});
