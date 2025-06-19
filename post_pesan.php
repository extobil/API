<?php
include "koneksimysql.php";
header("Content-type: application/json");

$order = $_POST['order'];
$order_detail = $_POST['order_detail'];

$hasilorder = json_decode($order);
$hasilorder_detail = json_decode($order_detail);

// Ambil trans_id terakhir
$sql = "SELECT IFNULL(MAX(trans_id), 0) AS trans_id FROM tbl_order";
$hasil = mysqli_query($conn, $sql);
$data = mysqli_fetch_array($hasil);
$trans_id = $data['trans_id'] + 1;

// Ambil data order
$id = $conn->real_escape_string($hasilorder->id);
$nama_kirim = $conn->real_escape_string($hasilorder->nama_kirim);
$email_kirim = $conn->real_escape_string($hasilorder->email_kirim);
$telp_kirim = $conn->real_escape_string($hasilorder->telp_kirim);
$alamat_kirim = $conn->real_escape_string($hasilorder->alamat_kirim);
$kota_kirim = $conn->real_escape_string($hasilorder->kota_kirim);
$provinsi_kirim = $conn->real_escape_string($hasilorder->provinsi_kirim);
$kodepos_kirim = $conn->real_escape_string($hasilorder->kodepos_kirim);
$lama_kirim = $conn->real_escape_string($hasilorder->lama_kirim);
$tgl_order = date("Y-m-d");
$subtotal = floatval($hasilorder->subtotal);
$ongkir = floatval($hasilorder->ongkir);
$total_bayar = floatval($hasilorder->total_bayar);
$metode_bayar = strtolower($conn->real_escape_string($hasilorder->metode_bayar));
$bukti_bayar = $conn->real_escape_string($hasilorder->bukti_bayar);
$status = $conn->real_escape_string($hasilorder->status); // diasumsikan VARCHAR

// DEBUG: Cetak ID ke log file
error_log("DEBUG - ID Pelanggan yang diterima: $id");

// Validasi apakah ID pelanggan ada di tabel tbl_pelanggan
$cek_id = mysqli_query($conn, "SELECT id FROM tbl_pelanggan WHERE id = '$id'");
if (mysqli_num_rows($cek_id) === 0) {
    $response = [
        "kode" => 0,
        "orderid" => null,
        "pesan" => "ID pelanggan tidak ditemukan di tabel tbl_pelanggan",
        "debug_id" => $id
    ];
    echo json_encode($response);
    exit;
}

// Validasi metode_bayar enum
if (!in_array($metode_bayar, ['cod', 'transfer'])) {
    $metode_bayar = 'cod'; // fallback
}

// Simpan ke tbl_order
$sql = "INSERT INTO tbl_order (
    trans_id, id, nama_kirim, email_kirim, telp_kirim, alamat_kirim, kota_kirim, provinsi_kirim, kodepos_kirim,
    lama_kirim, tgl_order, subtotal, ongkir, total_bayar, metode_bayar, bukti_bayar, status
) VALUES (
    $trans_id, '$id', '$nama_kirim', '$email_kirim', '$telp_kirim', '$alamat_kirim', '$kota_kirim', '$provinsi_kirim',
    '$kodepos_kirim', '$lama_kirim', '$tgl_order', $subtotal, $ongkir, $total_bayar, '$metode_bayar', '$bukti_bayar', '$status'
)";

$hasil = mysqli_query($conn, $sql);

if ($hasil) {
    $kode = 1;
    $pesan = "Order berhasil disimpan";
    $orderid = $trans_id;

    // Simpan detail order
    foreach ($hasilorder_detail as $item) {
        $kode_produk = $conn->real_escape_string($item->kode);
        $harga = floatval($item->harga);
        $qty = intval($item->qty);
        $bayar = $harga * $qty;

        $sql_detail = "INSERT INTO tbl_order_detail (trans_id, kode, harga, qty, bayar)
                       VALUES ($trans_id, '$kode_produk', $harga, $qty, $bayar)";
        $hasil_detail = mysqli_query($conn, $sql_detail);

        if (!$hasil_detail) {
            $kode = 0;
            $pesan = "Gagal menyimpan detail order: " . mysqli_error($conn);
            break;
        }
    }
} else {
    $kode = 0;
    $pesan = "Gagal menyimpan order: " . mysqli_error($conn);
    $orderid = null;
}

// Output JSON response
$response = [
    "kode" => $kode,
    "orderid" => $orderid,
    "pesan" => $pesan,
    "debug_id" => $id // tambahan untuk debug
];

echo json_encode($response);
?>