<!DOCTYPE html>
<html lang="en">
<head>
  <title>Gym Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      min-height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      background: linear-gradient(to bottom right, #f4f4f9, #e9ecef);
      color: #333;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h1 {
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: #222;
    }

    nav ul {
      justify-content: center;
      flex-wrap: wrap;
    }

    .nav-link {
      color: #555;
      background-color: #fff;
      border: 1px solid #ddd;
      margin: 0.3rem;
      border-radius: 50px;
      transition: all 0.3s;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .nav-link:hover {
      background-color: #198754; /* Bootstrap green */
      color: white;
      border-color: #198754;
      box-shadow: 0 3px 10px rgba(25,135,84,0.3);
    }

    p {
      margin-top: 1.5rem;
      font-size: 1.1rem;
      color: #444;
    }
  </style>
</head>
<body>
  <div class="container text-center">
    <h1>The Grind House Gym</h1>
    <nav class="mb-4">
      <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link" href="members.php">Members</a></li>
        <li class="nav-item"><a class="nav-link" href="trainers.php">Trainers</a></li>
        <li class="nav-item"><a class="nav-link" href="facilities.php">Facilities</a></li>
        <li class="nav-item"><a class="nav-link" href="sessions.php">Sessions</a></li>
        <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
        <li class="nav-item"><a class="nav-link" href="payments.php">Payments</a></li>
        <li class="nav-item"><a class="nav-link" href="memberships.php">Memberships</a></li>
      </ul>
    </nav>
    <p>Welcome to the Gym Management System.</p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
