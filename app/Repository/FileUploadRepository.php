<?php

namespace App\Repository;

use App\Contracts\FileUploadInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Override;

class FileUploadRepository implements FileUploadInterface
{
    /**
     * Upload an uploaded file to public storage and return its public URL.
     */
    #[Override]
    public function upload(UploadedFile $file, string $directory = 'products'): string
    {
        $path = $file->store($directory, 'public');

        return url('storage/'.$path);
    }

    /**
     * Delete a file from public storage by URL or relative path.
     */
    #[Override]
    public function delete(?string $pathOrUrl): bool
    {
        if (empty($pathOrUrl)) {
            return false;
        }

        $parsedPath = parse_url($pathOrUrl, PHP_URL_PATH) ?? $pathOrUrl;
        $relativePath = preg_replace('#^/?storage/#', '', $parsedPath);

        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->delete($relativePath);
        }

        return false;
    }

    /**
     * Check if a file exists in public storage by its URL or relative path.
     */
    #[Override]
    public function exists(?string $pathOrUrl): bool
    {
        if (empty($pathOrUrl)) {
            return false;
        }

        $parsedPath = parse_url($pathOrUrl, PHP_URL_PATH) ?? $pathOrUrl;
        $relativePath = preg_replace('#^/?storage/#', '', $parsedPath);

        return Storage::disk('public')->exists($relativePath);
    }
}
