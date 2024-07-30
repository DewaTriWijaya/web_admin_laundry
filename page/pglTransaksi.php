<?php
include "koneksi.php";

// Fetch jenis_cucian data from database
$jenisCucianOptions = ''; // Initialize variable to store options
$jenisCucian = []; // Array to store jenis_cucian data

$query = "SELECT id_jenis_cucian , jenis_cucian , harga FROM jenis_cucian";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $jenisCucianOptions .= "<option value='{$row['id_jenis_cucian']}'>{$row['jenis_cucian']}</option>";
    $jenisCucian[$row['id_jenis_cucian']] = $row['harga'];
}

echo '<script>';
echo 'var jenisCucian = ' . json_encode($jenisCucian) . ';'; // Pass the data to JavaScript
echo '</script>';

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
        }else{

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT nama FROM pelanggan WHERE No_HP = ?");
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

if (isset($_POST['simpan_data_nota'])) {
    // Generate ID Nota
    $date = date('dmY');
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomLetters = $letters[rand(0, 25)] . $letters[rand(0, 25)];
    $noNota = $date . $randomLetters;

    // Retrieve form data
    $noHp = $_POST['no_hp'];
    $jenisPembayaran = $_POST['metode_pembayaran']; // Assuming 'Qris' or 'Tunai'
    $tanggalTransaksi = $_POST['tanggal_transaksi'];
    $estimasiSelesai = $_POST['Estimasi_selesai'];
    $totalHarga = str_replace('Rp ', '', $_POST['total_harga']); // Remove 'Rp ' prefix
    $totalHarga = str_replace(',', '', $totalHarga); // Remove any commas
    $totalHarga = (int)$totalHarga;

    // Calculate total weight and ensure it's an integer
    $totalBerat = 0;
    foreach ($_POST['berat'] as $berat) {
        $totalBerat += (int)$berat;
    }

    // Insert into `nota` table
    $query = "INSERT INTO nota (No_Nota, No_HP, Jenis_pembayaran, Berat_cucian, Tgl_masuk, Estimasi_selesai, Total_Harga) 
              VALUES ('$noNota', '$noHp', '$jenisPembayaran', '$totalBerat', '$tanggalTransaksi', '$estimasiSelesai', '$totalHarga')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlertModal('Data Berhasil disimpan');
                    });
                </script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    // Insert into `detail_laundry` table
    // $dataCucian = $_POST['tabel_cucian']; // Assuming this contains an array of items
    // foreach ($dataCucian as $item) {
    //     $jenisCucian = $item['jenis_cucian'];
    //     $harga = $item['harga'];
    //     $jenisCucian = mysqli_real_escape_string($conn, $jenisCucian);

    //     // Query to get id_jenis_cucian based on jenis_cucian
    //     $query_idCucian = "SELECT id_jenis_cucian FROM jenis_cucian_table WHERE jenis_cucian = '$jenisCucian'";

    //     $result = mysqli_query($conn, $query_idCucian);

    //     // Check if the query was successful and if we got a result
    //     if ($result && mysqli_num_rows($result) > 0) {
    //         $row = mysqli_fetch_assoc($result);
    //         $idJenisCucian = $row['id_jenis_cucian'];
    //     }
        
    //     $detailQuery = "INSERT INTO detail_laundry (No_Nota, id_jenis_cucian, Harga) 
    //                     VALUES ('$noNota', '$idJenisCucian', '$harga')";
        
    //     if (!mysqli_query($conn, $detailQuery)) {
    //         echo "Error: " . $detailQuery . "<br>" . mysqli_error($conn);
    //     }
    
    // }
    $dataCucianJson = $_POST['tabel_cucian'];
    $dataCucian = json_decode($dataCucianJson, true); // Decode JSON to associative array

    foreach ($dataCucian as $item) {
        $jenisCucian = $item['jenis_cucian'];
        $harga = (int)$item['harga']; // Ensure harga is an integer
        $jenisCucian = mysqli_real_escape_string($conn, $jenisCucian);

        // Query to get id_jenis_cucian based on jenis_cucian
        $query_idCucian = "SELECT id_jenis_cucian FROM jenis_cucian WHERE jenis_cucian = '$jenisCucian'";

        $result = mysqli_query($conn, $query_idCucian);

        // Check if the query was successful and if we got a result
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $idJenisCucian = $row['id_jenis_cucian'];
        } else {
            echo "Jenis cucian tidak ditemukan: $jenisCucian<br>";
            continue; // Skip this item if no match is found
        }
        
        $detailQuery = "INSERT INTO detail_laundry (No_Nota, id_jenis_cucian, Harga) 
                        VALUES ('$noNota', '$idJenisCucian', '$harga')";
        
        if (!mysqli_query($conn, $detailQuery)) {
            echo "Error: " . $detailQuery . "<br>" . mysqli_error($conn);
        }
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
        .table td,
        .table th {
            padding: 15px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .table-fixed-header {
            height: 250px;
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
    </style>
</head>
<body class="bg-secondary" style="--bs-bg-opacity:.15;">
<form method="POST" action="">
    <div class="container vh-100">
        <div class="row mb-3 ">

            <div class="head mb-5 mt-1 ">
                <h2 class="fw-bold">Pengelolaan Transaksi</h2>
            </div>
            <div class="col">
                <h6><b>Masukan No Handphone Pelanggan</b></h6>
            </div>
            <div class="d-flex justify-content-start gap-4 mb-3">
                <form method="POST" action="">
                <div class="col">
                    <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukan No Handphone Pelanggan">
                </div>
                <div class="col">
                    <button class="btn btn btn-outline-dark" type="submit"  id = "cari_pelanggan" name="cari_pelanggan"><i class="bi bi-search"></i> Cari</button>
                </div>
                </form> 
            </div>
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
            <div class="d-flex ">
                <table class="table mt-2 text-center" id="tabel_cucian">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Jenis Cucian</th>
                            <th scope="col">Jumlah Kilogram</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-light" >
                        
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col ">
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
                        <fieldset disabled>
                            <input type="text" id="total_harga" name="total_harga" class="form-control bg-light" value="Rp ">
                        </fieldset >    
                    </div>         
                </div>
            </div>

            <div class="row mb-5">
                <div class="col me-4">
                    <h6><b>Estimasi Selesai</b></h6>
                    <div class="col-md-7">
                        <input type="date" class="form-control" name="Estimasi_selesai">
                    </div>
                </div>
                <div class="col me-4">
                    <h6><b>Pembayaran<b></h6>
                    <div class="d-flex flex-row mb-3">
                        <div class="row me-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="qris" value="Qris">
                                <p>QRIS</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="tunai" value="Tunai">
                                <p>Tunai</p>
                            </div>
                        </div>
                    </div>         
                </div>
            </div>
        </div>

        <div class="d-flex flex-row ms-5 mb-3">
            <div class="row me-5">
                <button type="submit" class="btn btn-success p-2 px-4" name="simpan_data_nota">Simpan</button>
            </div>
            <div class="row ms-3">
                <button type="button" class="btn btn-danger p-2 px-4">Hapus</button>
            </div>
        </div>
    </div>
</form>
<!-- Add Modal -->
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
<!-- dialogue Modal -->
<!-- peringatan data tidak ada Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title d-flex align-items-center justify-content-center w-100" id="alertModalLabel"><i class="bi bi-question-circle me-2"></i> Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center w-100 text-center">
                <p id="confirmationMessage" class="fw-normal"></p>
            </div>
            <div class="modal-footer d-flex align-items-center justify-content-center w-100">
                <a class="text-white" href="index.php?page=pendaftaran" style="text-decoration:none"><button id="confirmYes" class="btn btn-success">Ya</button></a>
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
                <h5 class="modal-title d-flex align-items-center justify-content-center w-100" id="alertModalLabel"><i class="bi bi-exclamation-circle-fill me-2"></i> Peringatan</h5>
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

<!-- JavaScript to handle price calculation and table update -->
<script>
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

    function collectData() {
    const table = document.getElementById('tabel_cucian');
    const rows = table.getElementsByTagName('tr');
    let data = [];

    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip table headers
        const jenisCucian = rows[i].querySelector('[data-jenis="jenis_cucian"]').innerText;
        const harga = rows[i].querySelector('[data-harga="harga"]').innerText;

        data.push({ jenis_cucian: jenisCucian, harga: harga });
    }

    document.getElementById('tabelCucianData').value = JSON.stringify(data);
    }

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

    document.getElementById('addModal').querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form submission

    var jenisCucianSelect = document.getElementById('jenis_cucian');
    var jumlah = parseFloat(document.getElementById('jumlah').value);
    var selectedJenis = jenisCucianSelect.options[jenisCucianSelect.selectedIndex].text;
    var selectedJenisId = jenisCucianSelect.value;
    var price = jenisCucian[selectedJenisId] * jumlah;

    if (jumlah && selectedJenisId) {
        var table = document.getElementById('tabel_cucian').getElementsByTagName('tbody')[0];
        var existingRow = Array.from(table.rows).find(row => row.cells[1].innerText === selectedJenis);

        if (existingRow) {
            // Update existing row
            var existingJumlah = parseFloat(existingRow.cells[2].innerText);
            var newJumlah = existingJumlah + jumlah;
            existingRow.cells[2].innerText = newJumlah;
            existingRow.cells[3].innerText = 'Rp ' + (jenisCucian[selectedJenisId] * newJumlah).toLocaleString('id-ID');
        } else {
            // Insert new row
            var newRow = table.insertRow();
            newRow.innerHTML = `
                <td>${table.rows.length}</td>
                <td>${selectedJenis}</td>
                <td>${jumlah}</td>
                <td>Rp ${price.toLocaleString('id-ID')}</td>
                <td><button class="btn btn-danger btn-sm" onclick="removeRow(this)">Delete</button></td>
            `;
        }


        // Reset form fields
        document.getElementById('jenis_cucian').value = '';
        document.getElementById('jumlah').value = '';

        // Update the total price
        calculateTotal();
    } else {
        alert("Please fill out all fields.");
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
    var rows = document.querySelectorAll('#tabel_cucian tbody tr');
    rows.forEach(row => {
        var priceText = row.cells[3].innerText.replace('Rp ', '').replace(/,/g, ''); // Hapus simbol mata uang dan semua koma
        console.log('Original priceText:', row.cells[3].innerText); // Debugging: teks asli dari sel
        console.log('Cleaned priceText:', priceText); // Debugging: periksa priceText sebelum konversi
        var price = parseFloat(priceText);
        console.log('Parsed price:', price); // Debugging: periksa nilai price setelah konversi

        if (!isNaN(price)) {
            total += price;
        }
    });
    // Kalikan total dengan 1000 sebelum format dan menampilkannya
    let totalMultiplied = total * 1000;
    document.getElementById('total_harga').value = 'Rp ' + totalMultiplied.toLocaleString('id-ID');
    console.log('Total after formatting:', document.getElementById('total_harga').value); // Debugging: hasil akhir yang ditampilkan
}


document.addEventListener('DOMContentLoaded', calculateTotal);

// Jika ada perubahan dalam tabel, hitung ulang total
document.querySelector('tbody').addEventListener('DOMSubtreeModified', calculateTotal);

</script>

</body>
</html>