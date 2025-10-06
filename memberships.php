<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $membership_id = $_POST['membership_id'];
    $type = $_POST['type'];
    $duration_months = $_POST['duration_months'];
    $cost = $_POST['cost'];

    $sql = "INSERT INTO Memberships (membership_id, type, duration_months, cost) VALUES ('$membership_id', '$type', '$duration_months', '$cost')";
    if ($conn->query($sql) === TRUE) {
        echo "New membership added successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM Memberships");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Memberships</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Memberships</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2>Add New Membership</h2>
<form method="post" class="mb-4">
<div class="mb-3">
<label for="membership_id" class="form-label">Membership ID</label>
<input type="number" class="form-control" name="membership_id" required>
</div>
<div class="mb-3">
<label for="type" class="form-label">Type</label>
<input type="text" class="form-control" name="type" required>
</div>
<div class="mb-3">
<label for="duration_months" class="form-label">Duration (months)</label>
<input type="number" class="form-control" name="duration_months" required>
</div>
<div class="mb-3">
<label for="cost" class="form-label">Cost</label>
<input type="number" step="0.01" class="form-control" name="cost" required>
</div>
<button type="submit" class="btn btn-primary">Add Membership</button>
</form>
<h2>Existing Memberships</h2>
<table class="table table-striped">
<thead>
<tr><th>Membership ID</th><th>Type</th><th>Duration (months)</th><th>Cost</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['membership_id']; ?></td>
<td><?php echo $row['type']; ?></td>
<td><?php echo $row['duration_months']; ?></td>
<td><?php echo $row['cost']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php $conn->close(); ?>
</body>
</html>
