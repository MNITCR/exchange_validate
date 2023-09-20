$(document).ready(function() {
    var countdownInterval; // Define a variable to hold the countdown interval

    $("#getOTP_btn").click(function() {
        var button = $("#getOTP_btn");
        var countdown = 25;

        // Clear the previous countdown interval (if any)
        clearInterval(countdownInterval);

        // Send an AJAX request to generate_otp.php
        $.ajax({
            type: "POST",
            url: "php/generate_otp.php",
            data: $("#resetForm").serialize(),
            dataType: "json",
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    $("#verify_OTP").val(response.otp);
                    alert("OTP generated: " + response.otp);
                    // Get the value of the OTP from the AJAX response
                    var generatedOTP = response.otp;

                    // Set a cookie with the OTP value
                    document.cookie = "otpCode=" + generatedOTP;

                    // Log the value of the verify_OTP input field
                    console.log("Verify OTP Value:", generatedOTP);

                    if (generatedOTP.trim() !== "") {
                        // Disable the button and set the initial text
                        button.prop("disabled", true);
                        button.text(countdown + " s");

                        // Start the countdown timer and update button text
                        countdownInterval = setInterval(function() {
                            countdown--;

                            if (countdown <= 0) {
                                // Re-enable the button and reset text
                                button.prop("disabled", false);
                                button.text("GET OTP");
                                clearInterval(countdownInterval);
                            } else {
                                // Update the button text with the remaining time
                                button.text(countdown + " s");
                            }
                        }, 1000);
                    }
                }
            },
            error: function() {
                alert("An error occurred while generating OTP.");
            }
        });
    });
});
