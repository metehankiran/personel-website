<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageGenerator
{
    public static function placeholder(int $width, int $height, string $text, string $directory = 'placeholders'): string
    {
        $image = imagecreatetruecolor($width, $height);

        $bg = imagecolorallocate($image, rand(100, 200), rand(100, 200), rand(100, 200));
        imagefill($image, 0, 0, $bg);

        $white = imagecolorallocate($image, 255, 255, 255);
        $fontWidth = imagefontwidth(5);
        $fontHeight = imagefontheight(5);
        $x = (int) (($width - strlen($text) * $fontWidth) / 2);
        $y = (int) (($height - $fontHeight) / 2);
        imagestring($image, 5, max($x, 0), max($y, 0), $text, $white);

        ob_start();
        imagepng($image);
        $contents = ob_get_clean();
        imagedestroy($image);

        $path = $directory.'/'.Str::uuid().'.png';
        Storage::disk('public')->put($path, $contents);

        return $path;
    }

    public static function avatar(string $initials, int $size = 200): string
    {
        return static::placeholder($size, $size, $initials, 'avatars');
    }

    public static function logo(string $name, int $width = 300, int $height = 100): string
    {
        return static::placeholder($width, $height, $name, 'brands');
    }

    public static function cover(string $text, int $width = 1200, int $height = 630): string
    {
        return static::placeholder($width, $height, $text, 'covers');
    }
}
