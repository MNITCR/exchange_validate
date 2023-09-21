<?php
    // session_start();
    // $exBld = $_SESSION["exchange_bland"];
    // print($exBld);
    include "../links/link.php";
    include "../php/deduct_exchange_bland.php";
    $user_id = $_SESSION["user_id"];
    $query = "SELECT * FROM topup WHERE register_id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $topupData = mysqli_fetch_assoc($result);
            // print_r($topupData);
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
    <title>Video</title>
    <style>
        /* if title have tow line break show this */
        #video-title {
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Number of lines to show */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .card-text.show-full-text {
            white-space: normal; /* Display the text as normal (not nowrap) */
            overflow: visible; /* Allow the text to overflow the container */
            text-overflow: initial; /* Remove ellipsis */
        }

        /* Style the scrollbar track */
        ::-webkit-scrollbar {
        width: 2px; /* Set the width of the scrollbar */
        }

        /* Style the scrollbar thumb (the draggable part) */
        ::-webkit-scrollbar-thumb {
        background: #888; /* Set the background color of the thumb */
        border-radius: 5px; /* Add some rounded corners */
        }

        /* Style the scrollbar track when it's hovered */
        ::-webkit-scrollbar-thumb:hover {
        background: #555;
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
                <li class="nav-item d-none">
                    <a class="nav-link">Exchange Bland Count: <input type="text" name="exchange-bland-count" id="exchange-bland-count" value="<?php echo $topupData["exchange_bland"]; ?>"></input></a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" id="vdSearch" placeholder="Search" aria-label="Search">
            </form>
            </div>
        </div>
    </nav>

    <!-- card video -->
    <div class="container-fluid bg-dark text-white">
        <div class="container d-flex flex-column justify-content-center" style="padding: 130px 0 130px 0;">
            <div class="mb-5">
                <h1 class="text-center">Album Video</h1>
            </div>
            <div class="d-flex gap-3 flex-wrap justify-content-center" id="video-container">
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


                                <h5 class="card-title" id="video-title"><?php echo $videoTitle; ?></h5>
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

            <!-- placeholders card -->
            <div class="d-flex justify-content-center align-content-center">
                <div class="card" aria-hidden="true" id="placeholder-card" style="display: none;width: 18.9rem;">
                    <img src="https://oldweb.dyu.edu.tw/english/design/no-video.gif" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title placeholder-glow">
                        <span class="placeholder col-6"></span>
                        </h5>
                        <p class="card-text placeholder-glow">
                        <span class="placeholder col-7"></span>
                        <span class="placeholder col-4"></span>
                        <span class="placeholder col-4"></span>
                        <span class="placeholder col-6"></span>
                        <span class="placeholder col-8"></span>
                        </p>
                        <a class="btn btn-primary disabled placeholder col-6" aria-disabled="true"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- search video card -->
    <script>
        $(document).ready(function () {
            $("#vdSearch").on("input", function () {
                var searchTerm = $(this).val().toLowerCase();
                $("#video-container .card").each(function () {
                    var videoTitle = $(this).find(".card-title").text().toLowerCase();
                    if (videoTitle.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                // Show the placeholder card if no results found
                var visibleCards = $("#video-container .card:visible").length;
                if (visibleCards === 0) {
                    $("#placeholder-card").show();
                } else {
                    $("#placeholder-card").hide();
                }
            });
        });
    </script>


    <script src="../js/video.js?<?php echo time(); ?>" type="text/javascript"></script>
</body>
</html>
