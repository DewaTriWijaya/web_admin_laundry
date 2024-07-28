<?php
// $servername = "127.0.0.1:3306";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_laundry";



// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
