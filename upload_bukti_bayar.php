<?php
include 'koneksimysql.php';

header('Content-Type: application/json');

// Pastikan permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek apakah ada file dan trans_id
    if (isset($_POST['trans_id']) && isset($_FILES['bukti_bayar'])) {

        $trans_id = $_POST['trans_id'];
        $file = $_FILES['bukti_bayar'];

        // Validasi file gambar
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            echo json_encode([
                'status' => false,
                'message' => 'Format file tidak didukung (hanya jpg, jpeg, png, gif)'
            ]);
            exit;
        }

        if ($file_size > 2000000) {
            echo json_encode([
                'status' => false,
                'message' => 'Ukuran file maksimal 2MB'
            ]);
            exit;
        }

        // Generate nama file unik
        $newFileName = 'bukti_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $upload_path = 'images/' . $newFileName;

        // Pindahkan file ke folder images/
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Simpan nama file ke database
            $query = "UPDATE tbl_order SET bukti_bayar = '$newFileName' WHERE trans_id = '$trans_id'";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Upload bukti bayar berhasil',
                    'filename' => $newFileName
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Gagal update database',
                    'error' => mysqli_error($conn)
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal upload file'
            ]);
        }
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'Parameter tidak lengkap'
        ]);
    }
} else {
    echo json_encode([
        'status' => false,
        'message' => 'Method harus POST'
    ]);
}
?>