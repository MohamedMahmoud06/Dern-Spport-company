<?php
include 'config.php';

// Fetch category details
if (isset($_GET['category_id'])) {
    $categoryId = $_GET['category_id'];
    $categorySql = "SELECT * FROM categories WHERE id = $categoryId";
    $categoryResult = $conn->query($categorySql);
    $category = $categoryResult->fetch_assoc();

    $servicesSql = "SELECT * FROM services WHERE category_id = $categoryId";
    $servicesResult = $conn->query($servicesSql);
    $services = $servicesResult->fetch_all(MYSQLI_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $serviceName = $_POST['service_name'];
        $serviceDescription = $_POST['service_description'];
        $servicePrice = $_POST['service_price'];

        $insertServiceSql = "INSERT INTO services (category_id, name, description, price) VALUES ($categoryId, '$serviceName', '$serviceDescription', $servicePrice)";
        if ($conn->query($insertServiceSql) === TRUE) {
            header("Location: services.php?category_id=$categoryId");
            exit();
        } else {
            echo "Error: " . $insertServiceSql . "<br>" . $conn->error;
        }
    }

    if (isset($_GET['delete_id'])) {
        $serviceId = $_GET['delete_id'];

        $deleteServiceSql = "DELETE FROM services WHERE id = $serviceId";
        if ($conn->query($deleteServiceSql) === TRUE) {
            header("Location: services.php?category_id=$categoryId");
            exit();
        } else {
            echo "Error: " . $deleteServiceSql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
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
                    <h3 class="text-center mb-4"><?php echo $category['category_name']; ?> Services</h3>
                    <form action="services.php?category_id=<?php echo $categoryId; ?>" method="POST">
                        <div class="mb-3">
                            <label for="service_name" class="form-label">Service Name</label>
                            <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Enter service name" required>
                        </div>
                        <div class="mb-3">
                            <label for="service_description" class="form-label">Description</label>
                            <textarea class="form-control" id="service_description" name="service_description" placeholder="Enter description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="service_price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="service_price" name="service_price" placeholder="Enter price" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Add Service</button>
                        </div>
                    </form>

                    <h4 class="mt-5">Services List</h4>
                    <?php if (!empty($services)) : ?>
                        <ul>
                            <?php foreach ($services as $service) : ?>
                                <li>
                                    <?php echo $service['name']; ?>
                                    <a href="services.php?category_id=<?php echo $categoryId; ?>&delete_id=<?php echo $service['id']; ?>">Delete</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>No services found for this category.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

