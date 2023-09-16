document.addEventListener('DOMContentLoaded', function () {
    // Add an event listener to the input element name
    var nameInput = document.getElementById('name');
    nameInput.addEventListener('input', function() {
        validateName(this);
    });


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

    // Function to validate the "Name" input
    function validateName(input) {
        // var nameRegex = /[^A-Za-z\s]/g;
        var nameRegex = /^[A-Za-z\s]+$/; // Only allow letters and spaces

        // Check if the input is empty
        if (input.value.trim() === '') {
            input.classList.remove('is-valid', 'is-invalid');
            // var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
            // exclamationIcon.classList.add('d-none');
            return;
        }

        if (nameRegex.test(input.value) && (input.value.length === 4 || (input.value.length > 4 && input.value.length <= 30))) {
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
            // var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
            // exclamationIcon.classList.remove('d-none');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            // var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
            // exclamationIcon.classList.add('d-none');
        }
    }


    // Function to validate the "Phone number" input
    function validatePhone(input) {
        var phoneRegex = /^[0-9]+$/; // Match only digits

        // Check if the input is empty
        if (input.value.trim() === '') {
            input.classList.remove('is-valid', 'is-invalid');
            // var checkIcon = input.nextElementSibling.querySelector('.fa-check');
            // checkIcon.classList.add('d-none');
            // var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
            // exclamationIcon.classList.add('d-none');
            return;
        }

        if (input.value.length === 10 && phoneRegex.test(input.value)) {
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
            // var checkIcon = input.nextElementSibling.querySelector('.fa-check');
            // checkIcon.classList.remove('d-none');
            // var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
            // exclamationIcon.classList.add('d-none');
        } else if (input.value.length > 10 || !phoneRegex.test(input.value)) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            // var checkIcon = input.nextElementSibling.querySelector('.fa-check');
            // checkIcon.classList.add('d-none');
            // var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
            // exclamationIcon.classList.remove('d-none');
        }
        else {
            input.classList.remove('is-valid', 'is-invalid');
            // var checkIcon = input.nextElementSibling.querySelector('.fa-check');
            // checkIcon.classList.add('d-none');
            // var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
            // exclamationIcon.classList.add('d-none');
        }
    }


    // Function to validate the "Password" input
    function validatePassword(input) {
        var password = input.value;

        // Check if the input is empty
        if (password.trim() === '') {
            input.classList.remove('is-valid', 'is-invalid');
            // var exclamationIcon = input.nextElementSibling.querySelector('.fa-exclamation');
            // exclamationIcon.classList.add('d-none');
            return;
        }

        var specialCharacterRegex = /[!@#$%^&*()_+|'`:<>\?/\\]/;

        if (password.length > 6 && specialCharacterRegex.test(password)) {
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
            // var checkIcon = input.nextElementSibling.querySelector('.fa-check');
            // checkIcon.classList.remove('d-none');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid'); // Apply is-invalid class
            // var checkIcon = input.nextElementSibling.querySelector('.fa-check');
            // checkIcon.classList.add('d-none');
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
        var nameInput = document.getElementById('name');
        var phoneNumberInput = document.getElementById('phonenumber');
        var passwordInput = document.getElementById('password');

        validateName(nameInput);
        validatePhone(phoneNumberInput);
        validatePassword(passwordInput);

        if (
            nameInput.classList.contains('is-invalid') ||
            phoneNumberInput.classList.contains('is-invalid') ||
            phoneNumberInput.value.length !== 10 ||
            passwordInput.classList.contains('is-invalid')
        ) {
            // Prevent form submission if validation fails
            alert('Validation failed. Please check your inputs.');
            e.preventDefault();
        } else {
            alert('Validation successful. Form will be submitted.');
        }
    });
});
