<?php
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
