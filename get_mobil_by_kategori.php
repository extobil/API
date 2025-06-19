<?php
header("Content-Type: application/json");
include 'koneksimysql.php';

$response = [];

// Ambil parameter kategori dari GET
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

if (empty($kategori)) {
    echo json_encode([
        "success" => false,
        "message" => "Kategori tidak boleh kosong"
    ]);
    exit;
}

// Gunakan prepared statement untuk keamanan
$stmt = $conn->prepare("SELECT * FROM mobil WHERE kategori = ?");
$stmt->bind_param("s", $kategori);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

if (count($data) > 0) {
    echo json_encode([
        "success" => true,
        "message" => "Data ditemukan",
        "data" => $data
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak ditemukan",
        "data" => []
    ]);
}

$stmt->close();
$conn->close();
?>
