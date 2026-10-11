<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->query("SELECT p.*, u.full_name, u.email, c.category_name, DATE_FORMAT(p.created_at, '%d %b %Y, %h:%i %p') as formatted_date 
                    FROM projects p 
                    JOIN users u ON p.user_id = u.id 
                    JOIN categories c ON p.category_id = c.id 
                    ORDER BY p.id DESC");
$submissions = $stmt->fetchAll();
include 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold m-0"><i class="bi bi-list-task me-2"></i>All Student Submissions</h3>
    <span class="badge bg-primary fs-6">Total: <?= count($submissions) ?> Projects</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Student Name</th>
                    <th>Project Title</th>
                    <th>Category</th>
                    <th>Tech Stack</th>
                    <th>Submitted File</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $sub): ?>
                    <tr>
                        <td>
                            <strong class="d-block"><?= htmlspecialchars($sub['full_name']) ?></strong>
                            <small class="text-muted"><?= htmlspecialchars($sub['email']) ?></small>
                        </td>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($sub['title']) ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($sub['category_name']) ?></span></td>
                        <td><small><?= htmlspecialchars($sub['tech_stack']) ?></small></td>
                        <td><a href="<?= htmlspecialchars($sub['file_path']) ?>" class="btn btn-sm btn-outline-primary" download><i class="bi bi-file-earmark-arrow-down me-1"></i>File</a></td>
                        <td><small><?= $sub['formatted_date'] ?></small></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>