<?php
// Include the database connection function
include 'tools/db.php'; // This should contain your getDatabaseConnection() function

// Get the database connection
$conn = getDatabaseConnection();

// Fetch the blog ID from the URL
if (isset($_GET['id'])) {
    $blogId = $_GET['id'];

    // Query to fetch the blog post details by ID
    $sql = "SELECT id, title, description, image FROM blogs WHERE id = ?";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $blogId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the blog exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Display the blog details
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Link to Bootstrap CSS -->
                <title>' . htmlspecialchars($row['title']) . '</title>
            </head>
            <body>
                <div class="container my-5">
                    <h1>' . htmlspecialchars($row['title']) . '</h1>
                    <img src="' . htmlspecialchars($row['image']) . '" alt="Blog Image" class="img-fluid">
                    <p class="mt-4">' . nl2br(htmlspecialchars($row['description'])) . '</p>
                    <a href="index.php" class="btn btn-secondary">Back to Blogs</a>
                </div>
            </body>
            </html>';
        } else {
            // Redirect to index.php if the blog is not found
            header('Location: index.php');
            exit();
        }
    } else {
        // Error preparing the statement
        die('Error preparing the SQL statement.');
    }
} else {
    // Redirect to index.php if no blog ID is provided
    header('Location: index.php');
    exit();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
