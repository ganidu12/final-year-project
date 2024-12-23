<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f8f9fa;
        }
        .signup-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        .signup-image {
            flex: 2;
            background-image: url("{{ asset('images/login-logo.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .signup-form {
            flex: 1;
            padding: 50px 30px;
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .signup-form h4 {
            margin-bottom: 30px;
            font-weight: 400;
        }
        .signup-form button {
            width: 100%;
        }
        .signin-link {
            margin-top: 20px;
            text-align: center;
        }
        .signin-link a {
            color: #007bff;
            text-decoration: none;
        }
        .signin-link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .signup-container {
                flex-direction: column;
            }
            .signup-image {
                height: 50vh;
                flex: none;
            }
            .signup-form {
                height: 50vh;
                flex: none;
            }
        }
    </style>
</head>
<body>
<div class="signup-container">
    <!-- Left Panel with Background Image -->
    <div class="signup-image"></div>

    <!-- Right Panel with Sign Up Form -->
    <div class="signup-form">
        <h4 class="text-center">Sign Up</h4>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" class="form-control" placeholder="Name" name="name" required>
            </div>
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" placeholder="Email" name="email" required>
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <div class="form-group mb-3">
                <label for="user_type" class="form-label">Register As</label>
                <select id="user_type" name="user_type" class="form-select" required>
                    <option value="regular_user" selected>Regular User</option>
                    <option value="doctor">Doctor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>

        <!-- Sign In Link -->
        <div class="signin-link">
            <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
        </div>
    </div>
</div>
</body>
</html>
