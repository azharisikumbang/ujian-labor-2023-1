<?php

session_start();

// alihkan ke halaman login jika belum login
if(empty($_SESSION) || !isset($_SESSION['user']) || $_SESSION['user']['username'] == '') {
    header("Location: ./login.php");
    exit();
}

// buat keranjang pembelian
if(!isset($_SESSION['total']) || $_SESSION['total'] == 0) {
    $_SESSION = [
        'total' => 0,
        'diskon' => 0,
        'keranjang' => [],
        'pembeli' => null,
        'user' => $_SESSION['user']
    ];
}

// import koneksi database
$database = require_once './database.php';

// list barang dari database
$query = mysqli_query($database,"SELECT * FROM barang");
$list_barang = [];
while($barang = mysqli_fetch_assoc($query)) {
    $list_barang[] = $barang;
}

// jika barang ditambahkan ke keranjang
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['pembeli'] = $_POST['pembeli'];
    $nobrg = $_POST['nobrg'];
    $jumlah_beli = $_POST['jumlah_barang'];

    foreach ($list_barang as $barang) {
        if($barang['nobrg'] == $nobrg) {

            // aturan bisnis
            $persen_diskon = 0;
            $subtotal = $barang['harga'] * $jumlah_beli;
            if($_SESSION['total'] >= 30000 || $subtotal) $persen_diskon = 0.1;
            $diskon = $persen_diskon * $subtotal;
            $bayar = $subtotal - $diskon;

            $barang['subtotal'] = $subtotal;
            $barang['diskon'] = $diskon;
            $barang['bayar'] = $bayar;
            $barang['jumlah_beli'] = $jumlah_beli;

            // perbaharui data pembelian
            $_SESSION['keranjang'][] = $barang;
            $_SESSION['total'] += $bayar;
            $_SESSION['diskon'] += $diskon;
            break;
        }
    }
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
        <h3 class="title">Form Pembelian</h3>
        <div class="form-group">
            <label>Nama Pembeli <span>*</span></label>
            <input type="text" name="pembeli" value="<?php echo $_SESSION['pembeli'] ?? '' ?>">
        </div>
		<div class="form-group">
			<label>Pilih Barang <span>*</span></label>  
			<select name="nobrg">
				<?php foreach($list_barang as $barang): ?>
				<option value="<?php echo $barang['nobrg'] ?>">
                        <?php echo $barang['namabrg'] ?> - Rp <?php echo number_format($barang['harga'], 0, ".", ".") ?>
				</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label>Jumlah Barang <span>*</span></label>
			<input type="number" name="jumlah_barang">
		</div>
		<div class="form-group">
			<button type="submit">Tambah Ke Keranjang</button>
		</div>
        </div>
	</form>
	<div class="card-belanja">
		<h3>Daftar Belanja :</h3>
		<table border="1" style="border-collapse: collapse">
			<tr>
				<td>Nama Barang</td>
				<td>Harga</td>
				<td>Jumlah Beli</td>
				<td>SubTotal</td>
				<td>Diskon</td>
				<td>Bayar</td>
			</tr>
            <?php foreach ($_SESSION['keranjang'] as $barang): ?>
            <tr>
                <td><?php echo $barang['namabrg'] ?></td>
                <td>Rp <?php echo number_format($barang['harga'], 0, ".", ".") ?></td>
                <td><?php echo $barang['jumlah_beli'] ?></td>
                <td>Rp <?php echo number_format($barang['subtotal'], 0, ".", ".") ?></td>
                <td>Rp <?php echo number_format($barang['diskon'], 0, ".", ".") ?></td>
                <td>Rp <?php echo number_format($barang['bayar'], 0, ".", ".") ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5">Total Bayar:</td>
                <td>Rp <?php echo number_format($_SESSION['total'], 0, ".", ".") ?></td>
            </tr>
		</table>
        <div>
            <a href="./transaksi.php">Beli Barang</a>
        </div>
	</div>
</body>
</html>