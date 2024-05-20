<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $age = $_POST['age'];
    $type = $_POST['flexRadioDefault'];

    $sql = "INSERT INTO users (name, email, pass, age, type) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $name, $email, $pass, $age, $type);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <div class="d-flex justify-content-center py-5">
        <form class="form" method="POST" action="signup.php">
            <p class="form-title">Sign up to your account</p>
            <div class="input-container">
                <input type="text" placeholder="Enter Name" name="name">
            </div>
            <div class="input-container">
                <input type="email" placeholder="Enter E-mail" name="email">
            </div>
            <div class="input-container">
                <input type="password" placeholder="Enter password" name="pass">
            </div>
            <div class="input-container">
                <input type="number" placeholder="Enter Your age" name="age">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="indvidual" value="indvidual">
                <label class="form-check-label" for="indvidual">
                    Individual
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="team">
                <label class="form-check-label" for="flexRadioDefault2">
                    Company
                </label>
            </div>
            <button type="submit" class="submit">Sign up</button>
            <p class="signup-link">
                Have an account? <a href="index.php">Sign in</a>
            </p>
        </form>
    </div>
</body>
</html>