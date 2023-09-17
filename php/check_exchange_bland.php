<?php
    include_once '../conn.php';
    session_start();
    // Check exchange_bland for the user (replace with your actual query)
    $user_id = $_SESSION["user_id"]; // Assuming you have a user session
    // print($user_id);
    $query = "SELECT exchange_bland FROM topup WHERE register_id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $exchange_bland = intval($row['exchange_bland']);
        $_SESSION["exchange_bland"] = $exchange_bland;
        echo json_encode(['exchange_bland' => $exchange_bland]);
    } else {
        // Handle the database error
        echo json_encode(['exchange_bland' => -1]); // Return a negative value to indicate an error
    }
?>
