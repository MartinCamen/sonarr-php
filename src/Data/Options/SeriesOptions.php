<?php

namespace MartinCamen\Sonarr\Data\Options;

use MartinCamen\ArrCore\Data\Options\BuildsRequestParams;
use MartinCamen\ArrCore\Data\Options\RequestOptions;

final readonly class SeriesOptions implements RequestOptions
{
    use BuildsRequestParams;

    public function __construct(
        public ?int $tvdbId = null,
        public ?bool $includeSeason = null,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $params = [];

        $this->addIfNotNull($params, 'tvdbId', $this->tvdbId);
        $this->addIfNotNull($params, 'includeSeason', $this->includeSeason);

        return $params;
    }

    public static function make(
        ?int $tvdbId = null,
        ?bool $includeSeason = null,
    ): self {
        return new self(
            tvdbId: $tvdbId,
            includeSeason: $includeSeason,
        );
    }

    public function withTvdbId(int $tvdbId): self
    {
        return new self($tvdbId, $this->includeSeason);
    }

    public function withIncludeSeason(bool $includeSeason): self
    {
        return new self($this->tvdbId, $includeSeason);
    }
}
