<?php
include 'config/db.php';

$edit_row = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_result = $conn->query("SELECT * FROM Members WHERE member_id = '$edit_id'");
    $edit_row = $edit_result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $member_id = $_POST['member_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $dob = $_POST['dob'];
        $membership_id = $_POST['membership_id'];

        $sql = "UPDATE Members SET first_name='$first_name', last_name='$last_name', dob='$dob', membership_id='$membership_id' WHERE member_id='$member_id'";
        if ($conn->query($sql) === TRUE) {
            header("Location: members.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $member_id = $_POST['member_id'];
        // Delete related records first to avoid foreign key constraint errors
        $sql1 = "DELETE FROM Payments WHERE member_id='$member_id'";
        $conn->query($sql1);
        $sql2 = "DELETE FROM Bookings WHERE member_id='$member_id'";
        $conn->query($sql2);
        $sql = "DELETE FROM Members WHERE member_id='$member_id'";
        if ($conn->query($sql) === TRUE) {
            header("Location: members.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $member_id = $_POST['member_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $dob = $_POST['dob'];
        $membership_id = $_POST['membership_id'];

        $sql = "INSERT INTO Members (member_id, first_name, last_name, dob, membership_id) VALUES ('$member_id', '$first_name', '$last_name', '$dob', '$membership_id')";
        if ($conn->query($sql) === TRUE) {
            header("Location: members.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$members_result = $conn->query("SELECT m.*, ms.type AS membership_type FROM Members m JOIN Memberships ms ON m.membership_id = ms.membership_id");
$memberships_result = $conn->query("SELECT * FROM Memberships");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Members</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Members</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2><?php echo $edit_row ? 'Edit Member' : 'Add New Member'; ?></h2>
<div class="row justify-content-center">
<div class="col-md-6">
<form method="post" class="mb-4">
<?php if ($edit_row): ?>
<input type="hidden" name="action" value="update">
<?php endif; ?>
<div class="mb-3">
<label for="member_id" class="form-label">Member ID</label>
<input type="number" class="form-control" name="member_id" value="<?php echo $edit_row ? $edit_row['member_id'] : ''; ?>" <?php echo $edit_row ? 'readonly' : 'required'; ?>>
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
<label for="dob" class="form-label">DOB</label>
<input type="date" class="form-control" name="dob" value="<?php echo $edit_row ? $edit_row['dob'] : ''; ?>" required>
</div>
<div class="mb-3">
<label for="membership_id" class="form-label">Membership</label>
<select class="form-control" name="membership_id" required>
<option value="">Select Membership</option>
<?php
$memberships_result->data_seek(0); // Reset pointer
while($ms = $memberships_result->fetch_assoc()) {
    $selected = ($edit_row && $edit_row['membership_id'] == $ms['membership_id']) ? 'selected' : '';
    echo "<option value='{$ms['membership_id']}' $selected>{$ms['type']} - Cost: \${$ms['cost']}</option>";
}
?>
</select>
</div>
<button type="submit" class="btn btn-primary"><?php echo $edit_row ? 'Update Member' : 'Add Member'; ?></button>
<?php if ($edit_row): ?>
<a href="members.php" class="btn btn-secondary">Cancel</a>
<?php endif; ?>
</form>
</div>
</div>
<h2>Existing Members</h2>
<table class="table table-striped">
<thead>
<tr><th>Member ID</th><th>First Name</th><th>Last Name</th><th>DOB</th><th>Membership Type</th><th>Actions</th></tr>
</thead>
<tbody>
<?php while($row = $members_result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['member_id']; ?></td>
<td><?php echo $row['first_name']; ?></td>
<td><?php echo $row['last_name']; ?></td>
<td><?php echo $row['dob']; ?></td>
<td><?php echo $row['membership_type']; ?></td>
<td>
<a href="?edit_id=<?php echo $row['member_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<form method="post" style="display:inline;">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="member_id" value="<?php echo $row['member_id']; ?>">
<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this member?')">Delete</button>
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
