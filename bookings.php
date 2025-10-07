<?php
include 'config/db.php';

$edit_row = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_result = $conn->query("SELECT * FROM Bookings WHERE booking_id = '$edit_id'");
    $edit_row = $edit_result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $booking_id = $_POST['booking_id'];
        $member_id = $_POST['member_id'];
        $session_id = $_POST['session_id'];

        $sql = "UPDATE Bookings SET member_id='$member_id', session_id='$session_id' WHERE booking_id='$booking_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Booking updated successfully<br>";
            header("Location: bookings.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $booking_id = $_POST['booking_id'];
        $sql = "DELETE FROM Bookings WHERE booking_id='$booking_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Booking deleted successfully<br>";
            header("Location: bookings.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $booking_id = $_POST['booking_id'];
        $member_id = $_POST['member_id'];
        $session_id = $_POST['session_id'];

        $sql = "INSERT INTO Bookings (booking_id, member_id, session_id) VALUES ('$booking_id', '$member_id', '$session_id')";
        if ($conn->query($sql) === TRUE) {
            echo "New booking added successfully<br>";
            header("Location: bookings.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$members_result = $conn->query("SELECT * FROM Members");
$sessions_result = $conn->query("SELECT * FROM Sessions");
$result = $conn->query("SELECT b.booking_id, m.first_name AS member_first, m.last_name AS member_last, s.session_date, t.first_name AS trainer_first, t.last_name AS trainer_last FROM Bookings b JOIN Members m ON b.member_id = m.member_id JOIN Sessions s ON b.session_id = s.session_id JOIN Trainers t ON s.trainer_id = t.trainer_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Bookings</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Bookings</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2><?php echo $edit_row ? 'Edit Booking' : 'Add New Booking'; ?></h2>
<div class="row justify-content-center">
<div class="col-md-6">
<form method="post" class="mb-4">
<?php if ($edit_row): ?>
<input type="hidden" name="action" value="update">
<?php endif; ?>
<div class="mb-3">
<label for="booking_id" class="form-label">Booking ID</label>
<input type="number" class="form-control" name="booking_id" value="<?php echo $edit_row ? $edit_row['booking_id'] : ''; ?>" <?php echo $edit_row ? 'readonly' : 'required'; ?>>
</div>
<div class="mb-3">
<label for="member_id" class="form-label">Member</label>
<select class="form-control" name="member_id" required>
<option value="">Select Member</option>
<?php
$members_result->data_seek(0);
while($m = $members_result->fetch_assoc()) {
    $selected = ($edit_row && $edit_row['member_id'] == $m['member_id']) ? 'selected' : '';
    echo "<option value='{$m['member_id']}' $selected>{$m['first_name']} {$m['last_name']} (ID: {$m['member_id']})</option>";
}
?>
</select>
</div>
<div class="mb-3">
<label for="session_id" class="form-label">Session</label>
<select class="form-control" name="session_id" required>
<option value="">Select Session</option>
<?php
$sessions_result->data_seek(0);
while($s = $sessions_result->fetch_assoc()) {
    $selected = ($edit_row && $edit_row['session_id'] == $s['session_id']) ? 'selected' : '';
    echo "<option value='{$s['session_id']}' $selected>Session ID: {$s['session_id']} on {$s['session_date']}</option>";
}
?>
</select>
</div>
<button type="submit" class="btn btn-primary"><?php echo $edit_row ? 'Update Booking' : 'Add Booking'; ?></button>
<?php if ($edit_row): ?>
<a href="bookings.php" class="btn btn-secondary">Cancel</a>
<?php endif; ?>
</form>
</div>
</div>
<h2>Existing Bookings</h2>
<table class="table table-striped">
<thead>
<tr><th>Booking ID</th><th>Member</th><th>Session Date</th><th>Trainer</th><th>Actions</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['booking_id']; ?></td>
<td><?php echo $row['member_first'] . ' ' . $row['member_last']; ?></td>
<td><?php echo $row['session_date']; ?></td>
<td><?php echo $row['trainer_first'] . ' ' . $row['trainer_last']; ?></td>
<td>
<a href="?edit_id=<?php echo $row['booking_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<form method="post" style="display:inline;">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</button>
</form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php $conn->close(); ?>
</body>
</html>
