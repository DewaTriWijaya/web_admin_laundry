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
            <div class="d-flex">
                <table class="table table-borderless">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">No. Nota</th>
                            <th scope="col">Tanggal Selesai</th>
                            <th scope="col">Status Laundry</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="table-light text-center">
                        <tr>
                            <td scope="row">0112</td>
                            <td>
                                <input type="date" class="form-control">
                            </td>
                            <td scope="row">
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
                            <td scope="row" class="d-flex justify-content start">
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
</body>

</html>