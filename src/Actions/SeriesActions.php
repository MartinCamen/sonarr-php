<?php

namespace MartinCamen\Sonarr\Actions;

use MartinCamen\ArrCore\Client\RestClientInterface;
use MartinCamen\Sonarr\Data\Enums\SeriesEndpoint;
use MartinCamen\Sonarr\Data\Responses\Series;
use MartinCamen\Sonarr\Data\Responses\SeriesCollection;

/** @link https://sonarr.tv/docs/api/ */
final readonly class SeriesActions
{
    public function __construct(private RestClientInterface $client) {}

    /** Get all series. */
    public function all(?int $tvdbId = null): SeriesCollection
    {
        $params = [];

        if ($tvdbId !== null) {
            $params['tvdbId'] = $tvdbId;
        }

        $result = $this->client->get(SeriesEndpoint::All, $params);

        return SeriesCollection::fromArray($result);
    }

    /** Get series by ID. */
    public function find(int $id): Series
    {
        $result = $this->client->get(SeriesEndpoint::ById, ['id' => $id]);

        return Series::fromArray($result);
    }

    /**
     * Search for series by term (title, TVDb ID, or IMDb ID).
     */
    public function search(string $term): SeriesCollection
    {
        $result = $this->client->get(SeriesEndpoint::Lookup, ['term' => $term]);

        return SeriesCollection::fromArray($result);
    }

    /** Lookup series by TVDb ID. */
    public function searchByTvdb(int $tvdbId): Series
    {
        $result = $this->client->get(SeriesEndpoint::LookupTvdb, ['tvdbId' => $tvdbId]);

        return Series::fromArray($result);
    }

    /**
     * Add a new series.
     *
     * @param array<string, mixed> $seriesData
     */
    public function add(array $seriesData): Series
    {
        $result = $this->client->post(SeriesEndpoint::All, $seriesData);

        return Series::fromArray($result);
    }

    /**
     * Update an existing series.
     *
     * @param array<string, mixed> $seriesData
     */
    public function update(int $id, array $seriesData): Series
    {
        $result = $this->client->put(
            SeriesEndpoint::ById,
            array_merge(['id' => $id], $seriesData),
        );

        return Series::fromArray($result);
    }

    /** Delete a series. */
    public function delete(int $id, bool $deleteFiles = false, bool $addImportListExclusion = false): void
    {
        $this->client->delete(SeriesEndpoint::ById, [
            'id'                     => $id,
            'deleteFiles'            => $deleteFiles,
            'addImportListExclusion' => $addImportListExclusion,
        ]);
    }
}
