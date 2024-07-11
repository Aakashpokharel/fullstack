<!DOCTYPE html>
<html>

<head>
    <title>OTP Email</title>
</head>

<body>
    <h1>Your OTP</h1>
    @if($otpRecord !== null)
    Your OTP: {{ ($otpRecord->otp_code) }}
    @else
    No OTP provided
    @endif
</body>

</html>