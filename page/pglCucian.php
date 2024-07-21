<?php
include "koneksi.php"; // Ensure this file establishes the $conn variable

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
    $hargaCucian = (int) $hargaCucian;

    // Menggunakan prepared statement untuk menghindari SQL injection
    $stmt = $conn->prepare("INSERT INTO DataJenisCucian (jenis_cucian, harga) VALUES (?, ?)");
    $stmt->bind_param("si", $jenisCucian, $hargaCucian);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success' role='alert'>Jenis cucian baru berhasil ditambahkan.</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Mendapatkan data jenis cucian dari database
$sql = "SELECT * FROM DataJenisCucian";
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
                                <button class="btn" type="button">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                                <button class="btn">
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

    <script src="../js/bootstrap.min.js"></script>
</body>

</html>

<?php
mysqli_close($conn);
?>
