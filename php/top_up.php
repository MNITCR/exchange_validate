<?php
    // =================Top up=====================
    if (isset($_POST['top_up'])) {
        $numQr = trim($_POST['numQr']);
        $simNumber = $_COOKIE['simNumber'];
        $id_number = $_POST['id_number'];
        $id = $_POST['rg_id'];

        if (empty($numQr)) {
            echo "<script>alert('This input can\'t be null or empty !!!');</script>";
        } elseif ($simNumber == $numQr) {
            // Check if the num_bland value already exists in the database
            $checkQuery = "SELECT * FROM main_bland_table WHERE num_bland = '$numQr'";
            $checkResult = mysqli_query($conn, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                // num_bland value already exists, show an error message
                echo "<script>alert('This card number is already used. Please choose a different one.');</script>";
            } else {
                // Determine the sv_by value based on the length of num_bland
                $sv_by = '';
                $num_bland_length = strlen($numQr);

                if ($num_bland_length == 15) {
                    $sv_by = 'Smart';
                } elseif ($num_bland_length == 14) {
                    $sv_by = 'Metfone';
                } elseif ($num_bland_length == 10) {
                    $sv_by = 'Cellcard';
                }

                // Check if the phone number already exists in the database
                $checkQuery = "SELECT * FROM main_bland_table WHERE id_number = '$id_number' AND register_id = '$id'";
                $checkResult = mysqli_query($conn, $checkQuery);

                if (mysqli_num_rows($checkResult) > 0) {
                    // Phone number already exists, check topup_activity
                    $row = mysqli_fetch_assoc($checkResult);
                    $topupActivity = intval($row['topup_activity']);
                    $main_bland_AT = intval($row['main_bland']);

                    if ($main_bland_AT >= 3) {
                        // check if main bland have larger 3 $
                        echo "<script>alert('You have reached the topup limit. Cannot top up at this time.');</script>";

                    }else if($topupActivity >= 3) {
                        // User has exceeded topup limit for the day
                        echo "<script>alert('You have reached the topup limit for the day.');</script>";

                    }else {
                        // Increment the main_bland value and topup_activity
                        $updateQuery = "UPDATE main_bland_table SET main_bland = main_bland + 1, num_bland = '$numQr', topup_activity = topup_activity + 1, sv_by = '$sv_by', updated_at = now()  WHERE id_number = '$id_number' AND register_id = '$id'";

                        if (mysqli_query($conn, $updateQuery)) {
                            // Top-up successful, insert data into history_topup table
                            $updateQueryTP = "INSERT INTO history_topup (card_number, service_by, transaction_date, register_id)
                            VALUES ('$numQr', '$sv_by', NOW(), '$id')";

                            if (mysqli_query($conn, $updateQueryTP)) {
                                echo "<script>
                                    swal({
                                        text: 'Top up successful.',
                                        icon: 'success',
                                    }).then(function() {
                                        window.location.href = 'dashboard.php';
                                    });
                                </script>";
                                // echo "<script>alert('Top up successful.');window.location.href=('dashboard.php');</script>";
                            } else {
                                echo "Error recording top-up transaction: " . mysqli_error($conn);
                            }
                        } else {
                            // Handle the case where the update query fails
                            echo "<script>alert('Error Top up: " . mysqli_error($conn) . "');</script>";
                        }
                    }
                } else {
                    // Insert new user data into the database
                    $insertQuery = "INSERT INTO main_bland_table (id_number, main_bland, topup_activity, num_bland, sv_by, register_id, topup_date, created_at) VALUES ('$id_number', 1, 1, '$numQr', '$sv_by', '$id', now() ,now())";

                    if (mysqli_query($conn, $insertQuery)) {
                        // Top-up successful, insert data into history_topup table
                        $insertQueryTP = "INSERT INTO history_topup (card_number, service_by, transaction_date, register_id)
                        VALUES ('$numQr', '$sv_by', NOW(), '$id')";

                        if (mysqli_query($conn, $insertQueryTP)) {
                            echo "<script>
                                swal({
                                    text: 'Top up successful.',
                                    icon: 'success',
                                }).then(function() {
                                    window.location.href = 'dashboard.php';
                                });
                            </script>";
                        } else {
                            echo "Error recording top-up transaction: " . mysqli_error($conn);
                        }
                    } else {
                        // Handle the case where the insert query fails
                        echo "<script>alert('Error Top up: " . mysqli_error($conn) . "');</script>";
                    }
                }
            }
        } else {
            echo "<script>alert('This card number is expired')</script>";
        }
    }
?>
