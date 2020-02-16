<?php
    include 'config/functions.php';
    $amount = 1;
    $detallePago = "Detalle de pago";

    $token = generateToken();
    $sesion = generateSesion($amount, $token);
    $purchaseNumber = generatePurchaseNumber();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $detallePago ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>

    <br>

    <div class="container">
        <h1 class="text-center">Pago con Visa</h1>
        <hr>
        <h3>Información del pago</h3>
        <b style="padding-left:20px;">Importe a pagar: </b> S/. <?= $amount; ?> <br>
        <b style="padding-left:20px;">Número de pedido: </b> <?= $purchaseNumber; ?> <br>
        <b style="padding-left:20px;">Concepto: </b> <?= $detallePago; ?> <br>
        <b style="padding-left:20px;">Fecha: </b> <?= date("d/m/Y"); ?> <br>
        <hr>
        <!-- <h3>Realiza el pago</h3> -->
        <input type="checkbox" name="ckbTerms" id="ckbTerms" onclick="visaNetEc3()"> <label for="ckbTerms">Acepto los <a href="#" target="_blank">Términos y condiciones</a></label>
        <form id="frmVisaNet"
              method="POST"
              action="https://misc.zvu:8000/php/PagoWebPhp/finalizar.php?amount=<?= $amount ?>&purchaseNumber=<?= $purchaseNumber ?>">
            <script src="<?= VISA_URL_JS?>" 
                data-sessiontoken="<?= $sesion;?>"
                data-channel="web"
                data-merchantid="<?= VISA_MERCHANT_ID?>"
                data-merchantlogo="https://misc.zvu:8000/php/PagoWebPhp/assets/img/logo.png"
                data-purchasenumber="<?= $purchaseNumber;?>"
                data-amount="<?= $amount; ?>"
                data-expirationminutes="5"
                data-timeouturl="https://misc.zvu:8000/php/PagoWebPhp/"
                data-recurrence="TRUE"
                data-recurrencetype="FIXED"
                data-recurrencefrequency="MONTHLY"
                data-recurrencemaxamount="12.00"
                data-recurrenceamount="1.00"
            ></script>
        </form>
    </div>
    
</body>
<script src="assets/js/script.js"></script>
</html>