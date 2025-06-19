<?php
header('Content-Type: application/json');
include 'koneksimysql.php';

$kode = $_POST['kode'] ?? null;
$view = $_POST['view'] ?? null;

if ($kode === null || $view === null) {
    echo json_encode(['status' => false, 'message' => 'Parameter kurang lengkap']);
    exit;
}

// Update view di tabel tbl_product
$sql = "UPDATE tbl_product SET view = ? WHERE kode = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $view, $kode);

if ($stmt->execute()) {
    echo json_encode(['status' => true, 'message' => 'View berhasil diperbarui']);
} else {
    echo json_encode(['status' => false, 'message' => 'Gagal memperbarui view']);
}
?>
