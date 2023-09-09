<?php
    include "links/link.php";
    include "conn.php";

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get user input
        $phoneNumber = $_POST["phoneNumber"];
        $password = $_POST["password"];

        // Prepare and execute a SQL query to fetch user data
        $sql = "SELECT * FROM login_table WHERE phone_number = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $phoneNumber);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                // Verify the password
                if (password_verify($password, $row["password"])) {
                    // Password is correct, perform login actions
                    echo "Login successful!";
                } else {
                    // Password is incorrect
                    echo "Incorrect password!";
                }
            } else {
                // User not found
                echo "User not found!";
            }

            $stmt->close();
        }

        $conn->close();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-dark">
    <div class="container text-white">
        <div class="d-flex justify-content-center align-items-center flex-row position-relative" style="height: 100vh;">
            <form method="POST" action="" class="col-lg-5">
                <div class="mb-3">
                    <h2 class="text-center fw-bold">LOGIN FORM</h2>
                </div>
                <div class="mb-3">
                    <label for="phonenumber" class="form-label">Phone number</label>
                    <input type="text" name="phoneNumber" class="form-control" id="phonenumber" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required autofocus>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <div class="position-absolute d-flex justify-content-end" style="right: 0rem;bottom: 2rem">
                <div class="main-register" title="register">
                    <p class="text-uppercase bg-info p-2 px-4 rounded" onclick="location.href=('register/register.php')"><span class="text-register">Create account</span> <i class="fas fa-user-plus"></i></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
