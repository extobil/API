<?php
include 'koneksimysql.php';

header('Content-Type: application/json');

// Ambil ID pelanggan dari parameter GET atau POST
$id_pelanggan = isset($_GET['id']) ? $_GET['id'] : die(json_encode(["status" => false, "message" => "ID pelanggan tidak ditemukan."]));

// Query untuk ambil data pesanan beserta detail mobil
$query = "SELECT 
            o.trans_id,
            o.id,
            o.nama_kirim,
            o.email_kirim,
            o.telp_kirim,
            o.alamat_kirim,
            o.kota_kirim,
            o.provinsi_kirim,
            o.kodepos_kirim,
            o.lama_kirim,
            o.tgl_order,
            o.subtotal,
            o.ongkir,
            o.total_bayar,
            o.metode_bayar,
            o.bukti_bayar,
            o.status,
            d.kode,
            d.harga,
            d.qty,
            d.bayar,
            m.merk,
            m.foto
        FROM tbl_order o
        JOIN tbl_order_detail d ON o.trans_id = d.trans_id
        JOIN mobil m ON d.kode = m.kode
        WHERE o.id = '$id_pelanggan'
        ORDER BY o.trans_id DESC";

$result = mysqli_query($conn, $query);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode([
        "status" => true,
        "message" => "Data pesanan ditemukan.",
        "data" => $data
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Gagal mengambil data: " . mysqli_error($conn)
    ]);
}
?>