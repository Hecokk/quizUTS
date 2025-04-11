<?php
require_once 'includes/koneksi.php';

$status = 'success';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $film_id = $_GET['id'];
    $sql = "DELETE FROM film WHERE film_id = ?";

    try {
        $stmt = $conn->prepare($sql);
        if (!$stmt->execute([$film_id])) {
            $status = 'delete_error';
        }
    } catch (PDOException $e) {
        $status = 'delete_error';
    }
} else {
    $status = 'invalid_id';
}

if ($status === 'delete_error') {
    header("Location: index.php?status=delete_error");
} elseif ($status === 'invalid_id') {
    header("Location: index.php?status=invalid_id");
} else {
    header("Location: index.php?status=deleted");
}
exit;
