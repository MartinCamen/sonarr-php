<?php

namespace MartinCamen\Sonarr\Data\Options;

use MartinCamen\ArrCore\Data\Options\BuildsRequestParams;
use MartinCamen\ArrCore\Data\Options\RequestOptions;

final readonly class EpisodeOptions implements RequestOptions
{
    use BuildsRequestParams;

    /** @param array<int, int>|null $episodeIds */
    public function __construct(
        public ?int $seriesId = null,
        public ?int $seasonNumber = null,
        public ?array $episodeIds = null,
        public ?bool $includeImages = null,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $params = [];

        $this->addIfNotNull($params, 'seriesId', $this->seriesId);
        $this->addIfNotNull($params, 'seasonNumber', $this->seasonNumber);
        $this->addArrayAsStringIfNotNull($params, 'episodeIds', $this->episodeIds);
        $this->addIfNotNull($params, 'includeImages', $this->includeImages);

        return $params;
    }

    /** @param array<int, int>|null $episodeIds */
    public static function make(
        ?int $seriesId = null,
        ?int $seasonNumber = null,
        ?array $episodeIds = null,
        ?bool $includeImages = null,
    ): self {
        return new self(
            seriesId: $seriesId,
            seasonNumber: $seasonNumber,
            episodeIds: $episodeIds,
            includeImages: $includeImages,
        );
    }

    public function forSeries(int $seriesId, ?int $seasonNumber = null): self
    {
        return new self($seriesId, $seasonNumber, $this->episodeIds, $this->includeImages);
    }

    /** @param array<int, int> $episodeIds */
    public function withEpisodeIds(array $episodeIds): self
    {
        return new self($this->seriesId, $this->seasonNumber, $episodeIds, $this->includeImages);
    }

    public function withIncludeImages(bool $includeImages): self
    {
        return new self($this->seriesId, $this->seasonNumber, $this->episodeIds, $includeImages);
    }
}
