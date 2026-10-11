<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit;
}
include 'header.php';
?>

<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>Welcome back, <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['name'] ?? 'Student') ?>!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold m-0">Project Showcase Directory</h2>
        <p class="text-muted small m-0">Explore student portfolio submissions and FYP projects</p>
    </div>
    <a href="submit_project.php" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Submit New Project
    </a>
</div>

<div class="mb-4">
    <div class="input-group">
        <span class="input-group-text bg-white">
            <i class="bi bi-search"></i>
        </span>
        <input type="text" id="searchInput" class="form-control py-2"
               placeholder="Live search by project title, tech stack, or student name...">
    </div>
</div>

<div id="projectContainer" class="row g-3">
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");

    function esc(v) {
        const d = document.createElement('div');
        d.textContent = v ?? '';
        return d.innerHTML;
    }

    function fetchProjects(query = "") {
        fetch(`search_projects.php?q=${encodeURIComponent(query)}`)
            .then(res => {
                if (!res.ok) throw new Error('Request failed');
                return res.json();
            })
            .then(data => {
                const container = document.getElementById("projectContainer");
                container.innerHTML = "";

                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center text-muted py-4">
                            No matching projects found.
                        </div>
                    `;
                    return;
                }

                data.forEach(item => {
                    container.innerHTML += `
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body d-flex flex-column">

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-primary">
                                            ${esc(item.category_name)}
                                        </span>

                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            ${esc(item.formatted_date)}
                                        </small>
                                    </div>

                                    <h5 class="card-title fw-bold">
                                        ${esc(item.title)}
                                    </h5>

                                    <p class="card-text text-secondary small flex-grow-1">
                                        ${esc(item.description)}
                                    </p>

                                    <div class="mb-3">
                                        <strong class="d-block small text-dark">
                                            Tech Stack:
                                        </strong>

                                        <span class="badge bg-light text-dark border">
                                            ${esc(item.tech_stack)}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">

                                        <small class="text-muted">
                                            <i class="bi bi-person me-1"></i>
                                            ${esc(item.full_name)}
                                        </small>

                                        <a href="${esc(item.file_path)}"
                                           class="btn btn-sm btn-outline-primary"
                                           download>
                                            <i class="bi bi-download me-1"></i>
                                            Report
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(() => {
                document.getElementById("projectContainer").innerHTML =
                    '<div class="col-12 text-center text-danger py-4">Could not load projects. Please try again.</div>';
            });
    }

    fetchProjects();

    let timer;
    searchInput.addEventListener("input", () => {
        clearTimeout(timer);
        timer = setTimeout(() => fetchProjects(searchInput.value.trim()), 250);
    });
});
</script>

<?php include 'footer.php'; ?>