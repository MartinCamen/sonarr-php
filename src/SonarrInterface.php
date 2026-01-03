<?php

declare(strict_types=1);

namespace MartinCamen\Sonarr;

use MartinCamen\ArrCore\Actions\WantedActions;
use MartinCamen\ArrCore\Domain\Download\DownloadItemCollection;
use MartinCamen\ArrCore\Domain\Media\Series;
use MartinCamen\ArrCore\Domain\System\SystemStatus;
use MartinCamen\Sonarr\Actions\CalendarActions;
use MartinCamen\Sonarr\Actions\CommandActions;
use MartinCamen\Sonarr\Actions\EpisodeActions;
use MartinCamen\Sonarr\Actions\EpisodeFileActions;
use MartinCamen\Sonarr\Actions\HistoryActions;
use MartinCamen\Sonarr\Client\SonarrApiClientInterface;

/**
 * Interface for the Sonarr SDK client.
 *
 * This interface defines the public API for interacting with Sonarr,
 * using unified terminology and Core domain models.
 */
interface SonarrInterface
{
    /**
     * Get all active downloads (queue items).
     */
    public function downloads(): DownloadItemCollection;

    /**
     * Get all series.
     *
     * @return array<int, Series>
     */
    public function series(): array;

    /**
     * Get a single series by ID.
     */
    public function seriesById(int $id): Series;

    /**
     * Get system status including health checks.
     */
    public function systemStatus(): SystemStatus;

    /**
     * Access episode functionality.
     */
    public function episode(): EpisodeActions;

    /**
     * Access episode file functionality.
     */
    public function episodeFile(): EpisodeFileActions;

    /**
     * Access calendar functionality.
     */
    public function calendar(): CalendarActions;

    /**
     * Access history functionality.
     */
    public function history(): HistoryActions;

    /**
     * Access wanted functionality.
     */
    public function wanted(): WantedActions;

    /**
     * Access command functionality.
     */
    public function command(): CommandActions;

    /**
     * Get the underlying API client for advanced operations.
     */
    public function api(): SonarrApiClientInterface;
}
