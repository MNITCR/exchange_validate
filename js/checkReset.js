$(document).ready(function() {
    // Add an event listener to the input element verify_OTP
    var verify_OTP = document.getElementById('verify_OTP');
    verify_OTP.addEventListener('input', function() {
        validateVerifyOTP(this);
    });

    // Function to validate the "verify_OTP" input
    function validateVerifyOTP(input) {
        var verify_OTPinput = input.value;

        // Check if the input is empty
        if (verify_OTPinput.trim() === '') {
            input.classList.remove('is-valid', 'is-invalid');
            return;
        }


        // Get the OTP from the cookie named "otpCode"
        function getCookie(otpCode) {
            var cookies = document.cookie.split("; ");
            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i].split("=");
                if (cookie[0] === otpCode) {
                    return decodeURIComponent(cookie[1]);
                }
            }
            return null;
        }

        var optSession = getCookie("otpCode");

        console.log("cokie js " +optSession);
        if (verify_OTPinput.length == 6 && verify_OTPinput == optSession) {
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid'); // Apply is-invalid class
        }
    }

    // Add an event listener to the input element Phonenumber
    var PhonenumberReset = document.getElementById('PhonenumberReset');
    PhonenumberReset.addEventListener('input', function() {
        validatePhoneReset(this);
    });

    // Function to validate the "Phone number" input
    function validatePhoneReset(input) {
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


    // Add an event listener to the input element password
    var PasswordInput = document.getElementById('newPassword');
    PasswordInput.addEventListener('input', function() {
        validateNewPassword(this);
    });


    // Function to validate the "Password" input
    function validateNewPassword(input) {
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
    document.getElementById('NewTogglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('newPassword');
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
    document.getElementById('SaveResetPassword').addEventListener('click', function (e) {
        // Perform client-side validation before submitting the form
        var verify_OTPCheck = document.getElementById('verify_OTP');
        var PhonenumberResetCheck = document.getElementById('PhonenumberReset');
        var newPasswordCheck = document.getElementById('newPassword');

        validateVerifyOTP(verify_OTPCheck);
        validatePhoneReset(PhonenumberResetCheck);
        validateNewPassword(newPasswordCheck);

        if (
            PhonenumberResetCheck.classList.contains('is-invalid') ||
            PhonenumberReset.value.length !== 10 ||
            newPasswordCheck.classList.contains('is-invalid')||
            verify_OTPCheck.classList.contains('is-invalid')
        ) {
            // Prevent form submission if validation fails
            alert('Validation failed. Please check your inputs.');
            e.preventDefault();
        } else {
            // alert('Validation successful. Form will be submitted.');
        }
    });
});
