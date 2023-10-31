<?php
$api_url = 'https://api.dtb-bank.com/infinitipay/v2/payments/paymentRequest';
$api_key = 'API_KEY'; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $transactionId = $_POST['transactionId'];
    $amount = $_POST['amount'];
    $merchantId = $_POST['merchantId'];
    $payerAccount = $_POST['payerAccount'];
    $narration = $_POST['narration'];

    // Prepare the data to send to the API
    $data = [
        'transactionId' => $transactionId,
        'amount' => $amount,
        'merchantId' => $merchantId,
        'transactionTypeId' => 1, // You may need to adjust this value
        'payerAccount' => $payerAccount,
        'narration' => $narration,
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
}
?>
