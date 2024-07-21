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

    <title>Document</title>
</head>
<body>
<div class="container">
        <div class="row mb-3">

            <div class="head mb-4">
                <h1>Pengelolaan Transaksi</h1>
            </div>
            
            <div class="d-flex justify-content-start mb-4">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Masukan No Handphone Pelanggan">
                </div>
                <div class="col">
                    <button class="btn btn btn-outline-secondary" type="button"><i class="bi bi-search"></i> Cari</button>
                </div>   
            </div>
            <div class="d-flex flex-row">
                <div class="row align-self-center me-1">
                    <h5><b>Jenis Cucian</b></h5>  
                </div>
                <div class="row p-2">
                    <button class="btn btn-sm" type="button"><h3><i class="bi bi-plus-circle"></i></h3></button>
                </div>
            </div>
            <div class="d-flex justify-content-start">
                <table class="table mt-2 w-75">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Jenis Cucian</th>
                            <th scope="col">Jumlah Kilogram</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>
                                <button class="btn btn-outline-danger" type="button">
                                <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="col">
                    <div class="row">
                        <h6><b>Tanggal Transaksi</b></h6>
                        <div class="col-md-4">
                            <input type="date" class="form-control">
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
</div>

    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
