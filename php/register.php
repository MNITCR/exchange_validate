<?php
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
                mysqli_close($conn);
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
