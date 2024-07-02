<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (nim, password) VALUES ('$nim', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        echo "User berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
</head>
<body>
    <form method="post" action="">
        NIM: <input type="text" name="nim" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Tambah User</button>
    </form>
</body>
</html>
