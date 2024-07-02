<?php
session_start();
if (!isset($_SESSION['nim'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';

// Fetch data groups
$sqlGroups = "SELECT * FROM groups";
$resultGroups = $conn->query($sqlGroups);

// Fetch data countries
$sqlCountries = "SELECT * FROM countries";
$resultCountries = $conn->query($sqlCountries);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    if ($action == 'add') {
        $group_id = $_POST['group'];
        $name = $_POST['name'];
        $wins = $_POST['wins'];
        $draws = $_POST['draws'];
        $losses = $_POST['losses'];
        $points = $_POST['points'];

        $sqlInsert = "INSERT INTO countries (group_id, name, wins, draws, losses, points) VALUES ('$group_id', '$name', '$wins', '$draws', '$losses', '$points')";
        if ($conn->query($sqlInsert) === TRUE) {
            echo "Data negara berhasil ditambahkan!";
        } else {
            echo "Error: " . $sqlInsert . "<br>" . $conn->error;
        }
    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $wins = $_POST['wins'];
        $draws = $_POST['draws'];
        $losses = $_POST['losses'];
        $points = $_POST['points'];

        $sqlUpdate = "UPDATE countries SET wins='$wins', draws='$draws', losses='$losses', points='$points' WHERE id='$id'";
        if ($conn->query($sqlUpdate) === TRUE) {
            echo "Data negara berhasil diupdate!";
        } else {
            echo "Error: " . $sqlUpdate . "<br>" . $conn->error;
        }
    } elseif ($action == 'delete') {
        $id = $_POST['id'];

        $sqlDelete = "DELETE FROM countries WHERE id='$id'";
        if ($conn->query($sqlDelete) === TRUE) {
            echo "Data negara berhasil dihapus!";
        } else {
            echo "Error: " . $sqlDelete . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Klasemen UEFA 2024</title>
</head>
<body>
    <h1>Selamat Datang, <?php echo $_SESSION['nim']; ?></h1>
    <a href="logout.php">Logout</a>
    <form method="post" action="">
        <h2>Masukkan Data Negara</h2>
        <input type="hidden" name="action" value="add">
        Group:
        <select name="group">
            <?php while($rowGroup = $resultGroups->fetch_assoc()) { ?>
                <option value="<?php echo $rowGroup['id']; ?>"><?php echo $rowGroup['name']; ?></option>
            <?php } ?>
        </select><br>
        Nama Negara: 
        <select name="name">
            <?php
            $countries = ['France', 'Germany', 'Italy', 'Spain', 'England', 'Portugal', 'Netherlands', 'Belgium', 'Switzerland', 'Denmark', 'Sweden', 'Norway', 'Poland', 'Austria', 'Czech Republic', 'Turkey'];
            foreach ($countries as $country) {
                echo "<option value='$country'>$country</option>";
            }
            ?>
        </select><br>
        Jumlah Menang: <input type="number" name="wins" required><br>
        Jumlah Seri: <input type="number" name="draws" required><br>
        Jumlah Kalah: <input type="number" name="losses" required><br>
        Jumlah Poin: <input type="number" name="points" required><br>
        <button type="submit">Tambah Negara</button>
    </form>

    <h2>Data Negara</h2>
    <table border="1">
        <tr>
            <th>Group</th>
            <th>Nama Negara</th>
            <th>Menang</th>
            <th>Seri</th>
            <th>Kalah</th>
            <th>Poin</th>
            <th>Aksi</th>
        </tr>
        <?php while($rowCountry = $resultCountries->fetch_assoc()) { ?>
        <tr>
            <form method="post" action="">
                <td><?php echo $rowCountry['group_id']; ?></td>
                <td><?php echo $rowCountry['name']; ?></td>
                <td><input type="number" name="wins" value="<?php echo $rowCountry['wins']; ?>"></td>
                <td><input type="number" name="draws" value="<?php echo $rowCountry['draws']; ?>"></td>
                <td><input type="number" name="losses" value="<?php echo $rowCountry['losses']; ?>"></td>
                <td><input type="number" name="points" value="<?php echo $rowCountry['points']; ?>"></td>
                <td>
                    <input type="hidden" name="id" value="<?php echo $rowCountry['id']; ?>">
                    <input type="hidden" name="action" value="update">
                    <button type="submit">Update</button>
                </form>
                <form method="post" action="" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $rowCountry['id']; ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit">Delete</button>
                </form>
                </td>
        </tr>
        <?php } ?>
    </table>
    <form method="post" action="export_pdf.php">
        <button type="submit">Cetak PDF</button>
    </form>
</body>
</html>
