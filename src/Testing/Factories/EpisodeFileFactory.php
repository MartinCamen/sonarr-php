<?php

namespace MartinCamen\Sonarr\Testing\Factories;

use MartinCamen\ArrCore\ValueObject\ArrFileSize;

class EpisodeFileFactory
{
    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function make(int $id = 1, int $seriesId = 1, int $seasonNumber = 1, array $overrides = []): array
    {
        return array_merge([
            'id'           => $id,
            'seriesId'     => $seriesId,
            'seasonNumber' => $seasonNumber,
            'relativePath' => "Season {$seasonNumber}/Test.Series.S" . str_pad((string) $seasonNumber, 2, '0', STR_PAD_LEFT) . 'E' . str_pad((string) $id, 2, '0', STR_PAD_LEFT) . '.1080p.BluRay.mkv',
            'path'         => "/tv/Test Series/Season {$seasonNumber}/Test.Series.S" . str_pad((string) $seasonNumber, 2, '0', STR_PAD_LEFT) . 'E' . str_pad((string) $id, 2, '0', STR_PAD_LEFT) . '.1080p.BluRay.mkv',
            'size'         => ArrFileSize::fromGigabytes(1.5)->toBytes(),
            'dateAdded'    => '2024-01-01T00:00:00Z',
            'sceneName'    => 'Test.Series.S' . str_pad((string) $seasonNumber, 2, '0', STR_PAD_LEFT) . 'E' . str_pad((string) $id, 2, '0', STR_PAD_LEFT) . '.1080p.BluRay.x264-GROUP',
            'releaseGroup' => 'GROUP',
            'quality'      => [
                'quality' => [
                    'id'   => 7,
                    'name' => 'Bluray-1080p',
                ],
            ],
            'mediaInfo' => [
                'videoCodec'    => 'x264',
                'audioCodec'    => 'AAC',
                'audioChannels' => 6,
                'runTime'       => '00:45:00',
            ],
            'qualityWeight' => 7,
        ], $overrides);
    }

    /** @return array<int, array<string, mixed>> */
    public static function makeMany(int $count = 5, int $seriesId = 1, int $seasonNumber = 1): array
    {
        $files = [];

        for ($i = 1; $i <= $count; $i++) {
            $files[] = self::make($i, $seriesId, $seasonNumber);
        }

        return $files;
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function makeHdtv(int $id = 1, int $seriesId = 1, int $seasonNumber = 1, array $overrides = []): array
    {
        return self::make($id, $seriesId, $seasonNumber, array_merge([
            'quality' => [
                'quality' => [
                    'id'   => 4,
                    'name' => 'HDTV-1080p',
                ],
            ],
            'qualityWeight' => 4,
            'size'          => ArrFileSize::zero()->gigabyte()->toBytes(),
        ], $overrides));
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function makeWebdl(int $id = 1, int $seriesId = 1, int $seasonNumber = 1, array $overrides = []): array
    {
        return self::make($id, $seriesId, $seasonNumber, array_merge([
            'quality' => [
                'quality' => [
                    'id'   => 5,
                    'name' => 'WEBDL-1080p',
                ],
            ],
            'qualityWeight' => 5,
            'size'          => ArrFileSize::fromGigabytes(1.2)->toBytes(),
        ], $overrides));
    }
}
