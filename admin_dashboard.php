<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Counts
$studentCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
$categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$submissionCount = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();

// Recent Submissions
$recent = $pdo->query("SELECT p.*, u.full_name, c.category_name, DATE_FORMAT(p.created_at, '%d %b %Y') as formatted_date 
                       FROM projects p 
                       JOIN users u ON p.user_id = u.id 
                       JOIN categories c ON p.category_id = c.id 
                       ORDER BY p.id DESC LIMIT 5")->fetchAll();
include 'header.php';
?>

<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>Welcome back, Admin!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<h3 class="fw-bold mb-1"><i class="bi bi-speedometer2 me-2"></i>Admin Overview</h3>
<p class="text-muted small mb-4">Lecturer & Coordinator Evaluation Portal</p>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase fw-bold mb-1">Students</h6>
                    <h2 class="display-6 fw-bold m-0"><?= $studentCount ?></h2>
                </div>
                <i class="bi bi-people-fill fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase fw-bold mb-1">Categories</h6>
                    <h2 class="display-6 fw-bold m-0"><?= $categoryCount ?></h2>
                </div>
                <i class="bi bi-layers-fill fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase fw-bold mb-1">Submissions</h6>
                    <h2 class="display-6 fw-bold m-0"><?= $submissionCount ?></h2>
                </div>
                <i class="bi bi-folder-fill fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<h5 class="fw-bold mb-3">Recent Submissions</h5>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Student Name</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $r): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($r['full_name']) ?></td>
                        <td><?= htmlspecialchars($r['title']) ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($r['category_name']) ?></span></td>
                        <td><small><?= $r['formatted_date'] ?></small></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>