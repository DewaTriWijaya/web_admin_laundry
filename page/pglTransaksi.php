<?php
include "koneksi.php";

// Fetch jenis_cucian data from database
$jenisCucianOptions = '';
$jenisCucian = [];
$query = "SELECT id_jenis_cucian , jenis_cucian , harga_satuan_kilo FROM jeniscucian";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $jenisCucianOptions .= "<option value='{$row['id_jenis_cucian']}'>{$row['jenis_cucian']}</option>";
    $jenisCucian[$row['id_jenis_cucian']] = $row['harga_satuan_kilo'];
}
echo '<script>';
echo 'var jenisCucian = ' . json_encode($jenisCucian) . ';'; // Pass the data to JavaScript
echo '</script>';

// Customer Search Handling
if (isset($_POST['cari_pelanggan'])) {
    $no_hp = trim($_POST['no_hp']);

    if (empty($no_hp)) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showAlertModal('Data Tidak Boleh Kosong.');
                });
            </script>";
    } else {
        // Validate phone number format
        if (!preg_match('/^[0-9]{1,15}$/', $no_hp)) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlertModal('Data harus 0-9.');
                    });
                </script>";
        } else {

            // Prepare the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT nama FROM pelanggan WHERE no_hp = ?");
            $stmt->bind_param('s', $no_hp);
            $stmt->execute();
            $stmt->store_result(); // Needed to check the number of rows

            if ($stmt->num_rows > 0) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlertModal('Data Pelanggan Ditemukan');
                    });
                </script>";
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showConfirmationModal( 'Data Pelanggan tidak dapat ditemukan. Apakah anda ingin menambahkan ?');
                    });
                </script>";
            }

            $stmt->close();
        }
    }
    $conn->close();
}

// Simpan Data
if (isset($_POST['simpan_data_nota'])) {
    $errors = [];
    $date = date('dmY');
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomLetters = $letters[rand(0, 25)] . $letters[rand(0, 25)];
    $noNota = $date . $randomLetters;
    $noHp = trim($_POST['no_hp']);
    $jenisPembayaran = trim($_POST['jenis_pembayaran']);
    $tanggalTransaksi = trim($_POST['tanggal_transaksi']);
    $estimasiSelesai = trim($_POST['estimasi_selesai']);
    $totalHarga = isset($_POST['total_harga_int']) ? intval($_POST['total_harga_int']) : 0;
    $totalBerat = isset($_POST['total_berat']) ? floatval($_POST['total_berat']) : 0.0;

    // Validasi di sisi server
    if (empty($noHp) || empty($jenisPembayaran) || empty($tanggalTransaksi) || empty($estimasiSelesai) || $totalHarga <= 0 || $totalBerat <= 0) {
        $errors[] = "Semua field harus diisi dan nilai harus valid.";
    }

    if (empty($errors)) {
        $query = "INSERT INTO nota (no_nota, no_hp, jenis_pembayaran, berat_cucian, tgl_masuk, estimasi_selesai, harga_total_bayar) 
                  VALUES ('$noNota', '$noHp', '$jenisPembayaran', '$totalBerat', '$tanggalTransaksi', '$estimasiSelesai', '$totalHarga')";
        $query_status = "INSERT INTO statuslaundry (no_nota, status_laundry, tanggal_selesai) 
                         VALUES ('$noNota', 'Belum' , '$estimasiSelesai')";

        // Bjirrr
        error_log("Pesan kesalahan atau log informasi $jenisCucian / $noNota / $", 3, "/path/to/custom.log");
        if (mysqli_query($conn, $query) && mysqli_query($conn, $query_status)) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() { showAlertModal('Data Berhasil disimpan'); });</script>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "<script>document.addEventListener('DOMContentLoaded', function() { showAlertModal('" . implode("\\n", $errors) . "'); });</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Status Laundry</title>
    <style>
        .modal-header .bi-exclamation-circle-fill {
            font-size: 1.5rem;
        }

        .modal-title {
            display: flex;
            align-items: center;
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

        .modal-body {
            font-size: 1.1rem;
            margin-top: 10px;
            margin-bottom: 10px;
        }

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

        .home-uhuy {
            margin-left: 300px;
        }
    </style>
</head>

<body class="bg-secondary" style="--bs-bg-opacity:.15;">

    <form method="POST" id="main_form" action="">
        <div class="container vh-100 home-uhuy">
            <div class="row mb-2">
                <div class="head mb-5 mt-1 ">
                    <h2 class="fw-bold">Pengelolaan Transaksi</h2>
                </div>

                <div class="col">
                    <h6><b>No Handphone Pelanggan</b></h6>
                </div>

                <!-- Inputan Data No Handphone -->
                <div class="d-flex justify-content-start gap-4 mb-3">
                    <form method="POST" action="">
                        <div class="col">
                            <input type="text" class="form-control" id="no_hp" name="no_hp"
                                placeholder="Masukan No Handphone Pelanggan"
                                value="<?php echo isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : ''; ?>">
                        </div>
                        <div class="col">
                            <button class="btn btn btn-outline-dark" type="submit" id="cari_pelanggan"
                                name="cari_pelanggan"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </form>
                </div>

                <!-- Button Tambah Jenis Cucian-->
                <div class="d-flex flex-row ">
                    <div class="row align-self-center me-1">
                        <h5><b>Jenis Cucian</b></h5>
                    </div>
                    <div class="row p-2">
                        <button class="btn btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                            <h3><i class="bi bi-plus-circle"></i></h3>
                        </button>
                    </div>
                </div>

                <!-- Table Jenis Cucian -->
                <table class="table table-bordered table-striped" id="tabel_cucian">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Jenis Cucian</th>
                            <th scope="col">Jumlah Kilogram</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-ligh text-center">

                    </tbody>
                </table>

            </div>


            <div class="col">
                <div class="row mb-4">
                    <div class="col me-4">
                        <h6><b>Tanggal Transaksi</b></h6>
                        <div class="col-md-7">
                            <input type="date" class="form-control" name="tanggal_transaksi">
                        </div>
                    </div>
                    <div class="col me-4">
                        <h6><b>Total Harga</b></h6>
                        <div class="col-md-5">
                            <fieldset id="field_HargadanBerat" disabled>
                                <input type="text" id="total_harga" name="total_harga" class="form-control bg-light">
                                <input type="hidden" name="total_harga_int" id="total_harga_int" value="0">
                                <input type="hidden" name="total_berat" id="total_berat" value="0">
                            </fieldset>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col me-4">
                        <h6><b>Estimasi Selesai</b></h6>
                        <div class="col-md-7">
                            <input type="date" class="form-control" name="estimasi_selesai">
                        </div>
                    </div>
                    <div class="col me-4">
                        <h6><b>Pembayaran<b></h6>
                        <div class="d-flex flex-row mb-3">
                            <div class="row me-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_pembayaran" id="qris"
                                        value="Qris">
                                    <p>QRIS</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-check">
                                    <input class="form-check-input" checked type="radio" name="jenis_pembayaran"
                                        id="tunai" value="Tunai">
                                    <p>Tunai</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-row ms-3">
                <div class="row me-5">
                    <button type="submit" class="btn btn-success p-2 px-4" name="simpan_data_nota">Simpan</button>
                </div>
                <div class="row">
                    <button type="button" class="btn btn-danger p-2 px-4">Hapus</button>
                </div>
            </div>

        </div>
    </form>


    <!-- Add Jenis Cucian Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Jenis Cucian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="jenis_cucian">Jenis Cucian:</label>
                            <select class="form-control" id="jenis_cucian" name="jenis_cucian" required>
                                <option value="">Pilih Jenis Cucian</option>
                                <?= $jenisCucianOptions; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah Per-Kilogram:</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" required>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" name="simpan_jenis_cucian">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- peringatan data tidak ada Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title d-flex align-items-center justify-content-center w-100" id="alertModalLabel">
                        <i class="bi bi-question-circle me-2"></i> Konfirmasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center w-100 text-center">
                    <p id="confirmationMessage" class="fw-normal"></p>
                </div>
                <div class="modal-footer d-flex align-items-center justify-content-center w-100">
                    <a class="text-white" href="index.php?page=pendaftaran" style="text-decoration:none"><button
                            id="confirmYes" class="btn btn-success">Ya</button></a>
                    <button id="confirmNo" data-bs-dismiss="modal" class="btn btn-danger w-10">Tidak</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Alert -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title d-flex align-items-center justify-content-center w-100" id="alertModalLabel">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> Peringatan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center w-100">
                    <p id="alertMessage" class="fw-normal"></p>
                </div>
                <div class="modal-footer d-flex align-items-center justify-content-center w-100">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>


    <script>
        document.getElementById('main_form').addEventListener('submit', function () {
            // Enable the fieldset just before submitting the form
            document.getElementById('field_HargadanBerat').disabled = false;
        });

        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            const pesan = urlParams.get('pesan');
            if (message) {
                showAlertModal(message);
            }
            if (pesan) {
                showConfirmationModal(pesan)
            }
        });

        function showAlertModal(message) {
            document.getElementById('alertMessage').innerText = message;
            const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
            alertModal.show();
        }

        function showConfirmationModal(pesan) {
            document.getElementById('confirmationMessage').innerText = pesan;
            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        }

        document.getElementById('addModal').querySelector('form').addEventListener('submit', function (e) {
            e.preventDefault();

            // Ambil nilai dari form
            var jenisCucianSelect = document.getElementById('jenis_cucian');
            var jumlah = document.getElementById('jumlah').value.trim();

            // Validasi form
            if (jenisCucianSelect.value === "" || jumlah === "") {
                alert("Semua field harus diisi!");
                return; // Jangan lanjutkan jika validasi gagal
            }

            // Lanjutkan jika validasi berhasil
            var selectedJenis = jenisCucianSelect.options[jenisCucianSelect.selectedIndex].text;
            var selectedJenisId = jenisCucianSelect.value;
            var jumlahFloat = parseFloat(jumlah);
            var price = jenisCucian[selectedJenisId] * jumlahFloat;

            if (jumlahFloat && selectedJenisId) {
                var table = document.getElementById('tabel_cucian').getElementsByTagName('tbody')[0];
                var existingRow = Array.from(table.rows).find(row => row.cells[1].innerText === selectedJenis);
                if (existingRow) {
                    var existingJumlah = parseFloat(existingRow.cells[2].innerText);
                    var newJumlah = existingJumlah + jumlahFloat;
                    existingRow.cells[2].innerText = newJumlah;
                    existingRow.cells[3].innerText = 'Rp ' + (jenisCucian[selectedJenisId] * newJumlah).toLocaleString('id-ID');
                } else {
                    var newRow = table.insertRow();
                    newRow.innerHTML = `
                <td>${table.rows.length}</td>
                <td>${selectedJenis}</td>
                <td>${jumlahFloat}</td>
                <td>Rp ${price.toLocaleString('id-ID')}</td>
                <td><button class="btn btn-danger btn-sm" onclick="removeRow(this)">Delete</button></td>
            `;
                }
                document.getElementById('jenis_cucian').value = '';
                document.getElementById('jumlah').value = '';
                calculateTotal();
            } else {
                alert("Silakan isi semua field dengan benar.");
            }
        });

        // Function to remove a row and update the total price
        function removeRow(button) {
            var row = button.closest('tr');
            row.parentNode.removeChild(row);
            calculateTotal();
        }

        // Calculate the total price
        function calculateTotal() {
            let total = 0;
            let totalWeight = 0;
            var rows = document.querySelectorAll('#tabel_cucian tbody tr');
            rows.forEach(row => {
                var priceText = row.cells[3].innerText.replace('Rp ', '').replace(/,/g, '');
                var weightText = row.cells[2].innerText;
                var price = parseFloat(priceText);
                var weight = parseFloat(weightText);

                if (!isNaN(price)) {
                    total += price;
                }
                if (!isNaN(weight)) {
                    totalWeight += weight;
                }
            });

            let Multipled_total = total * 1000
            document.getElementById('total_harga').value = 'Rp ' + Multipled_total.toLocaleString('id-ID');
            document.getElementById('total_harga_int').value = Multipled_total;
            document.getElementById('total_berat').value = totalWeight;
        }

        document.addEventListener('DOMContentLoaded', calculateTotal);
        // Jika ada perubahan dalam tabel, hitung ulang total
        document.querySelector('tbody').addEventListener('DOMSubtreeModified', calculateTotal);
    </script>

</body>

</html>