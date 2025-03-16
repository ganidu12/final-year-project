<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f8f9fa;
        }
        .forgot-password-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        .forgot-password-image {
            flex: 2;
            background-image: url("{{ asset('images/login-image.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .forgot-password-form {
            flex: 1;
            padding: 50px 30px;
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .forgot-password-form h4 {
            margin-bottom: 30px;
            font-weight: 400;
        }
        .forgot-password-form button {
            width: 100%;
        }
        .back-to-login {
            margin-top: 20px;
            text-align: center;
        }
        .back-to-login a {
            color: #000;
            text-decoration: none;
        }
        .back-to-login a:hover {
            text-decoration: underline;
        }
        #next-btn, #verify-btn, #submit-btn {
            background-color: #2f2c2c;
            color: #fff;
            border: none;
        }
        .hidden {
            display: none;
        }
        @media (max-width: 768px) {
            .forgot-password-container {
                flex-direction: column;
            }
            .forgot-password-image {
                height: 50vh;
                flex: none;
            }
            .forgot-password-form {
                height: 50vh;
                flex: none;
            }
        }
    </style>
</head>
<body>
<div class="forgot-password-container">
    <!-- Left Panel with Background Image -->
    <div class="forgot-password-image"></div>

    <!-- Right Panel with Forgot Password Form -->
    <div class="forgot-password-form">
        <h4 class="text-center">Forgot Password</h4>

        <!-- Step 1: Enter Email -->
        <div id="email-step">
            <form id="email-form">
                @csrf
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Enter Your Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <button type="button" id="next-btn" class="btn btn-primary">Next</button>
            </form>
        </div>

        <!-- Step 2: Enter Verification Code -->
        <div id="verification-step" class="hidden">
            <form id="code-form">
                @csrf
                <div class="form-group mb-3">
                    <label for="verification-code" class="form-label">Enter Verification Code</label>
                    <input type="text" id="verification-code" class="form-control" placeholder="Verification Code" required>
                </div>
                <button type="button" id="verify-btn" class="btn btn-primary">Verify</button>
            </form>
        </div>

        <!-- Step 3: Enter New Password -->
        <div id="password-step" class="hidden">
            <form id="password-form">
                @csrf
                <div class="form-group mb-3">
                    <label for="email-confirm" class="form-label">Enter Your Email Again</label>
                    <input type="email" id="email-confirm" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="form-group mb-3">
                    <label for="new-password" class="form-label">New Password</label>
                    <input type="password" id="new-password" class="form-control" name="new_password" required>
                </div>
                <div class="form-group mb-3">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm-password" class="form-control" name="confirm_password" required>
                </div>
                <button type="submit" id="submit-btn" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <!-- Back to login -->
        <div class="back-to-login">
            <p><a href="{{ route('login') }}">Back to Login</a></p>
        </div>
    </div>
</div>

<script>
    document.getElementById('next-btn').addEventListener('click', function() {
        let email = document.getElementById('email').value;

        if (email === '') {
            Swal.fire({
                title: 'Error!',
                text: 'Please enter your email!',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: { popup: 'animate__animated animate__shakeX' }
            });
            return;
        }

        fetch("/send-reset-code", {
            method: "POST",
            body: JSON.stringify({ email: email }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'A verification code has been sent to your email.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                    // Show the verification code step without reloading
                    document.getElementById('email-step').classList.add('hidden');
                    document.getElementById('verification-step').classList.remove('hidden');
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Something went wrong!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => console.error("Error:", error));
    });

    document.getElementById('verify-btn').addEventListener('click', function() {
        let code = document.getElementById('verification-code').value;

        if (code === '') {
            Swal.fire({
                title: 'Error!',
                text: 'Please enter the verification code!',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: { popup: 'animate__animated animate__shakeX' }
            });
            return;
        }

        fetch("/verify-reset-code", {
            method: "POST",
            body: JSON.stringify({ code: code }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Verification code is correct! Please enter your new password.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                    // Show new password input without reloading
                    document.getElementById('verification-step').classList.add('hidden');
                    document.getElementById('password-step').classList.remove('hidden');
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Invalid verification code!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => console.error("Error:", error));
    });

    // Step 3: Reset Password
    document.getElementById('submit-btn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent normal form submission

        let email = document.getElementById('email-confirm').value;
        let newPassword = document.getElementById('new-password').value;
        let confirmPassword = document.getElementById('confirm-password').value;

        if (newPassword === '' || confirmPassword === '') {
            Swal.fire({
                title: 'Error!',
                text: 'Please enter your new password and confirm it!',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (newPassword !== confirmPassword) {
            Swal.fire({
                title: 'Error!',
                text: 'Passwords do not match!',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        fetch("/reset-password", {
            method: "POST",
            body: JSON.stringify({ email: email, new_password: newPassword }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your password has been reset successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "/login"; // Redirect to login page
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Something went wrong!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => console.error("Error:", error));
    });
</script>
</body>
</html>
