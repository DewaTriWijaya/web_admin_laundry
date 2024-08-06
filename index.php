<?php
include "koneksi.php";

$success = false;
$error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Check if the phone number already exists in the database
    $check_sql = "SELECT * FROM pelanggan WHERE No_HP = '$phone'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        $error = true;
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
        exit();
    } else {
        // Prepare and execute the SQL query
        $sql = "INSERT INTO pelanggan (nama, No_HP, alamat) VALUES ('$name', '$phone', '$address')";
        if (mysqli_query($conn, $sql)) {
            $success = true;
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit();
        } else {
            $error = true;
            header("Location: " . $_SERVER['PHP_SELF'] . "?error=2");
            exit();
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <style>
        body {
            font-family: poppins;
        }
        .sidebar {
            width: 380px;
        }

        .bg-active {
            background-color: #0086ac;
        }
    </style>
    <title>Laundry</title>
</head>

<body>
    <div class="d-flex">
        <div class="p-4 bg-custom sidebar">
            <div class="d-flex gap-4">
                <img src="img/Logo.png" alt="logo" class="w-25 h-25">
                <div class="text-white align-self-center">
                    <h1 class="fs-5">Admin</h1>
                    <p class="lh-1">Sistem Laundry</p>
                </div>
            </div>
            <div class="mt-5" >
                <div class="side-menu bg-active" onclick="window.location.href=">
                    <img src="img/user.png" alt="pendaftaran">
                    <a class="text-white" href="index.php" style="text-decoration:none">Pendaftaran</a>
                </div>

                <div class="side-menu" onclick="window.location.href='page/pglTransaksi.php'">
                    <img src="img/tag.png" alt="transaksi">
                    <a class="text-white" href="page/pglTransaksi.php" style="text-decoration:none">Pengelolaan Transaksi</a>
                </div>

                <div class="side-menu" onclick="window.location.href='page/pglCucian.php'">
                    <img src="img/washing.png" alt="cucian">
                    <a class="text-white" href="page/pglCucian.php" style="text-decoration:none">Pengelolaan Cucian</a>
                </div>

                <div class="side-menu" onclick="window.location.href='page/laporan.php'">
                    <img src="img/bar-chart.png" alt="laporan">
                    <a class="text-white" href="page/laporan.php" style="text-decoration:none">Laporan</a>
                </div>

                <div class="side-menu" onclick="window.location.href='page/statusLaundry.php'">
                    <img src="img/bell.png" alt="status">
                    <a class="text-white" href="page/statusLaundry.php" style="text-decoration:none">Status Laundry</a>
                </div>
            </div>
        </div>

        <div class="p-3 d-flex w-100 bg-secondary" style="--bs-bg-opacity: .15;">
            <!-- awal -->
            <div class="container-fluid h-100 d-flex flex-column vh-100">
                <div class="row">
                    <div class="col">
                        <h2 class="fw-bold mb-5 p-4">Pendaftaran Pelanggan</h2>
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" id="customerForm">
                                    <div class="form-group">
                                        <label for="name"><b>Nama</b></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukan Nama" required="" oninvalid="this.setCustomValidity('Tolong isi form ini!')" oninput="setCustomValidity('')>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="phone"><b>Nomor Handphone</b></label>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Masukan Nomor Handphone" required>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="address"><b>Alamat</b></label>
                                        <textarea class="form-control" id="address" name="address" placeholder="Masukan Alamat" required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-center mt-2">
                                        <button type="submit" class="btn btn-success px-4 mx-2">Simpan</button>
                                        <button type="reset" class="btn btn-danger px-4">Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Modal -->
            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h4 class="modal-title bi bi-info-circle fw-bold" id="successModalLabel"> Pemberitahuan</h>
                        </div>
                        <div class="modal-body text-center">
                            Data Pelanggan berhasil disimpan.
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-success" style="padding: 7px 20px;" data-dismiss="modal" id="confirmButton">Ya</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Modal -->
            <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h4 class="modal-title bi bi-exclamation-circle-fill fw-bold" id="errorModalLabel"> Peringatan</h4>
                        </div>
                        <div class="modal-body text-center">
                            <?php
                            if (isset($_GET['error']) && $_GET['error'] == 1) {
                                echo "Data yang ada isi sudah tersedia. Isi data terbaru anda!";
                            } elseif (isset($_GET['error']) && $_GET['error'] == 2) {
                                echo "Error: There was an error saving the data.";
                            }
                            ?>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-success" style="padding: 7px 20px;" data-dismiss="modal" id="errorConfirmButton">Ya</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openHomeAndToggleDropdown() {
            // Arahkan ke home.php
            window.location.href = 'index.php?page=home&menu=transaksi';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
