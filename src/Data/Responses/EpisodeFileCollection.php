<?php

namespace MartinCamen\Sonarr\Data\Responses;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use MartinCamen\ArrCore\ValueObject\ArrFileSize;
use Traversable;

/** @implements IteratorAggregate<int, EpisodeFile> */
final class EpisodeFileCollection implements Countable, IteratorAggregate
{
    /** @param  array<int, EpisodeFile>  $files */
    public function __construct(private array $files = []) {}

    /** @param  array<int, array<string, mixed>>  $data */
    public static function fromArray(array $data): self
    {
        return new self(
            array_map(
                EpisodeFile::fromArray(...),
                $data,
            ),
        );
    }

    /** @return array<int, EpisodeFile> */
    public function all(): array
    {
        return $this->files;
    }

    public function count(): int
    {
        return count($this->files);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function first(): ?EpisodeFile
    {
        return $this->files[0] ?? null;
    }

    public function last(): ?EpisodeFile
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->files[$this->count() - 1];
    }

    public function get(int $index): ?EpisodeFile
    {
        return $this->files[$index] ?? null;
    }

    /** @return Traversable<int, EpisodeFile> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->files);
    }

    /** @return array<int, array<string, mixed>> */
    public function toArray(): array
    {
        return array_map(
            static fn(EpisodeFile $file): array => $file->toArray(),
            $this->files,
        );
    }

    public function forSeason(int $seasonNumber): self
    {
        return new self(
            array_values(array_filter(
                $this->files,
                static fn(EpisodeFile $file): bool => $file->seasonNumber === $seasonNumber,
            )),
        );
    }

    public function totalSizeGb(): float
    {
        $totalSize = array_reduce(
            $this->files,
            static fn(int $carry, EpisodeFile $file): int => $carry + $file->size,
            0,
        );

        return ArrFileSize::fromBytes($totalSize)->toGigabytes(precision: 2);
    }
}
