<?php
    include "links/link.php";
    include "conn.php";
    include "php/reset_password.php";
    session_start();
    include "php/login.php";

?>


<title>Login</title>

<style>
    .text-register {
        display: none;
        transition: display 1s ease-in;
        transition-delay: 0.5s;
    }
    .main-register:hover .text-register {
        transition-delay: 0s;
        display: inline;
    }
    .main-register:hover {
        cursor: pointer;
    }
    input#captcha{
        background-image: url(imgCaptcha/captcha-bg.png);
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>

<!-- form login -->
<div class="bg-dark">
    <div class="container text-white">
        <div class="d-flex justify-content-center align-items-center flex-row position-relative" style="height: 100vh;">
            <form method="POST" action="" class="col-lg-5">
                <div class="mb-3">
                    <h2 class="text-center fw-bold">LOGIN FORM</h2>
                </div>
                <div class="mb-3">
                    <label for="phonenumber" class="form-label">Phone number</label>
                    <input type="text" name="phonenumber" class="form-control" id="phonenumber" required autofocus
                    value="<?php echo isset($_COOKIE['remember_phone_number']) ? $_COOKIE['remember_phone_number'] : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="password" required autofocus
                        value="<?php echo isset($_COOKIE['remember_password']) ? $_COOKIE['remember_password'] : ''; ?>">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-radius: 0px 5px 5px 0px">
                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember"
                    <?php echo isset($_COOKIE['remember_phone_number']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Login</button>
            </form>
            <div class="position-absolute d-flex justify-content-end" style="right: 0rem;bottom: 2rem">
                <div class="main-register" title="register">
                    <p class="text-uppercase bg-info p-2 px-4 rounded" onclick="location.href=('register/register.php')"><span class="text-register">Create account</span> <i class="fas fa-user-plus"></i></p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ForgotPasswordModal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Forgot Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You have exceeded the maximum number of login attempts. Would you like to reset your password?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="resetPassword" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">Reset Password</button>
            </div>
        </div>
    </div>
</div>


<!-- resetPassword -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Forgot Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="resetForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="PhonenumberReset" class="form-label">Phone number</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="PhonenumberReset" name="PhonenumberReset">
                            <button type="button" class="btn btn-outline-secondary" id="getOTP_btn" style="border-radius: 0px 5px 5px 0px">
                                GET OTP
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="verify_OTP" class="form-label">Verify OTP</label>
                        <input type="text" class="form-control" id="verify_OTP" name="verify_OTP">
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="newPassword"  id="newPassword">
                            <button class="btn btn-outline-secondary" type="button" id="NewTogglePassword" style="border-radius: 0px 5px 5px 0px">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> -->
                    <button type="submit" name="SaveResetPassword" class="btn btn-primary" id="SaveResetPassword">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="js/checkReset.js?<?php echo time(); ?>" type="text/javascript"></script>
<script src="js/getOtpCode.js?<?php echo time(); ?>" type="text/javascript"></script>
<script src="js/login.js?<?php echo time(); ?>" type="text/javascript"></script>
