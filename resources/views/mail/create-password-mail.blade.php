<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Your Password</title>
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header h2 {
            color: #333;
            margin: 0;
        }
        .content {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            padding: 0 20px;
        }
        .button-container {
            text-align: center;
            padding: 20px;
        }
        .button {
            background-color: #007BFF;
            color: #ffffff;
            padding: 12px 24px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .fallback {
            font-size: 14px;
            color: #777;
        }
        .fallback a {
            color: #007BFF;
            text-decoration: none;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center">
                <table role="presentation" class="email-container" cellspacing="0" cellpadding="0" border="0">

                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <img src="{{ $message->embed(public_path('img/sample-logo.png')) }}" alt="Mary Grace Logo" height="100">
                            <h2>Create Your Password</h2>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <p>Hi {{ $full_name }},</p>
                            <p>You are receiving this email because an account has been created for you.
                            Click the button below to set up your password.</p>
                        </td>
                    </tr>

                    <!-- Button -->
                    <tr>
                        <td class="button-container">
                            <a href="{{ $temporary_url }}" target="_blank" class="button" style="color:white;">Create Password</a>
                        </td>
                    </tr>

                    <!-- Fallback Link -->
                    <tr>
                        <td class="button-container">
                            <p class="fallback">If the button does not work, click this
                                <a href="{{ $temporary_url }}" target="_blank" >link</a>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p>If you didn’t request this email, you can safely ignore it.</p>
                            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
