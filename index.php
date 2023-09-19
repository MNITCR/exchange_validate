<?php
    include "links/link.php";
    include "conn.php";
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

        // Close the database connection
        mysqli_close($conn);
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

<script src="js/login.js?<?php echo time(); ?>" type="text/javascript"></script>
