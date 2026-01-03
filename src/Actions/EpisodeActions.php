<?php

namespace MartinCamen\Sonarr\Actions;

use MartinCamen\ArrCore\Client\RestClientInterface;
use MartinCamen\Sonarr\Data\Enums\EpisodeEndpoint;
use MartinCamen\Sonarr\Data\Options\EpisodeOptions;
use MartinCamen\Sonarr\Data\Responses\Episode;
use MartinCamen\Sonarr\Data\Responses\EpisodeCollection;

/** @link https://sonarr.tv/docs/api/ */
final readonly class EpisodeActions
{
    public function __construct(private RestClientInterface $client) {}

    /** Get all episodes with optional filters. */
    public function all(?EpisodeOptions $options = null): EpisodeCollection
    {
        $params = $options?->toArray() ?? [];

        $result = $this->client->get(EpisodeEndpoint::All, $params);

        return EpisodeCollection::fromArray($result);
    }

    /** Get episode by ID. */
    public function find(int $id): Episode
    {
        $result = $this->client->get(EpisodeEndpoint::ById, ['id' => $id]);

        return Episode::fromArray($result);
    }

    /** Get all episodes for a specific series. */
    public function forSeries(int $seriesId, ?int $seasonNumber = null): EpisodeCollection
    {
        $options = new EpisodeOptions(
            seriesId: $seriesId,
            seasonNumber: $seasonNumber,
        );

        return $this->all($options);
    }

    /**
     * Update an episode.
     *
     * @param array<string, mixed> $episodeData
     */
    public function update(int $id, array $episodeData): Episode
    {
        $result = $this->client->put(
            EpisodeEndpoint::ById,
            array_merge(['id' => $id], $episodeData),
        );

        return Episode::fromArray($result);
    }
}
