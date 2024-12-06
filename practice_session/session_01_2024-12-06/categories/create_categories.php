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

    // Insert Categories baru ke database
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$name]);

    echo "Categories berhasil ditambahkan!";
    header("Location: list_categories.php");  // Redirect ke daftar categories setelah berhasil
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Categories Baru</title>
</head>
<body>
    <h1>Tambah Categories Baru</h1>

    <form action="create_categories.php" method="post">
        <label for="name">Nama Categories:</label>
        <input type="text" name="name" id="name" required><br><br>

        <button type="submit">Tambah Categories</button>
    </form>
</body>
</html>
