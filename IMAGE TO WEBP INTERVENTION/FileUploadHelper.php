<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class FileUploadHelper
{
    public static function uploadSingleFile($file, $directory = 'uploads/', $quality = 50): array
    {
        $type = $file->getClientOriginalExtension();
        $mime = $file->getMimeType();
        $originalName = $file->getClientOriginalName();

        $originalFileName = uniqid() . '.' . $type;
        $originalPath = public_path($directory . $originalFileName);

        // move file
        $file->move($directory, $originalFileName);

        // image হলে webp convert
        if (Str::startsWith($mime, 'image/')) {

            $webpName = uniqid() . '.webp';
            $webpPath = public_path($directory . $webpName);

            Image::read($originalPath)
                ->toWebp($quality)
                ->save($webpPath);

            // original image delete
            if (file_exists($originalPath)) {
                unlink($originalPath);
            }

            $fileUrl = $directory . $webpName;

        } else {
            $fileUrl = $directory . $originalFileName;
        }

        return [
            'file_original_name' => $originalName,
            'file_url'           => $fileUrl,
            'file_type'          => $type,
        ];
    }
}
