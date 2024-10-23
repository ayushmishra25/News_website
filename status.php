<?php
// Start session to access user data
session_start();

// Include the database connection file
include('tools/db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure that the blog ID and action (activate or deactivate) are provided
if (isset($_GET['id']) && isset($_GET['action'])) {
    $blog_id = intval($_GET['id']);
    $action = $_GET['action'];

    // Establish a database connection
    $conn = getDatabaseConnection();

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the blog post belongs to the logged-in user
    $query = "SELECT * FROM blogs WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $blog_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user owns the blog post
    if ($result->num_rows > 0) {
        // Determine the new status based on the action
        if ($action == 'activate') {
            $new_status = 'active';
        } elseif ($action == 'deactivate') {
            $new_status = 'inactive';
        } else {
            // Invalid action provided
            echo "Invalid action.";
            exit();
        }

        // Update the status in the database
        $updateQuery = "UPDATE blogs SET status = ? WHERE id = ? AND user_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('sii', $new_status, $blog_id, $_SESSION['user_id']);

        if ($updateStmt->execute()) {
            // Status updated successfully
            echo "Status updated to '$new_status'.";
        } else {
            // Error updating blog status
            echo "Error updating blog status: " . $conn->error;
        }

        // Close the statement and connection
        $updateStmt->close();
        $stmt->close();
        $conn->close();

        // Redirect back to index.php after status change
        header("Location: index.php");
        exit();
    } else {
        echo "You do not have permission to change the status of this blog post.";
    }
} else {
    echo "Missing parameters.";
    exit();
}
?>
