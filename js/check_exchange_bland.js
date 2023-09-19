$(document).ready(function() {
    $("#video-link").click(function(event) {
        event.preventDefault(); // Prevent the default behavior of the link

        // Make an AJAX request to check the exchange_bland column
        $.ajax({
            url: "../php/check_exchange_bland.php", // Replace with the actual URL to your PHP script
            method: "GET",
            dataType: "json",
            success: function(data) {
                if (data.exchange_bland > 0) {
                    // User has enough exchange_bland, proceed to the video page
                    window.location.href = "./video.php";
                } else {
                    // User does not have enough exchange_bland, display an alert
                    swal({
                        text: 'You do not have enough exchange bland for this action!!!',
                        icon: 'error',
                    });
                }
            },
            error: function(xhr, status, error) {
                // Access the error information from the xhr object
                alert("Error: " + xhr.responseText);

                // alert("Status: " + status);
                // alert("Error: " + error);
            }
        });
    });
});
