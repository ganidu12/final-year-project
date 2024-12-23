<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f8f9fa;
        }
        .login-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        .login-image {
            flex: 2; /* Increase the size of the left panel */
            background-image: url("{{ asset('images/login-logo.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .login-form {
            flex: 1; /* Decrease the size of the right panel */
            padding: 50px 30px;
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-form h4 {
            margin-bottom: 30px;
            font-weight: 400;
        }
        .login-form button {
            width: 100%;
        }
        .signup-link {
            margin-top: 20px;
            text-align: center;
        }
        .signup-link a {
            color: #007bff;
            text-decoration: none;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-image {
                height: 50vh;
                flex: none;
            }
            .login-form {
                height: 50vh;
                flex: none;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <!-- Left Panel with Background Image -->
    <div class="login-image"></div>

    <!-- Right Panel with Login Form -->
    <div class="login-form">
        <h4 class="text-center">Sign In</h4>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" placeholder="Email" name="email" required>
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="#" class="text-decoration-none">Forgot your password?</a>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <!-- Signup Link -->
        <div class="signup-link">
            <p>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
        </div>
    </div>
</div>
</body>
</html>
