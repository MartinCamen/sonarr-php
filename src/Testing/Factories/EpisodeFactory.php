<?php

namespace MartinCamen\Sonarr\Testing\Factories;

class EpisodeFactory
{
    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function make(int $id = 1, int $seriesId = 1, int $seasonNumber = 1, int $episodeNumber = 1, array $overrides = []): array
    {
        return array_merge([
            'id'                       => $id,
            'seriesId'                 => $seriesId,
            'tvdbId'                   => 400000 + $id,
            'episodeFileId'            => 0,
            'seasonNumber'             => $seasonNumber,
            'episodeNumber'            => $episodeNumber,
            'title'                    => "Episode {$episodeNumber}",
            'airDate'                  => '2024-01-' . str_pad((string) $episodeNumber, 2, '0', STR_PAD_LEFT),
            'airDateUtc'               => '2024-01-' . str_pad((string) $episodeNumber, 2, '0', STR_PAD_LEFT) . 'T20:00:00Z',
            'overview'                 => "This is the overview for episode {$episodeNumber}.",
            'hasFile'                  => false,
            'monitored'                => true,
            'sceneEpisodeNumber'       => null,
            'sceneSeasonNumber'        => null,
            'absoluteEpisodeNumber'    => $id,
            'runtime'                  => 45,
            'ratings'                  => ['tvdb' => ['votes' => 500, 'value' => 8.0]],
            'images'                   => [],
            'unverifiedSceneNumbering' => false,
        ], $overrides);
    }

    /** @return array<int, array<string, mixed>> */
    public static function makeMany(int $count = 5, int $seriesId = 1, int $seasonNumber = 1): array
    {
        $episodes = [];

        for ($i = 1; $i <= $count; $i++) {
            $episodes[] = self::make($i, $seriesId, $seasonNumber, $i);
        }

        return $episodes;
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function makeDownloaded(int $id = 1, int $seriesId = 1, int $seasonNumber = 1, int $episodeNumber = 1, array $overrides = []): array
    {
        return self::make($id, $seriesId, $seasonNumber, $episodeNumber, array_merge([
            'hasFile'       => true,
            'episodeFileId' => $id,
        ], $overrides));
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function makeUnmonitored(int $id = 1, int $seriesId = 1, int $seasonNumber = 1, int $episodeNumber = 1, array $overrides = []): array
    {
        return self::make($id, $seriesId, $seasonNumber, $episodeNumber, array_merge([
            'monitored' => false,
        ], $overrides));
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function makeMissing(int $id = 1, int $seriesId = 1, int $seasonNumber = 1, int $episodeNumber = 1, array $overrides = []): array
    {
        return self::make($id, $seriesId, $seasonNumber, $episodeNumber, array_merge([
            'hasFile'    => false,
            'monitored'  => true,
            'airDateUtc' => '2024-01-01T20:00:00Z', // Aired in the past
        ], $overrides));
    }
}
