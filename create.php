<?php
require_once 'includes/koneksi.php';

$pesan = '';
$pesan_gagal = false;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $film_id = trim($_POST['film_id']);
    $judul = trim($_POST['judul']);
    $genre = isset($_POST['genre']) ? trim($_POST['genre']) : '';
    $rating = isset($_POST['rating']) ? trim($_POST['rating']) : '';
    $tahun_rilis = isset($_POST['tahun_rilis']) ? trim($_POST['tahun_rilis']) : '';
    $status_nonton = isset($_POST['status_nonton']) ? trim($_POST['status_nonton']) : '';

    if (empty($film_id)) {
        $errors[] = "Film ID wajib diisi!";
    } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $film_id)) {
        $errors[] = "Film ID hanya boleh berisi huruf dan angka!";
    }

    if (empty($judul)) {
        $errors[] = "Judul film wajib diisi!";
    }

    if (empty($genre)) {
        $errors[] = "Genre film wajib dipilih!";
    }

    if (empty($rating)) {
        $errors[] = "Rating film wajib dipilih!";
    }

    if (empty($tahun_rilis)) {
        $errors[] = "Tahun rilis wajib diisi!";
    } elseif (!is_numeric($tahun_rilis)) {
        $errors[] = "Tahun rilis harus berupa angka!";
    } elseif ($tahun_rilis < 1888 || $tahun_rilis > date('Y')) {
        $errors[] = "Tahun rilis tidak valid (harus antara 1888 dan " . date('Y') . ")!";
    }

    if (empty($status_nonton)) {
        $errors[] = "Status nonton wajib dipilih!";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO film (film_id, judul, genre, rating, tahun_rilis, status_nonton) VALUES (?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$film_id, $judul, $genre, $rating, $tahun_rilis, $status_nonton]);
            $pesan = "Film baru berhasil ditambahkan!";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errors[] = "Film ID '$film_id' sudah digunakan. Silakan gunakan ID lain.";
            } else {
                $errors[] = "Error: Gagal menambahkan film. " . $e->getMessage();
            }
            $pesan_gagal = true;
        }
    } else {
        $pesan_gagal = true;
    }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Film Baru</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Tambah Film ke Playlist</h1>

    <a href="index.php">Kembali ke Daftar Film</a>
    <br><br>

    <?php
    if (!empty($errors)) {
        echo "<div class='error-list'>";
        echo "<strong>Ada kesalahan pada form:</strong>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo "</div>";
    }

    if (!empty($pesan) && !$pesan_gagal) {
        echo "<div class='success-msg'>" . htmlspecialchars($pesan) . "</div>";
    }
    ?>

    <form action="create.php" method="post">
        <label for="film_id">Film ID:</label><br>
        <input type="text" id="film_id" name="film_id" value="<?php echo isset($_POST['film_id']) ? htmlspecialchars($_POST['film_id']) : ''; ?>" required><br>
        <small>Hanya boleh huruf dan angka, tanpa spasi.</small><br><br>

        <label for="judul">Judul Film:</label><br>
        <input type="text" id="judul" name="judul" value="<?php echo isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : ''; ?>" required><br><br>

        <label for="genre">Genre:</label><br>
        <select id="genre" name="genre" required>
            <option value="" disabled <?php echo !isset($_POST['genre']) ? 'selected' : ''; ?>>-- Pilih Genre --</option>
            <?php
            $genres = ["Aksi", "Drama", "Komedi", "Romansa", "Fiksi Ilmiah", "Fantasi", "Horor", "Lainnya"];
            foreach ($genres as $g) {
                $selected = (isset($_POST['genre']) && $_POST['genre'] == $g) ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($g) . "\" $selected>" . htmlspecialchars($g) . "</option>";
            }
            ?>
        </select><br><br>

        <label>Rating:</label><br>
        <?php
        $ratings = ["SU" => "SU (Semua Umur)", "BO" => "BO (Bimbingan Orangtua)", "R" => "R (Remaja)", "D" => "D (Dewasa)"];
        foreach ($ratings as $value => $label) {
            $checked = (isset($_POST['rating']) && $_POST['rating'] == $value) ? 'checked' : '';
            echo "<input type=\"radio\" id=\"rating_" . strtolower($value) . "\" name=\"rating\" value=\"" . htmlspecialchars($value) . "\" $checked required>";
            echo "<label for=\"rating_" . strtolower($value) . "\">" . htmlspecialchars($label) . "</label><br>";
        }
        ?><br>

        <label for="tahun_rilis">Tahun Rilis:</label><br>
        <input type="number" id="tahun_rilis" name="tahun_rilis" min="1888" max="<?php echo date('Y'); ?>"
            value="<?php echo isset($_POST['tahun_rilis']) ? htmlspecialchars($_POST['tahun_rilis']) : ''; ?>" required><br><br>

        <label>Status Nonton:</label><br>
        <?php
        $statuses = ["Belum Ditonton", "Sedang Ditonton", "Sudah Ditonton"];
        foreach ($statuses as $status) {
            $checked = (isset($_POST['status_nonton']) && $_POST['status_nonton'] == $status) ? 'checked' : '';
            $id = strtolower(str_replace(' ', '_', $status));
            echo "<input type=\"radio\" id=\"status_$id\" name=\"status_nonton\" value=\"" . htmlspecialchars($status) . "\" $checked required>";
            echo "<label for=\"status_$id\">" . htmlspecialchars($status) . "</label><br>";
        }
        ?><br>

        <input type="submit" value="Tambah Film">
    </form>

</body>

</html>