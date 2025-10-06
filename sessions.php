<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $session_id = $_POST['session_id'];
    $trainer_id = $_POST['trainer_id'];
    $session_date = $_POST['session_date'];
    $facility_id = $_POST['facility_id'];

    $sql = "INSERT INTO Sessions (session_id, trainer_id, session_date, facility_id) VALUES ('$session_id', '$trainer_id', '$session_date', '$facility_id')";
    if ($conn->query($sql) === TRUE) {
        echo "New session added successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$trainers_result = $conn->query("SELECT * FROM Trainers");
$facilities_result = $conn->query("SELECT * FROM Facilities");
$result = $conn->query("SELECT s.session_id, t.first_name AS trainer_first, t.last_name AS trainer_last, s.session_date, f.name AS facility_name FROM Sessions s JOIN Trainers t ON s.trainer_id = t.trainer_id JOIN Facilities f ON s.facility_id = f.facility_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Sessions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Sessions</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2>Add New Session</h2>
<form method="post" class="mb-4">
<div class="mb-3">
<label for="session_id" class="form-label">Session ID</label>
<input type="number" class="form-control" name="session_id" required>
</div>
<div class="mb-3">
<label for="trainer_id" class="form-label">Trainer</label>
<select class="form-control" name="trainer_id" required>
<option value="">Select Trainer</option>
<?php while($tr = $trainers_result->fetch_assoc()) { ?>
<option value="<?php echo $tr['trainer_id']; ?>"><?php echo $tr['first_name'] . ' ' . $tr['last_name']; ?> (ID: <?php echo $tr['trainer_id']; ?>)</option>
<?php } ?>
</select>
</div>
<div class="mb-3">
<label for="session_date" class="form-label">Session Date</label>
<input type="date" class="form-control" name="session_date" required>
</div>
<div class="mb-3">
<label for="facility_id" class="form-label">Facility</label>
<select class="form-control" name="facility_id" required>
<option value="">Select Facility</option>
<?php while($fc = $facilities_result->fetch_assoc()) { ?>
<option value="<?php echo $fc['facility_id']; ?>"><?php echo $fc['name']; ?> (ID: <?php echo $fc['facility_id']; ?>)</option>
<?php } ?>
</select>
</div>
<button type="submit" class="btn btn-primary">Add Session</button>
</form>
<h2>Existing Sessions</h2>
<table class="table table-striped">
<thead>
<tr><th>Session ID</th><th>Trainer</th><th>Session Date</th><th>Facility</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['session_id']; ?></td>
<td><?php echo $row['trainer_first'] . ' ' . $row['trainer_last']; ?></td>
<td><?php echo $row['session_date']; ?></td>
<td><?php echo $row['facility_name']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php $conn->close(); ?>
</body>
</html>
