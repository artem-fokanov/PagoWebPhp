<?php
    include 'config/functions.php';
    $transactionToken = $_POST["transactionToken"];
    $email = $_POST["customerEmail"];
    $amount = $_GET["amount"];
    $purchaseNumber = $_GET["purchaseNumber"];    

    $token = generateToken();

    $data = generateAuthorization($amount, $purchaseNumber, $transactionToken, $token);

class ResponseIterator extends RecursiveIteratorIterator {
    public function beginChildren()
    {
        parent::beginChildren();
        $parent = $this->getDepth()-1;
        $indent = str_repeat('   ', $parent);
        echo "$indent ├ {$this->getSubIterator($parent)->key()}\n";
    }

    public function current()
    {
        $val = parent::current();
        $key = $this->key();

        $indent = str_repeat('   ', $this->getDepth());
        return "$indent ├ $key = $val\n";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Respuesta de pago</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>

    <br>

    <div class="container">
        <?php 
            if (isset($data->dataMap)) {
                if ($data->dataMap->ACTION_CODE == "000") {
                    $c = preg_split('//', $data->dataMap->TRANSACTION_DATE, -1, PREG_SPLIT_NO_EMPTY);
                    ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $data->dataMap->ACTION_DESCRIPTION;?>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <b>Número de pedido: </b> <?php echo $purchaseNumber; ?>
                            </div>
                            <div class="col-md-12">
                                <b>Fecha y hora del pedido: </b> <?php echo $c[4].$c[5]."/".$c[2].$c[3]."/".$c[0].$c[1]." ".$c[6].$c[7].":".$c[8].$c[9].":".$c[10].$c[11]; ?>
                            </div>
                            <div class="col-md-12">
                                <b>Tarjeta: </b> <?php echo $data->dataMap->CARD." (".$data->dataMap->BRAND.")"; ?>
                            </div>
                            <div class="col-md-12">
                                <b>Importe pagado: </b> <?php echo $data->order->amount. " ".$data->order->currency; ?>
                            </div>
                        </div>
                    <?php
                }
            } else {
                $c = preg_split('//', $data->data->TRANSACTION_DATE, -1, PREG_SPLIT_NO_EMPTY);
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $data->data->ACTION_DESCRIPTION;?>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <b>Número de pedido: </b> <?php echo $purchaseNumber; ?>
                        </div>
                        <div class="col-md-12">
                            <b>Fecha y hora del pedido: </b> <?php echo $c[4].$c[5]."/".$c[2].$c[3]."/".$c[0].$c[1]." ".$c[6].$c[7].":".$c[8].$c[9].":".$c[10].$c[11]; ?>
                        </div>
                        <div class="col-md-12">
                            <b>Tarjeta: </b> <?php echo $data->data->CARD." (".$data->data->BRAND.")"; ?>
                        </div>
                    </div>
                <?php
            }
        ?>
        <div class="row mt-3">
            <div class="col-md-12">
                <p><b>Response:</b></p>
                <pre>
                    <?php
                    $it = new RecursiveArrayIterator($data);
                    $response = new ResponseIterator($it);

                    foreach ($response as $param) {
                        echo $param;
                    } ?>
                </pre>
            </div>
        </div>
    </div>
    
</body>
</html>