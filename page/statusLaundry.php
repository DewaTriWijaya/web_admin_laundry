<?php
// Menghubungkan ke database
include "../koneksi.php";

// Memeriksa apakah koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Mengambil semua data dari tabel status_laundry
$sql = "SELECT * FROM statuslaundry";
$result = mysqli_query($conn, $sql);

$success = false;

// Memeriksa apakah form dikirim menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $status_laundry = $_POST['status'];
    $No_Nota = $_POST['no_nota'];
    $tanggal_selesai = $_POST['tanggal_selesai'];

    // Menyiapkan query SQL untuk memperbarui setiap baris
    foreach ($No_Nota as $index => $nota) {
        $status = $status_laundry[$index];
        $tanggal = $tanggal_selesai[$index];
        
        $sql = "UPDATE statuslaundry SET tanggal_selesai='$tanggal', status_laundry='$status' WHERE no_nota='$nota'";

        // Menjalankan query dan memeriksa apakah berhasil
        if ($conn->query($sql) === TRUE) {
            $success = true;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Menutup koneksi database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
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

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .modal-title {
            font-size: 25px;
            font-weight: bold;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .modal-footer button {
            padding: 7px 20px;
        }

        /* CSS untuk membuat tabel bisa digulir */
        .table-container {
            max-height: 300px;
            overflow-y: auto;
        }
        .sidebar {
            width: 380px;
        }
        
        body {
            font-family: poppins;
        }

        .bg-active {
            background-color: #0086ac;
        }

        
    </style>
    <!-- Link jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
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

                <div class="side-menu" onclick="window.location.href='pglCucian.php'">
                    <img src="../img/washing.png" alt="cucian">
                    <a class="text-white" href="pglCucian.php" style="text-decoration:none">Pengelolaan Cucian</a>
                </div>

                <div class="side-menu" onclick="window.location.href='laporan.php'">
                    <img src="../img/bar-chart.png" alt="laporan">
                    <a class="text-white" href="laporan.php" style="text-decoration:none">Laporan</a>
                </div>

                <div class="side-menu bg-active" onclick="window.location.href='statusLaundry.php'">
                    <img src="../img/bell.png" alt="status">
                    <a class="text-white" href="statusLaundry.php" style="text-decoration:none">Status Laundry</a>
                </div>
            </div>
        </div>

        <div class="p-3 d-flex w-100 bg-secondary" style="--bs-bg-opacity: .15;">
     <!-- Akhir -->
    <div class="container vh-100 p-4">
        <div class="row "  >
            <h2 class="fw-bold mb-5">Informasi Status Laundry</h2>
            <div class="justify-content-center">
                <form method="POST" action="">
                    <div class="table-container">
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
                                            echo "<td scope='row' class='text-center'><input type='hidden' name='no_nota[]' value='".$row['no_nota']."'>".$row['no_nota']."</td>";
                                            echo "<td><input type='date' class='form-control' name='tanggal_selesai[]' value='".$row['tanggal_selesai']."'></td>";
                                            echo "<td class='text-center'><select name='status[]' class='form-select'>";
                                            echo "<option value='Belum'" . ($status == 'Belum' ? " selected" : "") . ">Belum</option>";
                                            echo "<option value='Selesai'" . ($status == 'Selesai' ? " selected" : "") . ">Selesai</option>";
                                            echo "</select></td>";
                                            echo "<td><button type='button' class='btn btn-primary notification-btn' data-nota='".$row['no_nota']."' data-status='".$status."' data-tanggal='".$row['tanggal_selesai']."'>Pemberitahuan</button></td>";
                                            echo "</tr>";
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button type="submit" name="simpan" class="btn btn-success px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Sukses -->
    <div id="successModal" class="modal">
        <div class="modal-dialog modal-dialog-centered">                           
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <span class="bi bi-info-circle modal-title"> Pemberitahuan</span>
                </div>
                <p class="text-center">Status laundry berhasil disimpan</p>
                <div class="modal-footer">
                    <button id="successClose" class="btn btn-success">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="confirmationModal" class="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <span class="bi bi-question-circle modal-title"> Konfirmasi</span>
                </div>
                <p class="text-center">Apakah anda yakin ingin memberitahu informasi status ini kepada pelanggan?</p>
                <div class="modal-footer">
                    <button id="confirmYes" class="btn btn-success">Ya</button>
                    <button id="confirmNo" class="btn btn-danger">Tidak</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script>
        // Mengambil elemen modal dan tombol tutup
        var successModal = document.getElementById("successModal");
        var confirmationModal = document.getElementById("confirmationModal");
        var closeBtns = document.getElementsByClassName("close");
        var selectedData = {};

        // Menampilkan modal sukses jika data berhasil diupdate
        <?php if ($success) { ?>
            successModal.style.display = "block";
        <?php } ?>

        // Menambahkan event listener untuk tombol tutup modal sukses
         document.getElementById("successClose").onclick = function () {
            successModal.style.display = "none";
            window.location.href = window.location.href;
        };

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

        // Menambahkan event listener untuk tombol pemberitahuan
        document.querySelectorAll('.notification-btn').forEach(button => {
            button.addEventListener('click', function () {
                selectedData = {
                    nota: this.getAttribute('data-nota'),
                    status: this.getAttribute('data-status'),
                    tanggal: this.getAttribute('data-tanggal')
                };
                showConfirmationModal();
            });
        });

        // Fungsi untuk menampilkan modal konfirmasi
        function showConfirmationModal() {
            confirmationModal.style.display = "block";
        }

        // Mengambil elemen tombol konfirmasi
        document.getElementById("confirmYes").onclick = function () {
            $.ajax({
                url: 'wa.php',
                type: 'POST',
                data: selectedData,
                success: function(response) {
                    confirmationModal.style.display = "none";
                    alert("Informasi status telah diberitahukan kepada pelanggan.");
                },
                error: function(error) {
                    confirmationModal.style.display = "none";
                    alert("Gagal mengirim pemberitahuan.");
                }
            });
        }

        document.getElementById("confirmNo").onclick = function () {
            confirmationModal.style.display = "none";
        }

        function openHomeAndToggleDropdown() {
            // Arahkan ke home.php
            window.location.href = 'index.php?page=home&menu=transaksi';
        }

    </script>
</body>
</html>
