const logoutButton = document.getElementById("logoutButton");

// Add a click event listener to the button
logoutButton.addEventListener('click', function() {
    const com = confirm('Are you sure you want to logout?');
    if (com) {
    // Redirect to the desired URL when the user confirms
    window.location.href = '../index.php';
    }
});


