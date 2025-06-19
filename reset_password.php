<?php
include "koneksimysql.php";
header('Content-Type: text/plain');

$email = $_POST['email'];
$newPassword = md5($_POST['new_password']);

// Periksa apakah email ada di database
$checkSql = "SELECT * FROM tbl_pelanggan WHERE email = '$email'";
$checkResult = mysqli_query($conn, $checkSql);

if (mysqli_num_rows($checkResult) == 0) {
    echo "Email tidak terdaftar";
    exit();
}

// Update password
$updateSql = "UPDATE tbl_pelanggan SET password = '$newPassword' WHERE email = '$email'";
if (mysqli_query($conn, $updateSql)) {
    echo "success";
} else {
    echo "Gagal mengupdate password: " . mysqli_error($conn);
}

mysqli_close($conn);
?>