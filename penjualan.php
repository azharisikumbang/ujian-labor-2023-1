<?php

session_start();

// buat keranjang pembelian
if(!isset($_SESSION['total']) || $_SESSION['total'] == 0) {
    $_SESSION = [
        'total' => 0,
        'diskon' => 0,
        'keranjang' => [],
        'pembeli' => null
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
</head>
<body>
	<form action="" method="post">
		<h3>Form Pembelian</h3>
        <div>
            <label>Nama Pembeli</label>
            <input type="text" name="pembeli" value="<?php echo $_SESSION['pembeli'] ?? '' ?>">
        </div>
		<div>
			<label>Pilih Barang</label>  
			<select name="nobrg">
				<?php foreach($list_barang as $barang): ?>
				<option value="<?php echo $barang['nobrg'] ?>">
					<?php echo $barang['namabrg'] ?> - Rp <?php echo number_format($barang['harga'], 0, ".", ".") ?>
				</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Jumlah Barang</label>
			<input type="number" name="jumlah_barang">
		</div>
		<div>
			<button type="submit">Tambah Ke Keranjang</button>
		</div>
	</form>

	<div>
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
                <td><?php echo $barang['harga'] ?></td>
                <td><?php echo $barang['jumlah_beli'] ?></td>
                <td><?php echo $barang['subtotal'] ?></td>
                <td><?php echo $barang['diskon'] ?></td>
                <td><?php echo $barang['bayar'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5">Total Bayar:</td>
                <td><?php echo $_SESSION['total'] ?></td>
            </tr>
		</table>
        <div>
            <a href="./transaksi.php">Beli Barang</a>
        </div>
	</div>
</body>
</html>