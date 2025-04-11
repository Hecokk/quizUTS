<?php
require_once 'includes/koneksi.php';

$film_id = null;
$film_data = null;
$pesan = '';
$pesan_sukses = false;
$pesan_gagal = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $film_id_update = $_POST['film_id'];
    $judul = $_POST['judul'];
    $genre = $_POST['genre'];
    $rating = $_POST['rating'];
    $tahun_rilis = $_POST['tahun_rilis'];
    $status_nonton = $_POST['status_nonton'];

    if (!empty($film_id_update) && !empty($judul) && !empty($genre) && !empty($rating) && !empty($tahun_rilis) && !empty($status_nonton)) {

        $sql_update = "UPDATE film SET judul = ?, genre = ?, rating = ?, tahun_rilis = ?, status_nonton = ? WHERE film_id = ?";

        try {
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->execute([$judul, $genre, $rating, $tahun_rilis, $status_nonton, $film_id_update]);

            $pesan = "Data film berhasil diperbarui!";
            $pesan_sukses = true;

            $film_data = [
                'judul' => $judul,
                'genre' => $genre,
                'rating' => $rating,
                'tahun_rilis' => $tahun_rilis,
                'status_nonton' => $status_nonton
            ];
        } catch (PDOException $e) {
            $pesan = "Error: Gagal memperbarui data film.";
            $pesan_gagal = true;
        }
    } else {
        $pesan = "Semua field wajib diisi!";
        $pesan_gagal = true;
    }
    $film_id = $film_id_update;
} elseif (isset($_GET['id'])) {
    $film_id = $_GET['id'];
    $sql_select = "SELECT judul, genre, rating, tahun_rilis, status_nonton FROM film WHERE film_id = ?";

    try {
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->execute([$film_id]);
        $film_data = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if (!$film_data) {
            $pesan = "Film tidak ditemukan.";
            $pesan_gagal = true;
            $film_id = null;
        }
    } catch (PDOException $e) {
        $pesan = "Error: Gagal mengambil data film.";
        $pesan_gagal = true;
        $film_id = null;
    }
} else {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $pesan = "ID Film tidak valid.";
        $pesan_gagal = true;
    }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Film</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Edit Data Film</h1>

    <a href="index.php">Kembali ke Daftar Film</a>
    <br><br>

    <?php
    if (!empty($pesan)) {
        if ($pesan_gagal) {
            echo "<div class='error-list'>" . htmlspecialchars($pesan) . "</div>";
        } else {
            echo "<div class='success-msg'>" . htmlspecialchars($pesan) . "</div>";
        }
    }
    ?>

    <?php if ($film_data && $film_id) : ?>
        <form action="update.php" method="post">
            <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($film_id); ?>">

            <label for="judul">Judul Film:</label><br>
            <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($film_data['judul']); ?>" required><br><br>

            <label for="genre">Genre:</label><br>
            <select id="genre" name="genre" required>
                <option value="" disabled>-- Pilih Genre --</option>
                <?php
                $genres = ["Aksi", "Drama", "Komedi", "Romansa", "Fiksi Ilmiah", "Fantasi", "Horor", "Lainnya"];
                foreach ($genres as $g) {
                    $selected = ($film_data['genre'] == $g) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($g) . "\" $selected>" . htmlspecialchars($g) . "</option>";
                }
                ?>
            </select><br><br>

            <label>Rating:</label><br>
            <?php
            $ratings = ["SU" => "SU (Semua Umur)", "BO" => "BO (Bimbingan Orangtua)", "R" => "R (Remaja)", "D" => "D (Dewasa)"];
            foreach ($ratings as $value => $label) {
                $checked = ($film_data['rating'] == $value) ? 'checked' : '';
                echo "<input type=\"radio\" id=\"rating_" . strtolower($value) . "\" name=\"rating\" value=\"" . htmlspecialchars($value) . "\" $checked required>";
                echo "<label for=\"rating_" . strtolower($value) . "\">" . htmlspecialchars($label) . "</label><br>";
            }
            ?><br>

            <label for="tahun_rilis">Tahun Rilis:</label><br>
            <input type="number" id="tahun_rilis" name="tahun_rilis" min="1888" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($film_data['tahun_rilis']); ?>" required><br><br>

            <label>Status Nonton:</label><br>
            <?php
            $statuses = ["Belum Ditonton", "Sedang Ditonton", "Sudah Ditonton"];
            foreach ($statuses as $status) {
                $checked = ($film_data['status_nonton'] == $status) ? 'checked' : '';
                $id_status = strtolower(str_replace(' ', '_', $status));
                echo "<input type=\"radio\" id=\"status_$id_status\" name=\"status_nonton\" value=\"" . htmlspecialchars($status) . "\" $checked required>";
                echo "<label for=\"status_$id_status\">" . htmlspecialchars($status) . "</label><br>";
            }
            ?><br>

            <input type="submit" value="Update Film">
        </form>
    <?php elseif (!$pesan_gagal) : ?>
        <a href="index.php">Kembali ke Daftar</a>
    <?php endif; ?>

</body>

</html>