<?php

session_start();

// alihkan ke halaman login jika belum login
if(empty($_SESSION) || !isset($_SESSION['user']) || $_SESSION['user']['username'] == '') {
    header("Location: ./login.php");
    exit();
}

// import koneksi database
$database = require_once './database.php';

// simpan barang baru ke database jika ada
if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_barang = $_POST['barang'];
    $harga = $_POST['harga'];

    mysqli_query($database, "INSERT INTO barang (namabrg, harga) VALUES ('$nama_barang', $harga)");

    header("Location: ./tambah-barang.php");
    exit();
}

// list barang dari database
$query = mysqli_query($database,"SELECT * FROM barang");
$list_barang = [];
while($barang = mysqli_fetch_assoc($query)) {
    $list_barang[] = $barang;
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
<nav>
    <ul>
        <li>
            <a href="./penjualan.php">Beli Barang</a>
        </li>
        <li>
            <a href="./tambah-barang.php">Tambah Data Barang</a>
        </li>
    </ul>
</nav>
<form action="" method="post" class="card-container">
    <div class="card">
        <h3 class="title">Form Penambahan Barang</h3>
        <div class="form-group">
            <label>Nama Barang <span>*</span></label>
            <input type="text" name="barang" required>
        </div>
        <div class="form-group">
            <label>Harga Rp<span>*</span></label>
            <input type="number" name="harga" min="0" required>
        </div>
        <div class="form-group">
            <button type="submit">Simpan</button>
        </div>
    </div>
</form>

<div class="card-belanja">
    <h3>Barang Tersedia :</h3>
    <table border="1" style="border-collapse: collapse">
        <tr>
            <td>Nama Barang</td>
            <td>Harga</td>
        </tr>
        <?php foreach ($list_barang as $barang): ?>
            <tr>
                <td><?php echo $barang['namabrg'] ?></td>
                <td>Rp <?php echo number_format($barang['harga'], 0, ".", ".") ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div>
        <a href="./transaksi.php">Beli Barang</a>
    </div>
</div>
</body>
</html>