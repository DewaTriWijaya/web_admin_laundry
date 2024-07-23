<?php
// include "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pelanggan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #f2f2f2;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar h2 {
            margin-top: 0;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
        }
        .sidebar ul li ul {
            margin-top: 5px;
            padding-left: 20px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        h1 {
            font-size: 24px;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 12px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="tel"], textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 10px;
        }
        button#save {
            background-color: #4CAF50;
            color: white;
        }
        button#delete {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Pendaftaran Pelanggan</h1>
        <div class="form-container">
            <label for="name">Nama</label>
            <input type="text" id="name" placeholder="Masukan Nama" required>
            <label for="phone">Nomor Handphone</label>
            <input type="tel" id="phone" placeholder="Masukan Nomor Handphone" required>
            <label for="address">Alamat</label>
            <textarea id="address" placeholder="Masukan Alamat" required></textarea>
            <button id="save">SIMPAN</button>
            <button id="delete">HAPUS</button>
        </div>
    </div>

    <script>
        document.getElementById('save').addEventListener('click', function() {
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('address').value;
            console.log(`Saved: Name - ${name}, Phone - ${phone}, Address - ${address}`);
            document.getElementById('name').value = '';
            document.getElementById('phone').value = '';
            document.getElementById('address').value = '';
        });

        document.getElementById('delete').addEventListener('click', function() {
            document.getElementById('name').value = '';
            document.getElementById('phone').value = '';
            document.getElementById('address').value = '';
        });
    </script>
</body>
</html>



