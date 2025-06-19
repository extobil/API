<?php
include "koneksimysql.php";
header("Content-Type: application/json");

$BASE_URL_IMAGES = "./images/";
$filename = "img_" . date("YmdHis") . "_" . rand(1000, 9999) . ".jpg";

$res = array();
$kode = 0;
$pesan = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['id']) && isset($_FILES['imageupload'])) {
        $email = $_POST['id'];

        // Cek apakah user dengan email tersebut ada
        $cek = "SELECT filename FROM tbl_pelanggan WHERE email = '$email'";
        $result = $conn->query($cek);

        if ($result->num_rows > 0) {
            // Upload gambar baru
            $temp_name = $_FILES['imageupload']['tmp_name'];
            $new_path = $BASE_URL_IMAGES . $filename;

            if (move_uploaded_file($temp_name, $new_path)) {
                $row = $result->fetch_assoc();
                $old_filename = $row['filename'];

                // Jika sudah ada gambar lama, hapus
                if (!empty($old_filename) && file_exists($BASE_URL_IMAGES . $old_filename)) {
                    unlink($BASE_URL_IMAGES . $old_filename);
                }

                // Update filename ke database
                $sql = "UPDATE tbl_pelanggan SET filename='$filename' WHERE email='$email'";
                if ($conn->query($sql) === TRUE) {
                    $kode = 1;
                    $pesan = "Foto profil berhasil diperbarui";
                } else {
                    $pesan = "Update gagal: " . $conn->error;
                }
            } else {
                $pesan = "Gagal mengunggah file";
            }
        } else {
            // Jika user tidak ditemukan, kirim pesan error
            $pesan = "User tidak ditemukan";
        }
    } else {
        $pesan = "ID atau file tidak lengkap";
    }
} else {
    $pesan = "Metode request tidak valid";
}

$res['kode'] = $kode;
$res['pesan'] = $pesan;

echo json_encode($res);

$conn->close();
?>
