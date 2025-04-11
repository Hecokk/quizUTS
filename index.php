<?php
require_once 'includes/koneksi.php';

$search_query = '';
$sql_where = '';
$params = [];

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = trim($_GET['search']);
    $search_term = "%" . $search_query . "%";
    $sql_where = " WHERE judul LIKE ? OR genre LIKE ? OR tahun_rilis LIKE ? ";
    $params = [$search_term, $search_term, $search_term];
}

$sql = "SELECT film_id, judul, genre, rating, tahun_rilis, status_nonton FROM film";
$sql .= $sql_where;
$sql .= " ORDER BY judul ASC";

$stmt = null;
$error_message = null;
$films = [];

try {
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->execute($params);
        $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $error_message = "Gagal mempersiapkan statement SELECT.";
    }
} catch (PDOException $e) {
    $error_message = "Error query database: Gagal mengambil data.";
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist Nonton Film</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Daftar Film dalam Playlist</h1>

    <a href="create.php">Tambah Film Baru</a>
    <br><br>

    <form action="index.php" method="get" class="search-form">
        <input type="text" name="search" placeholder="Cari Judul, Genre, Tahun..."
            value="<?php echo htmlspecialchars($search_query); ?>">
        <input type="submit" value="Cari">
        <?php if (!empty($search_query)): ?>
            <a href="index.php">Reset</a>
        <?php endif; ?>
    </form>
    <br>

    <?php
    if ($error_message) {
        echo "<div class='error-list'>" . htmlspecialchars($error_message) . "</div>";
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>ID Film</th>
                <th>Judul</th>
                <th>Genre</th>
                <th>Rating</th>
                <th>Tahun Rilis</th>
                <th>Status Nonton</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($films) > 0) {
                foreach ($films as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["film_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["judul"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["genre"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["rating"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["tahun_rilis"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["status_nonton"]) . "</td>";
                    echo "<td>";
                    echo "<a href='update.php?id=" . urlencode($row["film_id"]) . "'>Edit</a> | ";
                    echo "<a href='delete.php?id=" . urlencode($row["film_id"]) . "' onclick='return confirm(\"Yakin ingin menghapus film ini?\")'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                if ($error_message) {
                    echo "<tr><td colspan='7'>Tidak bisa menampilkan data.</td></tr>";
                } elseif (!empty($search_query)) {
                    echo "<tr><td colspan='7'>Tidak ada film yang cocok dengan pencarian '" . htmlspecialchars($search_query) . "'.</td></tr>";
                } else {
                    echo "<tr><td colspan='7'>Tidak ada film dalam playlist.</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'deleted'): ?>
            <div class="success-msg">Film berhasil dihapus!</div>
        <?php elseif ($_GET['status'] === 'delete_error'): ?>
            <div class="error-list">Terjadi kesalahan saat menghapus film.</div>
        <?php elseif ($_GET['status'] === 'invalid_id'): ?>
            <div class="error-list">ID Film tidak valid.</div>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>