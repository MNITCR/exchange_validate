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
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <title>Video</title>
    <style>
        .card-text.show-full-text {
        white-space: normal; /* Display the text as normal (not nowrap) */
        overflow: visible; /* Allow the text to overflow the container */
        text-overflow: initial; /* Remove ellipsis */
        }
    </style>
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
                    <a class="nav-link active">Exchange Bland Count: <input type="text" name="exchange-bland-count" id="exchange-bland-count" value="<?php echo $topupData["exchange_bland"]; ?>"></input></a>
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
                <?php
                    $videoFolder = "../video/"; // Replace with the path to your video folder
                    $videoFiles = scandir($videoFolder);

                    // Loop through video files
                    foreach ($videoFiles as $videoFile) {
                        // Check if the file is a video (you can add more file extensions as needed)
                        $allowedExtensions = array("mp4", "avi", "mkv", "mov");
                        $fileExtension = strtolower(pathinfo($videoFile, PATHINFO_EXTENSION));
                        if (in_array($fileExtension, $allowedExtensions)) {
                            $videoTitle = pathinfo($videoFile, PATHINFO_FILENAME);
                            $videoPath = $videoFolder . $videoFile;
                    ?>
                        <div class="card" style="width: 18.9rem;">
                            <div class="embed-responsive embed-responsive-16by9 rounded-top" style="overflow: hidden; position: relative;">
                                <video id="video-<?php echo $videoTitle; ?>" class="embed-responsive-item rounded-top video" src="<?php echo $videoPath; ?>" style="width: 18.9rem;"></video>
                                <div class="text-white full-video" data-video-id="video-<?php echo $videoTitle; ?>" style="position: absolute; bottom: 0.5rem; right: 10px; cursor: pointer;display: none;"><i class="fa-solid fa-expand"></i></div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $videoTitle; ?></h5>
                                <div class="card-text-container d-flex">
                                    <p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?php echo $videoTitle; ?>
                                    </p>
                                    <i class="fa-solid fa-caret-down caret-icon mt-1 text-secondary" style="cursor: pointer;"></i>
                                </div>
                                <button class="btn btn-primary watch-button" id="watch-button-<?php echo $videoTitle; ?>">Watch</button>
                            </div>
                        </div>
                    <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".caret-icon").click(function() {
                var $textElement = $(this).prev(".card-text");
                var $button = $(this).siblings(".watch-button");

                if ($textElement.hasClass("expanded")) {
                    $textElement.removeClass("expanded");
                    $textElement.css("white-space", "nowrap");
                    $textElement.css("overflow", "hidden");
                    $textElement.css("text-overflow", "ellipsis");
                    $button.text("Watch");
                    $(this).removeClass("fa-caret-up").addClass("fa-caret-down");
                } else {
                    $textElement.addClass("expanded");
                    $textElement.css("white-space", "normal");
                    $textElement.css("overflow", "visible");
                    $textElement.css("text-overflow", "clip");
                    $button.text("Hide");
                    $(this).removeClass("fa-caret-down").addClass("fa-caret-up");
                }
            });
        });
    </script>

    <!-- <script>
        $(document).ready(function() {
            // Enable the full-screen button
            var fullScreenButtonDisabled = false;
            // Click event handler for full-video element
            $(".full-video").click(function () {
                // Check if the full-screen button is disabled
                if (fullScreenButtonDisabled) {
                    return; // Do nothing if disabled
                }

                var videoId = $(this).data("video-id");
                var videoElement = document.getElementById(videoId);

                if (videoElement) {
                    toggleFullScreen(videoElement);
                }
            });

            // Function to toggle full-screen mode for a specific video element
            function toggleFullScreen(videoElement) {
                if (!document.fullscreenElement) {
                    if (videoElement.requestFullscreen) {
                        videoElement.requestFullscreen();
                    } else if (videoElement.mozRequestFullScreen) {
                        videoElement.mozRequestFullScreen();
                    } else if (videoElement.webkitRequestFullscreen) {
                        videoElement.webkitRequestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                }
            }

            // Object to store exchange balance for each video
            var exchangeBlandMap = {};
            var currentPlayingVideo = null;
            var interval = null;

            // button start and stop video
            $(document).on('click', '.watch-button', function(event) {
                var buttonId = event.currentTarget.id;
                var videoId = buttonId.replace('watch-button-', ''); // Extract the video ID
                var videoElement = document.getElementById('video-' + videoId);

                event.preventDefault();

                // Clear the interval for the previous video
                clearInterval(interval);

                if (!exchangeBlandMap.hasOwnProperty(videoId)) {
                    // If no exchange balance exists for this video, initialize it
                    exchangeBlandMap[videoId] = <?php echo $topupData["exchange_bland"]; ?>;
                }

                var exchangeBland = exchangeBlandMap[videoId];
                var inputValueCount = $("#exchange-bland-count").val();
                exchangeBland = inputValueCount;

                // updateExchangeBland in php
                function updateExchangeBland(){
                    $.ajax({
                        url: '../php/deduct_exchange_bland.php',
                        method: 'POST',
                        data: { exchangeBland: inputValueCount },
                        cache: false,
                        success: function(response) {
                            response = response.trim();
                            console.log('Response from server:', response);
                            if (response === 'success') {
                                if (inputValueCount <= 0) {
                                    swal({
                                        text: "You don't have enough data to continue.",
                                        icon: "error",
                                    });
                                    stopAllVideos();
                                }

                                // Check if inputValueCount is 100
                                else if (inputValueCount === 100) {
                                    stopAllVideos();
                                    swal({
                                        text: "Your data is only 100MB left!",
                                        icon: "warning",
                                    }).then((willDelete) => {
                                        if (willDelete) {
                                            stopAllVideos();
                                        }
                                    });
                                }
                                else{
                                    console.log(inputValueCount);
                                }
                            } else if (response === 'error') {
                                alert('Failed to deduct exchange_bland. Database error occurred.');
                            } else {
                                alert('Unexpected response from server: ' + response);
                            }
                        },
                        error: function() {
                            alert('An error occurred while deducting exchange_bland. Please try again later.');
                        }
                    });
                }

                // Function to check data status and prompt the user
                function checkDataStatus(inputValueCount) {
                    if (inputValueCount === 100) {
                        clearInterval(interval);

                        swal({
                            text: "Your data is only 100MB left!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                                // User clicked "OK," continue playing the video
                                interval = setInterval(function() {
                                    inputValueCount -= 1;
                                    $('#exchange-bland-count').val(inputValueCount);
                                    checkDataStatus(inputValueCount);

                                    if (inputValueCount <= 0) {
                                        // Stop video and clear interval if inputValueCount goes below 0
                                        clearInterval(interval);
                                        videoElement.pause();
                                        inputValueCount = 0;
                                        $('#exchange-bland-count').val(inputValueCount);
                                    }
                                }, 1000);
                            } else {
                                // User clicked "Cancel," stop playing the video and clear interval
                                videoElement.pause();
                                inputValueCount = 0;
                                $('#exchange-bland-count').val(inputValueCount);
                                currentPlayingVideo = null;
                                clearInterval(interval);
                                $(this).text('Watch').removeClass('btn-danger');
                            }
                        });
                    }
                }

                if (!currentPlayingVideo) {
                    // If no video is currently playing, start this one
                    currentPlayingVideo = videoElement;
                    videoElement.play();
                    $(this).text('Stop').addClass('btn-danger');

                    if (inputValueCount > 0) {
                        interval = setInterval(function() {
                            inputValueCount -= 1;
                            $('#exchange-bland-count').val(inputValueCount);
                            // Update the exchange_bland value periodically
                            updateExchangeBland();
                            checkDataStatus(inputValueCount);

                            // Enable the full-screen button
                            fullScreenButtonDisabled = true;
                        }, 1000);
                    } else {
                        swal({
                            text: "You don't have enough data to continue.",
                            icon: "error",
                        });
                        stopAllVideos();
                    }
                } else if (currentPlayingVideo === videoElement) {
                    // If this video is currently playing, stop it
                    videoElement.pause();
                    currentPlayingVideo = null;
                    $(this).text('Watch').removeClass('btn-danger');
                    clearInterval(interval);

                    // Enable the full-screen button
                    fullScreenButtonDisabled = false;
                } else {
                    // If another video is currently playing, stop it and start this one
                    currentPlayingVideo.pause();
                    currentPlayingVideo = videoElement;
                    videoElement.play();
                    $('.watch-button').not(this).text('Watch').removeClass('btn-danger');
                    $(this).text('Stop').addClass('btn-danger');

                    // Start a new interval for the currently playing video
                    if (inputValueCount > 0) {
                        interval = setInterval(function() {
                            inputValueCount -= 1;
                            $('#exchange-bland-count').val(inputValueCount);
                            // Update the exchange_bland value periodically
                            updateExchangeBland();
                            checkDataStatus(inputValueCount);

                            // Enable the full-screen button
                            fullScreenButtonDisabled = false;
                        }, 1000);
                    } else {
                        swal({
                            text: "You don't have enough data to continue.",
                            icon: "error",
                        });
                        stopAllVideos();
                    }
                }
            });

            // Function to stop all videos
            function stopAllVideos() {
                $('.video').each(function() {
                    this.pause();
                });
                currentPlayingVideo = null;
                $('.watch-button').text('Watch').removeClass('btn-danger');
                clearInterval(interval);

                // Enable the full-screen button
                fullScreenButtonDisabled = false;
            }
        });
    </script> -->


    <script>
        $(document).ready(function() {

            // Click event handler for full-video element
            $(".full-video").click(function () {

                var videoId = $(this).data("video-id");
                var videoElement = document.getElementById(videoId);

                if (videoElement) {
                    toggleFullScreen(videoElement);
                }
            });

            // Function to toggle full-screen mode for a specific video element
            function toggleFullScreen(videoElement) {
                if (!document.fullscreenElement) {
                    if (videoElement.requestFullscreen) {
                        videoElement.requestFullscreen();
                    } else if (videoElement.mozRequestFullScreen) {
                        videoElement.mozRequestFullScreen();
                    } else if (videoElement.webkitRequestFullscreen) {
                        videoElement.webkitRequestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                }
            }

            // Object to store exchange balance for each video
            var exchangeBlandMap = {};
            var currentPlayingVideo = null;
            var interval = null;

            // button start and stop video
            $(document).on('click', '.watch-button', function(event) {
                var buttonId = event.currentTarget.id;
                var videoId = buttonId.replace('watch-button-', ''); // Extract the video ID
                var videoElement = document.getElementById('video-' + videoId);

                event.preventDefault();

                // Clear the interval for the previous video
                clearInterval(interval);

                if (!exchangeBlandMap.hasOwnProperty(videoId)) {
                    // If no exchange balance exists for this video, initialize it
                    exchangeBlandMap[videoId] = <?php echo $topupData["exchange_bland"]; ?>;
                }

                var exchangeBland = exchangeBlandMap[videoId];
                var inputValueCount = $("#exchange-bland-count").val();
                exchangeBland = inputValueCount;


                 // Hide the full-video elements for all videos
                $('.full-video').hide();

                // Show the full-video element for the clicked video
                var fullVideoElement = $(this).closest('.card').find('.full-video');
                fullVideoElement.show();


                // updateExchangeBland in php
                function updateExchangeBland(dataSubtract){
                    $.ajax({
                        url: '../php/deduct_exchange_bland.php',
                        method: 'POST',
                        data: { exchangeBland: inputValueCount, // Deducted exchange_bland value
                            dataUse: dataSubtract  // Updated data_use value
                        },
                        cache: false,
                        success: function(response) {
                            response = response.trim();
                            console.log('Response from server:', response);
                            if (response === 'success') {
                                if (inputValueCount <= 0) {
                                    swal({
                                        text: "You don't have enough data to continue.",
                                        icon: "error",
                                    });
                                    stopAllVideos();

                                    // Hide the .full-video elements when inputValueCount is less than or equal to 0
                                    $('.full-video').hide();
                                    dataUse = 0;
                                }

                                // Check if inputValueCount is 100
                                else if (inputValueCount === 100) {
                                    stopAllVideos();
                                    swal({
                                        text: "Your data is only 100MB left!",
                                        icon: "warning",
                                    }).then((willDelete) => {
                                        if (willDelete) {
                                            stopAllVideos();
                                        }
                                    });
                                }
                                else{
                                    console.log(inputValueCount);
                                }
                            } else if (response === 'error') {
                                alert('Failed to deduct exchange_bland. Database error occurred.');
                            } else {
                                alert('Unexpected response from server: ' + response);
                            }
                        },
                        error: function() {
                            alert('An error occurred while deducting exchange_bland. Please try again later.');
                        }
                    });
                }

                // Function to check data status and prompt the user
                function checkDataStatus(inputValueCount) {
                    if (inputValueCount === 100) {
                        clearInterval(interval);

                        swal({
                            text: "Your data is only 100MB left!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                                // User clicked "OK," continue playing the video
                                interval = setInterval(function() {
                                    inputValueCount -= 1;
                                    $('#exchange-bland-count').val(inputValueCount);
                                    checkDataStatus(inputValueCount);

                                    if (inputValueCount <= 0) {
                                        // Stop video and clear interval if inputValueCount goes below 0
                                        clearInterval(interval);
                                        videoElement.pause();
                                        inputValueCount = 0;
                                        $('#exchange-bland-count').val(inputValueCount);
                                    }
                                }, 1000);
                            } else {
                                // User clicked "Cancel," stop playing the video and clear interval
                                videoElement.pause();
                                inputValueCount = 0;
                                $('#exchange-bland-count').val(inputValueCount);
                                currentPlayingVideo = null;
                                clearInterval(interval);
                                $(this).text('Watch').removeClass('btn-danger');
                            }
                        });
                    }
                }

                if (!currentPlayingVideo) {
                    // If no video is currently playing, start this one
                    currentPlayingVideo = videoElement;
                    videoElement.play();
                    $(this).text('Stop').addClass('btn-danger');

                    if (inputValueCount > 0) {
                        interval = setInterval(function() {
                            inputValueCount -= 1;
                            $('#exchange-bland-count').val(inputValueCount);

                            // Store the subtracted value in a variable
                            var dataSubtract = 1; // You can adjust this value if needed

                            // Update the exchange_bland value periodically
                            updateExchangeBland(dataSubtract);
                            checkDataStatus(inputValueCount);

                        }, 1000);
                    } else {
                        swal({
                            text: "You don't have enough data to continue.",
                            icon: "error",
                        });
                        stopAllVideos();

                        // Hide the .full-video elements when inputValueCount is less than or equal to 0
                        $('.full-video').hide();
                    }
                } else if (currentPlayingVideo === videoElement) {
                    // If this video is currently playing, stop it
                    videoElement.pause();
                    currentPlayingVideo = null;
                    $(this).text('Watch').removeClass('btn-danger');
                    clearInterval(interval);

                } else {
                    // If another video is currently playing, stop it and start this one
                    currentPlayingVideo.pause();
                    currentPlayingVideo = videoElement;
                    videoElement.play();
                    $('.watch-button').not(this).text('Watch').removeClass('btn-danger');
                    $(this).text('Stop').addClass('btn-danger');

                    // Start a new interval for the currently playing video
                    if (inputValueCount > 0) {
                        interval = setInterval(function() {
                            inputValueCount -= 1;
                            $('#exchange-bland-count').val(inputValueCount);

                            // Store the subtracted value in a variable
                            var dataSubtract = 1; // You can adjust this value if needed
                            
                            // Update the exchange_bland value periodically
                            updateExchangeBland(dataSubtract);
                            checkDataStatus(inputValueCount);

                        }, 1000);
                    } else {
                        swal({
                            text: "You don't have enough data to continue.",
                            icon: "error",
                        });
                        stopAllVideos();

                        // Hide the .full-video elements when inputValueCount is less than or equal to 0
                        $('.full-video').hide();
                    }
                }
            });

            // Function to stop all videos
            function stopAllVideos() {
                $('.video').each(function() {
                    this.pause();
                });
                currentPlayingVideo = null;
                $('.watch-button').text('Watch').removeClass('btn-danger');
                clearInterval(interval);

            }
        });
    </script>


    <!-- <script>
        $(document).ready(function() {

            // Click event handler for full-video element
            $(".full-video").click(function () {

                var videoId = $(this).data("video-id");
                var videoElement = document.getElementById(videoId);

                if (videoElement) {
                    toggleFullScreen(videoElement);
                }
            });

            // Function to toggle full-screen mode for a specific video element
            function toggleFullScreen(videoElement) {
                if (!document.fullscreenElement) {
                    if (videoElement.requestFullscreen) {
                        videoElement.requestFullscreen();
                    } else if (videoElement.mozRequestFullScreen) {
                        videoElement.mozRequestFullScreen();
                    } else if (videoElement.webkitRequestFullscreen) {
                        videoElement.webkitRequestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                }
            }

            // Object to store exchange balance for each video
            var exchangeBlandMap = {};
            var currentPlayingVideo = null;
            var interval = null;

            // button start and stop video
            $(document).on('click', '.watch-button', function(event) {
                var buttonId = event.currentTarget.id;
                var videoId = buttonId.replace('watch-button-', ''); // Extract the video ID
                var videoElement = document.getElementById('video-' + videoId);

                event.preventDefault();

                // Clear the interval for the previous video
                clearInterval(interval);

                if (!exchangeBlandMap.hasOwnProperty(videoId)) {
                    // If no exchange balance exists for this video, initialize it
                    exchangeBlandMap[videoId] = <?php echo $topupData["exchange_bland"]; ?>;
                }

                var exchangeBland = exchangeBlandMap[videoId];
                var inputValueCount = $("#exchange-bland-count").val();
                exchangeBland = inputValueCount;


                 // Hide the full-video elements for all videos
                $('.full-video').hide();

                // Show the full-video element for the clicked video
                var fullVideoElement = $(this).closest('.card').find('.full-video');
                fullVideoElement.show();


                // updateExchangeBland in php
                function updateExchangeBland(){
                    $.ajax({
                        url: '../php/deduct_exchange_bland.php',
                        method: 'POST',
                        data: { exchangeBland: inputValueCount },
                        cache: false,
                        success: function(response) {
                            response = response.trim();
                            console.log('Response from server:', response);
                            if (response === 'success') {
                                if (inputValueCount <= 0) {
                                    swal({
                                        text: "You don't have enough data to continue.",
                                        icon: "error",
                                    });
                                    stopAllVideos();

                                    // Hide the .full-video elements when inputValueCount is less than or equal to 0
                                    $('.full-video').hide();
                                }

                                // Check if inputValueCount is 100
                                else if (inputValueCount === 100) {
                                    stopAllVideos();
                                    swal({
                                        text: "Your data is only 100MB left!",
                                        icon: "warning",
                                    }).then((willDelete) => {
                                        if (willDelete) {
                                            stopAllVideos();
                                        }
                                    });
                                }
                                else{
                                    console.log(inputValueCount);
                                }
                            } else if (response === 'error') {
                                alert('Failed to deduct exchange_bland. Database error occurred.');
                            } else {
                                alert('Unexpected response from server: ' + response);
                            }
                        },
                        error: function() {
                            alert('An error occurred while deducting exchange_bland. Please try again later.');
                        }
                    });
                }

                // Function to check data status and prompt the user
                function checkDataStatus(inputValueCount) {
                    if (inputValueCount === 100) {
                        clearInterval(interval);

                        swal({
                            text: "Your data is only 100MB left!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                                // User clicked "OK," continue playing the video
                                interval = setInterval(function() {
                                    inputValueCount -= 1;
                                    $('#exchange-bland-count').val(inputValueCount);
                                    checkDataStatus(inputValueCount);

                                    if (inputValueCount <= 0) {
                                        // Stop video and clear interval if inputValueCount goes below 0
                                        clearInterval(interval);
                                        videoElement.pause();
                                        inputValueCount = 0;
                                        $('#exchange-bland-count').val(inputValueCount);
                                    }
                                }, 1000);
                            } else {
                                // User clicked "Cancel," stop playing the video and clear interval
                                videoElement.pause();
                                inputValueCount = 0;
                                $('#exchange-bland-count').val(inputValueCount);
                                currentPlayingVideo = null;
                                clearInterval(interval);
                                $(this).text('Watch').removeClass('btn-danger');
                            }
                        });
                    }
                }

                if (!currentPlayingVideo) {
                    // If no video is currently playing, start this one
                    currentPlayingVideo = videoElement;
                    videoElement.play();
                    $(this).text('Stop').addClass('btn-danger');

                    if (inputValueCount > 0) {
                        interval = setInterval(function() {
                            inputValueCount -= 1;
                            $('#exchange-bland-count').val(inputValueCount);
                            // Update the exchange_bland value periodically
                            updateExchangeBland();
                            checkDataStatus(inputValueCount);

                        }, 1000);
                    } else {
                        swal({
                            text: "You don't have enough data to continue.",
                            icon: "error",
                        });
                        stopAllVideos();

                        // Hide the .full-video elements when inputValueCount is less than or equal to 0
                        $('.full-video').hide();
                    }
                } else if (currentPlayingVideo === videoElement) {
                    // If this video is currently playing, stop it
                    videoElement.pause();
                    currentPlayingVideo = null;
                    $(this).text('Watch').removeClass('btn-danger');
                    clearInterval(interval);

                } else {
                    // If another video is currently playing, stop it and start this one
                    currentPlayingVideo.pause();
                    currentPlayingVideo = videoElement;
                    videoElement.play();
                    $('.watch-button').not(this).text('Watch').removeClass('btn-danger');
                    $(this).text('Stop').addClass('btn-danger');

                    // Start a new interval for the currently playing video
                    if (inputValueCount > 0) {
                        interval = setInterval(function() {
                            inputValueCount -= 1;
                            $('#exchange-bland-count').val(inputValueCount);
                            // Update the exchange_bland value periodically
                            updateExchangeBland();
                            checkDataStatus(inputValueCount);

                        }, 1000);
                    } else {
                        swal({
                            text: "You don't have enough data to continue.",
                            icon: "error",
                        });
                        stopAllVideos();

                        // Hide the .full-video elements when inputValueCount is less than or equal to 0
                        $('.full-video').hide();
                    }
                }
            });

            // Function to stop all videos
            function stopAllVideos() {
                $('.video').each(function() {
                    this.pause();
                });
                currentPlayingVideo = null;
                $('.watch-button').text('Watch').removeClass('btn-danger');
                clearInterval(interval);

            }
        });
    </script> -->

</body>
</html>
