function checkLogin() {
    fetch('session_check.php')
        .then(response => response.json())
        .then(data => {
            if (!data.isLoggedIn) {
                alert("You must log in to access this page.");
                window.location.href = 'index.php'; // Redirect to login page
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = 'index.php'; // Redirect on error
        });
}

// Call the function on page load
checkLogin();
