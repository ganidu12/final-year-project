<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        #toast-container > .toast-success {
            background-color: #28a745; /* Green */
        }

        /* Error Message */
        #toast-container > .toast-error {
            background-color: #dc3545; /* Red */
        }

        /* Sidebar and Top Bar Adjustments */
        .main-content {
            margin-left: 270px;
            margin-top: 80px;
            padding: 20px;
        }

        /* Profile Card */
        .profile-card {
            max-width: 1000px; /* Increased width */
            margin: 0 auto;
            background: white;
            padding: 40px;
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
            border: 3px solid black;
            object-fit: cover;
        }

        .profile-image-wrapper .tick {
            position: absolute;
            bottom: 0;
            right: calc(50% - 15px);
            width: 25px;
            height: 25px;
            background-color: black;
            color: white;
            border-radius: 50%;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: black;
            border-color: black;
        }

        .btn-secondary {
            background-color: gray;
            border-color: gray;
            color: white;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                margin-top: 60px;
                padding: 15px;
            }

            .profile-card {
                padding: 20px;
                max-width: 100%;
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
            <img id="profileImage" src="{{ auth()->user()->profile_img ? asset('storage/' . auth()->user()->profile_img) : asset('images/dummy-profile-pic.jpg') }}" alt="Profile Image">
            <div class="tick">✔</div>
        </div>
        <div class="text-center mb-3">
            <button type="button" class="btn btn-primary" style="background-color: black; border-color: black;" onclick="document.getElementById('imageInput').click();">Change Profile Picture</button>
            <button type="button" class="btn btn-secondary" id="removeImageBtn" style="display: none;">Remove Image</button>
            <input type="file" id="imageInput" accept="image/*" style="display: none;">
        </div>
        <form id="updateProfileForm">
            @csrf
            <div class="form-row">
                <div class="form-col">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" name="name" value="{{ auth()->user()->name }}" placeholder="Enter Full Name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-col">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" placeholder="Enter Email" required>
                </div>
                <div class="form-col">
                    <label for="phone" class="form-label">Contact</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone }}" placeholder="Enter Phone Number">
                </div>
            </div>
            <div class="form-row">
                <div class="form-col">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter Address">{{ auth()->user()->address }}</textarea>
                </div>
            </div>
            <button type="button" id="submitProfileForm" class="btn btn-primary w-100 mt-4" style="background-color: black; border-color: black;">Save Changes</button>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    const imageInput = document.getElementById('imageInput');
    const profileImage = document.getElementById('profileImage');
    const removeImageBtn = document.getElementById('removeImageBtn');

    // Trigger file input when the "Change Profile Picture" button is clicked
    imageInput.addEventListener('change', function () {
        const file = imageInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                profileImage.src = e.target.result; // Update image preview
                removeImageBtn.style.display = 'inline-block'; // Show "Remove Image" button
            };
            reader.readAsDataURL(file);
        }
    });

    removeImageBtn.addEventListener('click', function () {
        profileImage.src = "{{ auth()->user()->profile_img ? asset('storage/' . auth()->user()->profile_img) : asset('images/dummy-profile-pic.jpg') }}"; // Reset to default profile image
        imageInput.value = ''; // Clear the file input
        removeImageBtn.style.display = 'none'; // Hide the "Remove Image" button
    });

    $('#submitProfileForm').click(function () {
        // Create a FormData object from the form
        const formData = new FormData($('#updateProfileForm')[0]);

        // Include profile image file if it has been changed
        if ($('#imageInput')[0].files[0]) {
            formData.append('profile_img', $('#imageInput')[0].files[0]);
        }

        $.ajax({
            url: "{{ route('updateProfile') }}", // Route to update profile
            type: 'POST', // HTTP method
            data: formData, // Form data
            processData: false, // Do not process data automatically
            contentType: false, // Use FormData's content type
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    toastr.success(response.message);
                    if (response.profile_img_url) {
                        $('#profileImage').attr('src', response.profile_img_url); // Update image preview
                    }
                    setTimeout(() => location.reload(), 3000);
                } else {
                    toastr.error("Failed to update profile. Please try again.");
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', xhr.responseJSON?.errors);

                if (xhr.responseJSON?.errors) {
                    toastr.error(xhr.responseJSON?.errors);
                } else {
                    toastr.error("An error occurred while updating the profile.");
                }
            }
        });
    });



</script>

</body>
</html>
