<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit;
}

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $tech_stack = trim($_POST['tech_stack'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Server-side validation
    if (empty($title)) $errors[] = "Project title is required.";
    if (empty($category_id)) {
        $errors[] = "Category selection is required.";
    } else {
        $chk = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
        $chk->execute([$category_id]);
        if (!$chk->fetch()) $errors[] = "Selected category does not exist.";
    }
    if (empty($tech_stack)) $errors[] = "Tech stack is required.";
    if (empty($description)) $errors[] = "Description is required.";

    // File handling
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
            $errors[] = "File size exceeds limit of 5MB.";
        }

        if (empty($errors)) {
            $newName = time() . '_' . uniqid() . '.' . $ext;
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $destPath = $uploadDir . $newName;

            if (move_uploaded_file($fileTmp, $destPath)) {
                $stmt = $pdo->prepare("INSERT INTO projects (user_id, category_id, title, description, tech_stack, file_path) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $category_id, $title, $description, $tech_stack, $destPath]);
                $success = "Project entry submitted successfully!";
            } else {
                $errors[] = "Failed to upload file.";
            }
        }
    } else {
        $code = $_FILES['doc_file']['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($code === UPLOAD_ERR_INI_SIZE || $code === UPLOAD_ERR_FORM_SIZE) {
            $errors[] = "File size exceeds limit of 5MB.";
        } elseif ($code === UPLOAD_ERR_NO_FILE) {
            $errors[] = "Project documentation file is required.";
        } else {
            $errors[] = "File upload failed (error code $code).";
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
                <h4 class="card-title text-primary mb-4"><i class="bi bi-cloud-upload-fill me-2"></i>Submit Portfolio Project</h4>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger p-2 small">
                        <?php foreach ($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>"; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success p-2 small"><?= $success ?></div>
                <?php endif; ?>

                <form action="submit_project.php" method="POST" enctype="multipart/form-data" onsubmit="return validateSubmitForm()">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Project Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Category</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Tech Stack (Comma Separated)</label>
                        <input type="text" name="tech_stack" id="tech_stack" class="form-control" placeholder="e.g. PHP, MySQL, Bootstrap 5, AJAX" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Project Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Upload Documentation (PDF, DOCX, ZIP - Max 5MB)</label>
                        <input type="file" name="doc_file" id="doc_file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Submit Entry</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>