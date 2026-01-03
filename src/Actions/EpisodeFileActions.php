<?php

namespace MartinCamen\Sonarr\Actions;

use MartinCamen\ArrCore\Client\RestClientInterface;
use MartinCamen\Sonarr\Data\Enums\EpisodeFileEndpoint;
use MartinCamen\Sonarr\Data\Responses\EpisodeFile;
use MartinCamen\Sonarr\Data\Responses\EpisodeFileCollection;

/** @link https://sonarr.tv/docs/api/ */
final readonly class EpisodeFileActions
{
    public function __construct(private RestClientInterface $client) {}

    /** Get all episode files, optionally filtered by series. */
    public function all(?int $seriesId = null): EpisodeFileCollection
    {
        $params = [];

        if ($seriesId !== null) {
            $params['seriesId'] = $seriesId;
        }

        $result = $this->client->get(EpisodeFileEndpoint::All, $params);

        return EpisodeFileCollection::fromArray($result);
    }

    /** Get episode file by ID. */
    public function find(int $id): EpisodeFile
    {
        $result = $this->client->get(EpisodeFileEndpoint::ById, ['id' => $id]);

        return EpisodeFile::fromArray($result);
    }

    /**
     * Delete an episode file.
     */
    public function delete(int $id): void
    {
        $this->client->delete(EpisodeFileEndpoint::ById, ['id' => $id]);
    }

    /**
     * Bulk delete episode files.
     *
     * @param array<int, int> $episodeFileIds
     */
    public function bulkDelete(array $episodeFileIds): void
    {
        $this->client->delete(EpisodeFileEndpoint::Bulk, [
            'episodeFileIds' => $episodeFileIds,
        ]);
    }
}
