<?php
session_start();
include 'config.php'; // Include the database configuration file

// Function to mark a request as done and send email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_done'])) {
    $request_id = intval($_POST['request_id']);

    // Mark the request as done
    $markSql = "UPDATE requests SET is_done = TRUE WHERE request_id = ?";
    $stmt = $conn->prepare($markSql);
    if ($stmt === false) {
        die("Error preparing SQL statement: " . $conn->error);
    }

    $stmt->bind_param('i', $request_id); // Bind the parameter
    $stmt->execute(); // Execute the SQL command

    if ($stmt->affected_rows > 0) {
        echo "Request marked as done.";
    } else {
        echo "Error marking request as done.";
    }
}

// Fetch all requests to display in the dashboard
$requestSql = "SELECT * FROM requests";
$requestResult = $conn->query($requestSql);

// Check for errors
if ($requestResult === false) {
    die("Error fetching request data: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Hello, Admin!</a>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="categories.php">Add Category</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php">Dashboard</a>
      </li>
    </ul>
  </div>
</nav>

<div id="dashboard" class="container mt-4">
  <h2>Requests</h2>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>User Name</th>
        <th>Category</th>
        <th>Service Name</th>
        <th>Request Time</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($requestResult as $request) : ?>
        <tr>
          <td><?php echo $request['request_id']; ?></td>
          <td><?php echo htmlspecialchars($request['user_name']); ?></td>
          <td><?php echo htmlspecialchars($request['category_name']); ?></td>
          <td><?php echo htmlspecialchars($request['service_name']); ?></td>
          <td><?php echo $request['request_time']; ?></td>
          <td><?php echo $request['is_done'] ? 'Done' : 'Pending'; ?></td>
          <td>
            <?php if (!$request['is_done']) : ?>
              <form method="POST" action="#">
                <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                <button type="submit" name="mark_as_done" class="btn btn-success">Mark as Done</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
