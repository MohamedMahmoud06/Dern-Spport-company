<?php
session_start(); // Ensure the session is started
include 'config.php';

// Fetch user data (including email) from session
$userSql = "SELECT email, name FROM users WHERE email = ?";
$stmt = $conn->prepare($userSql);
$stmt->bind_param('s', $_SESSION['email']); // Use email from session
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc(); // Get the user's data

// Fetch categories
$categorySql = "SELECT * FROM categories";
$categoryResult = $conn->query($categorySql);
$categories = $categoryResult->fetch_all(MYSQLI_ASSOC);

// Fetch services based on the selected category (if specified)
$services = [];
if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);
    $serviceSql = "SELECT * FROM services WHERE category_id = ?";
    $stmt = $conn->prepare($serviceSql);
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    $services = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <!-- Navbar with User Greeting -->
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="#">Hello, <?php echo htmlspecialchars($user['name']); ?></a>
        <a class="nav-link" href="index.php">Logout</a>
        <a class="nav-link" href="profile.php">Profile</a>
    </nav>

    <!-- Category Selection -->
    <div class="row mt-4">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($category['category_name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($category['description']); ?></p>
                        <form method="GET">
                            <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category['id']); ?>">
                            <button class="btn btn-primary" type="submit">View Services</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Services -->
    <?php if ($services): ?>
        <div class="row mt-4">
            <?php foreach ($services as $service): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($service['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                            <form method="POST" action="record_request.php">
                                <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($user['name']); ?>">
                                <input type="hidden" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                                <input type="hidden" name="service_name" value="<?php echo htmlspecialchars($service['name']); ?>">
                                <button class="btn btn-primary" type="submit">Request Service</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
