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

// Check if the blog post ID is provided in the URL
if (isset($_GET['id'])) {
    $blog_id = intval($_GET['id']); // Get blog post ID from the URL

    // Verify that the blog post belongs to the logged-in user
    $query = "SELECT * FROM blogs WHERE id = '$blog_id' AND user_id = '" . $_SESSION['user_id'] . "'";
    $result = mysqli_query($conn, $query);

    // If the blog post exists and belongs to the user
    if (mysqli_num_rows($result) > 0) {
        // Perform the deletion
        $deleteQuery = "DELETE FROM blogs WHERE id = '$blog_id' AND user_id = '" . $_SESSION['user_id'] . "'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Deletion successful, redirect to the index page
            header("Location: index.php");
            exit();
        } else {
            // Error during deletion
            echo "Error deleting blog post: " . mysqli_error($conn);
        }
    } else {
        // Blog post not found or doesn't belong to the user
        echo "You do not have permission to delete this blog post.";
    }
} else {
    // No blog ID provided
    echo "No blog post specified for deletion.";
}

// Close the database connection
mysqli_close($conn);
?>
