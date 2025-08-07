<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Password</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border-top: 6px solid #f36c25; /* Accent border */
        }

        .header {
            text-align: center;
            padding: 30px 20px;
            background-color: #fdf2ec;
        }

        .header h2 {
            color: #f36c25; /* Updated to orange */
            font-size: 26px; /* Slightly bigger */
            font-weight: 600;
            margin-top: 12px;
            letter-spacing: 0.5px; /* Added spacing */
        }

        .content {
            font-size: 16px;
            color: #555555;
            line-height: 1.8;
            padding: 25px 30px;
        }

        .content p {
            margin-bottom: 15px;
        }

        .button-container {
            text-align: center;
            padding: 20px;
        }

        .button {
            background-color: #f36c25;
            color: #ffffff;
            padding: 14px 28px;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            transition: background-color 0.3s ease-in-out;
        }

        .button:hover {
            background-color: #e55a1d;
        }

        .fallback {
            font-size: 14px;
            color: #777777;
            margin-top: 10px;
        }

        .fallback a {
            color: #f36c25;
            text-decoration: none;
            font-weight: 500;
        }

        .fallback a:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #585454;
            padding: 15px 20px;
            background-color: #f8f9fa;
        }

        .footer p {
            margin: 5px 0;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .email-container {
                width: 100%;
                border-radius: 0;
            }
            .content, .button-container {
                padding: 20px;
            }
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
                            <img src="{{ $message->embed(public_path('img/sample-logo.png')) }}" alt="Company Logo" height="80">
                            <h2>Create Your Password</h2>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <p>Hi {{ $full_name }},</p>
                            <p>We're excited to have you onboard! An account has been created for you.</p>
                            <p>Click the button below to create your password and access your account.</p>
                        </td>
                    </tr>

                    <!-- Button -->
                    <tr>
                        <td class="button-container">
                            <a href="{{ $temporary_url }}" target="_blank" class="button" style="color:white">Create Password</a>
                        </td>
                    </tr>

                    <!-- Fallback Link -->
                    <tr>
                        <td class="button-container">
                            <p class="fallback">If the button doesn't work, please click this
                                <a href="{{ $temporary_url }}" target="_blank">link</a>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p>If you didn't request this email, you can safely ignore it.</p>
                            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
