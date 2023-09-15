<?php
    include "../links/link.php";
    include '../conn.php';

    if (isset($_POST["submit"])) {
        // Get user input
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $phoneNumber = mysqli_real_escape_string($conn, $_POST["phonenumber"]);
        $password = $_POST["password"];
        $idNumber = generateUniqueId();

        // Check if the phone number already exists in the database
        $checkQuery = "SELECT * FROM register WHERE phone_number = '$phoneNumber'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // Phone number already exists, show an error message or handle it as needed
            echo "<script>swal('Phone number already exists. Please choose a different one.');</script>";
        } else {
            // Insert new user data into the database
            $insertQuery = "INSERT INTO register (name, phone_number, password, id_number,created_at) VALUES ('$name', '$phoneNumber', '$password','$idNumber',now())";

            if (mysqli_query($conn, $insertQuery)) {
                $_SESSION["user_phonenumber"] = $phoneNumber;
                // Registration successful, you can redirect or display a success message

                echo "<script>
                swal({
                    title: 'Good job!',
                    text: 'Registration successful.',
                    icon: 'success',
                }).then(function() {
                    window.location.href = '../index.php';
                });
                </script>";
            } else {
                // Handle the case where the insert query fails
                echo "<script>swal('Error registering user: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
    // Function to generate a unique ID (you can modify this as needed)
    function generateUniqueId() {
        // Generate a unique ID using a combination of time and random number
        return uniqid() . rand(1000, 9999);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

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
    </style>
</head>
<body class="bg-dark">
    <div class="container text-white">
        <div class="d-flex justify-content-center align-items-center flex-row position-relative" style="height: 100vh;">
            <form method="POST" action="" class="col-lg-5">
                <div class="mb-3">
                    <h2 class="text-center fw-bold">REGISTER FORM</h2>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Full name</label>
                    <input type="text" name="name" class="form-control" id="name" required autofocus oninput="validateName(this)">
                </div>

                <!-- phone number -->
                <div class="mb-3">
                    <label for="phonenumber" class="form-label">Phone number</label>
                    <input type="text" name="phonenumber" class="form-control" id="phonenumber" required autofocus oninput="validatePhone(this)">
                    <div class="input-group-append d-none">
                        <span class="input-group-text">
                            <i class="fas fa-check text-success d-none"></i>
                        </span>
                    </div>
                </div>

                <!-- password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="password" required autofocus>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                        </button>
                    </div>
                    <input type="hidden" name="id_number" class="form-control" id="id_number">
                </div>

                <button type="submit" name="submit"  class="btn btn-primary">Register</button>
            </form>
            <div class="position-absolute d-flex justify-content-end" style="right: 0rem;bottom: 2rem">
                <div class="main-register" title="login">
                    <p class="text-uppercase bg-info p-2 px-4 rounded" onclick="location.href=('../index.php')"><span class="text-register">login account</span> <i class="fas fa-sign-in-alt"></i></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        let registerPass = document.getElementById('phonenumber').value;
        // // Function to validate the "Name" input
        // function validateName(input) {
        //     var nameRegex = /[^A-Za-z\s]/g;
        //     if (nameRegex.test(input.value)) {
        //         input.classList.add('is-invalid');
        //     } else {
        //         input.classList.remove('is-invalid');
        //     }
        // }

        // Function to validate the "Name" input
        function validateName(input) {
            var nameRegex = /[^A-Za-z\s]/g;

            // Check if the input is empty
            if (input.value.trim() === '') {
                input.classList.remove('is-valid', 'is-invalid');
                var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
                exclamationIcon.classList.add('d-none');
                return;
            }

            if (input.value.length === 6 || input.value.length > 6 && input.value.length <= 30 || /\d/.test(input.value)) {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
                var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
                exclamationIcon.classList.remove('d-none');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
                exclamationIcon.classList.add('d-none');
            }
        }


        // Function to validate the "Phone number" input
        function validatePhone(input) {
            var phoneRegex = /^[0-9]+$/; // Match only digits

            // Check if the input is empty
            if (input.value.trim() === '') {
                input.classList.remove('is-valid', 'is-invalid');
                var checkIcon = input.nextElementSibling.querySelector('.fa-check');
                checkIcon.classList.add('d-none');
                var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
                exclamationIcon.classList.add('d-none');
                return;
            }

            if (input.value.length === 10 && phoneRegex.test(input.value)) {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
                var checkIcon = input.nextElementSibling.querySelector('.fa-check');
                checkIcon.classList.remove('d-none');
                var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
                exclamationIcon.classList.add('d-none');
            } else if (input.value.length > 10 || !phoneRegex.test(input.value)) {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                var checkIcon = input.nextElementSibling.querySelector('.fa-check');
                checkIcon.classList.add('d-none');
                var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
                exclamationIcon.classList.remove('d-none');
            }
            else {
                input.classList.remove('is-valid', 'is-invalid');
                var checkIcon = input.nextElementSibling.querySelector('.fa-check');
                checkIcon.classList.add('d-none');
                var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
                exclamationIcon.classList.add('d-none');
            }
        }



        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
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
    </script>
</body>
</html>

