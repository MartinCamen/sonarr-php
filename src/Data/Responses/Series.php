<?php

namespace MartinCamen\Sonarr\Data\Responses;

use MartinCamen\Sonarr\Data\Enums\SeriesStatus;
use MartinCamen\Sonarr\Data\Enums\SeriesType;

final readonly class Series
{
    /**
     * @param array<int, Season> $seasons
     * @param array<string, mixed> $images
     * @param array<int, array<string, mixed>> $alternateTitles
     * @param array<int, string> $genres
     * @param array<string, mixed> $ratings
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $sortTitle,
        public ?string $originalTitle,
        public ?int $year,
        public ?int $tvdbId,
        public ?int $tvMazeId,
        public ?int $tvRageId,
        public ?string $imdbId,
        public SeriesStatus $status,
        public string $overview,
        public bool $monitored,
        public SeriesType $seriesType,
        public int $qualityProfileId,
        public int $languageProfileId,
        public bool $seasonFolder,
        public string $path,
        public ?string $added,
        public ?string $firstAired,
        public ?string $previousAiring,
        public ?string $nextAiring,
        public ?int $runtime,
        public array $seasons,
        public array $images,
        public array $alternateTitles,
        public array $genres,
        public array $ratings,
        public int $episodeCount,
        public int $episodeFileCount,
        public int $totalEpisodeCount,
        public int $sizeOnDisk,
        public bool $ended,
        public ?string $network,
        public ?string $airTime,
        public ?string $certification,
        public bool $useSceneNumbering,
    ) {}

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            title: $data['title'] ?? '',
            sortTitle: $data['sortTitle'] ?? '',
            originalTitle: $data['originalTitle'] ?? null,
            year: $data['year'] ?? null,
            tvdbId: $data['tvdbId'] ?? null,
            tvMazeId: $data['tvMazeId'] ?? null,
            tvRageId: $data['tvRageId'] ?? null,
            imdbId: $data['imdbId'] ?? null,
            status: isset($data['status'])
                ? SeriesStatus::from($data['status'])
                : SeriesStatus::Unknown,
            overview: $data['overview'] ?? '',
            monitored: $data['monitored'] ?? false,
            seriesType: isset($data['seriesType'])
                ? SeriesType::from($data['seriesType'])
                : SeriesType::Standard,
            qualityProfileId: $data['qualityProfileId'] ?? 0,
            languageProfileId: $data['languageProfileId'] ?? 0,
            seasonFolder: $data['seasonFolder'] ?? false,
            path: $data['path'] ?? '',
            added: $data['added'] ?? null,
            firstAired: $data['firstAired'] ?? null,
            previousAiring: $data['previousAiring'] ?? null,
            nextAiring: $data['nextAiring'] ?? null,
            runtime: $data['runtime'] ?? null,
            seasons: isset($data['seasons'])
                ? array_map(Season::fromArray(...), $data['seasons'])
                : [],
            images: $data['images'] ?? [],
            alternateTitles: $data['alternateTitles'] ?? [],
            genres: $data['genres'] ?? [],
            ratings: $data['ratings'] ?? [],
            episodeCount: $data['statistics']['episodeCount'] ?? $data['episodeCount'] ?? 0,
            episodeFileCount: $data['statistics']['episodeFileCount'] ?? $data['episodeFileCount'] ?? 0,
            totalEpisodeCount: $data['statistics']['totalEpisodeCount'] ?? $data['totalEpisodeCount'] ?? 0,
            sizeOnDisk: $data['statistics']['sizeOnDisk'] ?? $data['sizeOnDisk'] ?? 0,
            ended: $data['ended'] ?? false,
            network: $data['network'] ?? null,
            airTime: $data['airTime'] ?? null,
            certification: $data['certification'] ?? null,
            useSceneNumbering: $data['useSceneNumbering'] ?? false,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id'                  => $this->id,
            'title'               => $this->title,
            'sort_title'          => $this->sortTitle,
            'original_title'      => $this->originalTitle,
            'year'                => $this->year,
            'tvdb_id'             => $this->tvdbId,
            'tv_maze_id'          => $this->tvMazeId,
            'tv_rage_id'          => $this->tvRageId,
            'imdb_id'             => $this->imdbId,
            'status'              => $this->status->value,
            'overview'            => $this->overview,
            'monitored'           => $this->monitored,
            'series_type'         => $this->seriesType->value,
            'quality_profile_id'  => $this->qualityProfileId,
            'language_profile_id' => $this->languageProfileId,
            'season_folder'       => $this->seasonFolder,
            'path'                => $this->path,
            'added'               => $this->added,
            'first_aired'         => $this->firstAired,
            'previous_airing'     => $this->previousAiring,
            'next_airing'         => $this->nextAiring,
            'runtime'             => $this->runtime,
            'seasons'             => array_map(fn(Season $s): array => $s->toArray(), $this->seasons),
            'images'              => $this->images,
            'alternate_titles'    => $this->alternateTitles,
            'genres'              => $this->genres,
            'ratings'             => $this->ratings,
            'episode_count'       => $this->episodeCount,
            'episode_file_count'  => $this->episodeFileCount,
            'total_episode_count' => $this->totalEpisodeCount,
            'size_on_disk'        => $this->sizeOnDisk,
            'ended'               => $this->ended,
            'network'             => $this->network,
            'air_time'            => $this->airTime,
            'certification'       => $this->certification,
            'use_scene_numbering' => $this->useSceneNumbering,
        ];
    }

    public function isEnded(): bool
    {
        return $this->ended;
    }

    public function isContinuing(): bool
    {
        return $this->status === SeriesStatus::Continuing;
    }

    public function hasEpisodes(): bool
    {
        return $this->episodeFileCount > 0;
    }

    public function isMonitored(): bool
    {
        return $this->monitored;
    }

    public function getSizeOnDiskGb(): float
    {
        return round($this->sizeOnDisk / 1024 / 1024 / 1024, 2);
    }

    public function isAnime(): bool
    {
        return $this->seriesType === SeriesType::Anime;
    }

    public function isDaily(): bool
    {
        return $this->seriesType === SeriesType::Daily;
    }

    public function hasAllEpisodes(): bool
    {
        return $this->episodeFileCount === $this->totalEpisodeCount
            && $this->totalEpisodeCount > 0;
    }
}
