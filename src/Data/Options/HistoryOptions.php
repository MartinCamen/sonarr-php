<?php

namespace MartinCamen\Sonarr\Data\Options;

use MartinCamen\ArrCore\Data\Options\BuildsRequestParams;
use MartinCamen\ArrCore\Data\Options\HistoryRequestOptions;
use MartinCamen\Sonarr\Data\Enums\HistoryEventType;

final readonly class HistoryOptions implements HistoryRequestOptions
{
    use BuildsRequestParams;

    /**
     * @param array<int, int>|null $seriesIds
     * @param array<int, int>|null $episodeIds
     */
    public function __construct(
        public ?HistoryEventType $eventType = null,
        public ?bool $includeSeries = null,
        public ?bool $includeEpisode = null,
        public ?array $seriesIds = null,
        public ?array $episodeIds = null,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $params = [];

        $this->addEnumIfNotNull($params, 'eventType', $this->eventType);
        $this->addIfNotNull($params, 'includeSeries', $this->includeSeries);
        $this->addIfNotNull($params, 'includeEpisode', $this->includeEpisode);
        $this->addArrayAsStringIfNotNull($params, 'seriesIds', $this->seriesIds);
        $this->addArrayAsStringIfNotNull($params, 'episodeIds', $this->episodeIds);

        return $params;
    }

    /**
     * @param array<int, int>|null $seriesIds
     * @param array<int, int>|null $episodeIds
     */
    public static function make(
        ?HistoryEventType $eventType = null,
        ?bool $includeSeries = null,
        ?bool $includeEpisode = null,
        ?array $seriesIds = null,
        ?array $episodeIds = null,
    ): self {
        return new self(
            eventType: $eventType,
            includeSeries: $includeSeries,
            includeEpisode: $includeEpisode,
            seriesIds: $seriesIds,
            episodeIds: $episodeIds,
        );
    }

    public function withEventType(HistoryEventType $eventType): self
    {
        return new self(
            $eventType,
            $this->includeSeries,
            $this->includeEpisode,
            $this->seriesIds,
            $this->episodeIds,
        );
    }

    public function withIncludeSeries(bool $includeSeries): self
    {
        return new self(
            $this->eventType,
            $includeSeries,
            $this->includeEpisode,
            $this->seriesIds,
            $this->episodeIds,
        );
    }

    public function withIncludeEpisode(bool $includeEpisode): self
    {
        return new self(
            $this->eventType,
            $this->includeSeries,
            $includeEpisode,
            $this->seriesIds,
            $this->episodeIds,
        );
    }

    /** @param array<int, int> $seriesIds */
    public function withSeriesIds(array $seriesIds): self
    {
        return new self(
            $this->eventType,
            $this->includeSeries,
            $this->includeEpisode,
            $seriesIds,
            $this->episodeIds,
        );
    }

    /** @param array<int, int> $episodeIds */
    public function withEpisodeIds(array $episodeIds): self
    {
        return new self(
            $this->eventType,
            $this->includeSeries,
            $this->includeEpisode,
            $this->seriesIds,
            $episodeIds,
        );
    }
}
