<?php
    include '../conn.php';
    session_start();
    $exBld = $_SESSION["exchange_bland"];
    // Debugging: Output the received POST data


    if (isset($_POST["exchangeBland"])) {
        $newExchangeBland = intval($_POST["exchangeBland"]);

        // Update the exchange_bland value in the database (replace with your actual query)
        $user_id = $_SESSION["user_id"]; // Assuming you have a user session

        // Assuming you have a user session
        $updateQuery = "UPDATE topup SET exchange_bland = '$newExchangeBland' WHERE register_id = $user_id";

        if (mysqli_query($conn, $updateQuery)) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "invalid";
    }



?>



