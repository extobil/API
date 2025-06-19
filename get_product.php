<?php
include "koneksimysql.php";

// Query untuk mengambil data produk
$sql = "SELECT kode, merk,kategori, stok, hargajual, view, foto, deskripsi FROM mobil";
$result = $conn->query($sql);

$products = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Mengembalikan data dalam format JSON
echo json_encode($products);

$conn->close();
?>