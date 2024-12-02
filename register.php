<?php
include 'config.php';

$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $success = true; // Set success flag to true
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, input, button {
            margin-bottom: 15px;
        }
        input, button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
        }
        a {
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }
        .success-message {
            text-align: center;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>

        <!-- Success message section -->
        <div id="successMessage" class="success-message">
            <p>Registration successful!</p>
            <a href='index.php'>Login here</a>
        </div>

        <!-- Registration form -->
        <form id="registrationForm" method="POST" <?php if ($success) echo 'style="display:none;"'; ?>>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Register</button>
        </form>
    </div>

    <script>
        // JavaScript to handle form hiding and showing success message
        <?php if ($success): ?>
            document.getElementById('successMessage').style.display = 'block';
        <?php endif; ?>
    </script>
</body>
</html>
