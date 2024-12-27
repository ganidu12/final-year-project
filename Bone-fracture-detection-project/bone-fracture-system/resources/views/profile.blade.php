<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        /* Sidebar and Top Bar Adjustments */
        .main-content {
            margin-left: 270px;
            margin-top: 80px;
            padding: 20px;
        }

        /* Profile Card */
        .profile-card {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-image-wrapper {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-image-wrapper img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #007bff;
            object-fit: cover;
        }

        .profile-image-wrapper .tick {
            position: absolute;
            bottom: 0;
            right: calc(50% - 15px);
            width: 25px;
            height: 25px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                margin-top: 60px;
                padding: 15px;
            }

            .profile-card {
                padding: 15px;
                max-width: 100%;
            }

            .profile-image-wrapper img {
                width: 80px;
                height: 80px;
            }

            .profile-image-wrapper .tick {
                width: 20px;
                height: 20px;
                font-size: 12px;
            }
        }

        @media (max-width: 576px) {
            .profile-image-wrapper img {
                width: 70px;
                height: 70px;
            }

            .profile-image-wrapper .tick {
                width: 18px;
                height: 18px;
                font-size: 10px;
            }

            .profile-card {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Include Sidebar and Top Bar -->
@include('components/layout')

<!-- Main Content -->
<div class="main-content">
    <div class="profile-card">
        <div class="profile-image-wrapper">
            <img src="{{ asset('images/dummy-profile-pic.jpg') }}" alt="Profile Image">
            <div class="tick">✔</div>
        </div>
        <form>
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" placeholder="Enter First Name">
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" placeholder="Enter Last Name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Enter Email">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="phone" placeholder="Enter Phone Number">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" rows="3" placeholder="Enter Address"></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
