// show title and hide
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

// handle click events on button watch
$(document).ready(function() {
    // Function to handle the video end event
    function handleVideoEnd(videoElement) {
        videoElement.pause();
        $('.watch-button').not(this).text('Watch').removeClass('btn-danger');
        clearInterval(interval);

        // Hide the full-video elements for all videos
        $('.full-video').hide();
    }


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
            exchangeBlandMap[videoId] = $("#exchange-bland-count").val();
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
                        }, 3000);
                    } else {
                        // User clicked "Cancel," stop playing the video and clear interval
                        videoElement.pause();
                        inputValueCount = 0;
                        $('#exchange-bland-count').val(inputValueCount);
                        currentPlayingVideo = null;
                        clearInterval(interval);
                        $(this).text('Watch').removeClass('btn-danger');
                        handleVideoEnd(videoElement, $(this));
                    }
                });
            }
        }

        if (!currentPlayingVideo) {
            // If no video is currently playing, start this one
            currentPlayingVideo = videoElement;
            videoElement.play();
            $(this).text('Stop').addClass('btn-danger');

            // Add an event listener to the video to check for the end
            videoElement.addEventListener('ended', function() {
                handleVideoEnd(videoElement, $(this));
            });

            if (inputValueCount > 0) {
                interval = setInterval(function() {
                    inputValueCount -= 1;
                    $('#exchange-bland-count').val(inputValueCount);

                    // Store the subtracted value in a variable
                    var dataSubtract = 1; // You can adjust this value if needed

                    // Update the exchange_bland value periodically
                    updateExchangeBland(dataSubtract);
                    checkDataStatus(inputValueCount);

                }, 3000);
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

            // Hide the full-video elements for all videos
            $('.full-video').hide();
        } else {
            // If another video is currently playing, stop it and start this one
            currentPlayingVideo.pause();
            currentPlayingVideo = videoElement;
            videoElement.play();
            $('.watch-button').not(this).text('Watch').removeClass('btn-danger');
            $(this).text('Stop').addClass('btn-danger');

            // Add an event listener to the video to check for the end
            videoElement.addEventListener('ended', function() {
                handleVideoEnd(videoElement, $(this));
            });

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

                }, 3000);
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
