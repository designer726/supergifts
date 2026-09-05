<?php
/**
 * Resize + re-compress an already-uploaded image in place, to stop huge
 * phone-camera-sized uploads (often 1-3MB) from bloating the live site.
 * No-ops safely (leaves the original file untouched) if GD isn't available
 * or the file isn't a format we know how to re-encode (gif, video, etc).
 */
function compressUploadedImage($absolutePath, $maxWidth = 1600, $quality = 80) {
    if (!extension_loaded('gd') || !file_exists($absolutePath)) return;

    $info = @getimagesize($absolutePath);
    if (!$info) return;
    [$width, $height, $type] = $info;
    if ($width <= 0 || $height <= 0) return;

    switch ($type) {
        case IMAGETYPE_JPEG:
            $src = @imagecreatefromjpeg($absolutePath);
            break;
        case IMAGETYPE_PNG:
            $src = @imagecreatefrompng($absolutePath);
            break;
        case IMAGETYPE_WEBP:
            if (!function_exists('imagecreatefromwebp')) return;
            $src = @imagecreatefromwebp($absolutePath);
            break;
        default:
            return; // gif / bmp / unknown — leave untouched
    }
    if (!$src) return;

    if ($width > $maxWidth) {
        $newWidth  = $maxWidth;
        $newHeight = (int) round($height * ($maxWidth / $width));
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }
        imagecopyresampled($resized, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($src);
        $src = $resized;
    }

    switch ($type) {
        case IMAGETYPE_JPEG:
            @imagejpeg($src, $absolutePath, $quality);
            break;
        case IMAGETYPE_PNG:
            // PNG compression is 0 (none) - 9 (max); map our 0-100 quality to that range.
            @imagepng($src, $absolutePath, (int) round(9 - ($quality / 100) * 9));
            break;
        case IMAGETYPE_WEBP:
            @imagewebp($src, $absolutePath, $quality);
            break;
    }
    imagedestroy($src);
}
