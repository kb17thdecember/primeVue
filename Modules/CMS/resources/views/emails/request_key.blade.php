<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectText }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px;
        }
        .email-wrapper {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        .footer {
            margin-top: 40px;
            font-size: 0.8rem;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <h2>{{ $subjectText }}</h2>
    <p>{!! nl2br(e($content)) !!}</p>

    <div class="footer">
        This email was sent from kb17thdecember.
{{--        This email was sent from {{ config('app.name') }}.--}}
    </div>
</div>
</body>
</html>
