<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Attendance</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
            background: #f3f4f6;
        }
        .certificate-wrapper {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            box-sizing: border-box;
        }
        .certificate {
            width: 100%;
            max-width: 950px;
            background: white;
            border: 8px solid #f59e0b;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            text-align: center;
            position: relative;
            box-sizing: border-box;
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 2px solid #fde68a;
            border-radius: 12px;
            pointer-events: none;
        }
        .badge {
            font-size: 14px;
            color: #d97706;
            text-transform: uppercase;
            letter-spacing: 6px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 48px;
            color: #1f2937;
            margin: 0 0 10px 0;
            font-weight: 800;
        }
        .subtitle {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 40px;
        }
        .presented {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        .recipient {
            font-size: 42px;
            color: #1f2937;
            font-weight: 700;
            margin: 10px 0 20px 0;
            border-bottom: 3px solid #f59e0b;
            display: inline-block;
            padding-bottom: 10px;
        }
        .for-text {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .event-name {
            font-size: 28px;
            color: #1f2937;
            font-weight: 700;
            margin: 5px 0 30px 0;
        }
        .details {
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 40px;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #f3f4f6;
        }
        .footer-left {
            text-align: left;
        }
        .footer-left .label {
            font-size: 12px;
            color: #9ca3af;
        }
        .footer-left .value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
        }
        .footer-right {
            text-align: right;
        }
        .footer-right .label {
            font-size: 12px;
            color: #9ca3af;
        }
        .footer-right .value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
        }
        .seal {
            margin-top: 20px;
        }
        .seal svg {
            width: 60px;
            height: 60px;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="certificate">
            <div class="badge">Certificate of Attendance</div>
            <h1>SmartEvent</h1>
            <div class="subtitle">This certificate is proudly presented to</div>
            <div class="recipient">{{ $user->name }}</div>
            <div class="for-text">for attending</div>
            <div class="event-name">{{ $event->title }}</div>
            <div class="details">
                Held on {{ $event->event_date->format('F j, Y') }} {{ $event->venue ? 'at ' . $event->venue : '' }}
            </div>
            <div class="footer">
                <div class="footer-left">
                    <div class="label">Certificate Number</div>
                    <div class="value">{{ $certificate->certificate_number }}</div>
                </div>
                <div class="footer-right">
                    <div class="label">Issue Date</div>
                    <div class="value">{{ $certificate->issued_at ? $certificate->issued_at->format('F j, Y') : now()->format('F j, Y') }}</div>
                </div>
            </div>
            <div class="seal">
                <svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="#f59e0b" stroke-width="2" fill="none"/>
                    <circle cx="30" cy="30" r="22" stroke="#f59e0b" stroke-width="1.5" fill="none"/>
                    <text x="30" y="35" text-anchor="middle" fill="#f59e0b" font-size="16" font-weight="bold">✓</text>
                </svg>
            </div>
        </div>
    </div>
</body>
</html>
