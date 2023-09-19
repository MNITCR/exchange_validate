<?php
    // =================Edit Profile=====================
    if (isset($_POST["edit_submit"])) {
        // Get updated user data from the form
        $updatedName = mysqli_real_escape_string($conn, $_POST["name"]);
        $updatedPhone = mysqli_real_escape_string($conn, $_POST["phone"]);

        // Check if any changes were made
        $query = "SELECT * FROM register WHERE phone_number = '$user_phone'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $userData = mysqli_fetch_assoc($result);
            $currentName = $userData["name"];
            $currentPhone = $userData["phone_number"];

            if ($updatedName === $currentName && $updatedPhone === $currentPhone) {
                // No changes were made
                echo "<script>
                    swal('No changes were made. Please insert new data.');
                </script>";

            } else {
                // Check if the phone number is updated
                $isPhoneNumberUpdated = ($updatedPhone !== $user_phone);

                // Check if the new phone number already exists in the database
                $checkDuplicateQuery = "SELECT * FROM register WHERE phone_number = '$updatedPhone'";
                $checkDuplicateResult = mysqli_query($conn, $checkDuplicateQuery);

                if (mysqli_num_rows($checkDuplicateResult) > 0 && $isPhoneNumberUpdated) {
                    // Phone number already in use
                    echo "<script>
                        swal('The phone number is already in use. Please choose a different one.');
                    </script>";
                } else {
                    // Update user data in the database
                    $updateQuery = "UPDATE register SET
                                    name = '$updatedName',
                                    phone_number = '$updatedPhone',
                                    updated_at = NOW()
                                    WHERE phone_number = '$user_phone'";

                    if (mysqli_query($conn, $updateQuery)) {
                        // Data updated successfully
                        if ($isPhoneNumberUpdated) {
                            // Phone number is updated, redirect to index.php
                            echo "<script>
                                swal({
                                    title: 'Good job!',
                                    text: 'Update successful. Please login again.',
                                    icon: 'success',
                                }).then(function() {
                                    window.location.href = '../index.php';
                                });
                            </script>";
                        } else {
                            // Other data updated, redirect to dashboard.php
                            echo "<script>
                                swal({
                                    title: 'Good job!',
                                    text: 'Update successful',
                                    icon: 'success',
                                }).then(function() {
                                    window.location.href = 'dashboard.php';
                                });
                            </script>";

                        }
                        mysqli_close($conn);
                    } else {
                        // Handle the case where the update fails
                        echo "Error updating user data: " . mysqli_error($conn);
                    }
                }
            }
        } else {
            // Handle the case where user data retrieval fails
            echo "Error retrieving user data: " . mysqli_error($conn);
        }
    }
?>
