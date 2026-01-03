<?php

declare(strict_types=1);

namespace MartinCamen\Sonarr\Testing;

use MartinCamen\ArrCore\Actions\SystemActions;
use MartinCamen\ArrCore\Actions\WantedActions;
use MartinCamen\ArrCore\Testing\BaseApiFake;
use MartinCamen\Sonarr\Actions\CalendarActions;
use MartinCamen\Sonarr\Actions\CommandActions;
use MartinCamen\Sonarr\Actions\EpisodeActions;
use MartinCamen\Sonarr\Actions\EpisodeFileActions;
use MartinCamen\Sonarr\Actions\HistoryActions;
use MartinCamen\Sonarr\Actions\QueueActions;
use MartinCamen\Sonarr\Actions\SeriesActions;
use MartinCamen\Sonarr\Client\SonarrApiClientInterface;

/**
 * Fake implementation for the low-level Sonarr API client.
 *
 * Use this when you need to test code that interacts directly
 * with the API client layer.
 *
 * @internal
 */
class SonarrApiFake extends BaseApiFake implements SonarrApiClientInterface
{
    public function series(): SeriesActions
    {
        return new SeriesActions($this->getFakeClient());
    }

    public function episode(): EpisodeActions
    {
        return new EpisodeActions($this->getFakeClient());
    }

    public function episodeFile(): EpisodeFileActions
    {
        return new EpisodeFileActions($this->getFakeClient());
    }

    public function queue(): QueueActions
    {
        return new QueueActions($this->getFakeClient());
    }

    public function history(): HistoryActions
    {
        return new HistoryActions($this->getFakeClient());
    }

    public function calendar(): CalendarActions
    {
        return new CalendarActions($this->getFakeClient());
    }

    public function system(): SystemActions
    {
        return new SystemActions($this->getFakeClient());
    }

    public function command(): CommandActions
    {
        return new CommandActions($this->getFakeClient());
    }

    public function wanted(): WantedActions
    {
        return new WantedActions($this->getFakeClient());
    }
}
