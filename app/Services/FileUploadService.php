<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileUploadService
{
    private static string $directory = 'uploads';
    private static int $maxSize = 5242880; // 5MB
    private static array $allowedFormats = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];

    /**
     * Upload file to public path
     */
    public static function upload(
        UploadedFile $file,
        ?string $directory = null,
        ?int $maxSize = null,
        ?array $allowedFormats = null
    ): string {
        $directory = $directory ?? self::$directory;
        $maxSize = $maxSize ?? self::$maxSize;
        $allowedFormats = $allowedFormats ?? self::$allowedFormats;

        // Validate file size
        if ($file->getSize() > $maxSize) {
            throw new \Exception("File size exceeds limit of " . ($maxSize / 1024 / 1024) . "MB");
        }

        // Validate format
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedFormats)) {
            throw new \Exception("File format not allowed. Allowed: " . implode(', ', $allowedFormats));
        }

        // Create unique filename
        $filename = uniqid() . '_' . time() . '.' . $extension;

        // Store file
        $path = public_path($directory);
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $file->move($path, $filename);

        // Return full path
        return $directory . '/' . $filename;
        // return public_path($directory . '/' . $filename);
    }

    /**
     * Delete file
     */
    public static function delete(string $filePath): bool
    {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Get file URL for web access
     */
   public static function getUrl(string $relativePath): string
{
    // Simply wrap the relative path in the asset helper
    return asset($relativePath);
}
}
