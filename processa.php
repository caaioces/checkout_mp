<?php
include('config.php');
include('ApiConfig.php');

$json       = file_get_contents('php://input');
$result_request  = json_decode($json);
$id_reg = $result_request->id_reg;
$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
         "transaction_amount": '.(float)$result_request->transaction_amount.',
         "token": "'.$result_request->token.'",
         "description": "'.$result_request->description.'",
         "installments": '.$result_request->installments.',
         "payment_method_id": "'.$result_request->payment_method_id.'",
         "issuer_id": '.$result_request->issuer_id.',
         "payer": {
           "email": "'.$result_request->payer->email.'"
         }
   }',
    CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'content-type: application/json',
        'X-Idempotency-Key:' . '123',
        'Authorization: Bearer '.$access_token
    ),
    ));
    $response = curl_exec($curl);
    $resultado = json_decode($response);
    
    var_dump($response);
curl_close($curl);
$status = $resultado->status;

$sql="INSERT INTO status(nome, status, id_venda) VALUES('".$id_reg."','".$resultado->status."','".$resultado->id."')";
    mysqli_query($conexao, $sql);
?>
