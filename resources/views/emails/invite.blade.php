<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan Bergabung</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-w: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
        <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="color: #b71c1c; margin: 0; font-size: 24px;">Xcode Friends</h1>
        </div>
        <h2 style="color: #333333; margin-top: 0;">Halo!</h2>
        <p style="color: #555555; font-size: 16px; line-height: 1.5;">
            Anda telah diundang oleh <strong>{{ $inviterName }}</strong> untuk bergabung ke dalam jejaring sosial kami.
        </p>
        <p style="color: #555555; font-size: 16px; line-height: 1.5;">
            Temukan teman baru, bagikan momen terbaik Anda, dan nikmati pengalaman bersosial yang menyenangkan.
        </p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $inviteUrl }}" style="background-color: #b71c1c; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; display: inline-block;">
                Terima Undangan & Daftar Sekarang
            </a>
        </div>
        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 20px 0;">
        <p style="color: #999999; font-size: 12px; text-align: center;">
            Jika tombol di atas tidak berfungsi, Anda bisa menyalin tautan berikut ke browser Anda:<br>
            <a href="{{ $inviteUrl }}" style="color: #b71c1c; word-break: break-all;">{{ $inviteUrl }}</a>
        </p>
    </div>
</body>
</html>
