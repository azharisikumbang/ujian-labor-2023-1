<?php

session_start();

// load library pdf
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// import koneksi database
$database = require_once './database.php';

// get data
$result = mysqli_query($database, "
    SELECT
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
    ORDER BY t.nofakt
");

?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Penjualan Air</title>
    </head>
    <body>
    <div style="text-align: center">
        <h3>LAPORAN PENJUALAN CV AIR SEJUK</h3>
        <p>Periode: <?php echo date('M-Y') ?></p>
    </div>
    <table border="1" style="border-collapse: collapse; width: 100%" cellpadding="4">
        <tr style="text-align: center; font-weight: bold">
            <td>NO</td>
            <td>NO FAKT</td>
            <td>TGL PENJUALAN</td>
            <td>ID PELG</td>
            <td>PELANGGAN</td>
            <td>NO BRG</td>
            <td>NAMA BARANG</td>
            <td>HARGA Rp</td>
            <td>JUMLAH</td>
            <td>SUBTOTAL</td>
            <td>DISKON Rp</td>
            <td>BAYAR Rp</td>
        </tr>
        <?php
        $no = 1;
        $total = 0;
        while ($transaksi = mysqli_fetch_assoc($result)):
            $total += $transaksi['bayar'];
            ?>
            <tr>
                <td style="text-align: center"><?php echo $no++; ?></td>
                <td style="text-align: center"><?php echo $transaksi['nofakt'] ?></td>
                <td style="text-align: center"><?php echo $transaksi['tglpenjualan'] ?></td>
                <td style="text-align: center"><?php echo $transaksi['id_pelg'] ?></td>
                <td><?php echo $transaksi['nama_pelg'] ?></td>
                <td style="text-align: center"><?php echo $transaksi['nobrg'] ?></td>
                <td><?php echo $transaksi['namabrg'] ?></td>
                <td>Rp <?php echo number_format($transaksi['harga'], 0, ".", ".") ?></td>
                <td><?php echo $transaksi['jumlah'] ?></td>
                <td>Rp <?php echo number_format($transaksi['subtotal'], 0, ".", ".") ?></td>
                <td>Rp <?php echo number_format($transaksi['diskon'], 0, ".", ".") ?></td>
                <td style="min-width: 100px">Rp <?php echo number_format($transaksi['bayar'], 0, ".", ".") ?></td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="11">Total Penjualan</td>
            <td>Rp <?php echo number_format($total, 0, ".", ".") ?></td>
        </tr>
    </table>
    <div style="margin-top: 20px; text-align: right; width: 100%">
        <div style="width: 220px; text-align: left; float:right">
            <div style="margin-bottom: 60px">Padang, <?php echo date('d F Y') ?></div>
            <div>
                <div>Jalil Ibrahim</div>
                <div>Manager</div>
            </div>
        </div>
    </div>
    </body>
    </html>
<?php

// harus di bawah
$content = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($content);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Laporan-Penjualan-CV-Air-Sejuk.pdf', ['Attachment' => false]);