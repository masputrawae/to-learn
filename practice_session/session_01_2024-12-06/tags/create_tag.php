<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    // Insert tag baru ke database
    $stmt = $conn->prepare("INSERT INTO tags (name) VALUES (?)");
    $stmt->execute([$name]);

    echo "Tag berhasil ditambahkan!";
    header("Location: list_tags.php");  // Redirect ke daftar tags setelah berhasil
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tag Baru</title>
</head>
<body>
    <h1>Tambah Tag Baru</h1>

    <form action="create_tag.php" method="post">
        <label for="name">Nama Tag:</label>
        <input type="text" name="name" id="name" required><br><br>

        <button type="submit">Tambah Tag</button>
    </form>
</body>
</html>
