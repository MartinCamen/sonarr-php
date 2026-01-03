<?php

namespace MartinCamen\Sonarr\Testing\Factories;

use MartinCamen\ArrCore\Testing\Factories\ArrDownloadFactory;
use MartinCamen\PhpFileSize\FileSize;

class DownloadFactory extends ArrDownloadFactory
{
    /**
     * Get Sonarr-specific default attributes.
     *
     * @return array<string, mixed>
     */
    protected static function getServiceDefaults(int $id): array
    {
        $episodeNumber = str_pad((string) $id, 2, '0', STR_PAD_LEFT);

        $fileSize = new FileSize();

        return [
            'seriesId'   => $id,
            'episodeId'  => $id,
            'title'      => "Test.Series.S01E{$episodeNumber}.1080p.BluRay.mkv",
            'size'       => $fileSize->gigabytes(1.5)->toBytes(),
            'sizeleft'   => $fileSize->megabytes(750)->toBytes(),
            'outputPath' => "/downloads/complete/Test.Series.S01E{$episodeNumber}",
            'series'     => null,
            'episode'    => null,
        ];
    }
}
