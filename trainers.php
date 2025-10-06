<?php
include 'config/db.php';

$edit_row = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_result = $conn->query("SELECT * FROM Trainers WHERE trainer_id = '$edit_id'");
    $edit_row = $edit_result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $trainer_id = $_POST['trainer_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $specialty = $_POST['specialty'];

        $sql = "UPDATE Trainers SET first_name='$first_name', last_name='$last_name', specialty='$specialty' WHERE trainer_id='$trainer_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Trainer updated successfully<br>";
            header("Location: trainers.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $trainer_id = $_POST['trainer_id'];
        $sql = "DELETE FROM Trainers WHERE trainer_id='$trainer_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Trainer deleted successfully<br>";
            header("Location: trainers.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $trainer_id = $_POST['trainer_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $specialty = $_POST['specialty'];

        $sql = "INSERT INTO Trainers (trainer_id, first_name, last_name, specialty) VALUES ('$trainer_id', '$first_name', '$last_name', '$specialty')";
        if ($conn->query($sql) === TRUE) {
            echo "New trainer added successfully<br>";
            header("Location: trainers.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
<h2><?php echo $edit_row ? 'Edit Trainer' : 'Add New Trainer'; ?></h2>
<div class="row justify-content-center">
<div class="col-md-6">
<form method="post" class="mb-4">
<?php if ($edit_row): ?>
<input type="hidden" name="action" value="update">
<?php endif; ?>
<div class="mb-3">
<label for="trainer_id" class="form-label">Trainer ID</label>
<input type="number" class="form-control" name="trainer_id" value="<?php echo $edit_row ? $edit_row['trainer_id'] : ''; ?>" <?php echo $edit_row ? 'readonly' : 'required'; ?>>
</div>
<div class="mb-3">
<label for="first_name" class="form-label">First Name</label>
<input type="text" class="form-control" name="first_name" value="<?php echo $edit_row ? $edit_row['first_name'] : ''; ?>" required>
</div>
<div class="mb-3">
<label for="last_name" class="form-label">Last Name</label>
<input type="text" class="form-control" name="last_name" value="<?php echo $edit_row ? $edit_row['last_name'] : ''; ?>" required>
</div>
<div class="mb-3">
<label for="specialty" class="form-label">Specialty</label>
<input type="text" class="form-control" name="specialty" value="<?php echo $edit_row ? $edit_row['specialty'] : ''; ?>" required>
</div>
<button type="submit" class="btn btn-primary"><?php echo $edit_row ? 'Update Trainer' : 'Add Trainer'; ?></button>
<?php if ($edit_row): ?>
<a href="trainers.php" class="btn btn-secondary">Cancel</a>
<?php endif; ?>
</form>
</div>
</div>
<h2>Existing Trainers</h2>
<table class="table table-striped">
<thead>
<tr><th>Trainer ID</th><th>First Name</th><th>Last Name</th><th>Specialty</th><th>Actions</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['trainer_id']; ?></td>
<td><?php echo $row['first_name']; ?></td>
<td><?php echo $row['last_name']; ?></td>
<td><?php echo $row['specialty']; ?></td>
<td>
<a href="?edit_id=<?php echo $row['trainer_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<form method="post" style="display:inline;">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="trainer_id" value="<?php echo $row['trainer_id']; ?>">
<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this trainer?')">Delete</button>
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
