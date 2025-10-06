<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $membership_id = $_POST['membership_id'];

    $sql = "INSERT INTO Members (member_id, first_name, last_name, dob, membership_id) VALUES ('$member_id', '$first_name', '$last_name', '$dob', '$membership_id')";
    if ($conn->query($sql) === TRUE) {
        echo "New member added successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Members</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2>Add New Member</h2>
<form method="post" class="mb-4">
<div class="mb-3">
<label for="member_id" class="form-label">Member ID</label>
<input type="number" class="form-control" name="member_id" required>
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
<label for="dob" class="form-label">DOB</label>
<input type="date" class="form-control" name="dob" required>
</div>
<div class="mb-3">
<label for="membership_id" class="form-label">Membership</label>
<select class="form-control" name="membership_id" required>
<option value="">Select Membership</option>
<?php while($ms = $memberships_result->fetch_assoc()) { ?>
<option value="<?php echo $ms['membership_id']; ?>"><?php echo $ms['type']; ?> (ID: <?php echo $ms['membership_id']; ?>)</option>
<?php } ?>
</select>
</div>
<button type="submit" class="btn btn-primary">Add Member</button>
</form>
<h2>Existing Members</h2>
<table class="table table-striped">
<thead>
<tr><th>Member ID</th><th>First Name</th><th>Last Name</th><th>DOB</th><th>Membership Type</th></tr>
</thead>
<tbody>
<?php while($row = $members_result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['member_id']; ?></td>
<td><?php echo $row['first_name']; ?></td>
<td><?php echo $row['last_name']; ?></td>
<td><?php echo $row['dob']; ?></td>
<td><?php echo $row['membership_type']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php $conn->close(); ?>
</body>
</html>
