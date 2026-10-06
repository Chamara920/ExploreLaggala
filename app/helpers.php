<?php

if (! function_exists('media_url')) {
    /**
     * Get the public URL for a media file (local storage or remote cloud storage).
     */
    function media_url(?string $path, ?string $fallback = null): string
    {
        if (empty($path)) {
            return $fallback ?? '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $mediaUrl = config('filesystems.media_url');
        if (! empty($mediaUrl)) {
            return rtrim($mediaUrl, '/').'/'.ltrim($path, '/');
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
