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
    <title>Pendaftaran Pelanggan</title>

    <style>
        .home-uhuy {
            margin-left: 300px;
        }
    </style>
</head>
<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container-fluid h-100 d-flex flex-column vh-100 home-uhuy">
        <div class="row">
            <div class="col">
                <h2 class="fw-bold mb-5">Pendaftaran Pelanggan</h2>
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

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Check if the URL contains the success parameter
            if (new URLSearchParams(window.location.search).has('success')) {
                $('#successModal').modal('show');
            }

            // Check if the URL contains the error parameter
            if (new URLSearchParams(window.location.search).has('error')) {
                $('#errorModal').modal('show');
            }

            $('#confirmButton').on('click', function() {
                $('#successModal').modal('hide');
                $('#successModal').on('hidden.bs.modal', function () {
                    window.location.href = window.location.pathname; // Remove query parameters
                });
            });

            $('#errorConfirmButton').on('click', function() {
                $('#errorModal').modal('hide');
                $('#errorModal').on('hidden.bs.modal', function () {
                    window.location.href = window.location.pathname; // Remove query parameters
                });
            });
        });
    </script>
</body>
</html>
