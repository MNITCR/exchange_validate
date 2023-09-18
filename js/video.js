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
            exchangeBlandMap[videoId] = $("#exchange-bland-count").val();
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
                }, 1000);
            } else {
                swal({
                    text: "You don't have enough data to continue.",
                    icon: "error",
                });
                stopAllVideos();
            }
        }
        else if (currentPlayingVideo === videoElement) {
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
                    else {
                        console.log(inputValueCount);
                    }
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
    }
});
