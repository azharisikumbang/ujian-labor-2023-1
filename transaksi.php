<?php

session_start();

// alihkan ke halaman login jika belum login
if(empty($_SESSION) || !isset($_SESSION['user']) || $_SESSION['user']['username'] == '') {
    header("Location: ./login.php");
    exit();
}

if(!isset($_SESSION['keranjang']) || count($_SESSION['keranjang']) < 1) {
    echo "Keranjang belanja masih kosong. Silahkan lakukan <a href='./penjualan.php'>pembelian</a>.";
    exit();
}


$database = require_once './database.php';

// simpan nama pembeli
$nama_pelanggan = $_SESSION['pembeli'];
mysqli_query($database, "INSERT INTO pelanggan (nama_pelg) VALUES ('$nama_pelanggan')");
$id_pelg = mysqli_insert_id($database);

// simpan transaksi
$tglpenjualan = date('Y-m-d');
$total_diskon = $_SESSION['diskon'];
$total_bayar = $_SESSION['total'];

mysqli_query($database, "INSERT INTO transaksi (id_pelg, tglpenjualan, total_diskon, total_bayar) VALUES ($id_pelg, '$tglpenjualan', $total_diskon, $total_bayar)");

$no_fakt = mysqli_insert_id($database);

// simpan detail transaksi
foreach($_SESSION['keranjang'] as $barang) {
    $nobrg = $barang['nobrg'];
    $harga = $barang['harga'];
    $jumlah = $barang['jumlah_beli'];
    $subtotal = $barang['subtotal'];
    $diskon = $barang['diskon'];
    $bayar = $barang['bayar'];

    mysqli_query($database, "INSERT INTO detail_transaksi (nofakt, nobrg, harga, jumlah, subtotal, diskon, bayar) VALUES ($no_fakt, $nobrg, $harga, $jumlah, $subtotal, $diskon, $bayar)");
}

// kosongkan keranjang
$_SESSION = [
        'user' => $_SESSION['user']
];

// dapatkan data pembelian
$query = mysqli_query(
        $database,
        "SELECT
            t.nofakt,
            t.tglpenjualan,
            t.id_pelg,
            t.total_bayar,
            t.total_diskon,
            p.nama_pelg,
            b.nobrg,
            b.namabrg,
            dt.harga,
            dt.jumlah,
            dt.subtotal,
            dt.diskon,
            dt.bayar
        FROM transaksi t
            JOIN pelanggan p ON p.id_pelg = t.id_pelg
            JOIN detail_transaksi dt ON dt.nofakt = t.nofakt
            JOIN barang b ON b.nobrg = dt.nobrg
        WHERE t.nofakt = $no_fakt"
);

$transaksi = [];
while ($t = mysqli_fetch_assoc($query)) {
    $transaksi['id_pelg'] = $t['id_pelg'];
    $transaksi['nama_pelg'] = $t['nama_pelg'];
    $transaksi['nofakt'] = $t['nofakt'];
    $transaksi['tglpenjualan'] = $t['tglpenjualan'];
    $transaksi['total_bayar'] = $t['total_bayar'];
    $transaksi['barang'][] = $t;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penjualan Air</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card-detail">
        <h3>Detail Transaksi</h3>
        <div>
            <table>
                <tr>
                    <td>No. Faktur</td>
                    <td>: <?php echo $transaksi['nofakt'] ?></td>
                </tr>
                <tr>
                    <td>Tanggal Pembelian</td>
                    <td>: <?php echo $transaksi['tglpenjualan'] ?></td>
                </tr>
                <tr>
                    <td>Nama Pelanggan</td>
                    <td>: <?php echo $transaksi['nama_pelg']; ?></td>
                </tr>
            </table>
        </div>
        <table border="1">
            <tr>
                <td>Nama Barang</td>
                <td>Harga</td>
                <td>Jumlah Beli</td>
                <td>SubTotal</td>
                <td>Diskon</td>
                <td>Bayar</td>
            </tr>
            <?php foreach ($transaksi['barang'] as $barang): ?>
                <tr>
                    <td><?php echo $barang['namabrg'] ?></td>
                    <td>Rp <?php echo number_format($barang['harga'], 0, ".", ".") ?></td>
                    <td><?php echo $barang['jumlah'] ?></td>
                    <td>Rp <?php echo number_format($barang['subtotal'], 0, ".", ".") ?></td>
                    <td>Rp <?php echo number_format($barang['diskon'], 0, ".", ".") ?></td>
                    <td>Rp <?php echo number_format($barang['bayar'], 0, ".", ".") ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5">Total Bayar:</td>
                <td>Rp <?php echo number_format($transaksi['total_bayar'], 0, ".", ".") ?></td>
            </tr>
        </table>
        <div>
            <a href="./penjualan.php">Beli Barang Baru</a>
        </div>
    </div>
</body>
</html>