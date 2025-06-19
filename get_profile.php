<?php 
include "koneksimysql.php"; 
header('content-type: application/json'); 

$email = $_GET['email']; 
$datauser = array(); 
$getstatus = 0; 

$sql = "SELECT * FROM tbl_pelanggan WHERE email='" . $email . "'"; 
$hasil = mysqli_query($conn, $sql); 
$data = mysqli_fetch_object($hasil);

if (!$data) { 
    $getstatus = 0; 
} else { 
    $getstatus = 1; 
    $datauser = array(
        'email'    => $data->email, 
        'nama'     => $data->nama, 
        'alamat'   => $data->alamat, 
        'kota'     => $data->kota, 
        'provinsi' => $data->provinsi, 
        'kodepos'  => $data->kodepos, 
        'telp'     => $data->telp, 
        'filename'     => $data->filename  
    ); 
} 

echo json_encode(array('result' => $getstatus, 'data' => $datauser));
?>
