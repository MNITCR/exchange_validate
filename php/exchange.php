<?php
    // =================Exchange=====================
    if (isset($_POST["Exchange"])) {
        $transactions = $_POST['transactions'];
        $selectedPlanText = $_POST['flexRadioDefault'];
        $Exchange_Blade_hidden = $_POST['Exchange_Blade_hidden'];
        $main_bland_second = intval($_POST['exchange-input-second']);
        $ex_second = intval($_POST["Exchange_Blade_second"]);
        $id_mbt = $_POST['id_mbt'];
        $id_reg = $_POST['id_reg'];

        // print($id_mbt . $id_reg);
        print($Exchange_Blade_hidden);

        if($transactions > 0){
            // Check if the user has already exchanged before
            $checkPreviousExchangeQuery = "SELECT exchange_bland FROM topup WHERE register_id = $id_reg";
            $previousExchangeResult = mysqli_query($conn, $checkPreviousExchangeQuery);

            if (mysqli_num_rows($previousExchangeResult) > 0) {
                // User has exchanged before, retrieve the previous exchange value
                $row = mysqli_fetch_assoc($previousExchangeResult);
                $previousExchangeValue = intval($row['exchange_bland']);

                // Calculate the new exchange value
                $ex_second += $previousExchangeValue;

                // Update the exchange_bland column
                $updateExchangeQuery = "UPDATE topup SET exchange_bland = $ex_second WHERE register_id = $id_reg";
                if (!mysqli_query($conn, $updateExchangeQuery)) {
                    echo "<script>alert('Error updating exchange value: " . mysqli_error($conn) . "');</script>";
                }
            } else {
                // User has not exchanged before, insert data into topup table
                $insertQuery = "INSERT INTO topup (exchange_bland, created_at, register_id) VALUES ('$ex_second', now(), '$id_reg')";
                if (!mysqli_query($conn, $insertQuery)) {
                    echo "<script>alert('Error inserting data: " . mysqli_error($conn) . "');</script>";
                }
            }

            // Calculate the new main_bland value after the exchange
            $newMainBland = $main_bland_second - $transactions;
            // print($newMainBland);

            // Check if the new main_bland value is greater than or equal to 0
            if ($newMainBland >= 0) {
                // Update main blade value
                $updateMainBladeQuery = "UPDATE main_bland_table SET main_bland = $newMainBland WHERE id_mb = $id_mbt"; // Assuming $id_mbt is the user's ID

                if (mysqli_query($conn, $updateMainBladeQuery)) {
                    // Insert data into history_exchange table
                    $insertHistoryQuery = "INSERT INTO history_exchange (transactions, exchange_plan, exchange_data, date, user_id)
                                          VALUES ('$transactions', '$selectedPlanText', '$Exchange_Blade_hidden', now(), '$id_reg')";

                    if (mysqli_query($conn, $insertHistoryQuery)) {
                        echo "<script>alert('Exchange successful.');window.location.href=('./dashboard.php');</script>";
                    } else {
                        echo "<script>alert('Error inserting into history_exchange: " . mysqli_error($conn) . "');</script>";
                    }
                    echo "<script>alert('Exchange successful.');window.location.href=('dashboard.php');</script>";

                } else {
                    echo "<script>alert('Error updating main blade: " . mysqli_error($conn) . "');</script>";
                }

                // echo "<script>alert('Exchange successful.');window.location.href=('dashboard.php');</script>";
            } else {
                echo "<script>alert('You do not have enough main blade for this exchange.');</script>";
            }
        }
        else {
            echo "<script>alert('You transactions must be  greater than 0 !!!');</script>";
        }
        // Close the database connection
        mysqli_close($conn);
    }
?>
