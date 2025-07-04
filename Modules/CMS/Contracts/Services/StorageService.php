<?php

namespace Modules\CMS\Contracts\Services;

use Illuminate\Http\UploadedFile;

interface StorageService
{
    /**
     * @param UploadedFile $file
     * @param string $path
     * @param string $name
     * @return string|null
     */
    public function uploadFile(UploadedFile $file, string $path, string $name = ''): ?string;

    /**
     * @param string $path
     * @return string
     */
    public function getImageUrl(string $path): string;

    /**
     * @param string $url
     * @return string
     */
    public function removeBasePath(string $url): string;
}
