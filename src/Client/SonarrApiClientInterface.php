<?php

declare(strict_types=1);

namespace MartinCamen\Sonarr\Client;

use MartinCamen\ArrCore\Actions\SystemActions;
use MartinCamen\ArrCore\Actions\WantedActions;
use MartinCamen\Sonarr\Actions\CalendarActions;
use MartinCamen\Sonarr\Actions\CommandActions;
use MartinCamen\Sonarr\Actions\DownloadActions;
use MartinCamen\Sonarr\Actions\EpisodeActions;
use MartinCamen\Sonarr\Actions\EpisodeFileActions;
use MartinCamen\Sonarr\Actions\HistoryActions;
use MartinCamen\Sonarr\Actions\SeriesActions;

/**
 * Interface for low-level Sonarr API client.
 *
 * @internal This interface is for internal use. Use SonarrInterface for public API.
 */
interface SonarrApiClientInterface
{
    public function series(): SeriesActions;

    public function episode(): EpisodeActions;

    public function episodeFile(): EpisodeFileActions;

    public function downloads(): DownloadActions;

    public function history(): HistoryActions;

    public function calendar(): CalendarActions;

    public function system(): SystemActions;

    public function command(): CommandActions;

    public function wanted(): WantedActions;
}
