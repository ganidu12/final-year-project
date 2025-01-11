<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar with Full-Width Links</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Sidebar Styles */
        .sidebar {
            background-color: #2f2c2c;
            height: 100vh;
            padding: 20px 0;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
            color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar .logo img {
            height: 50px;
            margin: 0 auto 20px auto;
            display: block;
        }
        .sidebar .nav-link {
            color: white;
            font-size: 16px;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%; /* Full width */
            border-top: 1px solid rgba(255, 255, 255, 0.2); /* Top border */
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); /* Bottom border */
        }
        .sidebar .nav-link:first-child {
            border-top: none; /* Remove border for the first item */
        }
        .sidebar .nav-link.active {
            background-color: #ffffff;
            color: black;
            font-weight: bold;
            border: none; /* Remove borders for active state */
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Top Bar Styles */
        .top-bar {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            background-color: #ffffff;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .top-bar .account-btn {
            width: 45px;
            height: 45px;
            background-color: #ffffff;
            overflow: hidden; /* Ensure the image stays inside the circle */
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            border: 2px solid #1a73e8;
            transition: all 0.3s ease;
        }
        .top-bar .account-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .top-bar .account-btn:hover {
            background-color: #f5f6fa;
            border-color: #1667c1;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .top-bar {
                left: 200px;
            }
        }
        @media (max-width: 576px) {
            .sidebar {
                width: 180px;
            }
            .top-bar {
                left: 180px;
            }
        }
    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar d-flex flex-column justify-content-between">
    <div>
        <div class="logo">
            <img src="{{ asset('images/bone fracture system logo.jpg') }}" alt="System Logo">
        </div>
        <a href="/analyze-fracture" class="nav-link @if(request()->is('analyze-fracture')) active @endif">
            <i class="fas fa-x-ray me-2"></i> <!-- Icon for Analyze X-ray -->
            Analyze X-ray
        </a>
        <a href="/check-history" class="nav-link @if(request()->is('check-history')) active @endif">
            <i class="fas fa-history me-2"></i> <!-- Icon for Check History -->
            Check History
        </a>
    </div>
    <div>
        <a href="#" class="nav-link">
            <i class="fas fa-sign-out-alt me-2"></i> <!-- Icon for Logout -->
            Logout
        </a>
    </div>
</div>

<!-- Top Bar -->
<div class="top-bar">
    <a href="/edit-profile" class="account-btn">
        <img src="{{ asset('images/dummy-profile-pic.jpg') }}" alt="Profile picture">
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
