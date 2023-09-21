<?php
    include '../conn.php';
    session_start();

    if (isset($_POST["exchangeBland"]) && isset($_POST['dataUse'])) {
        $newExchangeBland = intval($_POST["exchangeBland"]);
        $dataUse = intval($_POST['dataUse']);
        // Update the exchange_bland value in the database
        $user_id = $_SESSION["user_id"];

        $updateQuery = "UPDATE topup SET exchange_bland = '$newExchangeBland', data_use = data_use + $dataUse   WHERE register_id = $user_id";

        if (mysqli_query($conn, $updateQuery)) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        // echo "invalid";
    }

?>



