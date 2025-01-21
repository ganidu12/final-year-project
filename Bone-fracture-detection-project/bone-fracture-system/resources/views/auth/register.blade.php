<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            background-image: url("{{ asset('images/sign-up-image.jpg') }}");
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
        .signin-link {
            margin-top: 20px;
            text-align: center;
        }
        .signin-link a {
            color: #000;
            text-decoration: none;
        }
        .signin-link a:hover {
            text-decoration: underline;
        }
        #sign-up-btn {
            background-color: #2f2c2c;
            color: #fff;
            border: none;
        }
    </style>
</head>
<body>
<div class="signup-container">
    <div class="signup-image"></div>
    <div class="signup-form">
        <h4 class="text-center">Sign Up</h4>

        @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ $errors->first() }}',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'animate__animated animate__fadeInDown'
                }
            });
        </script>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" id="signup-form">
            @csrf
            <div class="form-group mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" class="form-control" placeholder="Name" name="name" value="{{ old('name') }}">
            </div>
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Password" name="password">
            </div>
            <div class="form-group mb-3">
                <label for="user_type" class="form-label">Register As</label>
                <select id="user_type" name="user_type" class="form-select">
                    <option value="regular_user" {{ old('user_type') == 'regular_user' ? 'selected' : '' }}>Regular User</option>
                    <option value="doctor" {{ old('user_type') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                </select>
            </div>

            <!-- Age field will be dynamically added here -->
            <div class="form-group mb-3" id="age-container" style="display: none;">
                <label for="age" class="form-label">Age</label>
                <input type="number" id="age" class="form-control" placeholder="Age" name="age" value="{{ old('age') }}">
            </div>

            <button type="submit" id="sign-up-btn" class="btn btn-primary">Sign Up</button>
        </form>

        <div class="signin-link">
            <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
        </div>
    </div>
</div>

<script>
    const userType = document.getElementById('user_type');
    const ageContainer = document.getElementById('age-container');

    userType.addEventListener('change', function() {
        if (userType.value === 'regular_user') {
            ageContainer.style.display = 'block';
        } else {
            ageContainer.style.display = 'none';
        }
    });

    // Trigger change event on page load to maintain state
    window.addEventListener('load', function() {
        if (userType.value === 'regular_user') {
            ageContainer.style.display = 'block';
        }
    });
</script>
</body>
</html>
