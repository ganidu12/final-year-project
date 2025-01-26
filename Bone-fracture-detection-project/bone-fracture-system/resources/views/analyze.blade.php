<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            height: 60vh;
        }
        #toast-container > .toast-success {
            background-color: #28a745; /* Green */
        }

        /* Error Message */
        #toast-container > .toast-error {
            background-color: #dc3545; /* Red */
        }

        /* Adjust content to accommodate sidebar and top bar */
        .main-content {
            margin-left: 270px;
            margin-top: 80px; /* Space for the top bar */
            padding: 20px;
        }

        .upload-section,
        .visualization-section {
            background-color: ghostwhite;
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
        .loading-overlay.hidden {
            display: none;
        }
        #submit-btn {
            background-color: #2f2c2c;
            color: #fff;
            border: none;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
        }

        .loading-overlay.hidden {
            display: none;
        }

        #imagePreview {
            position: relative;
        }
    </style>
    @if (auth()->user()->user_type === 'regular_user')
    <style>
        .main-content {
            margin-left: 270px;
            margin-top: 30px; /* Space for the top bar */
            padding: 20px;
        }
    </style>
    @endif
</head>
<body>

<!-- Include Sidebar and Top Bar -->
@include('components/layout')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid mt-4">
        @if (auth()->user()->user_type === 'regular_user')
        <!-- Single Container for Regular User -->
        <div class="row g-4 justify-content-center" style="margin-top: -20px;"> <!-- Adjust the margin here -->
            <div class="col-lg-8">
                <div class="upload-section p-4 text-center shadow-sm rounded" style="background-color: #f8f9fa; height: auto;">
                    <form>
                        <!-- Upload Image Section -->
                        <div class="mb-4">
                            <label for="uploadImage" class="form-label d-block fw-bold" style="color: #2f2c2c;">Upload Image</label>
                            <input type="file" class="form-control" id="uploadImage" accept="image/*" style="max-width: 400px; margin: 0 auto;">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submit-btn" class="btn btn-dark w-100" style="background-color: #2f2c2c;">Submit</button>
                    </form>

                    <!-- Visualization Section -->
                    <div class="visualization-section mt-4 p-3 text-center shadow-sm rounded" style="background-color: #ffffff; border: 1px solid #e0e0e0; height: auto;">
                        <h4 style="color: #2f2c2c;">Visualization</h4>
                        <div id="imagePreview" class="no-image-placeholder mt-3 d-flex justify-content-center align-items-center" style="width: 100%; max-width: 300px; height: 300px; margin: 0 auto; border: 2px dashed #ccc; border-radius: 8px; background-color: #f8f9fa; color: #777;">
                            No Image
                            <div class="loading-overlay" id="loadingOverlay">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden" >Loading...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Remove Image Button -->
                        <button id="removeImage" class="btn btn-danger mt-2 remove-btn" style="display: none;">Remove Image</button>

                        <!-- Result Info -->
                        <p id="resultInfo" class="mt-2 text-muted" style="font-size: 14px;">
                            Uploaded image and detection results will be displayed here.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @else

        <div class="row g-4">
            <!-- Left Column: Upload and Add Patient Details -->
            <div class="col-lg-6 col-md-12">
                <div class="upload-section">
                    <h5>Analyze X-ray</h5>
                    <form>
                        <div class="mb-3">
                            <label for="uploadImage" class="form-label">Upload Image</label>
                            <input type="file" class="form-control" id="uploadImage" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="patientName" class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="patientName" placeholder="Enter Patient Name">
                        </div>
                        <div class="mb-3 position-relative">
                            <label for="patientEmail" class="form-label">Patient Email</label>
                            <input type="email" class="form-control" id="patientEmail" placeholder="Enter Patient Email" autocomplete="off">
                            <ul id="emailSuggestions" class="list-group position-absolute w-100" style="z-index: 100; display: none;"></ul>
                        </div>
                        <div class="mb-3">
                            <label for="patientAge" class="form-label">Patient Age</label>
                            <input type="number" class="form-control" id="patientAge" placeholder="Enter Patient Age" readonly>
                        </div>
                        <button type="submit" id ="submit-btn" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Visualization -->
            <div class="col-lg-6 col-md-12">
                <div class="visualization-section">
                    <h5>Visualization</h5>
                    <div id="imagePreview" class="no-image-placeholder">
                        No Image
                        <div class="loading-overlay" id="loadingOverlay">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden" >Loading...</span>
                            </div>
                        </div>
                    </div>
                    <button id="removeImage" class="btn btn-danger remove-btn" style="display: none">Remove Image</button>
                    <p id="resultInfo" class="mt-3">
                        Uploaded image and detection results will be displayed here.
                    </p>
                </div>
            </div>

            <div class="row g-4 mt-4" id="additionalInfoRow" style="display: none;">
                <div class="col-12">
                    <div class="upload-section" style="height: auto; padding: 20px;">
                        <h5>Add Feedback</h5>
                        <form>
                            <div class="mb-3">
                                <textarea class="form-control" id="fractureDetails" rows="3" placeholder="Enter details about the fracture"></textarea>
                            </div>
                            <button id="submitAdditionalInfo" class="btn w-100" style="background-color: #2f2c2c; color: #fff;">Submit Feedback</button>
                        </form>
                    </div>
                </div>
            </div>
            @endif



        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right", // Sets toast position
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    const uploadImageInput = document.getElementById('uploadImage');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageButton = document.getElementById('removeImage');
    const submitButton = document.querySelector('button[type="submit"]');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const additionalInfoRow = document.getElementById('additionalInfoRow');
    const submitAdditionalInfoButton = document.getElementById('submitAdditionalInfo');

    const emailInput = document.getElementById('patientEmail');
    const nameInput = document.getElementById('patientName');
    const ageInput = document.getElementById('patientAge');
    const emailSuggestions = document.getElementById('emailSuggestions');
    const nameSuggestions = document.createElement('ul');
    if (nameSuggestions && nameInput){
        nameSuggestions.className = 'list-group position-absolute w-100';
        nameSuggestions.style = 'z-index: 100; display: none;';
        nameInput.parentElement.appendChild(nameSuggestions);
    }


    const predictRoute = "{{ route('predict') }}";
    let scanningOverlay = null;

    document.addEventListener('DOMContentLoaded', () => {
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    });
    // Handle image upload and preview
    uploadImageInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Uploaded Image" class="image-preview">`;
                if (loadingOverlay) {
                    imagePreview.appendChild(loadingOverlay);
                    loadingOverlay.classList.add('hidden');
                }
                removeImageButton.style.display = 'block'; // Show remove button
            };
            reader.readAsDataURL(file);
        } else {
            resetPreview();
        }
    });

    submitButton.addEventListener('click', async function (event) {
        event.preventDefault(); // Prevent default form submission
        const file = uploadImageInput.files[0];
        const patientEmail = document.getElementById('patientEmail');
        if (!file) {
            toastr.error('Please upload an image before submitting.');
            return;
        }
        loadingOverlay.classList.remove('hidden'); // Show loading animation
        removeImageButton.style.display = 'none';


        const formData = new FormData();
        formData.append('image', file);
        if (patientEmail){
            formData.append('patientEmail', patientEmail.value);
        }



        try {
            await new Promise(resolve => setTimeout(resolve, 3000));
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token

            const response = await fetch(predictRoute, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            });

            if (!response.ok) {
                const errorData = await response.json();
                if (errorData.message) {
                    console.log(errorData)
                    toastr.error(`Error: ${errorData.message}`);
                } else {
                    throw new Error('Failed to process the image.'); // Fallback error
                }
            }else{
                const result = await response.json();
                document.getElementById('resultInfo').innerHTML = '';

                if (result.image_class === 'Non-Fractured') {
                    document.getElementById('resultInfo').innerHTML = `
                <strong>Diagnosis Result:</strong> ${result.image_class}
            `;
                } else{
                    if (result.image_url) {
                        imagePreview.innerHTML = `<img src="${result.image_url}" alt="Processed Image" class="image-preview">`;
                    }
                    document.getElementById('resultInfo').innerHTML = `
                <strong>Diagnosis Result:</strong> ${result.image_class} <br>
                <strong>Diagonal Length (mm):</strong> ${result.diagonal_mm}<br>
                <strong>Healing Time :</strong> ${result.healing_time}
            `;
                    if (additionalInfoRow){
                        additionalInfoRow.style.display = 'block';
                    }
                }
            }
        }finally {
            loadingOverlay.classList.add('hidden');
        }
    });

    if (submitAdditionalInfoButton) {
        submitAdditionalInfoButton.addEventListener('click', async function (event) {
            event.preventDefault();
            const feedback = document.getElementById('fractureDetails').value.trim();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!feedback) {
                toastr.error('Please enter feedback before submitting.');
                return;
            }

            try {
                const response = await fetch("{{ route('addFeedback') }}", {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({feedback: feedback}),
                });

                if (!response.ok) {
                    throw new Error('Failed to submit feedback.');
                }

                const result = await response.json();
                toastr.success('Please enter feedback before submitting.');

                resetFields();
            } catch (error) {
                alert(`Error submitting feedback: ${error.message}`);
            }
        });
    }

    async function fetchAndDisplaySuggestions(query, route, inputField, suggestionsList) {
        if (query.length < 2) {
            suggestionsList.style.display = 'none';
            return;
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ query: query }),
            });

            const results = await response.json();
            suggestionsList.innerHTML = '';

            if (results.length > 0) {
                suggestionsList.style.display = 'block';
                results.forEach(patient => {
                    const suggestionItem = document.createElement('li');
                    suggestionItem.className = 'list-group-item list-group-item-action';
                    suggestionItem.textContent = inputField === emailInput ? patient.email : patient.name;
                    suggestionItem.dataset.email = patient.email;
                    suggestionItem.dataset.name = patient.name;
                    suggestionItem.dataset.age = patient.age;

                    suggestionItem.addEventListener('click', function () {
                        inputField.value = inputField === emailInput ? patient.email : patient.name;
                        emailInput.value = patient.email;
                        nameInput.value = patient.name;
                        ageInput.value = patient.age;

                        suggestionsList.style.display = 'none';
                    });

                    suggestionsList.appendChild(suggestionItem);
                });
            } else {
                suggestionsList.style.display = 'none';
            }
        } catch (error) {
            console.error('Error fetching suggestions:', error);
        }
    }

    if (emailInput){
        emailInput.addEventListener('input', function () {
            fetchAndDisplaySuggestions(this.value.trim(), "{{ route('fetchPatientDetailsEmail') }}", emailInput, emailSuggestions);
        });
    }
    if (nameInput){
        nameInput.addEventListener('input', function () {
            fetchAndDisplaySuggestions(this.value.trim(), "{{ route('fetchPatientDetailsName') }}", nameInput, nameSuggestions);
        });
    }

    if (emailSuggestions && nameSuggestions){
        document.addEventListener('click', function (e) {
            if (!emailSuggestions.contains(e.target) && e.target !== emailInput) {
                emailSuggestions.style.display = 'none';
            }
            if (!nameSuggestions.contains(e.target) && e.target !== nameInput) {
                nameSuggestions.style.display = 'none';
            }
        });
    }

    function resetFields() {
        uploadImageInput.value = '';
        resetPreview();

        document.getElementById('patientName').value = '';
        document.getElementById('patientEmail').value = '';
        document.getElementById('patientAge').value = '';

        document.getElementById('fractureDetails').value = '';

        additionalInfoRow.style.display = 'none';

        document.getElementById('resultInfo').innerHTML = 'Uploaded image and detection results will be displayed here.';
    }


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

</script>

</body>
</html>
