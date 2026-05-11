<?php
include 'database.php';

$id = intval($_GET['id']);

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM supplies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php?msg=deleted");
exit();
?>
