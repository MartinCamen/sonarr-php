<?php

namespace MartinCamen\Sonarr\Data\Responses;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, Episode>
 */
final class EpisodeCollection implements Countable, IteratorAggregate
{
    /** @param array<int, Episode> $episodes */
    public function __construct(private array $episodes = []) {}

    /** @param array<int, array<string, mixed>> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            array_map(
                Episode::fromArray(...),
                $data,
            ),
        );
    }

    /** @return array<int, Episode> */
    public function all(): array
    {
        return $this->episodes;
    }

    public function count(): int
    {
        return count($this->episodes);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function first(): ?Episode
    {
        return $this->episodes[0] ?? null;
    }

    public function last(): ?Episode
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->episodes[$this->count() - 1];
    }

    public function get(int $index): ?Episode
    {
        return $this->episodes[$index] ?? null;
    }

    /** @return Traversable<int, Episode> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->episodes);
    }

    /** @return array<int, array<string, mixed>> */
    public function toArray(): array
    {
        return array_map(
            fn(Episode $episode): array => $episode->toArray(),
            $this->episodes,
        );
    }

    public function monitored(): self
    {
        return new self(
            array_values(array_filter(
                $this->episodes,
                fn(Episode $episode): bool => $episode->isMonitored(),
            )),
        );
    }

    public function downloaded(): self
    {
        return new self(
            array_values(array_filter(
                $this->episodes,
                fn(Episode $episode): bool => $episode->isDownloaded(),
            )),
        );
    }

    public function missing(): self
    {
        return new self(
            array_values(array_filter(
                $this->episodes,
                fn(Episode $episode): bool => $episode->isMissing(),
            )),
        );
    }

    public function forSeason(int $seasonNumber): self
    {
        return new self(
            array_values(array_filter(
                $this->episodes,
                fn(Episode $episode): bool => $episode->seasonNumber === $seasonNumber,
            )),
        );
    }
}
