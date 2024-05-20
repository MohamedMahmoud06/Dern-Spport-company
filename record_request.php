<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect POST data
    $user_name = $_POST['user_name'];
    $email = $_POST['email']; // This should be a variable, not a constant
    $category_id = intval($_POST['category_id']);
    $service_name = $_POST['service_name'];
    $request_time = date('Y-m-d H:i:s'); // Current time when request is processed

    // Validate inputs
    if (empty($user_name) || empty($email) || empty($service_name) || $category_id <= 0) {
        echo "Error: Missing required data.";
        exit();
    }

    // Insert into the requests table
    $sql = "INSERT INTO requests (user_name, email, category_name, service_name, request_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "Error preparing statement.";
        exit();
    }

    // Bind parameters and execute
    $stmt->bind_param('ssiss', $user_name, $email, $category_id, $service_name, $request_time); // Use $email as a variable
    $stmt->execute();

    if ($stmt->errno) {
        echo "Error: " . $stmt->error;
    } else {
        echo "Request recorded successfully.";
    }
} else {
    echo "Invalid request method.";
}
