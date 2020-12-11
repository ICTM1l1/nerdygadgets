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

    $('.count-characters-100').on('keyup', function () {
        const input_field = $(this);
        const char_count = input_field.closest('.form-group').find('#char-count');

        charCount(char_count, input_field.val(), 100)
    });

    $('.count-characters-250').on('keyup', function () {
        const input_field = $(this);
        const char_count = input_field.closest('.form-group').find('#char-count');

        charCount(char_count, input_field.val(), 250)
    });

    $('.count-characters-2000').on('keyup', function () {
        const input_field = $(this);
        const char_count = input_field.closest('.form-group').find('#char-count');

        charCount(char_count, input_field.val(), 2000)
    })

    $('button[data-confirm]').on('click', function () {
        const button = $(this);
        const confirm_text = button.data('confirm');

        return confirm(confirm_text);
    })

    /**
     * Counts the length of the chars.
     *
     * @param char_count
     *   The char count display div selector.
     * @param value
     *   The value.
     * @param max_length
     *   The max length.
     */
    function charCount(char_count, value, max_length) {
        const lng = value.length;
        const remainingLengthInPercentage = lng / max_length * 100;

        char_count.hide();
        if (remainingLengthInPercentage > 80) {
            char_count.show();
            char_count.html(lng + ' van de 100 maximale karakters')
        }
    }

    $('.submit-form-on-change').on('change', function () {
        $(this).closest('form').submit();
    })

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
 * Submits a form.
 *
 * @param token
 *   The token.
 */
function onSubmit(token) {
    document.getElementById("recaptcha-form").submit();
}

function getStarsFromRating(rating){
    if(rating > 5) return;
    let stars = [];
    let i = 1;
    const estar = '<i class="far fa-star"></i>';
    const fstar = '<i class="fas fa-star"></i>';
    for(; i <= rating; i++){
        stars.push('<i class="fas fa-star" onclick="handleStars(' + i + ')"></i> ');
    }
    for(; i <= 5; i++){
        stars.push('<i class="far fa-star" onclick="handleStars(' + i + ')"></i> ');
    }
    return stars;
}

function handleStars(rating){
    console.log(rating);
    const stars = getStarsFromRating(rating);
    const wrapper = document.getElementById("score-container").children[0];
    wrapper.querySelectorAll("*").forEach(n => n.remove());
    stars.forEach(star => wrapper.insertAdjacentHTML("beforeend", star));
    document.getElementById("score-value").value = rating;
    document.getElementById("submit-review").disabled = false;
}