const logoutButton = document.getElementById('logoutButton');

// Add a click event listener to the button
logoutButton.addEventListener('click', function() {
    const com = confirm('Are you sure you want to logout?');
    if (com) {
    // Redirect to the desired URL when the user confirms
    window.location.href = '../index.php';
    }
});


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
        const simNumber = document.getElementById("sim-smart-number").value = randomSimNumber;
        // const n = document.cookie = `${simNumber}`;
        const n = document.cookie = `simNumber=${simNumber}; path=/`;
        console.log(n);
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
