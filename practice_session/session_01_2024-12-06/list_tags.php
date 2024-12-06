<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ambil semua tag
$stmt = $conn->prepare("SELECT * FROM tags");
$stmt->execute();
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tags</title>
</head>
<body>
    <h1>Manajemen Tags</h1>

    <a href="create_tag.php">Tambah Tag Baru</a><br><br>

    <h2>Daftar Tags</h2>
    <ul>
        <?php foreach ($tags as $tag): ?>
            <li>
                <?php echo htmlspecialchars($tag['name']); ?>
                <a href="edit_tag.php?id=<?php echo $tag['id']; ?>">Edit</a> | 
                <a href="delete_tag.php?id=<?php echo $tag['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus tag ini?')">Hapus</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
