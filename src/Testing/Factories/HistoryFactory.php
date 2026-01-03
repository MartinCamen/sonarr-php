<?php

namespace MartinCamen\Sonarr\Testing\Factories;

use MartinCamen\ArrCore\Testing\Factories\ArrHistoryFactory;

class HistoryFactory extends ArrHistoryFactory
{
    /**
     * Get Sonarr-specific default attributes.
     *
     * @return array<string, mixed>
     */
    protected static function getServiceDefaults(int $id): array
    {
        $episodeNumber = str_pad((string) $id, 2, '0', STR_PAD_LEFT);

        return [
            'episodeId'   => $id,
            'seriesId'    => $id,
            'sourceTitle' => "Test.Series.S01E{$episodeNumber}.1080p.BluRay.x264-GROUP",
            'data'        => [
                'indexer'      => 'NZBGeek',
                'nzbInfoUrl'   => 'https://nzbgeek.info/details/' . str_pad((string) $id, 12, '0', STR_PAD_LEFT),
                'releaseGroup' => 'GROUP',
                'age'          => '0',
                'ageHours'     => '0.00',
                'ageMinutes'   => '0.00',
            ],
            'series'  => null,
            'episode' => null,
        ];
    }

    /**
     * Create a deleted episode file history record (Sonarr-specific).
     *
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public static function makeDeleted(int $id = 1, array $overrides = []): array
    {
        return static::make($id, array_merge([
            'eventType' => 'episodeFileDeleted',
            'data'      => [
                'reason' => 'Manual',
            ],
        ], $overrides));
    }
}
