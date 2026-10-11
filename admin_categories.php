<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($category_name) || empty($description)) {
        $error = "Category name and description are required.";
    } elseif (mb_strlen($category_name) > 100) {
        $error = "Category name must be 100 characters or less.";
    } else {
        $dup = $pdo->prepare("SELECT id FROM categories WHERE category_name = ?");
        $dup->execute([$category_name]);
        if ($dup->fetch()) {
            $error = "That category already exists.";
        } else {
        $stmt = $pdo->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
        if ($stmt->execute([$category_name, $description])) {
            $success = "Category added successfully!";
        } else {
            $error = "Failed to add category.";
        }
        }
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
include 'header.php';
?>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-folder-plus me-1"></i>Create Category</h5>
                
                <?php if ($error): ?><div class="alert alert-danger p-2 small"><?= $error ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert alert-success p-2 small"><?= $success ?></div><?php endif; ?>

                <form action="admin_categories.php" method="POST" onsubmit="return validateCategory()">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Category Name</label>
                        <input type="text" name="category_name" id="category_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>Add Category</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Existing Categories</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Category Name</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($cat['category_name']) ?></td>
                                    <td><small class="text-muted"><?= htmlspecialchars($cat['description']) ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>