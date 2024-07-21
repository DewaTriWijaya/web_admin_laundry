<?php
// include "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
</head>
<body class="bg-secondary" style="--bs-bg-opacity: .15;">
<div class="container">
        <div class="row">
            <h2 class="fw-bold mb-5">Laporan Laundry</h2>
            <h4 class="mb-4">Masukkan Jangka Waktu</h4>
            <d class="d-flex gap-4 mb-5">
                <div class="d-flex gap-4 ">
                    <input type="date" class="form-control">
                    <input type="date" class="form-control">
                </div>
            <button type="button" class="btn btn-success px-4">Cari</button>
            </div>

            <h4>Transaksi</h4>
            <div class="d-flex justify-content-center">
                <table class="table table-borderless">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">No. Nota</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Bulan</th>
                            <th scope="col">Tahun</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <tr>
                            <td scope="row" class="text-center">0112</td>
                            <td scope="row" class="text-center">Rp. 75,000</td>
                            <td scope="row" class="text-center">Rp. 75,000</td>
                            <td scope="row" class="text-center">Rp. 75,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-start mt-4">
                <button type="button" class="btn btn-success px-4">Unduh</button>
            </div>
        </div>
    </div>
</body>
</html>
