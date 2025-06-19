<?php
header('Content-Type: application/json');

$province_id = isset($_GET['province_id']) ? $_GET['province_id'] : ''; // Tangkap province_id dari URL

$curl = curl_init();

$url = "https://api.rajaongkir.com/starter/city";
if (!empty($province_id)) {
    $url .= "?province=" . $province_id; // Tambahkan parameter province jika ada
}

curl_setopt_array($curl, array(
  CURLOPT_URL => $url, // Gunakan URL yang sudah dimodifikasi
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "key: d041b25ce819bab598e663d27c5fa60d"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo json_encode(['error' => 'cURL Error #: ' . $err]);
} else {
    echo $response;
}
?>