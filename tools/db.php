<?php
function getDatabaseConnection(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "blog_system";
    // Establish the connection
    $connection = new mysqli($servername, $username, $password, $database);
    
    // Check for connection error
    if ($connection->connect_error) {
        die("Error failed to connect to MySQL: " . $connection->connect_error);
    }

    return $connection;
}
?>
