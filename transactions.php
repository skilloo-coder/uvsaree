<?php
include 'config.php';

// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$username = $_SESSION['username'];

// Fetch the order details for the logged-in user
$sql = "SELECT saree_name, price, order_date FROM orders WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$total_orders = $result->num_rows;
$total_price = 0;
$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
    $total_price += $row['price'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Transactions</title>
  <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .transactions-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
        text-align: center;
        box-sizing: border-box;
        margin: 20px;
    }

    h1 {
        font-size: 28px;
        color: #333;
        margin-bottom: 20px;
    }

    .summary {
        margin-bottom: 20px;
        text-align: left;
    }

    .summary p {
        font-size: 18px;
        color: #555;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .back-link {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        font-size: 16px;
        color: #007bff;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="transactions-container">
    <h1>Your Transactions</h1>

    <div class="summary">
      <p><strong>Total Orders Placed:</strong> <?php echo $total_orders; ?></p>
      <p><strong>Total Amount Spent:</strong> ₹<?php echo number_format($total_price, 2); ?></p>
    </div>

    <table>
      <thead>
        <tr>
          <th>Saree Name</th>
          <th>Price (₹)</th>
          <th>Order Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($total_orders > 0) { ?>
          <?php foreach ($orders as $order) { ?>
            <tr>
              <td><?php echo htmlspecialchars($order['saree_name']); ?></td>
              <td><?php echo number_format($order['price'], 2); ?></td>
              <td><?php echo date("d-m-Y", strtotime($order['order_date'])); ?></td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr>
            <td colspan="3">No orders placed yet.</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <a href="welcome.php" class="back-link">Back to Home</a>
  </div>
</body>
</html>
