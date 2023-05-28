CREATE VIEW v_laporan as
SELECT
    t.nofakt as "NO. FAKT",
    t.tglpenjualan as "TGL PENJUALAN",
    t.id_pelg as "ID PELG",
    p.nama_pelg as "PELANGGAN",
    b.nobrg as "NO BRG",
    b.namabrg as "NAMA BARANG",
    dt.harga as "HARGA Rp",
    dt.jumlah as "JUMLAH",
    dt.subtotal as "SUB TOTAL",
    dt.diskon as "DISKON Rp",
    dt.bayar as "BAYAR Rp"
FROM transaksi t
         JOIN pelanggan p ON p.id_pelg = t.id_pelg
         JOIN detail_transaksi dt ON dt.nofakt = t.nofakt
         JOIN barang b ON b.nobrg = dt.nobrg
ORDER BY t.nofakt