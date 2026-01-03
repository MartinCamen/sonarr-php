<?php

namespace MartinCamen\Sonarr\Data\Responses;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, Series>
 */
final class SeriesCollection implements Countable, IteratorAggregate
{
    /** @param  array<int, Series>  $series */
    public function __construct(private array $series = []) {}

    /** @param  array<int, array<string, mixed>>  $data */
    public static function fromArray(array $data): self
    {
        return new self(
            array_map(
                Series::fromArray(...),
                $data,
            ),
        );
    }

    /** @return array<int, Series> */
    public function all(): array
    {
        return $this->series;
    }

    public function count(): int
    {
        return count($this->series);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function first(): ?Series
    {
        return $this->series[0] ?? null;
    }

    public function last(): ?Series
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->series[$this->count() - 1];
    }

    public function get(int $index): ?Series
    {
        return $this->series[$index] ?? null;
    }

    /** @return Traversable<int, Series> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->series);
    }

    /** @return array<int, array<string, mixed>> */
    public function toArray(): array
    {
        return array_map(
            fn(Series $series): array => $series->toArray(),
            $this->series,
        );
    }

    public function monitored(): self
    {
        return new self(
            array_values(array_filter(
                $this->series,
                fn(Series $series): bool => $series->isMonitored(),
            )),
        );
    }

    public function continuing(): self
    {
        return new self(
            array_values(array_filter(
                $this->series,
                fn(Series $series): bool => $series->isContinuing(),
            )),
        );
    }

    public function ended(): self
    {
        return new self(
            array_values(array_filter(
                $this->series,
                fn(Series $series): bool => $series->isEnded(),
            )),
        );
    }

    public function withEpisodes(): self
    {
        return new self(
            array_values(array_filter(
                $this->series,
                fn(Series $series): bool => $series->hasEpisodes(),
            )),
        );
    }

    public function missing(): self
    {
        return new self(
            array_values(array_filter(
                $this->series,
                fn(Series $series): bool => ! $series->hasAllEpisodes() && $series->isMonitored(),
            )),
        );
    }
}
