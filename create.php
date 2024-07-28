<?php
// $servername = "127.0.0.1:3307";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_laundry";
// $socket = "/opt/lampp/var/mysql/mysql.sock"; // Update with the correct socket path

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname, null, $socket);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Membuat tabel DataJenisCucian
$createDataJenisCucian = "CREATE TABLE IF NOT EXISTS Jenis_Cucian (
    id_jenis_cucian VARCHAR(4) PRIMARY KEY,
    jenis_cucian VARCHAR(10) NOT NULL,
    harga INT(6) NOT NULL
);";

// Membuat tabel DataPelanggan
$createDataPelanggan = "CREATE TABLE IF NOT EXISTS Pelanggan (
    No_HP VARCHAR(15) PRIMARY KEY NOT NULL,
    nama VARCHAR(30),
    alamat VARCHAR(50)
);";

// Membuat tabel DataNota
$createDataNota = "CREATE TABLE IF NOT EXISTS Nota (
    No_Nota VARCHAR(10) PRIMARY KEY,
    No_HP VARCHAR(15) NOT NULL,
    Berat_cucian INT(3) NOT NULL,
    Total_Harga INT(7),
    Tgl_masuk DATE,
    Estimasi_selesai DATE,
    Jenis_cucian VARCHAR(200) NOT NULL,
    Jenis_pembayaran ENUM('Qris', 'Tunai') NOT NULL,
    FOREIGN KEY (No_HP) REFERENCES Pelanggan(No_HP)
);";

// Membuat tabel DataDetailLaundry
$createDataDetailLaundry = "CREATE TABLE IF NOT EXISTS Detail_Laundry (
    id_jenis_cucian VARCHAR(4) NOT NULL,
    No_Nota VARCHAR(10) NOT NULL,
    Harga INT(6),
    PRIMARY KEY (id_jenis_cucian, No_Nota),
    FOREIGN KEY (id_jenis_cucian) REFERENCES Jenis_Cucian(id_jenis_cucian),
    FOREIGN KEY (No_Nota) REFERENCES Nota(No_Nota)
);";

// Membuat tabel DataStatusLaundry
$createDataStatusLaundry = "CREATE TABLE IF NOT EXISTS Status_Laundry (
    id_status_laundry INT(4) AUTO_INCREMENT PRIMARY KEY,
    No_Nota VARCHAR(10) NOT NULL,
    status_laundry ENUM('Selesai', 'Belum') NOT NULL,
    tanggal_selesai DATE,
    FOREIGN KEY (No_Nota) REFERENCES Nota(No_Nota)
);";

// Eksekusi query pembuatan tabel
if ($conn->query($createDataJenisCucian) === TRUE) {
    echo "Table DataJenisCucian created successfully or already exists.\n";
} else {
    echo "Error creating table DataJenisCucian: " . $conn->error . "\n";
}

if ($conn->query($createDataPelanggan) === TRUE) {
    echo "Table DataPelanggan created successfully or already exists.\n";
} else {
    echo "Error creating table DataPelanggan: " . $conn->error . "\n";
}

if ($conn->query($createDataNota) === TRUE) {
    echo "Table Nota created successfully or already exists.\n";
} else {
    echo "Error creating table Nota: " . $conn->error . "\n";
}

if ($conn->query($createDataDetailLaundry) === TRUE) {
    echo "Table Detail_Laundry created successfully or already exists.\n";
} else {
    echo "Error creating table Detail_Laundry: " . $conn->error . "\n";
}

if ($conn->query($createDataStatusLaundry) === TRUE) {
    echo "Table DataStatusLaundry created successfully or already exists.\n";
} else {
    echo "Error creating table DataStatusLaundry: " . $conn->error . "\n";
}

// Menutup koneksi
$conn->close();
?>
