<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Your Password</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        *{
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
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
            padding-top: 20px;
            border-top: 20px solid #f36c25; /* Add border top */
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
            background-color: #f36c25;
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
            color: #585454;
            padding: 20px 0 20px 0;
            background-color: #f36d2556;
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
                            <img src="{{asset('img/sample-logo.png')}}" alt="Mary Grace Logo" height="100">
                            <h2>Create Your Password</h2>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <p>Hi Sample User,</p>
                            <p>You are receiving this email because an account has been created for you.
                            Click the button below to set up your password.</p>
                        </td>
                    </tr>

                    <!-- Button -->
                    <tr>
                        <td class="button-container">
                            <a href="#" target="_blank" class="button" style="color:white;">Create Password</a>
                        </td>
                    </tr>

                    <!-- Fallback Link -->
                    <tr>
                        <td class="button-container">
                            <p class="fallback">If the button does not work, click this
                                <a href="#" target="_blank" >link</a>.
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
