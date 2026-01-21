<?php

namespace MartinCamen\Sonarr\Data\Responses;

use MartinCamen\ArrCore\Concerns\DownloadHasSizeWithSizeLeft;
use MartinCamen\ArrCore\Concerns\DownloadHasTrackedDownloadState;
use MartinCamen\ArrCore\Concerns\DownloadHasTrackedDownloadStatus;
use MartinCamen\ArrCore\Enum\TrackedDownloadState;
use MartinCamen\ArrCore\Enum\TrackedDownloadStatus;

final readonly class Download
{
    use DownloadHasSizeWithSizeLeft;
    use DownloadHasTrackedDownloadState;
    use DownloadHasTrackedDownloadStatus;

    /**
     * @param array<string, mixed>|null $quality
     * @param array<int, array<string, mixed>> $statusMessages
     * @param array<string, mixed>|null $series
     * @param array<string, mixed>|null $episode
     */
    public function __construct(
        public int $id,
        public ?int $seriesId,
        public ?int $episodeId,
        public ?string $title,
        public string $status,
        public string $trackedDownloadStatus,
        public string $trackedDownloadState,
        public ?array $quality,
        public float $size,
        public float $sizeLeft,
        public ?string $timeLeft,
        public ?string $estimatedCompletionTime,
        public string $downloadClient,
        public string $downloadId,
        public string $protocol,
        public string $indexer,
        public string $outputPath,
        public array $statusMessages,
        public ?string $errorMessage,
        public ?array $series,
        public ?array $episode,
    ) {}

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            seriesId: $data['seriesId'] ?? null,
            episodeId: $data['episodeId'] ?? null,
            title: $data['title'] ?? null,
            status: $data['status'] ?? 'unknown',
            trackedDownloadStatus: $data['trackedDownloadStatus'] ?? TrackedDownloadStatus::Unknown->value,
            trackedDownloadState: $data['trackedDownloadState'] ?? TrackedDownloadState::Unknown->value,
            quality: $data['quality'] ?? null,
            size: (float) ($data['size'] ?? 0),
            sizeLeft: (float) ($data['sizeleft'] ?? 0),
            timeLeft: $data['timeleft'] ?? null,
            estimatedCompletionTime: $data['estimatedCompletionTime'] ?? null,
            downloadClient: $data['downloadClient'] ?? '',
            downloadId: $data['downloadId'] ?? '',
            protocol: $data['protocol'] ?? '',
            indexer: $data['indexer'] ?? '',
            outputPath: $data['outputPath'] ?? '',
            statusMessages: $data['statusMessages'] ?? [],
            errorMessage: $data['errorMessage'] ?? null,
            series: $data['series'] ?? null,
            episode: $data['episode'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id'                        => $this->id,
            'series_id'                 => $this->seriesId,
            'episode_id'                => $this->episodeId,
            'title'                     => $this->title,
            'status'                    => $this->status,
            'tracked_download_status'   => $this->trackedDownloadStatus,
            'tracked_download_state'    => $this->trackedDownloadState,
            'quality'                   => $this->quality,
            'size'                      => $this->size,
            'sizeleft'                  => $this->sizeLeft,
            'timeleft'                  => $this->timeLeft,
            'estimated_completion_time' => $this->estimatedCompletionTime,
            'download_client'           => $this->downloadClient,
            'download_id'               => $this->downloadId,
            'protocol'                  => $this->protocol,
            'indexer'                   => $this->indexer,
            'output_path'               => $this->outputPath,
            'status_messages'           => $this->statusMessages,
            'error_message'             => $this->errorMessage,
            'series'                    => $this->series,
            'episode'                   => $this->episode,
        ];
    }
}
