<?php
include 'config.php';

// Start session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Get saree details from the URL (passed from the product page)
if (isset($_GET['saree_name']) && isset($_GET['price'])) {
    $saree_name = $_GET['saree_name'];
    $price = $_GET['price'];
} else {
    echo "Invalid saree details!";
    exit();
}

// Get the logged-in user's username
$username = $_SESSION['username'];

// Handle form submission to insert the order into the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare the SQL query
    $sql = "INSERT INTO orders (saree_name, price, username) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("sds", $saree_name, $price, $username);

    // Execute the statement and check for errors
    if ($stmt->execute()) {
        // Success message after placing the order
        $order_success_message = "Order for '$saree_name' placed successfully!";
        $order_price = "₹$price";
    } else {
        $order_error_message = "Error placing order: " . $stmt->error;
    }
}

// Fetch the average rating for the saree
$average_rating_sql = "SELECT AVG(rating) AS avg_rating FROM feedback WHERE saree_name = ?";
$avg_rating_stmt = $conn->prepare($average_rating_sql);

if ($avg_rating_stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$avg_rating_stmt->bind_param("s", $saree_name);
$avg_rating_stmt->execute();
$avg_rating_result = $avg_rating_stmt->get_result();
$avg_rating_row = $avg_rating_result->fetch_assoc();

$average_rating = $avg_rating_row['avg_rating'] ? round($avg_rating_row['avg_rating'], 2) : 'No ratings yet';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order - <?php echo $saree_name; ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Your CSS code here */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .order-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        text-align: center;
    }

    h1 {
        font-size: 28px;
        color: #333;
        margin-bottom: 15px;
    }

    img {
        width: 100%;
        max-width: 250px;
        margin-bottom: 25px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    p {
        font-size: 18px;
        color: #333;
        margin-bottom: 25px;
    }

    .pay-button, .redirect-button {
        background-color: #4CAF50;
        color: #fff;
        font-size: 18px;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s;
        text-transform: uppercase;
        display: block;
        margin: 15px auto;
        text-decoration: none;
    }

    .pay-button:hover, .redirect-button:hover {
        background-color: #45a049;
        transform: scale(1.05);
    }

    .message {
        font-size: 18px;
        font-weight: 500;
        color: #333;
        margin-bottom: 20px;
    }

    .success-message {
        color: #28a745;
    }

    .error-message {
        color: #dc3545;
    }

    .avg-rating {
        font-size: 20px;
        color: #f39c12;
        margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="order-container">
    <?php if (isset($order_success_message)) { ?>
        <div class="message success-message">
            <?php echo $order_success_message; ?>
        </div>
        <p><strong>Price:</strong> <?php echo $order_price; ?></p>

        <!-- Home and Review buttons -->
        <a href="welcome.php" class="redirect-button">Go to Home</a>
        <a href="feedback.php?saree_name=<?php echo urlencode($saree_name); ?>" class="redirect-button">Review Product</a>

    <?php } elseif (isset($order_error_message)) { ?>
        <div class="message error-message">
            <?php echo $order_error_message; ?>
        </div>
    <?php } else { ?>
        <h1>Order: <?php echo $saree_name; ?></h1>

        <?php
        $image_filename = strtolower(str_replace(' ', '_', $saree_name)) . '.jpeg';
        $image_path = $image_filename;

        if (file_exists($image_path)) {
            echo "<img src='$image_path' alt='$saree_name'>";
        } else {
            echo "<img src='saree_image_placeholder.jpg' alt='Saree Image'>";
        }
        ?>

        <p><strong>Price:</strong> ₹<?php echo $price; ?></p>

        <!-- Average Rating -->
        <div class="avg-rating">
            <strong>Rating:</strong> <?php echo $average_rating; ?> / 5
        </div>

        <form method="POST" action="order.php?saree_name=<?php echo urlencode($saree_name); ?>&price=<?php echo $price; ?>">
            <button type="submit" class="pay-button">Pay ₹<?php echo $price; ?></button>
        </form>
    <?php } ?>
  </div>
</body>
</html>
