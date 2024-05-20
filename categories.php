<?php
include 'config.php';

// Fetch categories
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
$categories = $result->fetch_all(MYSQLI_ASSOC);

// Add category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryName = $_POST['category_name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO categories (category_name, description) VALUES ('$categoryName', '$description')";
    if ($conn->query($sql) === TRUE) {
        header('Location: categories.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete category
if (isset($_GET['delete_id'])) {
    $categoryId = $_GET['delete_id'];

    // Delete associated services first
    $deleteServicesSql = "DELETE FROM services WHERE category_id = $categoryId";
    $conn->query($deleteServicesSql);

    // Delete category
    $deleteCategorySql = "DELETE FROM categories WHERE id = $categoryId";
    if ($conn->query($deleteCategorySql) === TRUE) {
        header('Location: categories.php');
        exit();
    } else {
        echo "Error: " . $deleteCategorySql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Hello, Admin!</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
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
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <h3 class="text-center mb-4">Categories</h3>
                    <form action="categories.php" method="POST">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter category name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Enter description" required></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </div>
                    </form>

                    <h4 class="mt-5">Categories List</h4>
                    <?php if (!empty($categories)) : ?>
                        <ul>
                            <?php foreach ($categories as $category) : ?>
                                <li>
                                    <?php echo $category['category_name']; ?>
                                    <a class="btn btn-info" href="services.php?category_id=<?php echo $category['id']; ?>">View Services</a>
                                    <a class="btn btn-danger" href="categories.php?delete_id=<?php echo $category['id']; ?>">Delete</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>No categories found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>