<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        /* General Styles */
        body {
            background-color: #f5f6fa;
            font-family: 'Arial', sans-serif;
        }

        /* Sidebar and Top Bar Spacing */
        .main-content {
            margin-left: 270px;
            margin-top: 80px; /* Space for the top bar */
            padding: 20px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                margin-top: 60px;
                padding: 10px;
            }
        }

        /* Portlet Styles */
        .portlet {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .portlet-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: black;
            margin-bottom: 20px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        table thead {
            background-color: #f1f4fa;
        }
        table th {
            padding: 15px;
            font-size: 14px;
            font-weight: 600;
            color: black;
            border-bottom: 2px solid #e0e0e0;
        }
        table td {
            padding: 15px;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #e0e0e0;
        }
        table tbody tr:hover {
            background-color: #f8f9fa;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fbfc;
        }

        /* Buttons */
        .btn {
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .btn-outline-primary {
            color: #1a73e8;
            border-color: #1a73e8;
        }
        .btn-outline-primary:hover {
            background-color: #1a73e8;
            color: white;
        }

        /* Modal Customizations */
        .modal-dialog-centered {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Full height to ensure vertical centering */
        }

        .modal-lg {
            max-width: 90%; /* Larger width for responsiveness */
        }

        /* Blur background when modal is active */
        .modal-backdrop {
            backdrop-filter: blur(8px);
        }
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            /* Ensure the main content is full-width on smaller screens */
            .main-content {
                margin-left: 0;
                margin-top: 60px;
                padding: 10px;
            }

            /* Make the table scrollable */
            .portlet {
                overflow-x: auto;
            }

            /* Prevent table overflow */
            table {
                width: 100%;
                min-width: 800px; /* Ensure columns are not squished */
            }

            /* Handle modal responsiveness */
            .modal-lg {
                max-width: 95%;
            }

            /* Center modal content vertically */
            .modal-dialog-centered {
                min-height: calc(100vh - 1rem);
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            /* Ensure table remains fully visible with horizontal scroll */
            .portlet {
                overflow-x: auto;
            }

            /* Maintain table header and structure */
            table {
                width: 100%;
                min-width: 800px;
            }

            /* Ensure modal image scales correctly */
            #modalImage {
                max-width: 100%;
                height: auto;
            }
        }

    </style>
</head>
<body>

<!-- Include Sidebar and Top Bar -->
@include('/components/layout')

<!-- Main Content -->
<div class="main-content">
    <div class="portlet">
        <div class="portlet-title">
            Patient History
        </div>
        <table id="patientTable" class="display" style="width:100%">
            <thead>
            <tr>
                <th>Patient Name</th>
                <th>Patient Email</th>
                <th>Feedback</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @if($patientHistory && $patientHistory->isNotEmpty())
            @foreach($patientHistory as $history)
            <tr>
                @if($history->user)
                <td>{{ $history->user->name }}</td>
                <td>{{ $history->user->email }}</td>
                @else
                <td>{{ $history->patient_name }}</td>
                <td>{{ $history->patient_email }}</td>
                @endif
                @if($history->feedback)
                <td>{{ $history->feedback }}</td>
                @else
                <td style="text-indent: 40px;">--</td>
                @endif
                <td>{{ $history->created_at->format('Y-m-d')}}</td>
                <td>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal" data-url="{{ $history->image_url }}" data-fracture-size="{{ $history->fracture_size }}" data-healing-time="{{ $history->healing_time }}">
                    <i class="fa fa-eye"></i> View
                </button>
                <button type="button"
                        class="btn btn-outline-success download-btn"
                        data-patient-name="{{ $history->user->name ?? $history->patient_name }}"
                        data-patient-email="{{ $history->user->email ?? $history->patient_email }}"
                        data-fracture-size="{{ $history->fracture_size }}"
                        data-healing-time="{{ $history->healing_time }}"
                        data-url="{{ $history->image_url }}"
                        data-created-date="{{ $history->created_at->format('Y-m-d') }}"
                        data-feedback="{{ $history->feedback ?? 'No feedback available' }}">
                    <i class="fa fa-download"></i> Download PDF
                </button>


                    <button type="button" class="btn btn-outline-danger" onclick="openDeleteModal('{{ $history->id }}')">
                    <i class="fa fa-trash"></i> Delete
                </button>


                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4" class="text-center">No patient history found.</td>
            </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background-color: #ffffff; border-radius: 8px;">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color: #222020; color: white; border-bottom: none;">
                <h5 class="modal-title" id="viewModalLabel">Patient Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="background-color: white; color: #333;">
                <div class="row">
                    <!-- Left Section: Patient Details -->
                    <div class="col-md-6">
                        <h6 class="text-uppercase mb-3" style="color: #666;">Diagnosis Information</h6>
                        <p><strong>Fracture Size:</strong> <span id="modalFractureSize" style="color: #333;"></span> mm</p>
                        <p><strong>Healing Time:</strong> <span id="modalHealingTime" style="color: #333;"></span></p>
                    </div>
                    <!-- Right Section: Image -->
                    <div class="col-md-6 text-center">
                        <h6 class="text-uppercase mb-3" style="color: #666;">Fracture Image</h6>
                        <div class="d-flex justify-content-center">
                            <img id="modalImage" src="" alt="Patient Image"
                                 class="img-fluid rounded shadow"
                                 style="max-height: 300px; background-color: #f8f8f8; padding: 8px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this patient history?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>


<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#patientTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            searching: false,
            language: {
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    next: "Next",
                    previous: "Previous"
                }
            }
        });

    const viewModal = document.getElementById('viewModal');
    viewModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const url = button.getAttribute('data-url');
        const fractureSize = button.getAttribute('data-fracture-size');
        const healingTime = button.getAttribute('data-healing-time');

        document.getElementById('modalFractureSize').textContent = fractureSize || 'N/A';
        document.getElementById('modalHealingTime').textContent = healingTime || 'N/A';
        document.getElementById('modalImage').src = url;
    });
    });

    let selectedId = null;
    document.getElementById('confirmDeleteButton').addEventListener('click', function () {
        if (selectedId) {
            $.ajax({
                url: '/delete-history',
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { id: selectedId },
                success: function (response) {
                    if (response.success) {
                        location.reload();
                        $(`#row-${selectedId}`).remove(); // Dynamically remove the row
                    } else {
                        alert(response.message || 'Failed to delete patient history.');
                    }
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the patient history.');
                }
            });
        }
    });

    function openDeleteModal(id) {
        selectedId = id; // Store the ID in a variable
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        deleteModal.show(); // Show the modal
    }

    $(document).ready(function () {
        $('.download-btn').on('click', function () {
            const patientName = $(this).data('patient-name');
            const patientEmail = $(this).data('patient-email');
            const fractureSize = $(this).data('fracture-size');
            const healingTime = $(this).data('healing-time');
            const imageUrl = $(this).data('url');
            const createdDate = $(this).data('created-date');
            const feedback = $(this).data('feedback'); // Get the diagnosis/feedback

            $.ajax({
                url: "{{ route('download.pdf') }}",
                method: 'POST',
                xhrFields: {
                    responseType: 'blob'
                },
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: patientName,
                    email: patientEmail,
                    fracture_size: fractureSize,
                    healing_time: healingTime,
                    image_url: imageUrl,
                    date: createdDate,
                    feedback: feedback, // Pass the feedback to the controller
                },
                success: function (response, status, xhr) {
                    const filename = "Diagnosis_Report.pdf";

                    const link = document.createElement('a');
                    const url = window.URL.createObjectURL(new Blob([response]));
                    link.href = url;
                    link.download = filename;

                    document.body.appendChild(link);
                    link.click();

                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(link);
                },
                error: function (xhr, status, error) {
                    console.error('Error generating PDF:', error);
                    alert('Failed to download the PDF.');
                }
            });
        });
    });


</script>
</body>
</html>
