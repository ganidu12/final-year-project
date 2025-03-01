<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Sidebar with Fixed Profile Icon</title>
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
            z-index: 1050;
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
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar .nav-link:first-child {
            border-top: none;
        }
        .sidebar .nav-link.active {
            background-color: #ffffff;
            color: black;
            font-weight: bold;
            border: none;
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
            padding: 0 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .top-bar .account-btn {
            width: 45px;
            height: 45px;
            background-color: #ffffff;
            overflow: hidden;
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

        /* Mobile Sidebar Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .top-bar {
                left: 0;
            }
        }
    </style>
</head>
<body>
@if(session('logout_success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 1050;">
    {{ session('logout_success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<script>
    setTimeout(function() {
        window.location.href = '/login';
    }, 2000);
</script>
@endif

<!-- Mobile Sidebar Toggle Button -->
<div class="top-bar">
    <button class="btn btn-outline-light text-dark d-md-none" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Profile Icon Fixed to the Right -->
    <a href="/edit-profile" class="account-btn ms-auto">
        <img src="{{ auth()->user()->profile_img ? asset('storage/' . auth()->user()->profile_img) : asset('images/dummy-profile-pic.jpg') }}" alt="Profile picture">
    </a>
</div>

<!-- Desktop Sidebar -->
<div class="sidebar d-none d-md-flex flex-column justify-content-between">
    <div>
        <div class="logo">
            <img src="{{ asset('images/bone fracture system logo.jpg') }}" alt="System Logo">
        </div>
        <a href="/analyze-fracture" class="nav-link @if(request()->is('analyze-fracture')) active @endif">
            <i class="fas fa-x-ray me-2"></i> Analyze X-ray
        </a>
        <a href="/check-history" class="nav-link @if(request()->is('check-history')) active @endif">
            <i class="fas fa-history me-2"></i> Check History
        </a>
    </div>
    <div>
        <form id="logout-form" action="/logout" method="POST" style="display: inline;">
            @csrf
            <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </form>
    </div>
</div>

<!-- Mobile Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileSidebarLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <a href="/analyze-fracture" class="nav-link @if(request()->is('analyze-fracture')) active @endif">
            <i class="fas fa-x-ray me-2"></i> Analyze X-ray
        </a>
        <a href="/check-history" class="nav-link @if(request()->is('check-history')) active @endif">
            <i class="fas fa-history me-2"></i> Check History
        </a>
        <form id="logout-form-mobile" action="/logout" method="POST" style="display: inline;">
            @csrf
            <a href="#" class="nav-link" onclick="document.getElementById('logout-form-mobile').submit();">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
