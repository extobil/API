<?php 
include "koneksimysql.php"; 
header('content-type: application/json'); 

$email = $_POST['email']; 
$nama = $_POST['nama']; 
$password = $_POST['password']; 

$getstatus = 0; 
$getresult = 0; 
$message = ""; 

$sql = "SELECT * FROM tbl_pelanggan WHERE email='$email'"; 
$hasil = mysqli_query($conn, $sql);

if(mysqli_fetch_object($hasil)){ 
    $getstatus = 0; // pelanggan sudah ada 
    $message = "User sudah ada"; 
} else { 
    $getstatus = 1;
    $sql = "INSERT INTO tbl_pelanggan (nama, email, password) 
            VALUES('$nama', '$email', MD5('$password'))"; 
    $hasil = mysqli_query($conn, $sql);

    if($hasil){ 
        $getresult = 1; 
        $message = "Simpan Berhasil"; 
    } else { 
        $getresult = 0; 
        $message = "Simpan Gagal: " . mysqli_error($conn); 
    } 
} 

echo json_encode(array(
    'status' => $getstatus, 
    'result' => $getresult, 
    'message' => $message
)); 
?>