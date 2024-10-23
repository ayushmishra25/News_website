<?php


// Include header
include 'layout/header.php';

// Redirect if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /index.php");
    exit;
}

// Initialize variables
$email = "";
$error = "";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Include the database connection
    include('tools/db.php');
    
    // Get the database connection
    $conn = getDatabaseConnection();
    
    // Check if the connection was established
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Prevent SQL injection by using prepared statements
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);  // Use $conn, not $connection
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION["user_id"] = $user['id'];
            $_SESSION["user_name"] = $user['username'];
            $_SESSION["is_admin"] = $user['is_admin']; // Store admin status in session
        
            // Redirect to the home page
            header("Location: /index.php");
            exit;
        } else {
            // Invalid password
            $error = "Invalid email or password.";
        }
    } else {
        // User not found
        $error = "Invalid email or password.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<div class="container py-5">
    <div class="mx-auto border shadow p-4" style="width:400px">
        <h2 class="text-center mb-4">Login</h2>
        <hr />

        <!-- Error message display -->
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?= htmlspecialchars($error) ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <!-- Login form -->
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" required/>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required/>
            </div>

            <div class="row mb-3">
                <div class="col d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                <div class="col d-grid">
                    <a href="/index.php" class="btn btn-outline-primary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
