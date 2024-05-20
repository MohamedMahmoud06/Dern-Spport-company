<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
    $type = isset($_POST['flexRadioDefault']) ? $_POST['flexRadioDefault'] : '';

    if ($email && $pass) {
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            if ($pass == $row['pass']) {
                $_SESSION['email'] = $email; // Set the session variable

                if ($email === 'superAdmin@gmail.com') {
                    header('Location: categories.php');
                    exit;
                } elseif ($type !== 'admin') {
                    header('Location: home.php'); // Redirect to home page
                    exit;
                } else {
                    echo '<div class="alert alert-danger" role="alert">Invalid user type</div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">Invalid password</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">User not found</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Email and password are required</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign in</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <div class="d-flex justify-content-center py-5">
        <form class="form" method="POST" action="index.php">
            <p class="form-title">Sign in to your account</p>
            <div class="input-container">
                <input type="email" placeholder="Enter E-mail" name="email">
            </div>
            <div class="input-container">
                <input type="password" placeholder="Enter password" name="pass">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="individual" value="individual">
                <label class="form-check-label" for="individual">Individual</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="admin" value="admin">
                <label class="form-check-label" for="admin">Admin</label>
            </div>
            <button type="submit" class="submit btn btn-trinary">Sign in</button>
            <p class="signup-link">No account? <a href="signup.php">Sign up</a></p>
        </form>
    </div>
    <script src="Testing/index.js"></script>
</body>
</html>