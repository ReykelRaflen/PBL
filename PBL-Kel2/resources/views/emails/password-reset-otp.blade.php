<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Reset Password</title>
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
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        .otp-container {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #4a5568;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Fanya Publishing</h2>
            <h3>Reset Password</h3>
        </div>
        
        <p>Halo {{ $user->name }},</p>
        
        <p>Kami menerima permintaan untuk mereset password akun Anda. Gunakan kode OTP berikut untuk melanjutkan proses reset password:</p>
        
        <div class="otp-container">
            <div class="otp-code">{{ $otp }}</div>
        </div>
        
        <p>Kode OTP ini akan kedaluwarsa dalam 10 menit.</p>
        
        <p>Jika Anda tidak meminta reset password, Anda dapat mengabaikan email ini dan tidak ada perubahan yang akan dilakukan pada akun Anda.</p>
        
        <p>Terima kasih,<br>Tim Fanya Publishing</p>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon jangan membalas email ini.</p>
            <p>&copy; 2025 CV. Fanya Bintang Sejahtera. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
