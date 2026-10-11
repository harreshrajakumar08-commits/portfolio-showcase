<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: my_portfolio.php");
    exit;
}

// Fetch project and verify ownership
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$project = $stmt->fetch();

if (!$project) {
    header("Location: my_portfolio.php");
    exit;
}

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $tech_stack = trim($_POST['tech_stack'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $filePath = $project['file_path'];

    // Server-side validation
    if (empty($title)) $errors[] = "Project title is required.";
    if (empty($category_id)) $errors[] = "Category selection is required.";
    if (empty($tech_stack)) $errors[] = "Tech stack is required.";
    if (empty($description)) $errors[] = "Description is required.";

    // File update check (optional replacement file)
    if (isset($_FILES['doc_file']) && $_FILES['doc_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['doc_file']['tmp_name'];
        $fileName = $_FILES['doc_file']['name'];
        $fileSize = $_FILES['doc_file']['size'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExts = ['pdf', 'docx', 'zip', 'txt'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($ext, $allowedExts)) {
            $errors[] = "Invalid file type. Only PDF, DOCX, ZIP, and TXT are allowed.";
        }
        if ($fileSize > $maxSize) {
            $errors[] = "File size exceeds 5MB limit.";
        }

        if (empty($errors)) {
            $newName = time() . '_' . uniqid() . '.' . $ext;
            $destPath = 'uploads/' . $newName;

            if (move_uploaded_file($fileTmp, $destPath)) {
                // Delete old file
                if (file_exists($project['file_path'])) {
                    unlink($project['file_path']);
                }
                $filePath = $destPath;
            } else {
                $errors[] = "Failed to upload new file.";
            }
        }
    }

    if (empty($errors)) {
        $updateStmt = $pdo->prepare("UPDATE projects SET category_id = ?, title = ?, description = ?, tech_stack = ?, file_path = ? WHERE id = ? AND user_id = ?");
        if ($updateStmt->execute([$category_id, $title, $description, $tech_stack, $filePath, $id, $_SESSION['user_id']])) {
            $success = "Project updated successfully! <a href='my_portfolio.php'>Return to My Portfolio</a>";
            // Refresh local project data
            $stmt->execute([$id, $_SESSION['user_id']]);
            $project = $stmt->fetch();
        } else {
            $errors[] = "Failed to update project.";
        }
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();
include 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="card-title text-primary mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Portfolio Project</h4>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger p-2 small">
                        <?php foreach ($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>"; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success p-2 small"><?= $success ?></div>
                <?php endif; ?>

                <form action="edit_project.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateSubmitForm()">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Project Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($project['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Category</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $project['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Tech Stack (Comma Separated)</label>
                        <input type="text" name="tech_stack" id="tech_stack" class="form-control" value="<?= htmlspecialchars($project['tech_stack']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Project Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required><?= htmlspecialchars($project['description']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Upload New Documentation (Optional - Max 5MB)</label>
                        <input type="file" name="doc_file" id="doc_file" class="form-control">
                        <small class="text-muted">Current file: <a href="<?= htmlspecialchars($project['file_path']) ?>" download>View Current Document</a></small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Changes</button>
                    <a href="my_portfolio.php" class="btn btn-secondary me-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>