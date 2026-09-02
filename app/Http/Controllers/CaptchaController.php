<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    /**
     * Generate captcha image dan simpan teks ke session.
     * PENTING: Gunakan Laravel response() agar session tersimpan dengan benar.
     */
    public function generate()
    {
        // Buat teks captcha acak (5 karakter: huruf besar + angka, tanpa yang mirip: O, I, 0, 1)
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $text  = '';
        for ($i = 0; $i < 5; $i++) {
            $text .= $chars[random_int(0, strlen($chars) - 1)];
        }

        // Simpan ke session
        session(['captcha_text' => $text]);
        // Force save session SEBELUM output gambar
        session()->save();

        // Ukuran gambar
        $width  = 160;
        $height = 52;

        // Buat canvas
        $image = imagecreatetruecolor($width, $height);

        // Warna latar
        $bg        = imagecolorallocate($image, 248, 248, 248);
        $darkRed   = imagecolorallocate($image, 153,   0,   0);
        $darkGray  = imagecolorallocate($image, 70,   70,  70);
        imagefilledrectangle($image, 0, 0, $width, $height, $bg);

        // Garis noise acak
        for ($i = 0; $i < 6; $i++) {
            $lc = imagecolorallocate($image, rand(180, 220), rand(180, 220), rand(180, 220));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lc);
        }

        // Titik noise
        for ($i = 0; $i < 80; $i++) {
            $dc = imagecolorallocate($image, rand(150, 210), rand(150, 210), rand(150, 210));
            imagesetpixel($image, rand(0, $width), rand(0, $height), $dc);
        }

        // Tulis karakter satu per satu
        $x = 14;
        for ($i = 0; $i < strlen($text); $i++) {
            $char      = $text[$i];
            $fontIndex = 5; // Font built-in GD terbesar
            $y         = rand(10, 20); // Posisi Y sedikit acak
            $charColor = ($i % 2 === 0) ? $darkGray : $darkRed;
            imagestring($image, $fontIndex, $x, $y, $char, $charColor);
            $x += rand(25, 30);
        }

        // Garis tipis di atas teks untuk noise tambahan
        for ($i = 0; $i < 3; $i++) {
            $lc2 = imagecolorallocate($image, rand(180, 200), rand(180, 200), rand(180, 200));
            imageline($image, rand(0, $width/2), rand(15, $height-10), rand($width/2, $width), rand(15, $height-10), $lc2);
        }

        // Ambil output image ke buffer — JANGAN pakai exit/header langsung
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        // Kembalikan sebagai response Laravel yang benar
        return response($imageData, 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Verifikasi jawaban captcha dari session.
     * Case-insensitive.
     */
    public static function verify(string $answer): bool
    {
        $expected = session('captcha_text', '');

        // Hapus captcha dari session setelah digunakan (one-time use)
        session()->forget('captcha_text');

        return strtoupper(trim($answer)) === strtoupper(trim($expected));
    }
}
