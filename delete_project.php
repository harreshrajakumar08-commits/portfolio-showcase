<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: my_portfolio.php");
    exit;
}

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT file_path FROM projects WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $project = $stmt->fetch();

    if ($project) {
        if (file_exists($project['file_path'])) {
            unlink($project['file_path']);
        }

        $deleteStmt = $pdo->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
        $deleteStmt->execute([$id, $_SESSION['user_id']]);
    }
}

header("Location: my_portfolio.php");
exit;
?>