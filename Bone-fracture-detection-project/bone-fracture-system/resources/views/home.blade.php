<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            height: 100vh;
        }
        .navbar {
            background-color: #007bff;
            color: white;
        }
        .navbar-brand img {
            height: 50px;
        }
        .account-btn {
            width: 50px;
            height: 50px;
            background-color: white;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            border: 2px solid #007bff;
        }
        .account-btn:hover {
            background-color: #f8f9fa;
        }
        .upload-section,
        .visualization-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            height: 80vh;
        }
        .upload-section h5, .visualization-section h5 {
            margin-bottom: 20px;
        }
        .visualization-section {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .no-image-placeholder {
            width: 400px;
            height: 400px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #777;
            font-size: 16px;
            background-color: #f8f9fa;
            margin-bottom: 10px;
        }
        .image-preview {
            width: 100%;
            max-width: 400px;
            max-height: 400px;
            border-radius: 8px;
            object-fit: contain;
        }
        .remove-btn {
            display: none;
            margin-top: 10px;
        }
        /* Scanning animation */
        .scanning-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 255, 0, 0.3);
            animation: scan 2s infinite linear;
            pointer-events: none;
        }
        @keyframes scan {
            0% {
                transform: translateY(-100%);
            }
            100% {
                transform: translateY(100%);
            }
        }
        @media (max-width: 768px) {
            .upload-section, .visualization-section {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/bone fracture system logo.jpg') }}" alt="System Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#">Check History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Settings</a>
                </li>
            </ul>
            <a href="#" class="account-btn">A</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container-fluid mt-4">
    <div class="row g-4">
        <!-- Left Column: Upload and Add Patient Details -->
        <div class="col-lg-6 col-md-12">
            <div class="upload-section">
                <h5>Upload and Add Patient Details</h5>
                <form>
                    <div class="mb-3">
                        <label for="uploadImage" class="form-label">Upload Image</label>
                        <input type="file" class="form-control" id="uploadImage" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="patientName" class="form-label">Patient Name</label>
                        <input type="text" class="form-control" id="patientName" placeholder="Enter Patient Name">
                    </div>
                    <div class="mb-3">
                        <label for="patientEmail" class="form-label">Patient Email</label>
                        <input type="email" class="form-control" id="patientEmail" placeholder="Enter Patient Email">
                    </div>
                    <div class="mb-3">
                        <label for="patientAge" class="form-label">Patient Age</label>
                        <input type="number" class="form-control" id="patientAge" placeholder="Enter Patient Age">
                    </div>
                    <div class="mb-3">
                        <label for="actionType" class="form-label">Select Action</label>
                        <select class="form-select" id="actionType">
                            <option value="classify">Classify X-Ray</option>
                            <option value="analyze">Analyze and Locate Fracture</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>

        <!-- Right Column: Visualization -->
        <div class="col-lg-6 col-md-12">
            <div class="visualization-section">
                <h5>Visualization</h5>
                <div id="imagePreview" class="no-image-placeholder">
                    No Image
                </div>
                <button id="removeImage" class="btn btn-danger remove-btn">Remove Image</button>
                <p id="resultInfo" class="mt-3">
                    Uploaded image and detection results will be displayed here.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const uploadImageInput = document.getElementById('uploadImage');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageButton = document.getElementById('removeImage');
    const submitButton = document.querySelector('button[type="submit"]');
    const predictRoute = "{{ route('predict', ['type' => ':type']) }}";
    let scanningOverlay = null;

    // Handle image upload and preview
    uploadImageInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Uploaded Image" class="image-preview">`;
                removeImageButton.style.display = 'block'; // Show remove button
            };
            reader.readAsDataURL(file);
        } else {
            resetPreview();
        }
    });

    submitButton.addEventListener('click', async function (event) {
        event.preventDefault(); // Prevent default form submission
        removeImageButton.style.display = 'none';
        const file = uploadImageInput.files[0];
        const actionType = document.getElementById('actionType').value; // Get the selected type from the dropdown

        if (!file) {
            alert('Please upload an image before submitting.');
            return;
        }

        if (!actionType) {
            alert('Please select an action type from the dropdown.');
            return;
        }

        const formData = new FormData();
        formData.append('image', file);

        // Add scanning overlay
        addScanningAnimation();

        try {
            await new Promise(resolve => setTimeout(resolve, 3000));

            const url = predictRoute.replace(':type', actionType);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            });

            if (!response.ok) {
                throw new Error('Failed to process the image.');
            }

            const result = await response.json();

            // Clear the result info section
            document.getElementById('resultInfo').innerHTML = '';

            if (actionType === 'classify') {
                // Display classification result
                document.getElementById('resultInfo').innerHTML = `
                <strong>Diagnosis Result:</strong> ${result.image_class}
            `;
            } else if (actionType === 'analyze') {
                // Display analysis result
                if (result.image_url) {
                    // Update the image preview
                    imagePreview.innerHTML = `<img src="${result.image_url}" alt="Processed Image" class="image-preview">`;
                }
                // Display additional results
                document.getElementById('resultInfo').innerHTML = `
                <strong>Diagnosis Result:</strong> ${result.image_class} <br>
                <strong>Diagonal Length (mm):</strong> ${result.diagonal_mm}
            `;
            } else {
                throw new Error('Unknown action type.');
            }
        } catch (error) {
            alert(`Error: ${error.message}`);
        } finally {
            // Remove scanning animation
            removeScanningAnimation();
        }
    });




    // Handle image removal
    removeImageButton.addEventListener('click', function () {
        resetPreview();
        uploadImageInput.value = ''; // Reset file input
    });

    // Reset the image preview
    function resetPreview() {
        imagePreview.innerHTML = 'No Image';
        removeImageButton.style.display = 'none'; // Hide remove button
    }

    // Add scanning animation overlay
    function addScanningAnimation() {
        scanningOverlay = document.createElement('div');
        scanningOverlay.className = 'scanning-overlay';
        imagePreview.appendChild(scanningOverlay);
    }

    // Remove scanning animation overlay
    function removeScanningAnimation() {
        if (scanningOverlay) {
            scanningOverlay.remove();
            scanningOverlay = null;
        }
    }
</script>

</body>
</html>
