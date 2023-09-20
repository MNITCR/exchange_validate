<?php
    include "links/link.php";
    include "conn.php";
    session_start();
    // Check if the form is submitted
    if (isset($_POST["submit"])) {
        // Get user input
        $phoneNumber = $_POST["phonenumber"];
        $password = trim($_POST["password"]);

        // Query the database for the user
        $query = "SELECT * FROM register WHERE phone_number = '$phoneNumber'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Check if a matching user is found
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $hashedPassword = $row["password"];

                // Check if "Remember Me" is selected
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

                    // Check if the date is correct
                    $currentDate = date("Y-m-d");
                    $lastLoginDate = $row["last_login_date"]; // Assuming you have a column for last login date in your database

                    if ($lastLoginDate != $currentDate) {
                        // Date is incorrect, reset topup_activity
                        $resetTopupQuery = "UPDATE main_bland_table SET topup_activity = 0 WHERE register_id = '{$row['id']}'";
                        mysqli_query($conn, $resetTopupQuery);
                        // print($lastLoginDate);
                    }

                    // Update the last login date
                    $updateLastLoginQuery = "UPDATE register SET last_login_date = '$currentDate' WHERE id = '{$row['id']}'";
                    mysqli_query($conn, $updateLastLoginQuery);
                    // print("Success");

                    echo "<script>
                        swal({
                            title: 'Welcome!',
                            text: 'Login successful.',
                            icon: 'success',
                        }).then(function() {
                            window.location.href = 'dashboards/dashboard.php';
                        });
                    </script>";
                } else {
                    echo "<script>
                        swal({
                            text: 'Incorrect password. Please try again.',
                            icon: 'warning',
                        }).then(function() {
                            window.location.href = 'index.php';
                        });
                    </script>";
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
    }
?>
