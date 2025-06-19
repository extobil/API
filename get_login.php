<?php
include "koneksimysql.php";
header('content-type: application/json');

$email = $_POST['email'];
$password = $_POST['password'];
$datauser = array();
$getstatus = 0;

// Debug
error_log("Email: " . $email);
error_log("Password: " . $password);

// Ambil juga 'id' agar bisa dijadikan user_id
$sql = "SELECT id, nama, email, status, password FROM tbl_pelanggan WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['result' => 0, 'message' => 'Query gagal disiapkan']);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_object();

if ($data && isset($data->password) && md5($password) === $data->password) {
    // Login berhasil
    $getstatus = 1;

    // Update status menjadi 1
    $updateSql = "UPDATE tbl_pelanggan SET status = 1 WHERE email = ?";
    $updateStmt = $conn->prepare($updateSql);
    if ($updateStmt) {
        $updateStmt->bind_param("s", $email);
        $updateStmt->execute();
        $updateStmt->close();
    }

    // Sertakan user_id juga
    $datauser = array(
        'user_id' => (int)$data->id,
        'nama' => $data->nama,
        'email' => $data->email,
        'status' => 1
    );
} else {
    error_log("Data tidak ditemukan atau password salah.");
}

echo json_encode(array(
    'result' => $getstatus,
    'data' => $datauser
));

$stmt->close();
$conn->close();
?>