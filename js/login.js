document.addEventListener('DOMContentLoaded', function () {
    // Add an event listener to the input element Phonenumber
    var phonNumberInput = document.getElementById('phonenumber');
    phonNumberInput.addEventListener('input', function() {
        validatePhone(this);
    });


    // Add an event listener to the input element password
    var PasswordInput = document.getElementById('password');
    PasswordInput.addEventListener('input', function() {
        validatePassword(this);
    });


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
            input.classList.remove('is-valid', 'is-invalid');
        }
    }


    // Function to validate the "Password" input
    function validatePassword(input) {
        var password = input.value;

        // Check if the input is empty
        if (password.trim() === '') {
            input.classList.remove('is-valid', 'is-invalid');
            return;
        }

        var specialCharacterRegex = /[!@#$%^&*()_+|'`:<>\?/\\]/;

        if (password.length > 6 && specialCharacterRegex.test(password)) {
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid'); // Apply is-invalid class

        }
    }
    // hide and show password
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = this.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    });


    // Form submission handler
    document.querySelector('form').addEventListener('submit', function (e) {
        // Perform client-side validation before submitting the form
        var phoneNumberInput = document.getElementById('phonenumber');
        var passwordInput = document.getElementById('password');

        validatePhone(phoneNumberInput);
        validatePassword(passwordInput);

        if (
            phoneNumberInput.classList.contains('is-invalid') ||
            phoneNumberInput.value.length !== 10 ||
            passwordInput.classList.contains('is-invalid')
        ) {
            // Prevent form submission if validation fails
            alert('Validation failed. Please check your inputs.');
            e.preventDefault();
        } else {
            // alert('Validation successful. Form will be submitted.');
        }
    });
});
