<?php

namespace MartinCamen\Sonarr\Data\Options;

use MartinCamen\ArrCore\Data\Options\BuildsRequestParams;
use MartinCamen\ArrCore\Data\Options\RequestOptions;

final readonly class DownloadOptions implements RequestOptions
{
    use BuildsRequestParams;

    public function __construct(
        public ?bool $includeUnknownSeriesItems = null,
        public ?bool $includeSeries = null,
        public ?bool $includeEpisode = null,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $params = [];

        $this->addIfNotNull($params, 'includeUnknownSeriesItems', $this->includeUnknownSeriesItems);
        $this->addIfNotNull($params, 'includeSeries', $this->includeSeries);
        $this->addIfNotNull($params, 'includeEpisode', $this->includeEpisode);

        return $params;
    }

    public static function make(
        ?bool $includeUnknownSeriesItems = null,
        ?bool $includeSeries = null,
        ?bool $includeEpisode = null,
    ): self {
        return new self(
            includeUnknownSeriesItems: $includeUnknownSeriesItems,
            includeSeries: $includeSeries,
            includeEpisode: $includeEpisode,
        );
    }

    public function withIncludeUnknownSeriesItems(bool $includeUnknownSeriesItems): self
    {
        return new self($includeUnknownSeriesItems, $this->includeSeries, $this->includeEpisode);
    }

    public function withIncludeSeries(bool $includeSeries): self
    {
        return new self($this->includeUnknownSeriesItems, $includeSeries, $this->includeEpisode);
    }

    public function withIncludeEpisode(bool $includeEpisode): self
    {
        return new self($this->includeUnknownSeriesItems, $this->includeSeries, $includeEpisode);
    }
}
