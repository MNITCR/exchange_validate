const logoutButton = document.getElementById("logoutButton");

// Add a click event listener to the button
logoutButton.addEventListener('click', function() {
    $('#profileModal').modal('hide');
    swal({
        text: "Are you sure you want to logout?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
                swal("Your logout successfully!", {
                icon: "success",
            }).then(() => {
                window.location.href = '../index.php';
            });
        } else {
            // swal("Your imaginary file is safe!");
        }
    });
});


