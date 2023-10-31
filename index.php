<?php
$api_url = 'https://api.dtb-bank.com/infinitipay/v2/payments/paymentRequest';
$api_key = 'API_KEY'; 

$data = [
    'transactionId' => 'SDDS9234234',
    'amount' => 10,
    'merchantId' => 95,
    'transactionTypeId' => 1,
    'payerAccount' => '254712046329',
    'narration' => 'Testing',
];

$headers = [
    'Authorization: Bearer ' . $api_key,
    'Content-Type: application/json',
];

$data_json = json_encode($data);

$ch = curl_init($api_url);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($response === false) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_code == 200) {
        $response_data = json_decode($response, true);

        if (isset($response_data['statusCode']) && $response_data['statusCode'] == 200) {
            $payment_id = $response_data['results']['paymentId'];
            $mno_ref = $response_data['results']['mnoRef'];
            echo "Payment Request Successful. Payment ID: $payment_id, MNO Reference: $mno_ref";
        } else {
            $error_message = $response_data['statusMessage'];
            echo "Payment Request Failed. Error: $error_message";
        }
    } else {
        echo "HTTP Error: $http_code. Check your request and try again.";
    }
}

curl_close($ch);
?>
