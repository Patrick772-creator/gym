<?php
include 'config/db.php';

$edit_row = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_result = $conn->query("SELECT * FROM Memberships WHERE membership_id = '$edit_id'");
    $edit_row = $edit_result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $membership_id = $_POST['membership_id'];
        $type = $_POST['type'];
        $duration_months = $_POST['duration_months'];
        $cost = $_POST['cost'];

        $sql = "UPDATE Memberships SET type='$type', duration_months='$duration_months', cost='$cost' WHERE membership_id='$membership_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Membership updated successfully<br>";
            header("Location: memberships.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $membership_id = $_POST['membership_id'];
        $sql = "DELETE FROM Memberships WHERE membership_id='$membership_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Membership deleted successfully<br>";
            header("Location: memberships.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $membership_id = $_POST['membership_id'];
        $type = $_POST['type'];
        $duration_months = $_POST['duration_months'];
        $cost = $_POST['cost'];

        $sql = "INSERT INTO Memberships (membership_id, type, duration_months, cost) VALUES ('$membership_id', '$type', '$duration_months', '$cost')";
        if ($conn->query($sql) === TRUE) {
            echo "New membership added successfully<br>";
            header("Location: memberships.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$result = $conn->query("SELECT * FROM Memberships");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Memberships</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Memberships</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2><?php echo $edit_row ? 'Edit Membership' : 'Add New Membership'; ?></h2>
<div class="row justify-content-center">
<div class="col-md-6">
<form method="post" class="mb-4">
<?php if ($edit_row): ?>
<input type="hidden" name="action" value="update">
<?php endif; ?>
<div class="mb-3">
<label for="membership_id" class="form-label">Membership ID</label>
<input type="number" class="form-control" name="membership_id" value="<?php echo $edit_row ? $edit_row['membership_id'] : ''; ?>" <?php echo $edit_row ? 'readonly' : 'required'; ?>>
</div>
<div class="mb-3">
<label for="type" class="form-label">Type</label>
<input type="text" class="form-control" name="type" value="<?php echo $edit_row ? $edit_row['type'] : ''; ?>" required>
</div>
<div class="mb-3">
<label for="duration_months" class="form-label">Duration (months)</label>
<input type="number" class="form-control" name="duration_months" value="<?php echo $edit_row ? $edit_row['duration_months'] : ''; ?>" required>
</div>
<div class="mb-3">
<label for="cost" class="form-label">Cost</label>
<input type="number" step="0.01" class="form-control" name="cost" value="<?php echo $edit_row ? $edit_row['cost'] : ''; ?>" required>
</div>
<button type="submit" class="btn btn-primary"><?php echo $edit_row ? 'Update Membership' : 'Add Membership'; ?></button>
<?php if ($edit_row): ?>
<a href="memberships.php" class="btn btn-secondary">Cancel</a>
<?php endif; ?>
</form>
</div>
</div>
<h2>Existing Memberships</h2>
<table class="table table-striped">
<thead>
<tr><th>Membership ID</th><th>Type</th><th>Duration (months)</th><th>Cost</th><th>Actions</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['membership_id']; ?></td>
<td><?php echo $row['type']; ?></td>
<td><?php echo $row['duration_months']; ?></td>
<td><?php echo $row['cost']; ?></td>
<td>
<a href="?edit_id=<?php echo $row['membership_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<form method="post" style="display:inline;">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="membership_id" value="<?php echo $row['membership_id']; ?>">
<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this membership?')">Delete</button>
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
