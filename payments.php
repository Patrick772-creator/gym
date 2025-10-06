<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_id = $_POST['payment_id'];
    $member_id = $_POST['member_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];

    $sql = "INSERT INTO Payments (payment_id, member_id, amount, payment_date) VALUES ('$payment_id', '$member_id', '$amount', '$payment_date')";
    if ($conn->query($sql) === TRUE) {
        echo "New payment added successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

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
<h2>Add New Payment</h2>
<form method="post" class="mb-4">
<div class="mb-3">
<label for="payment_id" class="form-label">Payment ID</label>
<input type="number" class="form-control" name="payment_id" required>
</div>
<div class="mb-3">
<label for="member_id" class="form-label">Member ID</label>
<input type="number" class="form-control" name="member_id" required>
</div>
<div class="mb-3">
<label for="amount" class="form-label">Amount</label>
<input type="number" step="0.01" class="form-control" name="amount" required>
</div>
<div class="mb-3">
<label for="payment_date" class="form-label">Payment Date</label>
<input type="date" class="form-control" name="payment_date" required>
</div>
<button type="submit" class="btn btn-primary">Add Payment</button>
</form>
<h2>Existing Payments</h2>
<table class="table table-striped">
<thead>
<tr><th>Payment ID</th><th>Member</th><th>Amount</th><th>Payment Date</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['payment_id']; ?></td>
<td><?php echo $row['member_first'] . ' ' . $row['member_last']; ?></td>
<td><?php echo $row['amount']; ?></td>
<td><?php echo $row['payment_date']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php $conn->close(); ?>
</body>
</html>
