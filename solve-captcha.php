<?php
// Allow CORS (you can restrict this to your domain in production)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// Load JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($input['data'])) {
    echo json_encode(["error" => "No captcha data provided."]);
    exit;
}

// Your TrueCaptcha credentials (hidden from frontend)
$userid = "sanjit.webhosting@gmail.com";
$apikey = "AgbNpbuAYzRX1LZkzOms";
$imageData = $input['data'];

// Prepare request
$postData = json_encode([
    "userid" => $userid,
    "apikey" => $apikey,
    "data"   => $imageData
]);

// Send request to TrueCaptcha
$ch = curl_init("https://api.apitruecaptcha.org/one/gettext");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

// Handle response
if ($error) {
    echo json_encode(["error" => "Curl error: $error"]);
} else {
    echo $response;
}
