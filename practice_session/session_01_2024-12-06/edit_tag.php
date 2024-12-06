<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $tag_id = $_GET['id'];

    // Ambil data tag yang ingin diedit
    $stmt = $conn->prepare("SELECT * FROM tags WHERE id = ?");
    $stmt->execute([$tag_id]);
    $tag = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    // Update tag di database
    $stmt = $conn->prepare("UPDATE tags SET name = ? WHERE id = ?");
    $stmt->execute([$name, $tag_id]);

    echo "Tag berhasil diubah!";
    header("Location: list_tags.php");  // Redirect ke daftar tags setelah berhasil
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tag</title>
</head>
<body>
    <h1>Edit Tag</h1>

    <form action="edit_tag.php?id=<?php echo $tag['id']; ?>" method="post">
        <label for="name">Nama Tag:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($tag['name']); ?>" required><br><br>

        <button type="submit">Update Tag</button>
    </form>
</body>
</html>
