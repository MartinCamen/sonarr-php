<?php

namespace MartinCamen\Sonarr\Data\Responses;

final readonly class HistoryRecord
{
    /**
     * @param array<string, mixed>|null $quality
     * @param array<string, mixed>|null $data
     * @param array<string, mixed>|null $series
     * @param array<string, mixed>|null $episode
     */
    public function __construct(
        public int $id,
        public int $episodeId,
        public int $seriesId,
        public string $sourceTitle,
        public string $eventType,
        public ?array $quality,
        public ?string $date,
        public string $downloadId,
        public ?array $data,
        public ?array $series,
        public ?array $episode,
    ) {}

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            episodeId: $data['episodeId'] ?? 0,
            seriesId: $data['seriesId'] ?? 0,
            sourceTitle: $data['sourceTitle'] ?? '',
            eventType: $data['eventType'] ?? 'unknown',
            quality: $data['quality'] ?? null,
            date: $data['date'] ?? null,
            downloadId: $data['downloadId'] ?? '',
            data: $data['data'] ?? null,
            series: $data['series'] ?? null,
            episode: $data['episode'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'episode_id'   => $this->episodeId,
            'series_id'    => $this->seriesId,
            'source_title' => $this->sourceTitle,
            'event_type'   => $this->eventType,
            'quality'      => $this->quality,
            'date'         => $this->date,
            'download_id'  => $this->downloadId,
            'data'         => $this->data,
            'series'       => $this->series,
            'episode'      => $this->episode,
        ];
    }

    public function isGrabbed(): bool
    {
        return $this->eventType === 'grabbed';
    }

    public function isImported(): bool
    {
        return $this->eventType === 'downloadFolderImported'
            || $this->eventType === 'seriesFolderImported';
    }

    public function isFailed(): bool
    {
        return $this->eventType === 'downloadFailed';
    }

    public function isDeleted(): bool
    {
        return $this->eventType === 'episodeFileDeleted';
    }

    public function isRenamed(): bool
    {
        return $this->eventType === 'episodeFileRenamed';
    }
}
