<?php 
include "koneksimysql.php"; 
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

$email    = isset($_POST['email']) ? $_POST['email'] : '';
$nama     = isset($_POST['nama']) ? $_POST['nama'] : '';
$alamat   = isset($_POST['alamat']) ? $_POST['alamat'] : '';
$kota     = isset($_POST['kota']) ? $_POST['kota'] : '';
$provinsi = isset($_POST['provinsi']) ? $_POST['provinsi'] : '';
$telp     = isset($_POST['telp']) ? $_POST['telp'] : '';
$kodepos  = isset($_POST['kodepos']) ? $_POST['kodepos'] : '';

$response = array();
$result = 0;
$message = "";

// Prepared statement
$stmt = $conn->prepare("UPDATE tbl_pelanggan SET nama=?, alamat=?, kota=?, provinsi=?, telp=?, kodepos=? WHERE email=?");
$stmt->bind_param("sssssss", $nama, $alamat, $kota, $provinsi, $telp, $kodepos, $email);

if ($stmt->execute()) {
    $result = 1;
    $message = "Simpan Berhasil";
} else {
    $message = "Simpan Gagal: " . $stmt->error;
}

echo json_encode(array("result" => $result, "message" => $message));
?>
