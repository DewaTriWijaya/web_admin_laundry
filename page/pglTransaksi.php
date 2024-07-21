<?php
// include "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Document</title>
</head>
<body class="bg-secondary" style="--bs-bg-opacity:.15;">
    <div class="container">
        <div class="row mb-3">

            <div class="head mb-5 mt-1 ">
                <h2 class="fw-bold">Pengelolaan Transaksi</h2>
            </div>
            
            <div class="d-flex justify-content-start mb-3">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Masukan No Handphone Pelanggan">
                </div>
                <div class="col">
                    <button class="btn btn btn-outline-dark" type="button"><i class="bi bi-search"></i> Cari</button>
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
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Jenis Cucian</th>
                            <th scope="col">Jumlah Kilogram</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
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

        <div class="col">
            <div class="row mb-4">
                <div class="col me-4">
                    <h6><b>Tanggal Transaksi</b></h6>
                    <div class="col-md-7">
                        <input type="date" class="form-control">
                    </div>
                </div>
                <div class="col me-4">
                    <h6><b>Total Harga</b></h6>
                    <div class="col-md-5">
                        <fieldset disabled>
                            <input type="text" id="disabledTextInput" class="form-control bg-light">
                        </fieldset >    
                    </div>         
                </div>
            </div>

            <div class="row mb-5">
                <div class="col me-4">
                    <h6><b>Tanggal Transaksi</b></h6>
                    <div class="col-md-7">
                        <input type="date" class="form-control">
                    </div>
                </div>
                <div class="col me-4">
                    <h6><b>Pembayaran<b></h6>
                    <div class="d-flex flex-row mb-3">
                        <div class="row me-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                <p>QRIS</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                <p>Tunai</p>
                            </div>
                        </div>
                    </div>         
                </div>
            </div>
        </div>

        <div class="d-flex flex-row mb-3">
            <div class="row me-5">
                <button type="button" class="btn btn-success p-2 px-4">Simpan</button>
            </div>
            <div class="row ms-3">
                <button type="button" class="btn btn-danger p-2 px-4">Hapus</button>
            </div>
        </div>
    </div>

</body>
</html>
