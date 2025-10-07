<?php
include 'config/db.php';

$edit_row = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_result = $conn->query("SELECT * FROM Facilities WHERE facility_id = '$edit_id'");
    $edit_row = $edit_result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $facility_id = $_POST['facility_id'];
        $name = $_POST['name'];
        $location = $_POST['location'];

        $sql = "UPDATE Facilities SET name='$name', location='$location' WHERE facility_id='$facility_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Facility updated successfully<br>";
            header("Location: facilities.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $facility_id = $_POST['facility_id'];
        $sql = "DELETE FROM Facilities WHERE facility_id='$facility_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Facility deleted successfully<br>";
            header("Location: facilities.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $facility_id = $_POST['facility_id'];
        $name = $_POST['name'];
        $location = $_POST['location'];

        $sql = "INSERT INTO Facilities (facility_id, name, location) VALUES ('$facility_id', '$name', '$location')";
        if ($conn->query($sql) === TRUE) {
            echo "New facility added successfully<br>";
            header("Location: facilities.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$result = $conn->query("SELECT * FROM Facilities");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Facilities</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Facilities</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2><?php echo $edit_row ? 'Edit Facility' : 'Add New Facility'; ?></h2>
<div class="row justify-content-center">
<div class="col-md-6">
<form method="post" class="mb-4">
<?php if ($edit_row): ?>
<input type="hidden" name="action" value="update">
<?php endif; ?>
<div class="mb-3">
<label for="facility_id" class="form-label">Facility ID</label>
<input type="number" class="form-control" name="facility_id" value="<?php echo $edit_row ? $edit_row['facility_id'] : ''; ?>" <?php echo $edit_row ? 'readonly' : 'required'; ?>>
</div>
<div class="mb-3">
<label for="name" class="form-label">Name</label>
<input type="text" class="form-control" name="name" value="<?php echo $edit_row ? $edit_row['name'] : ''; ?>" required>
</div>
<div class="mb-3">
<label for="location" class="form-label">Location</label>
<input type="text" class="form-control" name="location" value="<?php echo $edit_row ? $edit_row['location'] : ''; ?>" required>
</div>
<button type="submit" class="btn btn-primary"><?php echo $edit_row ? 'Update Facility' : 'Add Facility'; ?></button>
<?php if ($edit_row): ?>
<a href="facilities.php" class="btn btn-secondary">Cancel</a>
<?php endif; ?>
</form>
</div>
</div>
<h2>Existing Facilities</h2>
<table class="table table-striped">
<thead>
<tr><th>Facility ID</th><th>Name</th><th>Location</th><th>Actions</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['facility_id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['location']; ?></td>
<td>
<a href="?edit_id=<?php echo $row['facility_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<form method="post" style="display:inline;">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="facility_id" value="<?php echo $row['facility_id']; ?>">
<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this facility?')">Delete</button>
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
