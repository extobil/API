<?php
include "koneksimysql.php";
header("Content-type: application/json");

$email = $_POST['email'];

$response = [];
$data = [];

// Validasi input email
if (empty($email)) {
    echo json_encode([
        "kode" => 0,
        "pesan" => "Email tidak boleh kosong",
        "data" => []
    ]);
    exit;
}

// Ambil data pesanan dari tbl_order dan join ke tbl_order_detail
$sql = "SELECT 
            o.trans_id,
            o.tgl_order,
            o.total_bayar,
            o.metode_bayar,
            o.status,
            d.kode,
            d.harga,
            d.qty,
            d.bayar
        FROM tbl_order o
        JOIN tbl_order_detail d ON o.trans_id = d.trans_id
        WHERE o.email_kirim = '$email'
        ORDER BY o.tgl_order DESC, o.trans_id DESC";

$hasil = mysqli_query($conn, $sql);

if (!$hasil) {
    echo json_encode([
        "kode" => 0,
        "pesan" => "Query gagal: " . mysqli_error($conn),
        "data" => []
    ]);
    exit;
}

// Format data menjadi nested (per transaksi)
while ($row = mysqli_fetch_assoc($hasil)) {
    $trans_id = $row['trans_id'];

    if (!isset($data[$trans_id])) {
        $data[$trans_id] = [
            "trans_id" => $trans_id,
            "tgl_order" => $row['tgl_order'],
            "total_bayar" => $row['total_bayar'],
            "metode_bayar" => $row['metode_bayar'],
            "status" => $row['status'],
            "items" => []
        ];
    }

    $data[$trans_id]["items"][] = [
        "kode" => $row['kode'],
        "harga" => $row['harga'],
        "qty" => $row['qty'],
        "bayar" => $row['bayar']
    ];
}

// Konversi associative ke indexed array
$response = [
    "kode" => 1,
    "pesan" => "Data pesanan ditemukan",
    "data" => array_values($data)
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
