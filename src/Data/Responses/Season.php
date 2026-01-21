<?php

namespace MartinCamen\Sonarr\Data\Responses;

final readonly class Season
{
    /** @param array<string, mixed> $statistics */
    public function __construct(
        public int $seasonNumber,
        public bool $monitored,
        public array $statistics,
    ) {}

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        return new self(
            seasonNumber: $data['seasonNumber'] ?? 0,
            monitored: $data['monitored'] ?? false,
            statistics: $data['statistics'] ?? [],
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'season_number' => $this->seasonNumber,
            'monitored'     => $this->monitored,
            'statistics'    => $this->statistics,
        ];
    }

    public function episodeCount(): int
    {
        return $this->statistics['episodeCount'] ?? 0;
    }

    public function episodeFileCount(): int
    {
        return $this->statistics['episodeFileCount'] ?? 0;
    }

    public function totalEpisodeCount(): int
    {
        return $this->statistics['totalEpisodeCount'] ?? 0;
    }

    public function sizeOnDisk(): int
    {
        return $this->statistics['sizeOnDisk'] ?? 0;
    }

    public function percentOfEpisodes(): float
    {
        return (float) ($this->statistics['percentOfEpisodes'] ?? 0.0);
    }

    public function hasAllEpisodes(): bool
    {
        return $this->episodeFileCount() === $this->totalEpisodeCount();
    }

    public function isFullyDownloaded(): bool
    {
        return $this->hasAllEpisodes() && $this->totalEpisodeCount() > 0;
    }
}
