<?php

namespace Modules\CMS\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\CMS\Contracts\Services\StorageService;
use RuntimeException;

class StorageServiceImpl implements StorageService
{
    /**
     * @param string $path
     * @return string
     */
    public function getImageUrl(string $path): string
    {
        return Str::isUrl($path) ? $path : $this->getImageFromPath($path);
    }

    /**
     * @param string $path
     * @return string
     */
    private function getImageFromPath(string $path): string
    {
        if (request()->is('api/*')) {
            return Storage::disk('public')->url($path);
        }

        return asset(Storage::url($path));
    }

    /**
     * @param UploadedFile $file
     * @return array
     */
    private function getFileInfo(UploadedFile $file): array
    {
        $imageSize = getimagesize($file->getPathname());
        $size = null;
        $width = 0;
        $height = 0;
        if ($imageSize) {
            $width = $imageSize[0];
            $height = $imageSize[1];
            $size = $width . 'x' . $height;
        }

        return [
            'width' => $width,
            'height' => $height,
            'size' => $size,
            'type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize()
        ];
    }

    /**
     * @param object $file
     * @param string $name
     * @return string
     */
    private function getFileName(object $file, string $name = ''): string
    {
        if (!$name) {
            $extension = $file->getClientOriginalExtension();
            $name = Str::replace(".$extension", '', $file->getClientOriginalName());
        }

        return $name;
    }

    /**
     * @param string $url
     * @return string
     */
    public function removeBasePath(string $url): string
    {
        $path = Str::replace(config('filesystems.disks.public.url'), '', $url);

        return Str::isUrl($path) ? $path : ltrim($path, '/');
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @param string $name
     * @return string|null
     */
    public function uploadFile(UploadedFile $file, string $path, string $name = ''): ?string
    {
        try {
            $dataInfo = $this->getFileInfo($file);
            $dataInfo['path'] = $path;
            $dataInfo['name'] = $this->getFileName($file, $name);
            $filePath = sprintf('%s/%s.%s', $dataInfo['path'], $dataInfo['name'], $dataInfo['type']);

            $fileContent = file_get_contents($file);
            if (!$fileContent) {
                throw new RuntimeException('The file is invalid.');
            }

            Storage::disk('public')->put($filePath, $fileContent);

            return $filePath;
        } catch (\Exception $e) {
            Log::error('[ERROR_UPLOAD_FILE] =>' . $e->getMessage());

            return null;
        }
    }
}
