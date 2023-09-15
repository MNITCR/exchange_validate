<?php
    include "conn.php";
    // session_start();
    // // Check if the form is submitted
    // if (isset($_POST["submit"])){
    //     // Get user input
    //     $phoneNumber = $_POST["phonenumber"];
    //     $password = trim($_POST["password"]);

    //     // Query the database for the user
    //     $query = "SELECT * FROM register WHERE phone_number = '$phoneNumber'";
    //     $result = mysqli_query($conn, $query);

    //     if ($result) {
    //         // Check if a matching user is found
    //         if (mysqli_num_rows($result) == 1) {
    //             $row = mysqli_fetch_assoc($result);
    //             $hashedPassword = $row["password"];

    //             // Check if "Remember Me" is selected
    //             $rememberMe = isset($_POST["remember"]) ? true : false;

    //             if ($rememberMe) {
    //                 setcookie("remember_phone_number", $phoneNumber, time() + (7 * 24 * 3600), "/");
    //                 setcookie("remember_password", $password, time() + (7 * 24 * 3600), "/");
    //             } else {
    //                 setcookie("remember_phone_number", "", time() - 3600, "/");
    //                 setcookie("remember_password", "", time() - 3600, "/");
    //             }


    //             if ($password === $hashedPassword) {
    //                 $_SESSION["user_phonenumber"] = $phoneNumber;
    //                 // $_SESSION["user_id_number"] = $idNumber;
    //                 echo "<script>
    //                 alert('Login successful. Welcome!');
    //                 window.location.href=('dashboards/dashboard.php');
    //                 </script>";
    //             } else {
    //                 echo "<script>
    //                 alert('Incorrect password. Please try again.');
    //                 window.location.href=('index.php');
    //                 </script>";
    //             }
    //         } else {
    //             echo "<script>
    //             alert('User not found. Please register.');
    //             window.location.href=('index.php');
    //             </script>";
    //         }
    //     } else {
    //         // Query execution error
    //         echo "Error: " . mysqli_error($conn);
    //     }
    // }



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
                    alert('Login successful. Welcome!');
                    window.location.href=('dashboards/dashboard.php');
                    </script>";
                } else {
                    echo "<script>
                    alert('Incorrect password. Please try again.');
                    window.location.href=('index.php');
                    </script>";
                }
            } else {
                echo "<script>
                alert('User not found. Please register.');
                window.location.href=('index.php');
                </script>";
            }
        } else {
            // Query execution error
            echo "Error: " . mysqli_error($conn);
        }
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
</style>

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
                    <input type="password" name="password" class="form-control" id="password" required autofocus
                    value="<?php echo isset($_COOKIE['remember_password']) ? $_COOKIE['remember_password'] : ''; ?>">
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
