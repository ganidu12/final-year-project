<!DOCTYPE html>
<html>
<head>
    <title>BoneScope - Patient Medical Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 30px;
        }

        table {
            width: 80%;
            margin: auto;
            background: #ffffff;
            border-collapse: collapse;
            border: 1px solid #ddd;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #004085;
            color: white;
            padding: 14px;
            font-size: 20px;
            text-align: center;
            text-transform: uppercase;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
            text-align: left;
        }

        .header {
            background-color: #004085;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 22px;
            font-weight: bold;
        }

        .sub-header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            padding: 10px;
            color: #004085;
        }

        .label {
            font-weight: bold;
            color: #333;
            width: 40%;
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #777;
            border-top: 2px solid #004085;
        }

        .signature-section {
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
        }

        .signature {
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>

<table>
    <!-- Header Section -->
    <tr>
        <th colspan="2">
            <span style="font-size: 20px; font-weight: bold;">BoneScope - Advanced Bone Fracture Analysis System</span>
        </th>
    </tr>


    <!-- Report Details -->
    <tr>
        <th colspan="2" class="sub-header">Fracture Analysis Report</th>
    </tr>
    <tr>
        <td class="label">Diagnosis:</td>
        <td>{{ $data['diagnosis'] }}</td>
    </tr>
    <tr>
        <td class="label">Attending Physician:</td>
        <td>{{ $data['doctorName'] }}</td>
    </tr>
    <tr>
        <td class="label">Fracture Size:</td>
        <td>{{ $data['fracture_size'] }} mm</td>
    </tr>
    <tr>
        <td class="label">Estimated Healing Time:</td>
        <td>{{ $data['healing_time'] }}</td>
    </tr>

    <!-- Clinical Notes -->
    <tr>
        <th colspan="2" class="sub-header">Clinical Notes</th>
    </tr>
    <tr>
        <td colspan="2">
            The fracture has been analyzed using BoneScope's AI-based fracture detection system.
            The estimated healing time is based on standard recovery periods but may vary based on the patient’s health condition and adherence to medical guidelines.
        </td>
    </tr>


    <!-- Footer -->
    <tr>
        <td colspan="2" class="footer">
            This report is generated based on AI-assisted medical analysis.
            If you have any concerns, please consult your healthcare provider.<br>
            **Not valid for medico-legal purposes**
        </td>
    </tr>
</table>

</body>
</html>
