<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bone Scope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        .hero-section {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            padding: 2rem;
            min-height: 100vh;
        }

        .text-container {
            max-width: 600px;
        }

        .text-container h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .text-container h1 span {
            display: block;
            font-size: 4rem;
            color: white;
        }

        .text-container p {
            font-size: 1.2rem;
            margin-top: 1rem;
        }

        .text-container .btn {
            margin-top: 1.5rem;
            font-size: 1.2rem;
            padding: 0.75rem 1.5rem;
            background-color: black; /* Button background color set to black */
            color: white; /* Button text color set to white */
            border: 1px solid white; /* Optional: Add a white border for contrast */
        }

        .text-container .btn:hover {
            background-color: #333; /* Slightly lighter shade on hover */
        }

        .image-container {
            position: relative;
            max-width: 600px;
            flex-grow: 1;
            display: flex;
            justify-content: center;
            right: 55px; /* Shift the image to the left by 2rem */
        }

        .image-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container-fluid hero-section">
    <!-- Text Content -->
    <div class="text-container">
        <h1>Welcome to <span>Bone Scope</span></h1>
        <p>Leverage state-of-the-art Machine Learning to detect bone fractures accurately and efficiently.</p><p> Upload your X-ray scans and get results in seconds.</p>
        <a href="/login" class="btn">Get Started >></a>
    </div>

    <!-- Image Section -->
    <div class="image-container">
        <img src="{{ asset('images/landing-page-logo-1.jpg') }}" alt="Bone Fracture Detection"> <!-- Replace with your image -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
