<?php
    include "links/link.php";
    include "conn.php";
    include "php/reset_password.php";
    session_start();

    if (isset($_POST["submit"])) {
        $phoneNumber = $_POST["phonenumber"];
        $password = trim($_POST["password"]);

        $query = "SELECT * FROM register WHERE phone_number = '$phoneNumber'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $hashedPassword = $row["password"];

                $rememberMe = isset($_POST["remember"]) ? true : false;

                if ($rememberMe) {
                    setcookie("remember_phone_number", $phoneNumber, time() + (7 * 24 * 3600), "/");
                    setcookie("remember_password", $password, time() + (7 * 24 * 3600), "/");
                } else {
                    setcookie("remember_phone_number", "", time() - 3600, "/");
                    setcookie("remember_password", "", time() - 3600, "/");
                }

                if ($password === $hashedPassword) {
                    $_SESSION["user_phonenumber"] = $phoneNumber;

                    // Calculate the date 7 days ago
                    $sevenDaysAgo = date("Y-m-d", strtotime("-7 days"));

                    // print($sevenDaysAgo);
                    // Check for top-ups exactly 7 days ago in main_bland_table
                    $checkTopupQueryMbt = "SELECT * FROM main_bland_table WHERE register_id = '{$row['id']}' AND topup_date = '$sevenDaysAgo'";
                    $topupResultMbt = mysqli_query($conn, $checkTopupQueryMbt);

                    if (mysqli_num_rows($topupResultMbt) > 0) {
                        // Update main_bland in main_bland_table to 0
                        $updateMainBlandQueryMbt = "UPDATE main_bland_table SET main_bland = 0 WHERE register_id = '{$row['id']}' AND topup_date = '$sevenDaysAgo'";
                        mysqli_query($conn, $updateMainBlandQueryMbt);

                        // Update exchange_bland and data_use in topup table to 0
                        $updateTopupQuery = "UPDATE topup SET exchange_bland = 0, data_use = 0 WHERE register_id = '{$row['id']}'";
                        mysqli_query($conn, $updateTopupQuery);
                    }

                    $currentDate = date("Y-m-d");
                    $lastLoginDate = $row["last_login_date"];

                    if ($lastLoginDate != $currentDate) {
                        // Update main_bland in main_bland_table
                        $updateMainBlandQuery = "UPDATE main_bland_table SET main_bland = 0 WHERE register_id = '{$row['id']}'";
                        mysqli_query($conn, $updateMainBlandQuery);
                    }

                    $updateLastLoginQuery = "UPDATE register SET last_login_date = '$currentDate' WHERE id = '{$row['id']}'";
                    mysqli_query($conn, $updateLastLoginQuery);

                    // Redirect to the dashboard upon successful login
                    header("Location: dashboards/dashboard.php");
                    exit;
                } else {
                    // Increment login attempts and check if it's 3 or more
                    if (!isset($_SESSION["login_attempts"])) {
                        $_SESSION["login_attempts"] = 0;
                    } else {
                        $_SESSION["login_attempts"]++;
                    }

                    if ($_SESSION["login_attempts"] >= 4) {
                        // If the user has attempted login 3 or more times, show the "forgot password" modal
                        echo "<script>
                            $(document).ready(function() {
                                $('#forgotPasswordModal').modal('show');
                            });
                        </script>";
                        $_SESSION["login_attempts"] = 0;
                    } else {
                        // If the user has not attempted login 3 times yet, show the "Incorrect password" message
                        echo "<script>
                            swal({
                                text: 'Incorrect password. Please try again.',
                                icon: 'warning',
                            });
                        </script>";
                    }
                }
            } else {
                echo "<script>
                    swal({
                        text: 'User not found. Please register.',
                        icon: 'warning',
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                </script>";
            }
        } else {
            // Query execution error
            echo "Error: " . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
    }else{
        // If the form is not submitted (user refreshes the page), clear login_attempts
        $_SESSION["login_attempts"] = 0;
    }
?>


<?php include "links/link.php";?>
<title>Login</title>

<style>
    .text-register {
        display: none;
        transition: display 1s ease-in;
        transition-delay: 0.5s;
    }
    .main-register:hover .text-register {
        transition-delay: 0s;
        display: inline;
    }
    .main-register:hover {
        cursor: pointer;
    }
    input#captcha{
        background-image: url(imgCaptcha/captcha-bg.png);
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>

<!-- form login -->
<div class="bg-dark">
    <div class="container text-white">
        <div class="d-flex justify-content-center align-items-center flex-row position-relative" style="height: 100vh;">
            <form method="POST" action="" class="col-lg-5">
                <div class="mb-3">
                    <h2 class="text-center fw-bold">LOGIN FORM</h2>
                </div>
                <div class="mb-3">
                    <label for="phonenumber" class="form-label">Phone number</label>
                    <input type="text" name="phonenumber" class="form-control" id="phonenumber" required autofocus
                    value="<?php echo isset($_COOKIE['remember_phone_number']) ? $_COOKIE['remember_phone_number'] : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="password" required autofocus
                        value="<?php echo isset($_COOKIE['remember_password']) ? $_COOKIE['remember_password'] : ''; ?>">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-radius: 0px 5px 5px 0px">
                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember"
                    <?php echo isset($_COOKIE['remember_phone_number']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Login</button>
            </form>
            <div class="position-absolute d-flex justify-content-end" style="right: 0rem;bottom: 2rem">
                <div class="main-register" title="register">
                    <p class="text-uppercase bg-info p-2 px-4 rounded" onclick="location.href=('register/register.php')"><span class="text-register">Create account</span> <i class="fas fa-user-plus"></i></p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ForgotPasswordModal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Forgot Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You have exceeded the maximum number of login attempts. Would you like to reset your password?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="resetPassword" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">Reset Password</button>
            </div>
        </div>
    </div>
</div>


<!-- resetPassword -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Forgot Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="resetForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="PhonenumberReset" class="form-label">Phone number</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="PhonenumberReset" name="PhonenumberReset">
                            <button type="button" class="btn btn-outline-secondary" id="getOTP_btn" style="border-radius: 0px 5px 5px 0px">
                                GET OTP
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="verify_OTP" class="form-label">Verify OTP</label>
                        <input type="text" class="form-control" id="verify_OTP" name="verify_OTP">
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="newPassword"  id="newPassword">
                            <button class="btn btn-outline-secondary" type="button" id="NewTogglePassword" style="border-radius: 0px 5px 5px 0px">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> -->
                    <button type="submit" name="SaveResetPassword" class="btn btn-primary" id="SaveResetPassword">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Add an event listener to the input element verify_OTP
        var verify_OTP = document.getElementById('verify_OTP');
        verify_OTP.addEventListener('input', function() {
            validateVerifyOTP(this);
        });

        // Function to validate the "verify_OTP" input
        function validateVerifyOTP(input) {
            var verify_OTPinput = input.value;

            // Check if the input is empty
            if (verify_OTPinput.trim() === '') {
                input.classList.remove('is-valid', 'is-invalid');
                return;
            }


            // Get the OTP from the cookie named "otpCode"
            function getCookie(otpCode) {
                var cookies = document.cookie.split("; ");
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = cookies[i].split("=");
                    if (cookie[0] === otpCode) {
                        return decodeURIComponent(cookie[1]);
                    }
                }
                return null;
            }

            var optSession = getCookie("otpCode");

            console.log("cokie js " +optSession);
            if (verify_OTPinput.length == 6 && verify_OTPinput == optSession) {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid'); // Apply is-invalid class
            }
        }

        // Add an event listener to the input element Phonenumber
        var PhonenumberReset = document.getElementById('PhonenumberReset');
        PhonenumberReset.addEventListener('input', function() {
            validatePhoneReset(this);
        });

        // Function to validate the "Phone number" input
        function validatePhoneReset(input) {
            var phoneRegex = /^[0-9]+$/; // Match only digits

            // Check if the input is empty
            if (input.value.trim() === '') {
                input.classList.remove('is-valid', 'is-invalid');
                return;
            }

            if (input.value.length === 10 && phoneRegex.test(input.value)) {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
            } else if (input.value.length > 10 || !phoneRegex.test(input.value)) {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            }
            else {
                input.classList.remove('is-valid', 'is-invalid');
            }
        }


        // Add an event listener to the input element password
        var PasswordInput = document.getElementById('newPassword');
        PasswordInput.addEventListener('input', function() {
            validateNewPassword(this);
        });


        // Function to validate the "Password" input
        function validateNewPassword(input) {
            var password = input.value;

            // Check if the input is empty
            if (password.trim() === '') {
                input.classList.remove('is-valid', 'is-invalid');
                return;
            }

            var specialCharacterRegex = /[!@#$%^&*()_+|'`:<>\?/\\]/;

            if (password.length > 6 && specialCharacterRegex.test(password)) {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid'); // Apply is-invalid class

            }
        }
        // hide and show password
        document.getElementById('NewTogglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('newPassword');
            const eyeIcon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });


        // Form submission handler
        document.getElementById('SaveResetPassword').addEventListener('click', function (e) {
            // Perform client-side validation before submitting the form
            var verify_OTPCheck = document.getElementById('verify_OTP');
            var PhonenumberResetCheck = document.getElementById('PhonenumberReset');
            var newPasswordCheck = document.getElementById('newPassword');

            validateVerifyOTP(verify_OTPCheck);
            validatePhoneReset(PhonenumberResetCheck);
            validateNewPassword(newPasswordCheck);

            if (
                PhonenumberResetCheck.classList.contains('is-invalid') ||
                PhonenumberReset.value.length !== 10 ||
                newPasswordCheck.classList.contains('is-invalid')||
                verify_OTPCheck.classList.contains('is-invalid')
            ) {
                // Prevent form submission if validation fails
                alert('Validation failed. Please check your inputs.');
                e.preventDefault();
            } else {
                // alert('Validation successful. Form will be submitted.');
            }
        });
    });
</script>


<script>
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
</script>


<script src="js/login.js?<?php echo time(); ?>" type="text/javascript"></script>
