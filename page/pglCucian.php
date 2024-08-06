<?php
include "../koneksi.php";

function generateId($jenisCucian)
{
    $prefix = strtoupper(substr($jenisCucian, 0, 2));
    $randomNumber = rand(10, 99);
    return $prefix . $randomNumber;
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission for adding new jenis cucian
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $jenisCucian = $_POST['jenisCucian'];
    $hargaCucian = $_POST['hargaCucian'];

    // Validate and sanitize inputs
    $jenisCucian = htmlspecialchars($jenisCucian);
    $hargaCucian = filter_var($hargaCucian, FILTER_VALIDATE_INT);

    if ($hargaCucian === false) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showAlertModal('Harga cucian tidak valid.');
        });
      </script>";
    } else {
        $idJenisCucian = generateId($jenisCucian);
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO jeniscucian (id_jenis_cucian, jenis_cucian, harga_satuan_kilo) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $idJenisCucian, $jenisCucian, $hargaCucian);

        if ($stmt->execute()) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlertModal('Data Sudah Ditambahkan');
            });
          </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlertModal('Data Error');
            });
          </script>";
        }

        $stmt->close();
    }
}

// Handle form submission for deleting a jenis cucian
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $idJenisCucian = $_POST['idJenisCucian'];

    $stmt = $conn->prepare("DELETE FROM jeniscucian WHERE id_jenis_cucian = ?");
    $stmt->bind_param("s", $idJenisCucian);

    if ($stmt->execute()) {
        $message = "Data Sudah Terhapus!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle form submission for editing a jenis cucian
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $idJenisCucian = $_POST['idJenisCucian'];
    $jenisCucian = $_POST['editJenisCucian'];
    $hargaCucian = $_POST['editHargaCucian'];

    // Validate and sanitize inputs
    $jenisCucian = htmlspecialchars($jenisCucian);
    $hargaCucian = filter_var($hargaCucian, FILTER_VALIDATE_INT);

    if ($hargaCucian === false) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showAlertModal('Harga cucian tidak valid.');
        });
      </script>";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE jeniscucian SET jenis_cucian = ?, harga_satuan_kilo = ? WHERE id_jenis_cucian = ?");
        $stmt->bind_param("sis", $jenisCucian, $hargaCucian, $idJenisCucian);

        if ($stmt->execute()) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlertModal('Jenis cucian berhasil diupdate.');
            });
          </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlertModal('Error: " . $stmt->error . "');
            });
          </script>";
        }

        $stmt->close();
    }
}

// Retrieve data from database
$sql = "SELECT * FROM jeniscucian";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="page/pglCucian/pglCucian.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
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
    <title>Cucian</title>
</head>

<body class="bg-secondary" style="--bs-bg-opacity: .15;">

<!-- awal -->
<div class="d-flex">
        <div class="p-4 bg-custom sidebar">
            <div class="d-flex gap-4">
                <img src="../img/Logo.png" alt="logo" class="w-25 h-25">
                <div class="text-white align-self-center">
                    <h1 class="fs-5">Admin</h1>
                    <p class="lh-1">Sistem Laundry</p>
                </div>
            </div>
            <div class="mt-5" >
                <div class="side-menu" onclick="window.location.href='../index.php'">
                    <img src="../img/user.png" alt="pendaftaran">
                    <a class="text-white" href="../index.php" style="text-decoration:none">Pendaftaran</a>
                </div>

                <div class="side-menu" onclick="window.location.href='pglTransaksi.php'">
                    <img src="../img/tag.png" alt="transaksi">
                    <a class="text-white" href="pglTransaksi.php" style="text-decoration:none">Pengelolaan Transaksi</a>
                </div>

                <div class="side-menu bg-active" onclick="window.location.href='/pglCucian.php'">
                    <img src="../img/washing.png" alt="cucian">
                    <a class="text-white" href="pglCucian.php" style="text-decoration:none">Pengelolaan Cucian</a>
                </div>

                <div class="side-menu" onclick="window.location.href='laporan.php'">
                    <img src="../img/bar-chart.png" alt="laporan">
                    <a class="text-white" href="laporan.php" style="text-decoration:none">Laporan</a>
                </div>

                <div class="side-menu" onclick="window.location.href='statusLaundry.php'">
                    <img src="../img/bell.png" alt="status">
                    <a class="text-white" href="statusLaundry.php" style="text-decoration:none">Status Laundry</a>
                </div>
            </div>
        </div>

        <div class="p-3 d-flex w-100">
<!-- akhir -->
    <div class="container vh-100 p-4">
        <h2 class="fw-bold mb-3">Pengelolaan Cucian</h2>
        <h5 class="card-title mb-2 mt-4">Daftar Jenis Cucian</h5>

        <!-- Tabel Jenis Cucian -->
        <div class="table-wrapper card">
            <div class="table-fixed-header">
                <table class="table table-bordered table-striped">

                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Jenis Cucian</th>
                            <th>Harga Per-Kilo</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo '<td class="text-center">' . $no . "</td>";
                                echo "<td>" . htmlspecialchars($row['jenis_cucian']) . "</td>";
                                echo "<td>Rp. " . number_format($row['harga_satuan_kilo'], 0, ',', '.') . "</td>";
                                echo '<td class="text-center">
                                    <button type="button" class="btn btn-link p-0 btn-lg" onclick="showConfirmationModal(\'' . htmlspecialchars($row['id_jenis_cucian']) . '\')">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                    <button class="btn btn-lg" type="button" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . htmlspecialchars($row['id_jenis_cucian']) . '" data-jenis="' . htmlspecialchars($row['jenis_cucian']) . '" data-harga="' . htmlspecialchars($row['harga_satuan_kilo']) . '">
                                        <i class="bi bi-pencil-square text-secondary"></i>
                                    </button>
                                </td>';
                                echo "</tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='4'>Tidak ada data jenis cucian.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tambah Jenis Cucian -->
        <div class="card mt-4 w-50">
            <div class="card-body p-4">
                <h5 class="card-title">Tambah Jenis Cucian</h5>
                <form method="POST" action="">
                    <input type="hidden" name="add" value="1">
                    <div class="form-group p-2">
                        <label for="jenisCucian">Jenis Cucian</label>
                        <input type="text" class="form-control" id="jenisCucian" name="jenisCucian"
                            placeholder="Masukan Jenis Cucian" required="" oninvalid="this.setCustomValidity('Tolong isi form ini!')" oninput="setCustomValidity('')">
                    </div>

                    <div class="form-group p-2">
                        <label for="hargaCucian">Harga Cucian</label>
                        <input type="number" class="form-control" id="hargaCucian" name="hargaCucian"
                            placeholder="Masukan Harga Cucian" required="" oninvalid="this.setCustomValidity('Tolong isi form ini!')" oninput="setCustomValidity('')">
                    </div>

                    <div class="form-group p-2">
                        <button type="submit" class="btn btn-success mt-2 w-100">Tambah</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal konfirmasi penghapusan -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header-uhuy justify-content-center m-4">
                        <span class="bi bi-question-circle modal-title-uhuy"> Konfirmasi</span>
                    </div>
                    <div class="modal-body d-flex align-items-center justify-content-center w-100">
                        Apakah Anda yakin ingin menghapus data ini?
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST" action=""
                            class="d-flex align-items-center justify-content-center w-100 gap-2">
                            <input type="hidden" name="delete" value="1">
                            <input type="hidden" name="idJenisCucian" id="deleteIdJenisCucian">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tidak</button>
                            <button type="submit" class="btn btn-success">Ya</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal edit data -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-square"></i> Edit Data Cucian
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <input type="hidden" name="edit" value="1">
                            <input type="hidden" name="idJenisCucian" id="editIdJenisCucian">
                            <div class="form-group p-2">
                                <label for="editJenisCucian">Jenis Cucian</label>
                                <input type="text" class="form-control" id="editJenisCucian" name="editJenisCucian"
                                    placeholder="Masukan Jenis Cucian" required="" oninvalid="this.setCustomValidity('Tolong isi form ini!')" oninput="setCustomValidity('')">
                            </div>

                            <div class="form-group p-2">
                                <label for="editHargaCucian">Harga Cucian</label>
                                <input type="number" class="form-control" id="editHargaCucian" name="editHargaCucian"
                                    placeholder="Masukan Harga Cucian" required="" oninvalid="this.setCustomValidity('Tolong isi form ini!')" oninput="setCustomValidity('')">
                            </div>

                            <div class="form-group p-2">
                                <button type="submit" class="btn btn-success mt-2 w-100">Perbarui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Alert -->
        <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header-uhuy justify-content-center m-4">
                        <span class="bi bi-question-circle modal-title-uhuy"> Informasi</span>
                    </div>
                    <div class="modal-body d-flex align-items-center justify-content-center w-100">
                        <p id="alertMessage"></p>
                    </div>
                    <div class="modal-footer d-flex align-items-center justify-content-center w-100">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ya</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            if (message) {
                showAlertModal(message);
            }
        });

        function showAlertModal(message) {
            document.getElementById('alertMessage').innerText = message;
            const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
            alertModal.show();
        }

        function showConfirmationModal(idJenisCucian) {
            document.getElementById('deleteIdJenisCucian').value = idJenisCucian;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        // Event listener to populate edit modal with data
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var idJenisCucian = button.getAttribute('data-id');
            var jenisCucian = button.getAttribute('data-jenis');
            var hargaCucian = button.getAttribute('data-harga');

            var modalIdInput = document.getElementById('editIdJenisCucian');
            var modalJenisInput = document.getElementById('editJenisCucian');
            var modalHargaInput = document.getElementById('editHargaCucian');

            modalIdInput.value = idJenisCucian;
            modalJenisInput.value = jenisCucian;
            modalHargaInput.value = hargaCucian;
        });
    </script>
</body>

</html>