// Function to validate the "Name" input
function validateName(input) {
    var nameRegex = /^[A-Za-z\s]+$/; // Only allow letters and spaces

    // Check if the input is empty
    if (input.value.trim() === '') {
        input.classList.remove('is-valid', 'is-invalid');
        return;
    }

    if (nameRegex.test(input.value) && (input.value.length === 4 || (input.value.length > 4 && input.value.length <= 30))) {
        input.classList.add('is-valid');
        input.classList.remove('is-invalid');
    } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
    }
}

// Function to validate the "Phone number" input
function validatePhone(input) {
    var phoneRegex = /^[0-9]+$/; // Match only digits

    // Check if the input is empty
    if (input.value.trim() === '') {
        input.classList.remove('is-valid', 'is-invalid');
        return;
    }

    if (input.value.length === 10 && phoneRegex.test(input.value)) {
        input.classList.add('is-valid');
        input.classList.remove('is-invalid');
    } else if (input.value.length > 10 || !phoneRegex.test(input.value)) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
    }
    else {
        input.classList.remove('is-valid', 'is-invalid');;
    }
}


// Form submission handler
document.querySelector('form').addEventListener('submit', function (e) {
    // Perform client-side validation before submitting the form
    var nameInput = document.getElementById('name');
    var phoneNumberInput = document.getElementById('phone');

    validateName(nameInput);
    validatePhone(phoneNumberInput);

    if (
        nameInput.classList.contains('is-invalid') ||
        phoneNumberInput.classList.contains('is-invalid') ||
        phoneNumberInput.value.length !== 10
    ) {
        // Prevent form submission if validation fails
        alert('Validation failed. Please check your inputs.');
        e.preventDefault();
    } else {
        // alert('Validation successful. Form will be submitted.');
    }
});
