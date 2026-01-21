<?php

declare(strict_types=1);

namespace MartinCamen\Sonarr\Testing;

use MartinCamen\ArrCore\Actions\SystemActions;
use MartinCamen\ArrCore\Actions\WantedActions;
use MartinCamen\ArrCore\Testing\BaseFake;
use MartinCamen\Sonarr\Actions\CalendarActions;
use MartinCamen\Sonarr\Actions\CommandActions;
use MartinCamen\Sonarr\Actions\DownloadActions;
use MartinCamen\Sonarr\Actions\EpisodeActions;
use MartinCamen\Sonarr\Actions\EpisodeFileActions;
use MartinCamen\Sonarr\Actions\HistoryActions;
use MartinCamen\Sonarr\Actions\SeriesActions;
use MartinCamen\Sonarr\Client\SonarrApiClientInterface;
use MartinCamen\Sonarr\SonarrInterface;

/**
 * Fake implementation for testing.
 *
 * Provides the same interface as Sonarr SDK but allows
 * custom responses and tracks method calls for assertions.
 *
 * @example
 * ```php
 * $fake = new SonarrFake([
 *     'series' => SeriesFactory::makeMany(5),
 * ]);
 *
 * $series = $fake->series()->all();
 * $fake->assertCalled('series');
 * ```
 */
final class SonarrFake extends BaseFake implements SonarrInterface
{
    private ?SonarrApiFake $apiFake = null;

    /** Access series functionality */
    public function series(): SeriesActions
    {
        $this->recordCall('series', []);

        return $this->api()->series();
    }

    /** Access download functionality */
    public function downloads(): DownloadActions
    {
        $this->recordCall('downloads', []);

        return $this->api()->downloads();
    }

    /** Access system functionality */
    public function system(): SystemActions
    {
        $this->recordCall('system', []);

        return $this->api()->system();
    }

    /** Access episode functionality */
    public function episode(): EpisodeActions
    {
        $this->recordCall('episode', []);

        return $this->api()->episode();
    }

    /** Access episode file functionality */
    public function episodeFile(): EpisodeFileActions
    {
        $this->recordCall('episodeFile', []);

        return $this->api()->episodeFile();
    }

    /** Access calendar functionality */
    public function calendar(): CalendarActions
    {
        $this->recordCall('calendar', []);

        return $this->api()->calendar();
    }

    /** Access history functionality */
    public function history(): HistoryActions
    {
        $this->recordCall('history', []);

        return $this->api()->history();
    }

    /** Access wanted functionality */
    public function wanted(): WantedActions
    {
        $this->recordCall('wanted', []);

        return $this->api()->wanted();
    }

    /** Access command functionality */
    public function command(): CommandActions
    {
        $this->recordCall('command', []);

        return $this->api()->command();
    }

    /** Get the underlying API client fake for advanced operations */
    public function api(): SonarrApiClientInterface
    {
        return $this->apiFake ??= new SonarrApiFake();
    }
}
