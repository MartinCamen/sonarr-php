<?php

declare(strict_types=1);

namespace MartinCamen\Sonarr;

use MartinCamen\ArrCore\Actions\SystemActions;
use MartinCamen\ArrCore\Actions\WantedActions;
use MartinCamen\Sonarr\Actions\CalendarActions;
use MartinCamen\Sonarr\Actions\CommandActions;
use MartinCamen\Sonarr\Actions\DownloadActions;
use MartinCamen\Sonarr\Actions\EpisodeActions;
use MartinCamen\Sonarr\Actions\EpisodeFileActions;
use MartinCamen\Sonarr\Actions\HistoryActions;
use MartinCamen\Sonarr\Actions\SeriesActions;
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
     * Access series functionality.
     */
    public function series(): SeriesActions;

    /**
     * Access download functionality.
     */
    public function downloads(): DownloadActions;

    /**
     * Access system functionality.
     */
    public function system(): SystemActions;

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
