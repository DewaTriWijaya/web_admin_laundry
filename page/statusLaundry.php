<?php
// Menghubungkan ke database
include "koneksi.php";

// Memeriksa apakah koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Mengambil semua data dari tabel status_laundry
$sql = "SELECT * FROM Status_Laundry";
$result = mysqli_query($conn, $sql);

$success = false;

// Memeriksa apakah form dikirim menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $status_laundry = $_POST['status'];
    $No_Nota = $_POST['No_Nota'];
    $tanggal_selesai = $_POST['tanggal_selesai'];

    // Menyiapkan query SQL untuk memperbarui setiap baris
    foreach ($No_Nota as $index => $nota) {
        $status = $status_laundry[$index];
        $tanggal = $tanggal_selesai[$index];
        
        $sql = "UPDATE Status_Laundry SET tanggal_selesai='$tanggal', status_laundry='$status' WHERE No_Nota='$nota'";

        // Menjalankan query dan memeriksa apakah berhasil
        if ($conn->query($sql) === TRUE) {
            $success = true;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Menutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Laundry</title>
    <style>
        /* CSS untuk modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            width: 40%;
            text-align: center;
            position: relative;
            border: none;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: bold;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .modal-footer button {
            padding: 12px 30px;
        }
    </style>
</head>
<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container">
        <div class="row">
            <h2 class="fw-bold mb-5">Informasi Status Laundry</h2>
            <div class="justify-content-center">
                <form method="POST" action="">
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
                            <?php 
                                // Menampilkan data dari hasil query
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $status = $row["status_laundry"];
                                        echo "<tr>";
                                        echo "<td scope='row' class='text-center'><input type='hidden' name='No_Nota[]' value='".$row['No_Nota']."'>".$row['No_Nota']."</td>";
                                        echo "<td><input type='date' class='form-control' name='tanggal_selesai[]' value='".$row['tanggal_selesai']."'></td>";
                                        echo "<td class='text-center'><select name='status[]' class='form-select'>";
                                        echo "<option value='Belum'" . ($status == 'Belum' ? " selected" : "") . ">Belum</option>";
                                        echo "<option value='Selesai'" . ($status == 'Selesai' ? " selected" : "") . ">Selesai</option>";
                                        echo "</select></td>";
                                        echo "<td><button type='button' class='btn btn-primary' onclick='showConfirmationModal()'>Pemberitahuan</button></td>";
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="simpan" class="btn btn-success px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Sukses -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Status Laundry Berhasil diubah</p>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="bi bi-question-circle pr-1 modal-title">    Konfirmasi</span>
            </div>
            <p>Apakah anda yakin ingin memberitahu informasi status ini kepada pelanggan?</p>
            <div class="modal-footer">
                <button id="confirmYes" class="btn btn-success">Ya</button>
                <button id="confirmNo" class="btn btn-danger">Tidak</button>
            </div>
        </div>
    </div>

    <script>
        // Mengambil elemen modal dan tombol tutup
        var successModal = document.getElementById("successModal");
        var confirmationModal = document.getElementById("confirmationModal");
        var closeBtns = document.getElementsByClassName("close");

        // Menampilkan modal sukses jika data berhasil diupdate
        <?php if ($success) { ?>
            successModal.style.display = "block";
        <?php } ?>

        // Menambahkan event listener untuk tombol tutup modal
        Array.from(closeBtns).forEach(function (btn) {
            btn.onclick = function () {
                this.parentElement.parentElement.style.display = "none";
                if (this.parentElement.parentElement === successModal) {
                    window.location.href = window.location.href;
                }
            }
        });

        // Menutup modal ketika pengguna mengklik di luar modal
        window.onclick = function (event) {
            if (event.target == successModal || event.target == confirmationModal) {
                event.target.style.display = "none";
                if (event.target === successModal) {
                    window.location.href = window.location.href;
                }
            }
        }

        // Fungsi untuk menampilkan modal konfirmasi
        function showConfirmationModal() {
            confirmationModal.style.display = "block";
        }

        // Mengambil elemen tombol konfirmasi
        document.getElementById("confirmYes").onclick = function () {
            confirmationModal.style.display = "none";
            alert("Informasi status telah diberitahukan kepada pelanggan.");
        }

        document.getElementById("confirmNo").onclick = function () {
            confirmationModal.style.display = "none";
        }
    </script>
</body>
</html>
