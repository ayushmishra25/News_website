<?php
// Start the session and include necessary files
session_start();
include('tools/db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize the authentication flag
$authenticated = false;

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $authenticated = true; // User is authenticated
}

// Get database connection
$conn = getDatabaseConnection();

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to get active blogs
$blogsQuery = "SELECT * FROM blogs WHERE status='active'";
$result = mysqli_query($conn, $blogsQuery);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to News Now, your one-stop destination for the latest updates from around the globe.">
    <title>News Now - Latest Blogs</title>
    <link rel="icon" href="/images/images1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="/index.php">
            <img src="/images/images1.png" width="30" height="30" class="d-inline-block align-top" alt="news image"> News Website
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

            <!-- Authentication Check -->
            <?php if ($authenticated) { ?>
                <?php if (isset($_SESSION['is_first_time']) && $_SESSION['is_first_time'] == true) { ?>
                    <!-- Show Admin Dropdown for First-Time User -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/myblog.php">My Blog</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php } else { ?>
                    <!-- Show Normal User Dropdown or Options -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/myblog.php">My Blog</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>
            <?php } else { ?>
                <!-- Show login and register buttons if not authenticated -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="/register.php" class="btn btn-outline-primary me-2">Register</a>
                    </li>
                    <li class="nav-item">
                        <a href="/login.php" class="btn btn-primary">Login</a>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="display-2"><strong>Best Daily News Website</strong></h1>
            <p>Welcome to <strong>News Now</strong>, your one-stop destination for the latest updates from around the globe.</p>
        </div>
        <div class="col-md-6 text-center">
            <img src="/images/image2.png" class="img-fluid" alt="hero" width="400" height="400" />
        </div>
    </div>

    <!-- Blog Section -->
    <h2 class="my-5">Latest Blogs</h2>
    <div class="row">
        <?php
        // Check if there are any blog posts
        if ($result->num_rows > 0) {
            // Output data for each blog post
            while ($row = $result->fetch_assoc()) {
                // Shorten description to 60 characters
                $shortDescription = (strlen($row['description']) > 60) ? substr($row['description'], 0, 60) . '...' : $row['description'];

                echo '
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="' . htmlspecialchars($row['image']) . '" class="card-img-top" alt="Blog Image" height="200">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>
                            <p class="card-text">' . htmlspecialchars($shortDescription) . '</p>';

                // "Read More" button
                echo '<a href="blog.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-primary">Read More</a>';

                echo '</div></div></div>';
            }
        } else {
            echo '<p>No blogs found</p>';
        }
        ?>
    </div>

</div>

<?php
// Close the database connection
$conn->close();
?>

</body>
</html>

<?php include 'layout/footer.php'; ?>
