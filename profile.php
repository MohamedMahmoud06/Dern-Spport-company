<?php
session_start(); // Start the session to access session variables
include 'config.php'; // Include your database configuration file

// Assume the user ID or email is stored in the session after login
$user_id = $_SESSION['email'];

// Query the database for the user's data
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); // Bind the user ID to the prepared statement
$stmt->execute();

// Fetch the result
$user = $stmt->get_result()->fetch_assoc();

// Check if user data was found
if (!$user) {
    echo "User not found";
    exit();
}

// Now $user contains the user's data
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>User Profile</h1>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <!-- Display the user's profile picture if available -->
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="img-fluid">
                    <?php else: ?>
                        <img src="default-profile.png" alt="Default Profile Picture" class="img-fluid">
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                    <!-- Add other user information as needed -->
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
