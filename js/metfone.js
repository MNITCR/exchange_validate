// $(document).ready(function () {
//     $('#smart-copy').popover();
//     $('#smart-create-qr').popover();
// });

document.addEventListener("DOMContentLoaded", function () {
    let qrImage = null;

    // Function to generate a random 14-digit number
    function generateRandomNumber() {
        let randomNumber = "";
        for (let i = 0; i < 14; i++) {
            randomNumber += Math.floor(Math.random() * 10);
        }
        return randomNumber;
    }

    // Click event handler for the "Buy now" button
    document.getElementById("metfone-sim").addEventListener("click", function () {
        // Generate a random number
        const randomSimNumber = generateRandomNumber();

        // Set the generated number as the value of the input field
        const simNumber = document.getElementById("sim-metfone-number").value = randomSimNumber;
        // const n = document.cookie = `${simNumber}`;
        const n = document.cookie = `simNumber=${simNumber}; path=/`;
        console.log(n);
    });

    // Click event handler for the "Copy code" button
    document.getElementById("metfone-copy").addEventListener("click", function () {
        // Get the input field
        const inputField = document.getElementById("sim-metfone-number");

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
    document.getElementById("metfone-create-qr").addEventListener("click", function () {
        // Get the value entered in the input field
        const inputValue = document.getElementById("sim-metfone-number").value;

        // Create a QR Code instance
        const typeNumber = 4;
        const errorCorrectionLevel = 'L';
        const qr = qrcode(typeNumber, errorCorrectionLevel);
        qr.addData(inputValue);
        qr.make();

        // Get the QR code data URL and set it as the src attribute of the img element
        const qrCodeDataURL = qr.createDataURL();
        document.getElementById("qrcode-image-metfone").src = qrCodeDataURL;

        // Store the QR code image element
        qrImage = document.getElementById("qrcode-image-metfone");
    });

    // Click event handler for the "Download QR Code Image" button
    document.getElementById("metfone-download-qr").addEventListener("click", function () {
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
