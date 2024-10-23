<?php
session_start();
include('tools/db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not authenticated
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = getDatabaseConnection();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch blogs created by the logged-in user
$blogsQuery = "SELECT * FROM blogs WHERE user_id='$user_id'";
$result = mysqli_query($conn, $blogsQuery);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage Your Blogs">
    <title>My Blogs</title>
    <link rel="icon" href="/images/images1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="/index.php">
            <img src="/images/images1.png" width="30" height="30" alt="news image"> News Website
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/index.php">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/logout.php" class="btn btn-outline-danger">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2>Manage Your Blogs</h2>
    <a href="create.php" class="btn btn-primary mb-3">Create New Blog</a>
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $shortDescription = (strlen($row['description']) > 60) ? substr($row['description'], 0, 60) . '...' : $row['description'];
                echo '
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="' . htmlspecialchars($row['image']) . '" class="card-img-top" alt="Blog Image" height="200">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>
                            <p class="card-text">' . htmlspecialchars($shortDescription) . '</p>
                            <a href="edit.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-warning">Edit</a>
                            <a href="delete.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-danger">Delete</a>
                            <a href="status.php?id=' . htmlspecialchars($row['id']) . '&action=' . ($row['status'] == 'active' ? 'deactivate' : 'activate') . '" class="btn btn-secondary">'
                            . ($row['status'] == 'active' ? 'Deactivate' : 'Activate') . '</a>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<p>You have not created any blogs yet.</p>';
        }
        ?>
    </div>
</div>

<?php $conn->close(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
