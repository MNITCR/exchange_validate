<?php
    include_once '../conn.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $phoneNumber = $_POST["PhonenumberReset"];

        // Check if the phone number is empty
        if (empty($phoneNumber)) {
            echo json_encode(["error" => "Please enter a phone number"]);
        }else{
            $queryGetOTP = "SELECT phone_number FROM register WHERE phone_number = ?";
            $stmt = mysqli_prepare($conn, $queryGetOTP);
            mysqli_stmt_bind_param($stmt, "s", $phoneNumber);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    // Phone number exists in the database, generate a 6-letter OTP
                    $otp = substr(str_shuffle("0123456789"), 0, 6);

                    // session_start();
                    // $_SESSION["otp"] = $otp;

                    // Return the OTP as JSON response
                    echo json_encode(["otp" => $otp]);
                } else {
                    echo json_encode(["error" => "Phone number not found in the database"]);
                }
            } else {
                echo json_encode(["error" => "Database query error: " . mysqli_error($conn)]);
            }
        }
    }
?>

