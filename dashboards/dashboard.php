<?php
    include "../links/link.php";
    include "../conn.php";

    // Start the session
    session_start();

    // Check if the user is logged in (session contains phone number)
    if (!isset($_SESSION["user_phone_number"])) {
        // Redirect to the login page or handle unauthorized access
        header("Location: ./../index.php");
        exit();
    }

    // Retrieve the phone number from the session
    $userPhoneNumber = $_SESSION["user_phone_number"];

    // Query the database to fetch user data from both tables based on id_number
    $query = "SELECT r.*, t.main_bland, t.exchange_bland, t.data_use
            FROM register_table AS r
            LEFT JOIN top_up AS t ON r.phone_number = t.phone_number
            WHERE r.phone_number = '$userPhoneNumber'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        // Check if a matching user is found
        if (mysqli_num_rows($result) == 1) {
            $userData = mysqli_fetch_assoc($result);
            $name = $userData["name"];
            $phone = $userData["phone_number"];
            $idNumber = $userData["id_number"];
            $main_bland = $userData["main_bland"];
            $ex_bland = $userData["exchange_bland"];
            $data_use = $userData["data_use"];
        } else {
            $name = '';
            $phone = '';
            $idNumber = '';
            $main_bland = '';
            $ex_bland = '';
            $data_use = '';
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Check if the edit form is submitted
    if (isset($_POST["edit_submit"])) {
        // Get updated user data from the form
        $updatedName = mysqli_real_escape_string($conn, $_POST["name"]);
        $updatedPhone = mysqli_real_escape_string($conn, $_POST["phone"]);
        $updatedIdNumber = mysqli_real_escape_string($conn, $_POST["id_number"]);
        $updatedBland = mysqli_real_escape_string($conn, $_POST["bland"]);

        // Update user data in the database
        $updateQuery = "UPDATE register_table SET
                        name = '$updatedName',
                        phone_number = '$updatedPhone',
                        updated_at = now()
                        WHERE phone_number = '$userPhoneNumber'";

        if (mysqli_query($conn, $updateQuery)) {
            print "<script>
                alert('Update successfully');window.location.href=('video.php');
                </script>";
            exit();
        } else {
            // Handle the case where the update fails
            echo "Error updating user data: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                    <a class="nav-link active" aria-current="page" href="./dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./video.php">Video</a>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Account
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal" data-bs-whatever="@mdo">Profile</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Top up</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
                </li>
                <li class="nav-item">
                <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            </div>
        </div>
    </nav>

    <!-- top up menu -->
    <div class="container-fluid bg-dark pt-5 pb-5 d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="container ">
            <h1 class="text-center text-white">Buy Card Top up</h1>
            <div class="text-white d-flex gap-3 pt-5 justify-content-center">
                <!-- smart -->
                <div class="card" style="width: 18rem;">
                    <img src="https://simcard.id/wp-content/uploads/2020/11/Smart-Sim-Card-Cambodia-1280x720.jpg" class="card-img-top" alt="..." style="height: 10.2rem;">
                    <div class="card-body">
                        <h5 class="card-title">Smart</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn text-white" style="background: #01a951;" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" id="smart-sim">Buy now</a>
                    </div>
                </div>

                <!-- metfone -->
                <div class="card" style="width: 18rem;">
                    <img src="https://simcard.id/wp-content/uploads/2020/11/Metfone-Sim-Card-Cambodia.jpg" class="card-img-top" alt="..." style="height: 10.2rem;">
                    <div class="card-body">
                        <h5 class="card-title">Metfone</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn text-white" style="background: #09856D;">Buy now</a>
                    </div>
                </div>

                <!-- cellcard -->
                <div class="card" style="width: 18rem;">
                    <img src="data:image/png;base64,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" class="card-img-top" alt="..." style="height: 10.2rem;">
                    <div class="card-body">
                        <h5 class="card-title">Cellcard</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn text-white" style="background: #F8991B;">Buy now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal smart -->
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Card number</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" name="sim-smart-number" class="form-control" id="sim-smart-number" disabled>
                    </div>

                    <button type="button" class="btn btn-primary" id="smart-copy" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="Copy">Copy code</button>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Open QR</button>
                </div>
            </div>
        </div>
    </div>
    <!-- generte qr -->
    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">QR Code</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="qrcode-image" src="" alt="" style="width: 150px; height: 150px;">
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-success" id="smart-copy-qr">Copy qr</button> -->
                    <button type="button" class="btn btn-info text-white" id="smart-download-qr">Download</button>
                    <button type="button" class="btn btn-success" id="smart-create-qr" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Generate">Generate</button>
                    <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Back</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true" style="z-index: 999999;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="https://media.istockphoto.com/id/1337144146/vector/default-avatar-profile-icon-vector.jpg?s=612x612&w=0&k=20&c=BIbFwuv7FxTWvh5S3vB6bkT0Qv8Vn8N5Ffseq84ClGI=" alt="User Profile Image" class="rounded-circle" style="width: 150px; height: 150px">
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h6 class="text-center">Detail information</h6>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="" style="width: 50%;">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" value="<?php echo $name; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone number</label>
                                    <input type="text" name="phone" class="form-control" id="phone" value="<?php echo $phone; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="id_number" class="form-label">Id number</label>
                                    <input type="text" name="id_number" class="form-control" id="id_number" value="<?php echo $idNumber; ?>" disabled>
                                </div>
                            </div>
                            <div class="" style="width: 50%;">
                                <div class="mb-3 d-flex align-items-center gap-2">
                                    <div class="" style="width: 250%;">
                                        <label for="bland" class="form-label">Main bland</label>
                                        <input type="text" name="bland" class="form-control" id="bland" value="<?php echo $main_bland; ?>" disabled>
                                    </div>
                                    <div class="" style="padding-top: 30px;" style="width: 10%;">
                                        <input type="text" name="bland" class="form-control" id="bland" value="$" disabled >
                                    </div>
                                </div>
                                <div class="mb-3 d-flex align-items-center gap-2">
                                    <div class="" style="width: 250%;">
                                        <label for="bland" class="form-label">Exchange bland</label>
                                        <input type="text" name="bland" class="form-control" id="bland" value="<?php echo $ex_bland; ?>" disabled>
                                    </div>
                                    <div class="" style="padding-top: 30px;" style="width: 10%;">
                                        <input type="text" name="bland" class="form-control" id="bland" value="MB" disabled >
                                    </div>
                                </div>
                                <div class="mb-3 d-flex align-items-center gap-2">
                                    <div class="" style="width: 250%;">
                                        <label for="bland" class="form-label">Data use</label>
                                        <input type="text" name="bland" class="form-control" id="bland" value="<?php echo $data_use; ?>" disabled>
                                    </div>
                                    <div class="" style="padding-top: 30px;" style="width: 10%;">
                                        <input type="text" name="bland" class="form-control" id="bland" value="MB" disabled >
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="logoutButton">Login out</button>
                        <button type="submit" class="btn btn-danger" name="edit_submit" id="editButton">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- model top up -->
    <div class="modal fade mt-5 " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tow Method To Top up</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="paste-input" class="col-form-label">Number or QR :</label>
                            <div id="input-container">
                                <input type="text" name="numQr" class="form-control" id="paste-input">
                                <div id="preview-container"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Exit</button>
                        <button type="button" name="top_up" class="btn btn-primary">Top Up</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.1.0/dist/jsQR.js"></script>

    <script>
        const logoutButton = document.getElementById('logoutButton');

        // Add a click event listener to the button
        logoutButton.addEventListener('click', function() {
            const com = confirm('Are you sure you want to logout?');
            if (com) {
            // Redirect to the desired URL when the user confirms
            window.location.href = '../index.php';
            }
        });
    </script>

    <script>
        // Get references to HTML elements
        const inputContainer = document.getElementById('input-container');
        const pasteInput = document.getElementById('paste-input');
        const previewContainer = document.getElementById('preview-container');

        // Add event listeners
        pasteInput.addEventListener('paste', (e) => {
            const clipboardData = e.clipboardData;
            if (!clipboardData) return;

            for (const item of clipboardData.items) {
                if (item.type.indexOf('image') !== -1) {
                    // Handle pasted image
                    handlePastedImage(item.getAsFile());
                } else if (item.type === 'text/plain') {
                    // Handle pasted text
                    handlePastedText(item);
                }
            }
        });

        function handlePastedImage(imageFile) {
            const img = new Image();
            img.src = URL.createObjectURL(imageFile);
            img.classList.add('preview-image');

            // Set custom width and height for the image
            img.style.width = '150px';
            img.style.height = '150px';

            // Clear previous content in the preview container
            previewContainer.innerHTML = '';
            previewContainer.appendChild(img);

            // Use jsQR library to decode the QR code
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            img.onload = () => {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0, img.width, img.height);

                const imageData = ctx.getImageData(0, 0, img.width, img.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    // QR code successfully decoded
                    pasteInput.value = code.data; // Set the input value to the decoded data
                } else {
                    // QR code could not be decoded
                    alert('Unable to decode QR code. Please check the image.');
                }
            };
        }

        function handlePastedText(textItem) {
            textItem.getAsString((text) => {
                // Display the pasted text in the preview container
                previewContainer.innerText = text;
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#smart-copy').popover();
            $('#smart-create-qr').popover();
        });

        document.addEventListener("DOMContentLoaded", function () {
            let qrImage = null;

            // Function to generate a random 14-digit number
            function generateRandomNumber() {
                let randomNumber = "";
                for (let i = 0; i < 15; i++) {
                    randomNumber += Math.floor(Math.random() * 10);
                }
                return randomNumber;
            }

            // Click event handler for the "Buy now" button
            document.getElementById("smart-sim").addEventListener("click", function () {
                // Generate a random number
                const randomSimNumber = generateRandomNumber();

                // Set the generated number as the value of the input field
                document.getElementById("sim-smart-number").value = randomSimNumber;
            });

            // Click event handler for the "Copy code" button
            document.getElementById("smart-copy").addEventListener("click", function () {
                // Get the input field
                const inputField = document.getElementById("sim-smart-number");

                // Create a range to select the text
                const range = document.createRange();
                range.selectNode(inputField);
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);

                try {
                    // Copy the selected text to the clipboard using the Clipboard API
                    document.execCommand("copy");
                    // alert("Code copied to clipboard");
                } catch (err) {
                    console.error("Unable to copy:", err);
                } finally {
                    // Deselect the text
                    window.getSelection().removeAllRanges();
                }
            });

            // Click event handler for the "Generate" button in the QR Code modal
            document.getElementById("smart-create-qr").addEventListener("click", function () {
                // Get the value entered in the input field
                const inputValue = document.getElementById("sim-smart-number").value;

                // Create a QR Code instance
                const typeNumber = 4;
                const errorCorrectionLevel = 'L';
                const qr = qrcode(typeNumber, errorCorrectionLevel);
                qr.addData(inputValue);
                qr.make();

                // Get the QR code data URL and set it as the src attribute of the img element
                const qrCodeDataURL = qr.createDataURL();
                document.getElementById("qrcode-image").src = qrCodeDataURL;

                // Store the QR code image element
                qrImage = document.getElementById("qrcode-image");
            });

            // Click event handler for the "Download QR Code Image" button
            document.getElementById("smart-download-qr").addEventListener("click", function () {
                if (qrImage) {
                    // Get the QR code image data URL
                    const qrCodeDataURL = qrImage.src;

                    // Create a temporary anchor element for downloading
                    const downloadLink = document.createElement("a");
                    downloadLink.href = qrCodeDataURL;
                    downloadLink.download = "qrcode.png";
                    downloadLink.click();
                } else {
                    alert("Please generate a QR Code first.");
                }
            });
        });
    </script>

</body>
</html>