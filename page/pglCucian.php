<?php
// include "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .table td,
        .table th {
            padding: 15px;
        }
    </style>

    <title>Document</title>
</head>

<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container">

        <h2 class="fw-bold mb-5">Pengelolaan Cucian</h2>

        <!--Tabel Jenis Cucian -->
        <h5 class="card-title mb-4">Daftar Jenis Cucian</h5>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Cucian</th>
                    <th>Harga Per-Kilogram</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Baju</td>
                    <td>Rp. 12.000</td>
                    <td>
                        <button class="btn" type="button">
                            <i class="bi bi-trash text-danger"></i>
                        </button>
                        <button class="btn">
                            <i class="bi bi-pencil-square text-secondary"></i>
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>


        <!--Tambah Jenis Cucian -->
        <div class="card mt-4">
            <div class="card-body p-4">
                <h5 class="card-title">Tambah Jenis Cucian</h5>
                <form>
                    <div class="form-group p-2">
                        <label for="jenisCucian">Jenis Cucian</label>
                        <input type="text" class="form-control" id="jenisCucian" placeholder="Masukan Jenis Cucian">
                    </div>
                    <div class="form-group p-2">
                        <label for="hargaCucian">Harga Jenis Cucian</label>
                        <input type="text" class="form-control" id="hargaCucian" placeholder="Masukan Harga Jenis Cucian">
                    </div>
                    <button type="submit" class="btn btn-success p-2">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.min.js"></script>
</body>

</html>