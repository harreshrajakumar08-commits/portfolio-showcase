<?php
require_once 'db.php';
header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');

$sql = "SELECT p.*, c.category_name, u.full_name, DATE_FORMAT(p.created_at, '%d/%m/%Y') as formatted_date 
        FROM projects p
        JOIN categories c ON p.category_id = c.id
        JOIN users u ON p.user_id = u.id";

if (!empty($q)) {
    $sql .= " WHERE p.title LIKE :q1 OR p.tech_stack LIKE :q2 OR u.full_name LIKE :q3";
}

$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);

if (!empty($q)) {
    $like = "%$q%";
    $stmt->execute(['q1' => $like, 'q2' => $like, 'q3' => $like]);
} else {
    $stmt->execute();
}

echo json_encode($stmt->fetchAll());
?>