<?php
include "koneksi.php";

// Function to generate the ID
function generateId($jenisCucian) {
    $prefix = strtoupper(substr($jenisCucian, 0, 2)); // Get the first two letters and convert to uppercase
    $randomNumber = rand(10, 99); // Generate a random two-digit number
    return $prefix . $randomNumber;
}

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Menangani form submit untuk menambahkan jenis cucian baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenisCucian = $_POST['jenisCucian'];
    $hargaCucian = $_POST['hargaCucian'];

    // Validate and sanitize inputs
    $jenisCucian = htmlspecialchars($jenisCucian);
    $hargaCucian = filter_var($hargaCucian, FILTER_VALIDATE_INT);

    if ($hargaCucian === false) {
        echo "<div class='alert alert-danger' role='alert'>Harga cucian tidak valid.</div>";
    } else {
        $idJenisCucian = generateId($jenisCucian);

        // Menggunakan prepared statement untuk menghindari SQL injection
        $stmt = $conn->prepare("INSERT INTO Jenis_Cucian (id_jenis_cucian, jenis_cucian, harga) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $idJenisCucian, $jenisCucian, $hargaCucian);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Jenis cucian baru berhasil ditambahkan.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

// Handle form submission for deleting a jenis cucian
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $idJenisCucian = $_POST['idJenisCucian'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM Jenis_Cucian WHERE id_jenis_cucian = ?");
    $stmt->bind_param("s", $idJenisCucian);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success' role='alert'>Jenis cucian berhasil dihapus.</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
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
        echo "<div class='alert alert-danger' role='alert'>Harga cucian tidak valid.</div>";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE Jenis_Cucian SET jenis_cucian = ?, harga = ? WHERE id_jenis_cucian = ?");
        $stmt->bind_param("sis", $jenisCucian, $hargaCucian, $idJenisCucian);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Jenis cucian berhasil diupdate.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

// Mendapatkan data jenis cucian dari database
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
            padding: 15px;
        }
    </style>
    <title>Pengelolaan Cucian</title>
</head>

<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container">
        <h2 class="fw-bold mb-5">Pengelolaan Cucian</h2>

        <!-- Tabel Jenis Cucian -->
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
                <?php
                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no . "</td>";
                        echo "<td>" . htmlspecialchars($row['jenis_cucian']) . "</td>";
                        echo "<td>Rp. " . number_format($row['harga'], 0, ',', '.') . "</td>";
                         echo '<td>
                                <form method="POST" action="" style="display:inline-block;">
                                    <input type="hidden" name="idJenisCucian" value="' . htmlspecialchars($row['id_jenis_cucian']) . '">
                                    <button type="submit" name="delete" class="btn btn-link p-0">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                                <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . htmlspecialchars($row['id_jenis_cucian']) . '" data-jenis="' . htmlspecialchars($row['jenis_cucian']) . '" data-harga="' . htmlspecialchars($row['harga']) . '">
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

        <!-- Tambah Jenis Cucian -->
        <div class="card mt-4">
            <div class="card-body p-4">
                <h5 class="card-title">Tambah Jenis Cucian</h5>
                <form method="POST" action="">
                    <div class="form-group p-2">
                        <label for="jenisCucian">Jenis Cucian</label>
                        <input type="text" class="form-control" id="jenisCucian" name="jenisCucian" placeholder="Masukan Jenis Cucian" required>
                    </div>
                    <div class="form-group p-2">
                        <label for="hargaCucian">Harga Jenis Cucian</label>
                        <input type="number" class="form-control" id="hargaCucian" name="hargaCucian" placeholder="Masukan Harga Jenis Cucian" required>
                    </div>
                    <button type="submit" class="btn btn-success p-2">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jenis Cucian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="edit" value="1">
                        <input type="hidden" name="idJenisCucian" id="editIdJenisCucian">
                        <div class="form-group p-2">
                            <label for="editJenisCucian">Jenis Cucian</label>
                            <input type="text" class="form-control" id="editJenisCucian" name="editJenisCucian" placeholder="Masukan Jenis Cucian" required>
                        </div>
                        <div class="form-group p-2">
                            <label for="editHargaCucian">Harga Jenis Cucian</label>
                            <input type="number" class="form-control" id="editHargaCucian" name="editHargaCucian" placeholder="Masukan Harga Jenis Cucian" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.min.js"></script>

    <script>
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var idJenisCucian = button.getAttribute('data-id');
            var jenisCucian = button.getAttribute('data-jenis');
            var hargaCucian = button.getAttribute('data-harga');

            var modalTitle = editModal.querySelector('.modal-title');
            var modalBodyInputId = editModal.querySelector('.modal-body input#editIdJenisCucian');
            var modalBodyInputJenis = editModal.querySelector('.modal-body input#editJenisCucian');
            var modalBodyInputHarga = editModal.querySelector('.modal-body input#editHargaCucian');

            modalBodyInputId.value = idJenisCucian;
            modalBodyInputJenis.value = jenisCucian;
            modalBodyInputHarga.value = hargaCucian;
        });
    </script>
    
</body>

</html>

<?php
mysqli_close($conn);
?>
