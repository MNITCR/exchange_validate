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
