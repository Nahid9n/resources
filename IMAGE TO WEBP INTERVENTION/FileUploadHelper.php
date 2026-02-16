<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class FileUploadHelper
{
    public static function uploadSingleFile($file, $directory = 'uploads/', $quality = 50,$width = null,$height = null): array
    {
        $type = $file->getClientOriginalExtension();
        $mime = $file->getMimeType();
        $originalName = $file->getClientOriginalName();

        // ensure directory exists
        if (!file_exists(public_path($directory))) {
            mkdir(public_path($directory), 0777, true);
        }

        $originalFileName = uniqid() . '.' . $type;
        $originalPath = public_path($directory . $originalFileName);

        // move file
        $file->move($directory, $originalFileName);

        // image processing
        if (Str::startsWith($mime, 'image/')) {

            $webpName = uniqid() . '.webp';
            $webpPath = public_path($directory . $webpName);

            $image = Image::read($originalPath);

            // 🔹 resize only if width/height provided
            if ($width || $height) {
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $image->toWebp($quality)->save($webpPath);

            // delete original image
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
