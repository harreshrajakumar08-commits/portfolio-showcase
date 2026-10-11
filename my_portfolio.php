<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, c.category_name, DATE_FORMAT(p.created_at, '%d %b %Y') as formatted_date 
                       FROM projects p 
                       JOIN categories c ON p.category_id = c.id 
                       WHERE p.user_id = ? 
                       ORDER BY p.id DESC");
$stmt->execute([$_SESSION['user_id']]);
$myProjects = $stmt->fetchAll();
include 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold m-0"><i class="bi bi-briefcase-fill me-2"></i>My Portfolio Submissions</h3>
    <a href="submit_project.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Submission</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Project Title</th>
                    <th>Category</th>
                    <th>Tech Stack</th>
                    <th>File</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($myProjects)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">No submissions found.</td></tr>
                <?php else: ?>
                    <?php foreach ($myProjects as $p): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?= htmlspecialchars($p['title']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($p['category_name']) ?></span></td>
                            <td><small><?= htmlspecialchars($p['tech_stack']) ?></small></td>
                            <td><a href="<?= htmlspecialchars($p['file_path']) ?>" class="btn btn-sm btn-outline-secondary" download><i class="bi bi-download me-1"></i>Download</a></td>
                            <td><small><?= $p['formatted_date'] ?></small></td>
                            <td>
                                <a href="edit_project.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                <form action="delete_project.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>