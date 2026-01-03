<?php

namespace MartinCamen\Sonarr\Data\Responses;

final readonly class EpisodeFile
{
    /**
     * @param array<string, mixed> $quality
     * @param array<string, mixed> $mediaInfo
     */
    public function __construct(
        public int $id,
        public int $seriesId,
        public int $seasonNumber,
        public string $relativePath,
        public string $path,
        public int $size,
        public string $dateAdded,
        public string $sceneName,
        public ?string $releaseGroup,
        public array $quality,
        public array $mediaInfo,
        public int $qualityWeight,
    ) {}

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            seriesId: $data['seriesId'] ?? 0,
            seasonNumber: $data['seasonNumber'] ?? 0,
            relativePath: $data['relativePath'] ?? '',
            path: $data['path'] ?? '',
            size: $data['size'] ?? 0,
            dateAdded: $data['dateAdded'] ?? '',
            sceneName: $data['sceneName'] ?? '',
            releaseGroup: $data['releaseGroup'] ?? null,
            quality: $data['quality'] ?? [],
            mediaInfo: $data['mediaInfo'] ?? [],
            qualityWeight: $data['qualityWeight'] ?? 0,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id'             => $this->id,
            'series_id'      => $this->seriesId,
            'season_number'  => $this->seasonNumber,
            'relative_path'  => $this->relativePath,
            'path'           => $this->path,
            'size'           => $this->size,
            'date_added'     => $this->dateAdded,
            'scene_name'     => $this->sceneName,
            'release_group'  => $this->releaseGroup,
            'quality'        => $this->quality,
            'media_info'     => $this->mediaInfo,
            'quality_weight' => $this->qualityWeight,
        ];
    }

    public function getSizeGb(): float
    {
        return round($this->size / 1024 / 1024 / 1024, 2);
    }

    public function getSizeMb(): float
    {
        return round($this->size / 1024 / 1024, 2);
    }

    public function getQualityName(): string
    {
        return $this->quality['quality']['name'] ?? 'Unknown';
    }
}
