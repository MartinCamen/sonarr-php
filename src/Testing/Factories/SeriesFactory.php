<?php

namespace MartinCamen\Sonarr\Testing\Factories;

use MartinCamen\PhpFileSize\FileSize;

class SeriesFactory
{
    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function make(int $id = 1, array $overrides = []): array
    {
        $fileSize = (new FileSize())->gigabytes(5)->toBytes();

        return array_merge([
            'id'                => $id,
            'title'             => "Test Series {$id}",
            'sortTitle'         => "test series {$id}",
            'originalTitle'     => null,
            'year'              => 2024,
            'tvdbId'            => 100000 + $id,
            'tvMazeId'          => 200000 + $id,
            'tvRageId'          => 300000 + $id,
            'imdbId'            => "tt000000{$id}",
            'status'            => 'continuing',
            'overview'          => "This is a test series overview for series {$id}.",
            'monitored'         => true,
            'seriesType'        => 'standard',
            'qualityProfileId'  => 1,
            'languageProfileId' => 1,
            'seasonFolder'      => true,
            'path'              => "/tv/Test Series {$id}",
            'added'             => '2024-01-01T00:00:00Z',
            'firstAired'        => '2024-01-15T00:00:00Z',
            'previousAiring'    => '2024-06-01T20:00:00Z',
            'nextAiring'        => '2024-06-08T20:00:00Z',
            'runtime'           => 45,
            'seasons'           => [
                [
                    'seasonNumber' => 1,
                    'monitored'    => true,
                    'statistics'   => [
                        'episodeCount'      => 10,
                        'episodeFileCount'  => 5,
                        'totalEpisodeCount' => 10,
                        'sizeOnDisk'        => $fileSize,
                        'percentOfEpisodes' => 50.0,
                    ],
                ],
            ],
            'images'          => [],
            'alternateTitles' => [],
            'genres'          => ['Drama', 'Action'],
            'ratings'         => ['tvdb' => ['votes' => 1000, 'value' => 8.5]],
            'statistics'      => [
                'episodeCount'      => 10,
                'episodeFileCount'  => 5,
                'totalEpisodeCount' => 10,
                'sizeOnDisk'        => $fileSize,
            ],
            'ended'             => false,
            'network'           => 'ABC',
            'airTime'           => '20:00',
            'certification'     => 'TV-14',
            'useSceneNumbering' => false,
        ], $overrides);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function makeMany(int $count = 5): array
    {
        $series = [];

        for ($i = 1; $i <= $count; $i++) {
            $series[] = self::make($i);
        }

        return $series;
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function makeWithEpisodes(int $id = 1, array $overrides = []): array
    {
        return self::make($id, array_merge([
            'statistics' => [
                'episodeCount'      => 10,
                'episodeFileCount'  => 10,
                'totalEpisodeCount' => 10,
                'sizeOnDisk'        => (new FileSize())->gigabytes(10)->toBytes(),
            ],
        ], $overrides));
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function makeEnded(int $id = 1, array $overrides = []): array
    {
        return self::make($id, array_merge([
            'status'     => 'ended',
            'ended'      => true,
            'nextAiring' => null,
        ], $overrides));
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function makeUnmonitored(int $id = 1, array $overrides = []): array
    {
        return self::make($id, array_merge([
            'monitored' => false,
        ], $overrides));
    }
}
