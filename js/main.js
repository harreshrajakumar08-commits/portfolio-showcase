function validateRegister() {
    const name = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const pass = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;

    if (name === "" || email === "" || pass === "") {
        alert("Please fill in all required fields.");
        return false;
    }
    if (pass.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }
    if (pass !== confirm) {
        alert("Passwords do not match.");
        return false;
    }
    return true;
}

function validateSubmitForm() {
    const fileInput = document.getElementById('doc_file');
    if (fileInput.files.length > 0) {
        const fileSize = fileInput.files[0].size / 1024 / 1024; // in MB
        if (fileSize > 5) {
            alert("File size exceeds 5MB limit.");
            return false;
        }
        const ext = fileInput.files[0].name.split('.').pop().toLowerCase();
        if (!['pdf', 'docx', 'zip', 'txt'].includes(ext)) {
            alert("Invalid file type. Only PDF, DOCX, ZIP and TXT are allowed.");
            return false;
        }
    }
    const title = document.getElementById('title').value.trim();
    const cat = document.getElementById('category_id').value;
    const tech = document.getElementById('tech_stack').value.trim();
    const desc = document.getElementById('description').value.trim();
    if (!title || !cat || !tech || !desc) {
        alert("Please fill in all required fields.");
        return false;
    }
    return true;
}

function validateLogin() {
    const email = document.getElementById('email').value.trim();
    const pass = document.getElementById('password').value;
    if (email === "" || pass === "") {
        alert("Please enter your email and password.");
        return false;
    }
    return true;
}

function validateCategory() {
    const name = document.getElementById('category_name').value.trim();
    const desc = document.getElementById('description').value.trim();
    if (name === "" || desc === "") {
        alert("Category name and description are required.");
        return false;
    }
    return true;
}