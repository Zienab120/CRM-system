HTML

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table {
            border-spacing: 0;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7f6;
            padding-top: 20px;
            padding-bottom: 40px;
        }

        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            color: #333333;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Components */

        .header {
            padding: 30px;
            text-align: center;
            background-color: #2d3748;
        }

        .hero-img {
            width: 100%;
            max-width: 600px;
            height: auto;
            display: block;
            border: 0;
        }

        .content {
            padding: 40px 30px;
            line-height: 1.6;
            font-size: 16px;
            color: #4a5568;
        }

        .button-container {
            text-align: center;
            padding: 10px 0 30px 0;
        }

        .button {
            background-color: #4a90e2;
            color: #ffffff !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #718096;
            padding: 30px;
            background-color: #edf2f7;
        }

        .footer a {
            color: #4a90e2;
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <center class="wrapper">
        <table class="main" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td class="header">
                    <img src="{{ config('app.url') }}/logo.png" alt="Company Logo" width="120"
                        style="display: block; margin: 0 auto;">
                </td>
            </tr>
            @if($heroImage)
                <tr>
                    <td>
                        <img src="{{ $heroImage }}" class="hero-img" alt="Campaign Feature">
                    </td>
                </tr>
            @endif
            <tr>
                <td class="content">
                    <h1 style="margin-top: 0; color: #1a202c; font-size: 24px;">{{ $title }}</h1>
                    <p>Hi {{ $leadName }},</p>
                    <div class="dynamic-content">
                        {!! $content !!}
                    </div>
                </td>
            </tr>
            @if($ctaUrl)
                <tr>
                    <td class="button-container">
                        <a href="{{ route('crm.track', ['type' => 'click', 'lead_id' => $leadId, 'cid' => $campaignId, 'target' => urlencode($ctaUrl)]) }}"
                            class="button">
                            {{ $ctaLabel ?? 'Learn More' }}
                        </a>
                    </td>
                </tr>
            @endif
            <tr>
                <td class="footer">
                    <p style="margin: 0 0 10px 0;">&copy; {{ date('Y') }} {{ config('app.name') }}.
                        All rights reserved.</p>
                    <p style="margin: 0;">
                        Too many emails?
                        <a
                            href="{{ route('crm.track', ['type' => 'bounce', 'lead_id' => $leadId, 'cid' => $campaignId]) }}">
                            Unsubscribe here
                        </a>
                    </p>
                    <img src="{{ route('campaign.track', ['type' => 'open', 'lead_id' => $leadId, 'cid' => $campaignId]) }}"
                        width="1" height="1" style="display:none !important;" alt="" />
                </td>
            </tr>
        </table>
    </center>
</body>

</html>