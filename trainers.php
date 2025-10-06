<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $trainer_id = $_POST['trainer_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $specialty = $_POST['specialty'];

    $sql = "INSERT INTO Trainers (trainer_id, first_name, last_name, specialty) VALUES ('$trainer_id', '$first_name', '$last_name', '$specialty')";
    if ($conn->query($sql) === TRUE) {
        echo "New trainer added successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM Trainers");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Trainers</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Trainers</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2>Add New Trainer</h2>
<form method="post" class="mb-4">
<div class="mb-3">
<label for="trainer_id" class="form-label">Trainer ID</label>
<input type="number" class="form-control" name="trainer_id" required>
</div>
<div class="mb-3">
<label for="first_name" class="form-label">First Name</label>
<input type="text" class="form-control" name="first_name" required>
</div>
<div class="mb-3">
<label for="last_name" class="form-label">Last Name</label>
<input type="text" class="form-control" name="last_name" required>
</div>
<div class="mb-3">
<label for="specialty" class="form-label">Specialty</label>
<input type="text" class="form-control" name="specialty" required>
</div>
<button type="submit" class="btn btn-primary">Add Trainer</button>
</form>
<h2>Existing Trainers</h2>
<table class="table table-striped">
<thead>
<tr><th>Trainer ID</th><th>First Name</th><th>Last Name</th><th>Specialty</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['trainer_id']; ?></td>
<td><?php echo $row['first_name']; ?></td>
<td><?php echo $row['last_name']; ?></td>
<td><?php echo $row['specialty']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php $conn->close(); ?>
</body>
</html>
