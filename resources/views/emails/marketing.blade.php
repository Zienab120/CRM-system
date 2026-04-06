<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Offer</title>
    <style>
        /* Reset and basic styles */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            font-family: 'Helvetica', Arial, sans-serif;
            background-color: #f4f4f4;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Header */
        .email-header {
            background: #1e3a8a;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Body */
        .email-body {
            padding: 30px;
            color: #333333;
            line-height: 1.5;
        }

        .email-body h2 {
            color: #1e3a8a;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .email-body p {
            margin-bottom: 20px;
        }

        /* Button */
        .btn {
            display: inline-block;
            background-color: #1e3a8a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #374eb8;
        }

        /* Footer */
        .email-footer {
            background: #f0f0f0;
            color: #555555;
            text-align: center;
            padding: 20px;
            font-size: 12px;
        }

        /* Responsive */
        @media screen and (max-width: 600px) {

            .email-body,
            .email-header,
            .email-footer {
                padding: 20px !important;
            }

            .btn {
                padding: 10px 20px !important;
                font-size: 16px !important;
            }
        }
    </style>
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="email-container" width="100%" cellpadding="0" cellspacing="0">

                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            <h1>Exclusive Offer Just for You!</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="email-body">
                            {{-- Static greeting --}}
                            <h2>Hello {{ $user->name ?? 'Friend' }},</h2>

                            {{-- Dynamic main content --}}
                            {!! $body ?? '' !!}

                            {{-- Static CTA button --}}
                            <p style="text-align:center;">
                                <a href="{{ $link ?? '#' }}" class="btn">Claim Your Offer</a>
                            </p>

                            {{-- Static closing --}}
                            <p>Thank you for being a valued member of our community. We can’t wait to see you take
                                advantage of this offer!</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
                            <p>If you prefer not to receive marketing emails, <a
                                    href="{{ $unsubscribe ?? '#' }}">unsubscribe here</a>.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>