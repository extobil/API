<?php
// Set header agar browser/aplikasi tahu bahwa responsnya adalah JSON
header('Content-Type: application/json');

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
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
    // Jika ada error cURL, kembalikan error dalam format JSON
    echo json_encode(['error' => 'cURL Error #: ' . $err]);
} else {
    // Langsung cetak respons JSON dari RajaOngkir
    // $response sudah dalam bentuk JSON, jadi tidak perlu di-decode lalu di-encode lagi
    echo $response;
}
?>