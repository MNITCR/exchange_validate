<?php
    if (isset($_POST["SaveResetPassword"])) {
        $phoneNumber = $_POST["PhonenumberReset"];
        $newPassword = $_POST["newPassword"];

        // Check if the phone number matches the cookie value
        $cookiePhoneNumber = isset($_COOKIE['remember_phone_number']) ? $_COOKIE['remember_phone_number'] : '';

        if ($phoneNumber !== $cookiePhoneNumber) {
            echo "Phone number does not match the cookie value. Access denied.";
            exit;
        }else{
            $query = "UPDATE register SET password = ?, updated_at = now() WHERE phone_number = ?";

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ss", $newPassword, $phoneNumber);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>
                    swal({
                        text: 'Password updated successfully.',
                        icon: 'success',
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                </script>";
            } else {
                echo "Error updating password: " . mysqli_error($conn);
            }
        }
    }
?>

