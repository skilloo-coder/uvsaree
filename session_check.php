<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    echo json_encode(['isLoggedIn' => true]);
} else {
    echo json_encode(['isLoggedIn' => false]);
}
?>
