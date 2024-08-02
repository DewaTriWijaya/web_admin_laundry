<?php
// Menghubungkan ke database
include "koneksi.php";

// Memeriksa apakah koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Mengambil semua data dari tabel status_laundry yang statusnya 'Belum' atau 'Selesai' yang belum diberitahukan
$sql = "SELECT * FROM statuslaundry WHERE status_laundry='Belum' OR (status_laundry='Selesai')";
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
        .home-uhuy {
            margin-left: 300px;
        }
    </style>
    <!-- Link jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container vh-100 home-uhuy">
        <div class="row">
            <h2 class="fw-bold mb-5">Status Laundry</h2>
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
                                            echo "<tr id='row-".$row['no_nota']."'>";
                                            echo "<td scope='row' class='text-center'><input type='hidden' name='No_Nota[]' value='".$row['no_nota']."'>".$row['no_nota']."</td>";
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
                    
                    // Update status notified di database hanya jika status adalah 'Selesai'
                    if (selectedData.status === 'Selesai') {
                        $.ajax({
                            url: 'update_notified.php',
                            type: 'POST',
                            data: { nota: selectedData.nota },
                            success: function(response) {
                                console.log("Status notified diperbarui.");
                                // Hapus baris dari tabel jika status Selesai
                                document.getElementById('row-' + selectedData.nota).style.display = 'none';
                            },
                            error: function(error) {
                                console.error("Gagal memperbarui status notified.");
                            }
                        });
                    }
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
    </script>
</body>
</html>
