<?php
// Include header
include 'layout/header.php';

// Redirect to index if already logged in
if (isset($_SESSION["username"])) {
    header("location: /index.php");
    exit;
}

// Initialize variables for form data and errors
$username = $email = $password = "";
$errors = [];

// Include database connection
include "tools/db.php";
$dbConnection = getDatabaseConnection(); // Corrected from db.connection to dbConnection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and assign form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate fields
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    // Check for username existence
    $statement = $dbConnection->prepare("SELECT id FROM users WHERE username = ?");
    $statement->bind_param("s", $username);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows > 0) {
        $errors['username'] = "Username already exists.";
    }
    $statement->close();

    // Check for email existence
    $statement = $dbConnection->prepare("SELECT id FROM users WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows > 0) {
        $errors['email'] = "Email already exists.";
    }
    $statement->close();

    // If no errors, proceed with registration logic
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL statement for insertion
        $stmt = $dbConnection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        
        // Get the current timestamp for created_at
        $created_at = date("Y-m-d H:i:s");
        
        // Bind parameters and execute
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Set session variables after successful registration
            $_SESSION['user_id'] = $dbConnection->insert_id; // Using dbConnection
            $_SESSION['user_name'] = $username;
            $_SESSION['is_first_time'] = true; // Flag for first-time user

            // Redirect to index.php after successful registration
            header('Location: index.php');
            exit();
        } else {
            echo '<div class="alert alert-danger">Registration failed, please try again.</div>';
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-6 mx-auto border shadow p-4">
            <h2 class="text-center mb-4">Register</h2>
            <hr />

            <form method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username*</label>
                    <input type="text" class="form-control" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    <?php if (isset($errors['username'])): ?>
                        <span class="text-danger"><?php echo $errors['username']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email*</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <span class="text-danger"><?php echo $errors['email']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password*</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                    <?php if (isset($errors['password'])): ?>
                        <span class="text-danger"><?php echo $errors['password']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
