<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $facility_id = $_POST['facility_id'];
    $name = $_POST['name'];
    $location = $_POST['location'];

    $sql = "INSERT INTO Facilities (facility_id, name, location) VALUES ('$facility_id', '$name', '$location')";
    if ($conn->query($sql) === TRUE) {
        echo "New facility added successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM Facilities");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Facilities</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Facilities</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2>Add New Facility</h2>
<form method="post" class="mb-4">
<div class="mb-3">
<label for="facility_id" class="form-label">Facility ID</label>
<input type="number" class="form-control" name="facility_id" required>
</div>
<div class="mb-3">
<label for="name" class="form-label">Name</label>
<input type="text" class="form-control" name="name" required>
</div>
<div class="mb-3">
<label for="location" class="form-label">Location</label>
<input type="text" class="form-control" name="location" required>
</div>
<button type="submit" class="btn btn-primary">Add Facility</button>
</form>
<h2>Existing Facilities</h2>
<table class="table table-striped">
<thead>
<tr><th>Facility ID</th><th>Name</th><th>Location</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['facility_id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['location']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php $conn->close(); ?>
</body>
</html>
