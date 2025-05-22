<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .header {
            background-color: #a5b4fc;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
            color: white;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 5px;
            margin: 20px 0;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Verifikasi Email</h2>
        </div>
        
        <p>Halo <strong>{{ $user->name }}</strong>,</p>
        
        <p>Terima kasih telah mendaftar di Fanya Publishing. Untuk menyelesaikan pendaftaran, silakan gunakan kode OTP berikut untuk memverifikasi alamat email Anda:</p>
        
        <div class="otp-code">{{ $otp }}</div>
        
        <p>Kode OTP ini akan kedaluwarsa dalam 10 menit untuk alasan keamanan.</p>
        
        <p>Jika Anda tidak melakukan pendaftaran ini, silakan abaikan email ini.</p>
        
        <div class="footer">
            <p>Ini adalah email otomatis, mohon tidak membalas.</p>
            <p>&copy; {{ date('Y') }} Fanya Publishing. Seluruh hak cipta dilindungi.</p>
        </div>
    </div>
</body>
</html>
