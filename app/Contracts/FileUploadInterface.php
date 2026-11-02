<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface FileUploadInterface
{
    /**
     * Upload an uploaded file to storage and return its public URL.
     */
    public function upload(UploadedFile $file, string $directory = 'products'): string;

    /**
     * Delete a stored file by its URL or relative path.
     */
    public function delete(?string $pathOrUrl): bool;

    /**
     * Check if a file exists in storage by its URL or relative path.
     */
    public function exists(?string $pathOrUrl): bool;
}
