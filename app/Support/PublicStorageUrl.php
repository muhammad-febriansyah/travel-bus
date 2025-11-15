<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class PublicStorageUrl
{
    /**
     * Resolve a publicly accessible URL for a stored file.
     * If the file lives on the private disk, move it to the public disk first.
     */
    public static function make(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $publicDisk = Storage::disk('public');

        if ($publicDisk->exists($path)) {
            return $publicDisk->url($path);
        }

        $privateDisk = Storage::disk('local');

        if ($privateDisk->exists($path)) {
            $contents = $privateDisk->get($path);

            if ($publicDisk->put($path, $contents)) {
                $privateDisk->delete($path);

                return $publicDisk->url($path);
            }
        }

        return null;
    }
}
