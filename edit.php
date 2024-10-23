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

// Initialize error message and success message variables
$error_message = "";

// Check if the blog post ID is provided in the URL
if (isset($_GET['id'])) {
    $blog_id = intval($_GET['id']); // Get blog post ID from the URL

    // Retrieve the blog post data from the database
    $query = "SELECT * FROM blogs WHERE id = '$blog_id' AND user_id = '" . $_SESSION['user_id'] . "'";
    $result = mysqli_query($conn, $query);

    // Check if the blog post exists and belongs to the logged-in user
    if (mysqli_num_rows($result) > 0) {
        $blog = mysqli_fetch_assoc($result); // Fetch blog data

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get updated data from the form
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $image = mysqli_real_escape_string($conn, $_POST['image']);

            // Validate inputs
            if (empty($title) || empty($description) || empty($image)) {
                $error_message = "All fields are required!";
            } else {
                // Update the blog post in the database
                $updateQuery = "UPDATE blogs SET title = '$title', description = '$description', image = '$image' WHERE id = '$blog_id' AND user_id = '" . $_SESSION['user_id'] . "'";

                if (mysqli_query($conn, $updateQuery)) {
                    // Update was successful, redirect to index.php
                    header("Location: index.php");
                    exit(); // Ensure the script stops after redirecting
                } else {
                    // If there was an error during the update query
                    $error_message = "Error updating blog post: " . mysqli_error($conn);
                }
            }
        }
    } else {
        // If the blog post doesn't exist or doesn't belong to the user
        $error_message = "Blog post not found or you do not have permission to edit this post.";
    }
} else {
    // If no blog ID is provided
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit your blog post on News Now.">
    <title>Edit Blog - News Now</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="/index.php">
            <img src="/images/images1.png" width="30" height="30" class="d-inline-block align-top" alt="news image"> News Website
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/index.php">Home</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/logout.php" class="btn btn-danger">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2>Edit Blog</h2>

    <!-- Display error message if any -->
    <?php if (!empty($error_message)) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php } ?>

    <!-- Edit blog form -->
    <?php if (isset($blog)) { ?>
        <form action="edit.php?id=<?php echo $blog_id; ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($blog['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image URL</label>
                <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($blog['image']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Blog</button>
        </form>
    <?php } ?>
</div>

<?php
// Close the database connection
mysqli_close($conn);
?>

</body>
</html>
