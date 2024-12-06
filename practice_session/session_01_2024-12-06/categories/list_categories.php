<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ambil semua categories
$stmt = $conn->prepare("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen categories</title>
</head>
<body>
    <h1>Manajemen categories</h1>

    <a href="create_categories.php">Tambah categories Baru</a><br><br>

    <h2>Daftar categories</h2>
    <ul>
        <?php foreach ($categories as $categories): ?>
            <li>
                <?php echo htmlspecialchars($categories['name']); ?>
                <a href="edit_categories.php?id=<?php echo $categories['id']; ?>">Edit</a> | 
                <a href="delete_categories.php?id=<?php echo $categories['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus categories ini?')">Hapus</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
