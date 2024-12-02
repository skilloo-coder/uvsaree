<?php
include 'config.php';

// Start session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Get the logged-in user's username
$username = $_SESSION['username'];

// Get the saree name from the URL
if (isset($_GET['saree_name'])) {
    $saree_name = $_GET['saree_name'];
} else {
    echo "Invalid saree details!";
    exit();
}

// Handle form submission to store feedback in the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    // Insert feedback into the database
    $sql = "INSERT INTO feedback (username, saree_name, rating, review) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $username, $saree_name, $rating, $review);

    if ($stmt->execute()) {
        $feedback_success_message = "Thank you for your feedback!";
    } else {
        $feedback_error_message = "Error submitting feedback: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback for <?php echo $saree_name; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .feedback-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 20px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: none;
        }

        .rating {
            margin-bottom: 20px;
            direction: ltr;
        }

        .rating input {
            display: none;
        }

        .rating label {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
        }

        .rating input:checked ~ label {
            color: #f39c12;
        }

        .rating label:hover,
        .rating label:hover ~ label {
            color: #f39c12;
        }

        .submit-button {
            background-color: #4CAF50;
            color: #fff;
            font-size: 18px;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .submit-button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .message {
            font-size: 18px;
            color: #28a745;
            margin-top: 20px;
        }

        .home-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            font-size: 18px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .home-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Added styles to prevent overlap */
        .home-button, .message {
            display: block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="feedback-container">
        <h1>Feedback for <?php echo $saree_name; ?></h1>

        <?php if (isset($feedback_success_message)) { ?>
            <p class="message"><?php echo $feedback_success_message; ?></p>
            <a href="welcome.php" class="home-button">Go to Home</a>
        <?php } else { ?>
            <form method="POST" action="feedback.php?saree_name=<?php echo urlencode($saree_name); ?>">
             <div class="rating">
                <input type="radio" id="star5" name="rating" value="5" required>
                <label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1">&#9733;</label>
            </div>


                <textarea name="review" placeholder="Write your review here..." required></textarea>

                <button type="submit" class="submit-button">Submit Feedback</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>
