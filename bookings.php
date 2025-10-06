<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $member_id = $_POST['member_id'];
    $session_id = $_POST['session_id'];

    $sql = "INSERT INTO Bookings (booking_id, member_id, session_id) VALUES ('$booking_id', '$member_id', '$session_id')";
    if ($conn->query($sql) === TRUE) {
        echo "New booking added successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$result = $conn->query("SELECT b.booking_id, m.first_name AS member_first, m.last_name AS member_last, s.session_date, t.first_name AS trainer_first, t.last_name AS trainer_last FROM Bookings b JOIN Members m ON b.member_id = m.member_id JOIN Sessions s ON b.session_id = s.session_id JOIN Trainers t ON s.trainer_id = t.trainer_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Bookings</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Bookings</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2>Add New Booking</h2>
<form method="post" class="mb-4">
<div class="mb-3">
<label for="booking_id" class="form-label">Booking ID</label>
<input type="number" class="form-control" name="booking_id" required>
</div>
<div class="mb-3">
<label for="member_id" class="form-label">Member ID</label>
<input type="number" class="form-control" name="member_id" required>
</div>
<div class="mb-3">
<label for="session_id" class="form-label">Session ID</label>
<input type="number" class="form-control" name="session_id" required>
</div>
<button type="submit" class="btn btn-primary">Add Booking</button>
</form>
<h2>Existing Bookings</h2>
<table class="table table-striped">
<thead>
<tr><th>Booking ID</th><th>Member</th><th>Session Date</th><th>Trainer</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['booking_id']; ?></td>
<td><?php echo $row['member_first'] . ' ' . $row['member_last']; ?></td>
<td><?php echo $row['session_date']; ?></td>
<td><?php echo $row['trainer_first'] . ' ' . $row['trainer_last']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php $conn->close(); ?>
</body>
</html>
