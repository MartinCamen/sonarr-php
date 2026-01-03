<?php

namespace MartinCamen\Sonarr\Data\Options;

use DateTimeInterface;
use MartinCamen\ArrCore\Data\Options\BuildsRequestParams;
use MartinCamen\ArrCore\Data\Options\RequestOptions;

final readonly class CalendarOptions implements RequestOptions
{
    use BuildsRequestParams;

    /** @param array<int, int>|null $tags */
    public function __construct(
        public ?DateTimeInterface $start = null,
        public ?DateTimeInterface $end = null,
        public ?bool $unmonitored = null,
        public ?int $seriesId = null,
        public ?array $tags = null,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $params = [];

        $this->addDateIfNotNull($params, 'start', $this->start);
        $this->addDateIfNotNull($params, 'end', $this->end);
        $this->addIfNotNull($params, 'unmonitored', $this->unmonitored);
        $this->addIfNotNull($params, 'seriesId', $this->seriesId);
        $this->addArrayAsStringIfNotNull($params, 'tags', $this->tags);

        return $params;
    }

    /** @param array<int, int>|null $tags */
    public static function make(
        ?DateTimeInterface $start = null,
        ?DateTimeInterface $end = null,
        ?bool $unmonitored = null,
        ?int $seriesId = null,
        ?array $tags = null,
    ): self {
        return new self(
            start: $start,
            end: $end,
            unmonitored: $unmonitored,
            seriesId: $seriesId,
            tags: $tags,
        );
    }

    public function withDateRange(?DateTimeInterface $start, ?DateTimeInterface $end): self
    {
        return new self($start, $end, $this->unmonitored, $this->seriesId, $this->tags);
    }

    public function withUnmonitored(bool $unmonitored): self
    {
        return new self($this->start, $this->end, $unmonitored, $this->seriesId, $this->tags);
    }

    public function forSeries(int $seriesId): self
    {
        return new self($this->start, $this->end, $this->unmonitored, $seriesId, $this->tags);
    }

    /** @param  array<int, int>  $tags */
    public function withTags(array $tags): self
    {
        return new self($this->start, $this->end, $this->unmonitored, $this->seriesId, $tags);
    }
}
