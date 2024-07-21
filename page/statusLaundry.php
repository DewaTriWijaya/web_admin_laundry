<?php
// include "../koneksi.php";
// Status

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Status Laundry</title>
</head>

<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container">
        <div class="row">
            <h2 class="fw-bold mb-5">Informasi Status Laundry</h2>
            <div class="d-flex justify-content-center">
                <table class="table table-borderless">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">No. Nota</th>
                            <th scope="col">Tanggal Selesai</th>
                            <th scope="col">Status Laundry</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <tr>
                            <td scope="row" class="text-center">0112</td>
                            <td>
                                <input type="date" class="form-control">
                            </td>
                            <td>
                                <div class="dropdown text-center">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Status Laundry
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Belum</a></li>
                                        <li><a class="dropdown-item" href="#">Selesai</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary">Pemberitahuan</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-success px-4">Simpan</button>
            </div>
        </div>
    </div>
    <!-- <div class="d-flex mb-3 row">
        <h2 class="fw-bold">Informasi Status Laundry</h2>
    </div>
    <div class="d-flex flex-row justify-content-center">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">No. Nota</th>
                    <th scope="col">Tanggal Selesai</th>
                    <th scope="col">Status Laundry</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row"></th>
                    <td>
                        <input type="date" class="form-control">
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Status Laundry
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Belum</a></li>
                                <li><a class="dropdown-item" href="#">Selesai</a></li>
                            </ul>
                        </div>
                    </td>
                    <td colspan="2"><button type="button" class="btn btn-primary">Pemberitahuan</button></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td>
                        <input type="date" class="form-control">
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Status Laundry
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Belum</a></li>
                                <li><a class="dropdown-item" href="#">Selesai</a></li>
                            </ul>
                        </div>
                    </td>
                    <td colspan="2"><button type="button" class="btn btn-primary">Pemberitahuan</button></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td>
                        <input type="date" class="form-control">
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Status Laundry
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Belum</a></li>
                                <li><a class="dropdown-item" href="#">Selesai</a></li>
                            </ul>
                        </div>
                    </td>
                    <td colspan="2"><button type="button" class="btn btn-primary">Pemberitahuan</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex flex-row-reverse">
        <button type="button" class="btn btn-success">Simpan</button>
    </div> -->
</body>

</html>