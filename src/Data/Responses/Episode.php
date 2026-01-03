<?php

namespace MartinCamen\Sonarr\Data\Responses;

final readonly class Episode
{
    /**
     * @param array<string, mixed> $ratings
     * @param array<string, mixed> $images
     */
    public function __construct(
        public int $id,
        public int $seriesId,
        public ?int $tvdbId,
        public int $episodeFileId,
        public int $seasonNumber,
        public int $episodeNumber,
        public string $title,
        public ?string $airDate,
        public ?string $airDateUtc,
        public ?string $overview,
        public bool $hasFile,
        public bool $monitored,
        public ?int $sceneEpisodeNumber,
        public ?int $sceneSeasonNumber,
        public ?int $absoluteEpisodeNumber,
        public ?int $runtime,
        public array $ratings,
        public array $images,
        public bool $unverifiedSceneNumbering,
    ) {}

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            seriesId: $data['seriesId'] ?? 0,
            tvdbId: $data['tvdbId'] ?? null,
            episodeFileId: $data['episodeFileId'] ?? 0,
            seasonNumber: $data['seasonNumber'] ?? 0,
            episodeNumber: $data['episodeNumber'] ?? 0,
            title: $data['title'] ?? '',
            airDate: $data['airDate'] ?? null,
            airDateUtc: $data['airDateUtc'] ?? null,
            overview: $data['overview'] ?? null,
            hasFile: $data['hasFile'] ?? false,
            monitored: $data['monitored'] ?? false,
            sceneEpisodeNumber: $data['sceneEpisodeNumber'] ?? null,
            sceneSeasonNumber: $data['sceneSeasonNumber'] ?? null,
            absoluteEpisodeNumber: $data['absoluteEpisodeNumber'] ?? null,
            runtime: $data['runtime'] ?? null,
            ratings: $data['ratings'] ?? [],
            images: $data['images'] ?? [],
            unverifiedSceneNumbering: $data['unverifiedSceneNumbering'] ?? false,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id'                         => $this->id,
            'series_id'                  => $this->seriesId,
            'tvdb_id'                    => $this->tvdbId,
            'episode_file_id'            => $this->episodeFileId,
            'season_number'              => $this->seasonNumber,
            'episode_number'             => $this->episodeNumber,
            'title'                      => $this->title,
            'air_date'                   => $this->airDate,
            'air_date_utc'               => $this->airDateUtc,
            'overview'                   => $this->overview,
            'has_file'                   => $this->hasFile,
            'monitored'                  => $this->monitored,
            'scene_episode_number'       => $this->sceneEpisodeNumber,
            'scene_season_number'        => $this->sceneSeasonNumber,
            'absolute_episode_number'    => $this->absoluteEpisodeNumber,
            'runtime'                    => $this->runtime,
            'ratings'                    => $this->ratings,
            'images'                     => $this->images,
            'unverified_scene_numbering' => $this->unverifiedSceneNumbering,
        ];
    }

    public function isDownloaded(): bool
    {
        return $this->hasFile;
    }

    public function isMonitored(): bool
    {
        return $this->monitored;
    }

    public function hasAired(): bool
    {
        if ($this->airDateUtc === null) {
            return false;
        }

        return strtotime($this->airDateUtc) <= time();
    }

    public function isMissing(): bool
    {
        return ! $this->hasFile && $this->monitored && $this->hasAired();
    }

    public function getSeasonEpisodeString(): string
    {
        return sprintf('S%02dE%02d', $this->seasonNumber, $this->episodeNumber);
    }
}
