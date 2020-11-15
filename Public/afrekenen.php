<?php
require_once __DIR__ . "/../Src/header.php";
#check hier of the klant is ingelogd, zo wel pak de NAW gegevens en skip de form direct naar afbetalen anders NAW gegevens via de form krijgen
?>
<form class="text-center" action="payment.php" method="get">
    <p class="h1 mb-5 my-4">Afrekenen</p>
    <div class="form-row mb-3 col-2 mx-auto">
        <label for="NaamLabel">Naam</label>
        <input type="text" id="Naam" class="form-control" placeholder="Naam">
    </div>
    <label for="AdresLabel">Adres</label>
    <div class="form-row mb-3 justify-content-md-center">
        <div class="col-2">
            <input type="text" id="Postcode" class="form-control" placeholder="1234 AB">
        </div>
        <div class="col-1">
            <input type="text" id="Huisnummer" class="form-control" placeholder="Huisnummer">
        </div>
    </div>
    <div class="form-row mb-3 col-2 mx-auto">
        <label for="StraatnaamLabel">Straatnaam</label>
        <input type="text" id="Straatnaam" class="form-control" placeholder="Straatnaam">
    </div>
    <div class="form-row mb-3 col-2 mx-auto">
        <label for="WoonplaatsLabel">Woonplaats</label>
        <input type="text" id="Woonplaats" class="form-control" placeholder="Woonplaats">
    </div>
    <div class="form-row mb-3 col-2 mx-auto">
        <label for="EmailLabel">E-Mail</label>
        <input type="email" id="Email" class="form-control" placeholder="E-mail">
    </div>
    <div class="form-row mb-3 col-2 mx-auto">
        <label for="TelefoonnummerLabel">Telefoonnummer</label>
        <input type="text" id="Telefoonnummer" class="form-control" placeholder="Telefoonnummer">
    </div>
    <button class="btn btn-success my-4" type="submit">Betalen</button>
</form>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>