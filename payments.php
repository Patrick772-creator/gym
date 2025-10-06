<?php
include 'config/db.php';

$edit_row = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_result = $conn->query("SELECT * FROM Payments WHERE payment_id = '$edit_id'");
    $edit_row = $edit_result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $payment_id = $_POST['payment_id'];
        $member_id = $_POST['member_id'];
        $amount = $_POST['amount'];
        $payment_date = $_POST['payment_date'];

        $sql = "UPDATE Payments SET member_id='$member_id', amount='$amount', payment_date='$payment_date' WHERE payment_id='$payment_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Payment updated successfully<br>";
            header("Location: payments.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $payment_id = $_POST['payment_id'];
        $sql = "DELETE FROM Payments WHERE payment_id='$payment_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Payment deleted successfully<br>";
            header("Location: payments.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $payment_id = $_POST['payment_id'];
        $member_id = $_POST['member_id'];
        $amount = $_POST['amount'];
        $payment_date = $_POST['payment_date'];

        $sql = "INSERT INTO Payments (payment_id, member_id, amount, payment_date) VALUES ('$payment_id', '$member_id', '$amount', '$payment_date')";
        if ($conn->query($sql) === TRUE) {
            echo "New payment added successfully<br>";
            header("Location: payments.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$members_result = $conn->query("SELECT * FROM Members");
$result = $conn->query("SELECT p.payment_id, m.first_name AS member_first, m.last_name AS member_last, p.amount, p.payment_date FROM Payments p JOIN Members m ON p.member_id = m.member_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Payments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4">Payments</h1>
<a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
<h2><?php echo $edit_row ? 'Edit Payment' : 'Add New Payment'; ?></h2>
<div class="row justify-content-center">
<div class="col-md-6">
<form method="post" class="mb-4">
<?php if ($edit_row): ?>
<input type="hidden" name="action" value="update">
<?php endif; ?>
<div class="mb-3">
<label for="payment_id" class="form-label">Payment ID</label>
<input type="number" class="form-control" name="payment_id" value="<?php echo $edit_row ? $edit_row['payment_id'] : ''; ?>" <?php echo $edit_row ? 'readonly' : 'required'; ?>>
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
<label for="amount" class="form-label">Amount</label>
<input type="number" step="0.01" class="form-control" name="amount" value="<?php echo $edit_row ? $edit_row['amount'] : ''; ?>" required>
</div>
<div class="mb-3">
<label for="payment_date" class="form-label">Payment Date</label>
<input type="date" class="form-control" name="payment_date" value="<?php echo $edit_row ? $edit_row['payment_date'] : ''; ?>" required>
</div>
<button type="submit" class="btn btn-primary"><?php echo $edit_row ? 'Update Payment' : 'Add Payment'; ?></button>
<?php if ($edit_row): ?>
<a href="payments.php" class="btn btn-secondary">Cancel</a>
<?php endif; ?>
</form>
</div>
</div>
<h2>Existing Payments</h2>
<table class="table table-striped">
<thead>
<tr><th>Payment ID</th><th>Member</th><th>Amount</th><th>Payment Date</th><th>Actions</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['payment_id']; ?></td>
<td><?php echo $row['member_first'] . ' ' . $row['member_last']; ?></td>
<td><?php echo $row['amount']; ?></td>
<td><?php echo $row['payment_date']; ?></td>
<td>
<a href="?edit_id=<?php echo $row['payment_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<form method="post" style="display:inline;">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">
<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment?')">Delete</button>
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
