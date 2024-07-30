<?php
include "koneksi.php";

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
        $stmt = $conn->prepare("INSERT INTO Jenis_Cucian (id_jenis_cucian, jenis_cucian, harga) VALUES (?, ?, ?)");
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

    $stmt = $conn->prepare("DELETE FROM Jenis_Cucian WHERE id_jenis_cucian = ?");
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
        $stmt = $conn->prepare("UPDATE Jenis_Cucian SET jenis_cucian = ?, harga = ? WHERE id_jenis_cucian = ?");
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
$sql = "SELECT * FROM Jenis_Cucian";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .table td,
        .table th {
            padding: 8px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .table-fixed-header {
            height: 190px;
            overflow-y: auto;
        }

        .table-fixed-header thead th {
            position: sticky;
            top: 0;
            background: white;
            z-index: 1000;
        }

        .modal-header .bi-exclamation-circle-fill {
            font-size: 1.5rem;
        }

        .modal-title {
            display: flex;
            align-items: center;
        }


        .modal-header-uhuy {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .modal-title-uhuy {
            font-size: 25px;
            font-weight: bold;
        }


        .modal-body {
            font-size: 1.1rem;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .modal-footer .btn-success {
            width: 100px;
        }

        .modal-header .bi-question-circle-fill,
        .modal-header .bi-exclamation-circle-fill {
            font-size: 1.5rem;
        }
    </style>
    <title>Pengelolaan Cucian</title>
</head>

<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container vh-100">
        <h2 class="fw-bold mb-3">Pengelolaan Cucian</h2>
        <h5 class="card-title mb-2">Daftar Jenis Cucian</h5>

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
                                echo "<td>Rp. " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                echo '<td class="text-center">
                                    <button type="button" class="btn btn-link p-0 btn-lg" onclick="showConfirmationModal(\'' . htmlspecialchars($row['id_jenis_cucian']) . '\')">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                    <button class="btn btn-lg" type="button" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . htmlspecialchars($row['id_jenis_cucian']) . '" data-jenis="' . htmlspecialchars($row['jenis_cucian']) . '" data-harga="' . htmlspecialchars($row['harga']) . '">
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
                            placeholder="Masukan Jenis Cucian" required>
                    </div>

                    <div class="form-group p-2">
                        <label for="hargaCucian">Harga Cucian</label>
                        <input type="number" class="form-control" id="hargaCucian" name="hargaCucian"
                            placeholder="Masukan Harga Cucian" required>
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
                                    placeholder="Masukan Jenis Cucian" required>
                            </div>

                            <div class="form-group p-2">
                                <label for="editHargaCucian">Harga Cucian</label>
                                <input type="number" class="form-control" id="editHargaCucian" name="editHargaCucian"
                                    placeholder="Masukan Harga Cucian" required>
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