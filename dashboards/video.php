<?php
    // session_start();
    // $exBld = $_SESSION["exchange_bland"];
    // print($exBld);
    include "../php/deduct_exchange_bland.php";
    $user_id = $_SESSION["user_id"];
    $query = "SELECT * FROM topup WHERE register_id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $topupData = mysqli_fetch_assoc($result);
            print_r($topupData);
            // echo($topupData["exchange_bland"]);
        }else{

        }
    }else {
        echo "Error: " . mysqli_error($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../links/link.php";?>
    <title>Video</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style="position: fixed;z-index: 9999;width: 100%">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="./video.php">Video</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active">Exchange Bland Count: <input type="text" name="exchange-bland-count" id="exchange-bland-count" value=""></input></a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            </div>
        </div>
    </nav>

    <!-- card video -->
    <div class="container-fluid bg-dark text-white">
        <div class="container d-flex flex-column justify-content-center" style="padding-top: 7rem;">
            <div class="mb-5">
                <h1 class="text-center">Album Video</h1>
            </div>
            <div class="d-flex gap-3 flex-wrap justify-content-center mb-5">
                <!-- video 1 -->
                <div class="card" style="width: 18.9rem;">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item rounded-top" src="../video/Lana Del Rey - Video Games -Lyrics-.mp4"></iframe>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Lana Del Rey - Video Games (Lyrics)</h5>
                        <p class="card-text">Lana Del Rey - Video Games (Lyrics)</p>
                        <button class="btn btn-primary" id="watch-button">Watch</button>
                    </div>
                </div>




            </div>
        </div>
    </div>

    <script>
        // $(document).ready(function() {
        //     $("#watch-button").click(function(event) {
        //         event.preventDefault(); // Prevent the default behavior of the link

        //         // Check if there is enough exchange_bland to deduct
        //         var exchangeBland = <?php echo $exBld; ?>; // Replace with the actual exchange_bland value

        //         console.log(exchangeBland);

        //         if (exchangeBland > 0) {
        //             // Deduct exchange_bland by 1 every second
        //             var interval = setInterval(function() {
        //                 exchangeBland -= 1;
        //                 // Update the UI to show the remaining exchange_bland
        //                 $("#exchange-bland-count").val(exchangeBland);

        //                 // Update the exchange_bland value in the database (via AJAX)
        //                 $.ajax({
        //                     url: "../php/deduct_exchange_bland.php", // Replace with the actual path to your PHP script
        //                     method: "POST",
        //                     data: { exchangeBland: exchangeBland },
        //                     cache: false, // Send the updated value
        //                     success: function(response) {
        //                         response = response.trim(); // Remove white spaces
        //                         console.log("Response from server:", response);
        //                         if (response === "success") {
        //                             // Deduction successful
        //                             if (exchangeBland === 0) {
        //                                 // No more exchange_bland left, disable the button
        //                                 $("#watch-button").attr("disabled", true);
        //                             }
        //                         } else if (response === "error") {
        //                             // Handle the case where deduction fails
        //                             alert("Failed to deduct exchange_bland. Database error occurred.");
        //                         } else {
        //                             // Handle other responses if needed
        //                             alert("Unexpected response from server: " + response);
        //                         }
        //                     },

        //                     error: function() {
        //                         alert("An error occurred while deducting exchange_bland. Please try again later.");
        //                     }
        //                 });
        //             }, 5000); // Repeat every 1 second
        //         } else {
        //             // Not enough exchange_bland, display an alert
        //             alert("You do not have enough exchange_bland to watch.");
        //         }
        //     });
        // });


        // $(document).ready(function() {
        //     var interval; // Declare the interval variable outside the click event handler

        //     $("#watch-button").click(function(event) {
        //         event.preventDefault();

        //         clearInterval(interval); // Clear the previous interval

        //         var exchangeBland = <?php echo $exBld; ?>;

        //         if (exchangeBland > 0) {
        //             interval = setInterval(function() {
        //                 exchangeBland -= 0.5;
        //                 $("#exchange-bland-count").val(exchangeBland);

        //                 $.ajax({
        //                     url: "../php/deduct_exchange_bland.php",
        //                     method: "POST",
        //                     data: { exchangeBland: exchangeBland },
        //                     cache: false,
        //                     success: function(response) {
        //                         response = response.trim();
        //                         console.log("Response from server:", response);
        //                         if (response === "success") {
        //                             if (exchangeBland === 0) {
        //                                 $("#watch-button").attr("disabled", true);
        //                                 clearInterval(interval); // Stop the interval when exchangeBland reaches 0
        //                             }
        //                         } else if (response === "error") {
        //                             alert("Failed to deduct exchange_bland. Database error occurred.");
        //                         } else {
        //                             alert("Unexpected response from server: " + response);
        //                         }
        //                     },
        //                     error: function() {
        //                         alert("An error occurred while deducting exchange_bland. Please try again later.");
        //                     }
        //                 });
        //             }, 5000);
        //         } else {
        //             alert("You do not have enough exchange_bland to watch.");
        //         }
        //     });
        // });


        $(document).ready(function() {
            var interval;
            var isPlaying = false; // Variable to track video state

            $("#watch-button").click(function(event) {
                event.preventDefault();

                clearInterval(interval);

                var exchangeBland = <?php echo $topupData["exchange_bland"]; ?>;

                if (!isPlaying) {
                    // If video is stopped, start playing
                    isPlaying = true;
                    $("#watch-button").text("Stop").addClass("btn-danger");

                    if (exchangeBland > 0) {
                        interval = setInterval(function() {
                            exchangeBland -= 0.5;
                            $("#exchange-bland-count").val(exchangeBland);

                            $.ajax({
                                url: "../php/deduct_exchange_bland.php",
                                method: "POST",
                                data: { exchangeBland: exchangeBland },
                                cache: false,
                                success: function(response) {
                                    response = response.trim();
                                    console.log("Response from server:", response);
                                    if (response === "success") {
                                        if (exchangeBland === 0) {
                                            $("#watch-button").attr("disabled", true);
                                            clearInterval(interval);
                                        }
                                    } else if (response === "error") {
                                        alert("Failed to deduct exchange_bland. Database error occurred.");
                                    } else {
                                        alert("Unexpected response from server: " + response);
                                    }
                                },
                                error: function() {
                                    alert("An error occurred while deducting exchange_bland. Please try again later.");
                                }
                            });
                        }, 1000);
                    } else {
                        alert("You do not have enough exchange_bland to watch.");
                        isPlaying = false; // Reset video state
                        $("#watch-button").text("Watch").removeClass("btn-danger");
                    }
                } else {
                    // If video is playing, stop it
                    isPlaying = false;
                    $("#watch-button").text("Watch").removeClass("btn-danger");
                    clearInterval(interval);
                }
            });
        });




    </script>
</body>
</html>
