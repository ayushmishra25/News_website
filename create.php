<?php
// Start session
session_start();
include('tools/db.php');

// Check if the user is authenticated (logged in)
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Get the database connection
$conn = getDatabaseConnection();

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize error message variable
$error_message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user input from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Validate inputs
    if (empty($title) || empty($description) || empty($image)) {
        $error_message = "All fields are required!";
    } elseif (strlen($title) > 30) { // Title length validation
        $error_message = "Title must not exceed 30 characters.";
    } else {
        // Insert the blog post into the database
        $user_id = $_SESSION['user_id']; // Get the logged-in user ID
        $insertQuery = "INSERT INTO blogs (user_id, title, description, image, status) VALUES ('$user_id', '$title', '$description', '$image', '$status')";

        if (mysqli_query($conn, $insertQuery)) {
            // Blog post created successfully, redirect to index.php
            header("Location: index.php");
            exit();
        } else {
            // If there was an error during the insert query
            $error_message = "Error creating blog post: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog Post</title>
    <link rel="icon" href="/images/images1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Navbar here (same as before) -->
<div class="container py-5">
    <h2>Create New Blog Post</h2>
    <?php if (!empty($error_message)) { ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Title (max 30 characters)</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image URL</label>
            <input type="text" class="form-control" id="image" name="image" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Blog</button>
    </form>
</div>

<?php $conn->close(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
