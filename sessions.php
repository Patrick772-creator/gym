<?php
include 'config/db.php';

$edit_row = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_result = $conn->query("SELECT * FROM Sessions WHERE session_id = '$edit_id'");
    $edit_row = $edit_result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $session_id = $_POST['session_id'];
        $trainer_id = $_POST['trainer_id'];
        $session_date = $_POST['session_date'];
        $facility_id = $_POST['facility_id'];

        $sql = "UPDATE Sessions SET trainer_id='$trainer_id', session_date='$session_date', facility_id='$facility_id' WHERE session_id='$session_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Session updated successfully<br>";
            header("Location: sessions.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $session_id = $_POST['session_id'];
        // Check for dependent records
        $check_sql = "SELECT COUNT(*) as count FROM Bookings WHERE session_id='$session_id'";
        $check_result = $conn->query($check_sql);
        $check_row = $check_result->fetch_assoc();
        if ($check_row['count'] > 0) {
            echo "Cannot delete session. There are bookings associated with this session.<br>";
        } else {
            $sql = "DELETE FROM Sessions WHERE session_id='$session_id'";
            if ($conn->query($sql) === TRUE) {
                echo "Session deleted successfully<br>";
                header("Location: sessions.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        $session_id = $_POST['session_id'];
        $trainer_id = $_POST['trainer_id'];
        $session_date = $_POST['session_date'];
        $facility_id = $_POST['facility_id'];

        $sql = "INSERT INTO Sessions (session_id, trainer_id, session_date, facility_id) VALUES ('$session_id', '$trainer_id', '$session_date', '$facility_id')";
        if ($conn->query($sql) === TRUE) {
            echo "New session added successfully<br>";
            header("Location: sessions.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Sessions</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2><?php echo $edit_row ? 'Edit Session' : 'Add New Session'; ?></h2>
<div class="row justify-content-center">
<div class="col-md-6">
<form method="post" class="mb-4">
<?php if ($edit_row): ?>
<input type="hidden" name="action" value="update">
<?php endif; ?>
<div class="mb-3">
<label for="session_id" class="form-label">Session ID</label>
<input type="number" class="form-control" name="session_id" value="<?php echo $edit_row ? $edit_row['session_id'] : ''; ?>" <?php echo $edit_row ? 'readonly' : 'required'; ?>>
</div>
<div class="mb-3">
<label for="trainer_id" class="form-label">Trainer</label>
<select class="form-control" name="trainer_id" required>
<option value="">Select Trainer</option>
<?php
$trainers_result->data_seek(0);
while($tr = $trainers_result->fetch_assoc()) {
    $selected = ($edit_row && $edit_row['trainer_id'] == $tr['trainer_id']) ? 'selected' : '';
    echo "<option value='{$tr['trainer_id']}' $selected>{$tr['first_name']} {$tr['last_name']}</option>";
}
?>
</select>
</div>
<div class="mb-3">
<label for="session_date" class="form-label">Session Date</label>
<input type="date" class="form-control" name="session_date" value="<?php echo $edit_row ? $edit_row['session_date'] : ''; ?>" required>
</div>
<div class="mb-3">
<label for="facility_id" class="form-label">Facility</label>
<select class="form-control" name="facility_id" required>
<option value="">Select Facility</option>
<?php
$facilities_result->data_seek(0);
while($fc = $facilities_result->fetch_assoc()) {
    $selected = ($edit_row && $edit_row['facility_id'] == $fc['facility_id']) ? 'selected' : '';
    echo "<option value='{$fc['facility_id']}' $selected>{$fc['name']}</option>";
}
?>
</select>
</div>
<button type="submit" class="btn btn-primary"><?php echo $edit_row ? 'Update Session' : 'Add Session'; ?></button>
<?php if ($edit_row): ?>
<a href="sessions.php" class="btn btn-secondary">Cancel</a>
<?php endif; ?>
</form>
</div>
</div>
<h2>Existing Sessions</h2>
<table class="table table-striped">
<thead>
<tr><th>Session ID</th><th>Trainer</th><th>Session Date</th><th>Facility</th><th>Actions</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['session_id']; ?></td>
<td><?php echo $row['trainer_first'] . ' ' . $row['trainer_last']; ?></td>
<td><?php echo $row['session_date']; ?></td>
<td><?php echo $row['facility_name']; ?></td>
<td>
<a href="?edit_id=<?php echo $row['session_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<form method="post" style="display:inline;">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="session_id" value="<?php echo $row['session_id']; ?>">
<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this session?')">Delete</button>
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
