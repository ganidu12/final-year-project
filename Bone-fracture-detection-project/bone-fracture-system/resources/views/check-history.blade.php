<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .btn-outline-danger {
            color: #e74c3c;
            border-color: #e74c3c;
        }
        .btn-outline-danger:hover {
            background-color: #e74c3c;
            color: white;
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
        <table>
            <thead>
            <tr>
                <th>Patient Name</th>
                <th>Patient Email</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>John Doe</td>
                <td>john.doe@example.com</td>
                <td>
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fa fa-eye"></i> View
                    </a>
                    <a href="#" class="btn btn-outline-danger">
                        <i class="fa fa-trash"></i> Delete
                    </a>
                </td>
            </tr>
            <tr>
                <td>Jane Smith</td>
                <td>jane.smith@example.com</td>
                <td>
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fa fa-eye"></i> View
                    </a>
                    <a href="#" class="btn btn-outline-danger">
                        <i class="fa fa-trash"></i> Delete
                    </a>
                </td>
            </tr>
            <tr>
                <td>Mark Johnson</td>
                <td>mark.johnson@example.com</td>
                <td>
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fa fa-eye"></i> View
                    </a>
                    <a href="#" class="btn btn-outline-danger">
                        <i class="fa fa-trash"></i> Delete
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
