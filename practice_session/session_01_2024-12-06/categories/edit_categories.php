<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $categories_id = $_GET['id'];

    // Ambil data categories yang ingin diedit
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$categories_id]);
    $categories = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    // Update categories di database
    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->execute([$name, $categories_id]);

    echo "categories berhasil diubah!";
    header("Location: list_categories.php");  // Redirect ke daftar categories setelah berhasil
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit categories</title>
</head>
<body>
    <h1>Edit categories</h1>

    <form action="edit_categories.php?id=<?php echo $categories['id']; ?>" method="post">
        <label for="name">Nama categories:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($categories['name']); ?>" required><br><br>

        <button type="submit">Update categories</button>
    </form>
</body>
</html>
