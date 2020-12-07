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

    $('#password').password({
        enterPass: '',
        shortPass: 'Wachtwoord is te kort',
        containsField: 'Dit wachtwoord bevat je gebruikersnaam',
        badPass: 'Zwak; probeer letters en cijfers te combineren',
        goodPass: 'Medium; probeer speciale tekens te gebruiken',
        strongPass: 'Sterk wachtwoord',
        steps: {
            // Easily change the steps' expected score here
            13: 'Echt onveilig wachtwoord',
            33: 'Zwak; probeer letters en cijfers te combineren',
            67: 'Medium; probeer speciale tekens te gebruiken',
            94: 'Sterk wachtwoord',
        },
        showPercent: true,
        showText: true, // shows the text tips
        animate: true, // whether or not to animate the progress bar on input blur/focus
        animateSpeed: 'fast', // the above animation speed
        field: false, // select the match field (selector or jQuery instance) for better password checks
        fieldPartialMatch: true, // whether to check for partials in field
        minimumLength: 8, // minimum password length (below this threshold, the score is 0)
        useColorBarImage: true, // use the (old) colorbar image
        customColorBarRGB: {
            red: [0, 240],
            green: [0, 240],
            blue: 10,
        } // set custom rgb color ranges for colorbar.
    }).on('password.score', function (e, score) {
        const registerButton = $('#registerSubmit');

        registerButton.attr('disabled', true);
        if (score > 75) {
            registerButton.removeAttr('disabled');
        }
    });

    $("#password").keyup(checkPasswordMatch);
    $("#password2").keyup(checkPasswordMatch);

    /**
     * Checks if the passwords are equal.
     */
    function checkPasswordMatch() {
        const password = $("#password").val();
        const confirmPassword = $("#password2").val();
        const feedback = $("#divCheckPasswordMatch");

        if (password !== confirmPassword) {
            feedback.html("Wachtwoorden zijn niet gelijk!").removeClass('text-success').addClass('text-danger');
            return;
        }

        feedback.html("Wachtwoorden zijn gelijk.").removeClass('text-danger').addClass('text-success');
    }
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
    const lng = str.length;
    const remainingLengthInPercentage = lng / length * 100;
    if (remainingLengthInPercentage > 80) {
        document.getElementById(selector).innerHTML = lng + ' van de ' + length + ' maximale karakters';
    }
}
