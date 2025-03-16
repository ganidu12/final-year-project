<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        table {
            max-width: 500px;
            margin: 30px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-collapse: collapse;
        }
        td {
            padding: 20px;
        }
        .header {
            background-color: #2f2c2c;
            color: white;
            font-size: 22px;
            font-weight: bold;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .content {
            text-align: center;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #2f2c2c;
            padding: 10px;
            background-color: #f8f8f8;
            border-radius: 5px;
            display: inline-block;
            letter-spacing: 2px;
        }
        .footer {
            font-size: 14px;
            color: #777;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>

<table>
    <!-- Header -->
    <tr>
        <td class="header">Bonescope</td>
    </tr>

    <!-- Content -->
    <tr>
        <td class="content">
            <p>Hello,</p>
            <p>You requested a password reset. Use the following code to proceed:</p>
            <p> <?php echo isset($code) ? $code : 'No Code'; ?></p>
            <p>If you did not request this, please ignore this email.</p>
        </td>
    </tr>

    <!-- Footer -->
    <tr>
        <td class="footer">
             Bonescope. All rights reserved.
        </td>
    </tr>
</table>

</body>
</html>
